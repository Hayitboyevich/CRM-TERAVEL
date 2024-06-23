<?php

namespace App\Http\Controllers;

use App\DataTables\OrdersDataTable;
use App\Events\NewInvoiceEvent;
use App\Events\NewOrderEvent;
use App\Helper\Reply;
use App\Http\Requests\CustomUpdateOrderRequest;
use App\Http\Requests\Orders\PlaceOrder;
use App\Http\Requests\Orders\UpdateOrder;
use App\Http\Requests\Stripe\StoreStripeDetail;
use App\Models\CompanyAddress;
use App\Models\CreditNoteItem;
use App\Models\CreditNoteItemImage;
use App\Models\CreditNotes;
use App\Models\Currency;
use App\Models\Integration;
use App\Models\IntegrationPartner;
use App\Models\Invoice;
use App\Models\InvoiceItemImage;
use App\Models\InvoiceItems;
use App\Models\Lead;
use App\Models\OfflinePaymentMethod;
use App\Models\Order;
use App\Models\OrderItemImage;
use App\Models\OrderItems;
use App\Models\Payment;
use App\Models\PaymentGatewayCredentials;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Project;
use App\Models\Tax;
use App\Models\TourPackage;
use App\Models\UnitType;
use App\Models\User;
use App\Scopes\ActiveScope;
use Carbon\Carbon;
use Exception;
use Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class OrderController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.orders';
        $this->middleware(
            function ($request, $next) {
                abort_403(!in_array('orders', $this->user->modules));

                return $next($request);
            }
        );
    }

    public function deleteItems($id)
    {
        $orderItem = OrderItems::query()->where('id', $id)->firstOrFail();
        $order_count = OrderItems::query()
            ->where('order_id', $orderItem->order_id)
            ->get()->count();
        $order = $orderItem->order;
        $order_currency = $order->currency->exchange_rate;

        $order->total = $order->total - ($orderItem->amount * $orderItem->exchange_rate / $order_currency);
        $order->net_price = $order->net_price - ($orderItem->nett_amount * $orderItem->nett_exchange_rate / $order_currency);

        if ($order_count == 1) {
            $order->total = 0;
            $order->net_price = 0;
        }

        $order->save();

        $orderItem->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);


        $this->deletePermission = user()->permission('delete_order');
        abort_403(in_array('client', user_roles()) || !($this->deletePermission == 'all' || ($this->deletePermission == 'both' && ($order->added_by == user()->id || $order->client_id == user()->id)) || ($this->deletePermission == 'added' && $order->added_by == user()->id) || ($this->deletePermission == 'owned' && $order->client_id == user()->id)));

        Order::destroy($id);

        return Reply::success(__('messages.deleteSuccess'));
    }

    public function index(OrdersDataTable $dataTable)
    {
        $viewPermission = user()->permission('view_order');
        abort_403(!in_array($viewPermission, ['all', 'added', 'owned', 'both']));

        if (!request()->ajax()) {
            $this->projects = Project::allProjects();

            if (in_array('client', user_roles())) {
                $this->clients = User::client();
            } else {
                $this->clients = User::allClients();
            }
        }

        return $dataTable->render('orders.index', $this->data);
    }

    public function saveOrder($request)
    {

        $order = new Order();
        $order->client_id = $request->client_id ?: user()->id;
        $order->order_date = now()->format('Y-m-d');
        $order->sub_total = round($request->sub_total, 2);
        $order->total = round($request->total, 2);
        $order->discount = is_null($request->discount_value) ? 0 : $request->discount_value;
        $order->discount_type = $request->discount_type;
        $order->status = $request->has('status') ? $request->status : 'pending';
        $order->currency_id = $this->company->currency_id;
        $order->note = trim_editor($request->note);
        $order->show_shipping_address = (($request->has('shipping_address') && $request->shipping_address != '') ? 'yes' : 'no');
        $order->company_address_id = $request->company_address_id ?: null;
        $order->save();

        if ($order->show_shipping_address == 'yes') {
            /**
             * @phpstan-ignore-next-line
             */
            $client = $order->clientdetails;
            $client->shipping_address = $request->shipping_address;
            $client->saveQuietly();

        }

        return $order;
    }

    /**
     * XXXXXXXXXXX
     *
     * @return Response
     * @throws RelatedResourceNotFoundException
     */
    public function store(PlaceOrder $request)
    {
        if (!in_array('client', user_roles())) {
            $this->addPermission = user()->permission('add_order');
            abort_403(!in_array($this->addPermission, ['all', 'added']));
        }

        $this->lastOrder = Order::lastOrderNumber() + 1;
        $this->orderSetting = invoice_setting();

        $zero = '';
        $customOrderNumber = '';

        if ($this->orderSetting && (strlen($this->lastOrder) < $this->orderSetting->order_digit)) {
            $condition = $this->orderSetting->order_digit - strlen($this->lastOrder);

            for ($i = 0; $i < $condition; $i++) {
                $zero = '0' . $zero;
            }

            $customOrderNumber = $this->orderSetting->order_prefix . '' . $this->orderSetting->order_number_separator . '' . $zero . '' . $request->order_number;
        }
        DB::beginTransaction();
        try {
            $order = new Order();
            $order->client_id = $request->client_id ?: user()->id;
            $order->order_date = now()->format('Y-m-d');
            $order->total_paid = $request->total_paid;
            $order->net_price = $request->net_price;
            $order->name = $request->item_name;
//        $order->integration_id = $request->integration_id;

            $order->hotel = $request->hotel;
            $order->visa = $request->visa;
            $order->air_ticket = $request->air_ticket;
            $order->transfer = $request->transfer;
            $order->insurance = $request->insurance;
            $order->service_fee = $request->service_fee;
            $order->adults_count = $request->adults_count;
            $order->children_count = $request->children_count;

            $order->total = round($request->total, 2);
            $order->status = $request->has('status') ? $request->status : 'pending';
            $order->currency_id = $request->currency_id ?? $this->company->currency_id;
            $order->note = trim_editor($request->note);
            $order->company_address_id = $request->company_address_id ?: null;
            $order->order_number = $request->order_number;
            $order->custom_order_number = $customOrderNumber;
            $order->save();
            //Payment

            $rates = Currency::query()
                ->where(['company_id' => company()->id])
                ->get();

            $rates->filter(function ($rate) use ($request, $order) {

                $payment = new Payment();
                $payment->currency_id = $rate->id;
                $payment->exchange_rate = $rate->exchange_rate;
                $payment->company_id = company()->id;
                $payment->default_currency_id = company()->currency_id;

                if ($rate->currency_code == 'EUR') {
                    $payment->amount = $request->paid_euro;
                }
                if ($rate->currency_code == 'USD') {
                    $payment->amount = $request->paid_usd;
                }
                if ($rate->currency_code == 'UZS') {
                    $payment->amount = $request->paid_uzs;
                }
                $payment->order_id = $order->id;
                $payment->customer_id = $request->client_id;
                $payment->added_by = auth()->id();
                $payment->paid_on = now();

                if ($request->paid_uzs != 0 && $rate->currency_code == 'UZS') {
                    $payment->save();
                }
                if ($request->paid_euro != 0 && $rate->currency_code == 'EUR') {
                    $payment->save();
                }
                if ($request->paid_usd != 0 && $rate->currency_code == 'USD') {
                    $payment->save();
                }

            });
            //
            $lead = Lead::query()
                ->where(['id' => $request->lead_id])
                ->first();

            $lead->value = $request->total;
            $lead->currency_id = $request->currency_id;
            $lead->order_id = $order->id;
            $lead->save();

            if ($order->show_shipping_address == 'yes') {
                $client = $order->clientdetails;
                $client->shipping_address = $request->shipping_address;
                $client->saveQuietly();
            }

            if ($request->has('status') && $request->status == 'completed') {
                $clientId = $order->client_id;
                // Notify client
                $notifyUser = User::withoutGlobalScope(ActiveScope::class)->findOrFail($clientId);

                if ($notifyUser) {
                    event(new NewOrderEvent($order, $notifyUser));
                }

                $invoice = $this->makeOrderInvoice($order);
                $this->makePayment($order->total, $invoice, 'complete');
            }
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();


        // Log search
        $this->logSearchEntry($order->id, $order->id, 'orders.show', 'order');

        return response(Reply::redirect(route('leads.show', $lead->id), __('messages.recordSaved')))->withCookie(Cookie::forget('productDetails'));

    }

    public function makeOrderInvoice($order)
    {
        if ($order->invoice) {
            /**
             * @phpstan-ignore-next-line
             */
            $order->invoice->status = 'paid';
            $order->push();

            return $order->invoice;
        }

        $invoice = new Invoice();
        $invoice->order_id = $order->id;
        $invoice->client_id = $order->client_id;
        $invoice->sub_total = $order->sub_total;
        $invoice->discount = $order->discount;
        $invoice->discount_type = $order->discount_type;
        $invoice->total = $order->total;
        $invoice->currency_id = $order->currency_id;
        $invoice->status = 'paid';
        $invoice->note = trim_editor($order->note);
        $invoice->issue_date = now();
        $invoice->send_status = 1;
        $invoice->invoice_number = Invoice::lastInvoiceNumber() + 1;
        $invoice->due_amount = 0;
        $invoice->hash = md5(microtime());
        $invoice->added_by = user() ? user()->id : null;
        $invoice->save();

        /* Make invoice items */
        $orderItems = OrderItems::where('order_id', $order->id)->get();

        foreach ($orderItems as $item) {
            $invoiceItem = new InvoiceItems();
            $invoiceItem->invoice_id = $invoice->id;
            $invoiceItem->item_name = $item->item_name;
            $invoiceItem->item_summary = $item->item_summary;
            $invoiceItem->type = 'item';
            $invoiceItem->quantity = $item->quantity;
            $invoiceItem->unit_price = $item->unit_price;
            $invoiceItem->amount = $item->amount;
            $invoiceItem->taxes = $item->taxes;
            $invoiceItem->product_id = $item->product_id;
            $invoiceItem->unit_id = $item->unit_id;
            $invoiceItem->saveQuietly();

            // Save invoice item image
            if (isset($item->orderItemImage)) {
                $invoiceItemImage = new InvoiceItemImage();
                $invoiceItemImage->invoice_item_id = $invoiceItem->id;
                $invoiceItemImage->external_link = $item->orderItemImage->external_link;
                $invoiceItemImage->save();
            }

        }

        $notifyUser = User::withoutGlobalScope(ActiveScope::class)->findOrFail($order->client_id);
        event(new NewInvoiceEvent($invoice, $notifyUser));

        return $invoice;
    }

    public function makePayment($amount, $invoice, $status = 'pending', $transactionId = null, $gateway = 'Offline')
    {
        $payment = Payment::where('invoice_id', $invoice->id)->first();

        $payment = ($payment && $transactionId) ? $payment : new Payment();
        $payment->project_id = $invoice->project_id;
        $payment->invoice_id = $invoice->id;
        $payment->order_id = $invoice->order_id;
        $payment->gateway = $gateway;
        $payment->transaction_id = $transactionId;
        $payment->event_id = $transactionId;
        $payment->currency_id = $invoice->currency_id;
        $payment->amount = $amount;
        $payment->paid_on = now();
        $payment->status = $status;
        $payment->save();

        return $payment;
    }

    public function addItem(Request $request)
    {
        $companyCurrencyID = company()->currency_id;
        $this->item = Product::with('tax')->findOrFail($request->id);
        $this->invoiceSetting = $this->company->invoiceSetting;
        $exchangeRate = ($request->currencyId) ? Currency::findOrFail($request->currencyId) : Currency::findOrFail($companyCurrencyID);

        if (!is_null($exchangeRate) && !is_null($exchangeRate->exchange_rate)) {

            if ($this->item->total_amount != '') {

                $this->item->price = floor($this->item->total_amount * $exchangeRate->exchange_rate);

            } else {
                /**
                 * @phpstan-ignore-next-line
                 */
                $this->item->price = $this->item->price * $exchangeRate->exchange_rate;
            }
        } else {
            if ($this->item->total_amount != '') {
                $this->item->price = $this->item->total_amount;
            }
        }

        $this->item->price = number_format((float)$this->item->price, 2, '.', '');
        $this->taxes = Tax::all();
        $view = view('orders.ajax.add_item', $this->data)->render();

        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function edit($id)
    {
        $this->order = Order::with('client', 'unit')->findOrFail($id);
        $this->editPermission = user()->permission('edit_order');
        $this->units = UnitType::all();
        abort_403(in_array('client', user_roles()) || !($this->editPermission == 'all' || ($this->editPermission == 'both' && ($this->order->added_by == user()->id || $this->order->client_id == user()->id)) || ($this->editPermission == 'added' && $this->order->added_by == user()->id) || ($this->editPermission == 'owned' && $this->order->client_id == user()->id)));
        abort_403(in_array($this->order->status, ['completed', 'canceled', 'refunded']));
        $this->pageTitle = $this->order->order_number;

        $this->currencies = Currency::all();
        $this->taxes = Tax::all();
        $this->products = Product::all();
        $this->categories = ProductCategory::all();
        $this->clients = User::allClients();
        $this->companyAddresses = CompanyAddress::all();

        if (request()->ajax()) {
            $html = view('orders.ajax.edit', $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'orders.ajax.edit';

        return view('orders.create', $this->data);
    }

    public function customUpdate($id, CustomUpdateOrderRequest $request)
    {
        $order = Order::findOrFail($id);
        if ($order->status == 'completed') {
            return Reply::error(__('messages.invalidRequest'));
        }
        $data = $request->validated();
        $data["hotel"] = Arr::get($data, 'hotel') === "on";
        $data["visa"] = Arr::get($data, 'visa') === "on";
        $data["insurance"] = Arr::get($data, 'insurance') === "on";
        $data["air_ticket"] = Arr::get($data, 'air_ticket') === "on";
        $data["transfer"] = Arr::get($data, 'transfer') === "on";

        DB::beginTransaction();
        try {

            $order->client_id = $request->client_id ?: user()->id;
            $data["name"] = $data["item_name"];

            $data["total"] = round($data["total"], 2);
            $data["created_at"] = date("Y-m-d H:m:s", strtotime($data["created_at"]));
            $data["created_at"] = strtotime($data["created_at"]);

            $data["currency_id"] = $data["currency_id"] ?? $this->company->currency_id;
//            $data["note"] = trim_editor($data["note"]);
            $order->fill($data);
            $order->save();

            //Payment

            $rates = Currency::query()
                ->where(['company_id' => company()->id])
                ->get();

            $rates->filter(function ($rate) use ($request, $order) {

                $payment = new Payment();
                $payment->currency_id = $rate->id;
                $payment->exchange_rate = $rate->exchange_rate;
                $payment->company_id = company()->id;
                $payment->default_currency_id = company()->currency_id;

                if ($rate->currency_code == 'EUR') {
                    $payment->amount = $request->paid_euro;
                }
                if ($rate->currency_code == 'USD') {
                    $payment->amount = $request->paid_usd;
                }
                if ($rate->currency_code == 'UZS') {
                    $payment->amount = $request->paid_uzs;
                }
                $payment->order_id = $order->id;
                $payment->customer_id = $request->client_id;
                $payment->added_by = auth()->id();
                $payment->paid_on = now();

                if ($request->paid_uzs != 0 && $rate->currency_code == 'UZS') {
                    $payment->save();
                }
                if ($request->paid_euro != 0 && $rate->currency_code == 'EUR') {
                    $payment->save();
                }
                if ($request->paid_usd != 0 && $rate->currency_code == 'USD') {
                    $payment->save();
                }

            });
            //

            $lead = $order->lead;
//
//            $lead->value = $request->total;
//
//            $lead->currency_id = $request->currency_id;
//            $lead->order_id = $order->id;
//
//            $lead->save();
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;

        }
        DB::commit();

        if (in_array('kassir', user_roles())) {
            return Reply::redirect(route('orders.index', $lead->id), __('messages.updateSuccess'));

        }

        return Reply::redirect(route('leads.show', $lead->id), __('messages.updateSuccess'));
    }

    public function customEdit($id)
    {
        $this->order = Order::with('client', 'unit')->findOrFail($id);
        $this->editPermission = user()->permission('edit_order');
        $this->units = UnitType::all();
        abort_403(in_array('client', user_roles()) || !($this->editPermission == 'all' || ($this->editPermission == 'both' && ($this->order->added_by == user()->id || $this->order->client_id == user()->id)) || ($this->editPermission == 'added' && $this->order->added_by == user()->id) || ($this->editPermission == 'owned' && $this->order->client_id == user()->id)));
        abort_403(in_array($this->order->status, ['completed', 'canceled', 'refunded']));
        $this->pageTitle = $this->order->order_number;

        $this->currencies = Currency::all();
        $this->taxes = Tax::all();
        $this->products = Product::all();
        $this->categories = ProductCategory::all();
        $this->clients = User::allClients();
        $this->companyAddresses = CompanyAddress::all();
        $this->partners = IntegrationPartner::all();
        $this->integration = $this->order?->client?->integrations()->first();
        if (request()->ajax()) {
            $html = view('orders.ajax.custom_edit', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }
        $this->view = 'orders.ajax.custom_edit';

        return view('orders.create', $this->data);
    }

    public function update(UpdateOrder $request, $id)
    {
        $items = $request->item_name;
        $itemsSummary = $request->item_summary;
        $hsn_sac_code = $request->hsn_sac_code;
        $cost_per_item = $request->cost_per_item;
        $quantity = $request->quantity;
        $amount = $request->amount;
        $tax = $request->taxes;
        $invoice_item_image_url = $request->invoice_item_image_url;
        $item_ids = $request->item_ids;

        if ($request->total == 0) {
            return Reply::error(__('messages.amountIsZero'));
        }

        foreach ($quantity as $qty) {
            if (!is_numeric($qty) && $qty < 1) {
                return Reply::error(__('messages.quantityNumber'));
            }
        }

        foreach ($cost_per_item as $rate) {
            if (!is_numeric($rate)) {
                return Reply::error(__('messages.unitPriceNumber'));
            }
        }

        foreach ($amount as $amt) {
            if (!is_numeric($amt)) {
                return Reply::error(__('messages.amountNumber'));
            }
        }

        foreach ($items as $itm) {
            if (is_null($itm)) {
                return Reply::error(__('messages.itemBlank'));
            }
        }

        $order = Order::findOrFail($id);

        if ($order->status == 'completed') {
            return Reply::error(__('messages.invalidRequest'));
        }

        $order->sub_total = round($request->sub_total, 2);
        $order->total = round($request->total, 2);
        $order->note = trim_editor($request->note);
        $order->show_shipping_address = $request->show_shipping_address;
        $order->discount = is_null($request->discount_value) ? 0 : $request->discount_value;
        $order->discount_type = $request->discount_type;
        $order->status = $request->has('status') ? $request->status : $order->status;
        $order->company_address_id = $request->company_address_id ?: null;
        $order->custom_order_number = $order->order_number;
        $order->save();

        // delete old data
        if (isset($item_ids) && !empty($item_ids)) {
            OrderItems::whereNotIn('id', $item_ids)->where('order_id', $order->id)->delete();
        }

        foreach ($items as $key => $item) :

            $order_item_id = isset($item_ids[$key]) ? $item_ids[$key] : 0;

            $orderItem = OrderItems::find($order_item_id);

            if ($orderItem === null) {
                $orderItem = new OrderItems();
            }

            $orderItem->order_id = $order->id;
            $orderItem->item_name = $item;
            $orderItem->item_summary = $itemsSummary[$key];
            $orderItem->type = $item;
            $orderItem->hsn_sac_code = (isset($hsn_sac_code[$key]) ? $hsn_sac_code[$key] : null);
            $orderItem->quantity = $quantity[$key];
            $orderItem->unit_price = round($cost_per_item[$key], 2);
            $orderItem->amount = round($amount[$key], 2);
            $orderItem->taxes = $tax ? (array_key_exists($key, $tax) ? json_encode($tax[$key]) : null) : null;
            $orderItem->save();

            // Save order image url
            if (isset($invoice_item_image_url[$key])) {
                OrderItemImage::create(
                    [
                        'order_item_id' => $orderItem->id,
                        'external_link' => isset($invoice_item_image_url[$key]) ? $invoice_item_image_url[$key] : ''
                    ]
                );
            }

        endforeach;

        if ($request->has('shipping_address')) {
            if ($order->client_id != null && $order->client_id != '') {
                /**
                 * @phpstan-ignore-next-line
                 */
                $client = $order->clientdetails;
            }

            if (isset($client)) {
                $client->shipping_address = $request->shipping_address;
                $client->save();
            }
        }

        if ($request->has('status') && $request->status == 'completed' && !$order->invoice) {
            $invoice = $this->makeOrderInvoice($order);
            $this->makePayment($order->total, $invoice, 'complete');
        }

        return Reply::redirect(route('orders.index'), __('messages.updateSuccess'));
    }

    public function create()
    {
        $this->addPermission = user()->permission('add_order');

        abort_403(in_array('client', user_roles()) || !in_array($this->addPermission, ['all', 'added', 'both']));

        $this->pageTitle = __('modules.orders.createOrder');
        $this->clients = User::allClients();
        $this->products = Product::all();
        $this->categories = ProductCategory::all();
        $this->unit_types = UnitType::all();
        $this->companyAddresses = CompanyAddress::all();

        $this->lastOrder = Order::lastOrderNumber() + 1;
        $this->orderSetting = invoice_setting();
        $this->zero = '';

        if ($this->orderSetting && (strlen($this->lastOrder) < $this->orderSetting->order_digit)) {
            $condition = $this->orderSetting->order_digit - strlen($this->lastOrder);

            for ($i = 0; $i < $condition; $i++) {
                $this->zero = '0' . $this->zero;
            }
        }

        if (request()->ajax()) {
            $html = view('orders.ajax.admin_create', $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'orders.ajax.admin_create';

        return view('orders.create', $this->data);

    }

    public function show($id)
    {
        $this->order = Order::with('client', 'unit')->findOrFail($id);

        $this->viewPermission = user()->permission('view_order');
        abort_403(!($this->viewPermission == 'all' || ($this->viewPermission == 'both' && ($this->order->added_by == user()->id || $this->order->client_id == user()->id)) || ($this->viewPermission == 'owned' && $this->order->client_id == user()->id) || ($this->viewPermission == 'added' && $this->order->added_by == user()->id)));

        $this->pageTitle = $this->order->order_number;

        $this->discount = 0;

        /**
         * @phpstan-ignore-next-line
         */
        if ($this->order->discount > 0) {
            /**
             * @phpstan-ignore-next-line
             */
            if ($this->order->discount_type == 'percent') {
                $this->discount = (($this->order->discount / 100) * $this->order->sub_total);
            } else {
                $this->discount = $this->order->discount;
            }
        }

        $taxList = array();

        /**
         * @phpstan-ignore-next-line
         */
        $items = OrderItems::whereNotNull('taxes')
            ->where('order_id', $this->order->id)
            ->get();

        foreach ($items as $item) {
            /**
             * @phpstan-ignore-next-line
             */
            if (isset($this->order) && $this->order->discount > 0 && $this->order->discount_type == 'percent') {
                $item->amount = $item->amount - (($this->order->discount / 100) * $item->amount);
            }

            foreach (json_decode($item->taxes) as $tax) {
                $this->tax = OrderItems::taxbyid($tax)->first();

                if (!isset($taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'])) {
                    $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = ($this->tax->rate_percent / 100) * $item->amount;
                } else {
                    $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] + (($this->tax->rate_percent / 100) * $item->amount);
                }
            }
        }

        $this->taxes = $taxList;
        $this->settings = company();
        $this->creditNote = 0;

        $this->credentials = PaymentGatewayCredentials::first();
        $this->methods = OfflinePaymentMethod::activeMethod();

        return view('orders.show', $this->data);
    }

    public function offlinePaymentModal(Request $request)
    {
        $this->orderID = $request->order_id;
        $this->methods = OfflinePaymentMethod::activeMethod();

        return view('orders.offline.index', $this->data);
    }

    /* This method will be called when payment fails from front end */

    public function stripeModal(Request $request)
    {
        $this->orderID = $request->order_id;
        $this->countries = countries();

        return view('orders.stripe.index', $this->data);
    }

    public function saveStripeDetail(StoreStripeDetail $request)
    {
        $id = $request->order_id;
        $this->order = Order::with(['client'])->findOrFail($id);
        $this->settings = $this->company;
        $this->credentials = PaymentGatewayCredentials::first();

        $client = null;

        if (isset($this->order) && !is_null($this->order->client_id)) {
            /**
             * @phpstan-ignore-next-line
             */
            $client = $this->order->client;
        }

        if (($this->credentials->test_stripe_secret || $this->credentials->live_stripe_secret) && !is_null($client)) {
            Stripe::setApiKey($this->credentials->stripe_mode == 'test' ? $this->credentials->test_stripe_secret : $this->credentials->live_stripe_secret);

            $total = $this->order->total;
            $totalAmount = $total;

            $customer = Customer::create(
                [
                    'email' => $client->email,
                    'name' => $request->clientName,
                    'address' => [
                        'line1' => $request->clientName,
                        'city' => $request->city,
                        'state' => $request->state,
                        'country' => $request->country,
                    ],
                ]
            );

            $intent = PaymentIntent::create(
                [
                    'amount' => $totalAmount * 100,
                    /**
                     * @phpstan-ignore-next-line
                     */
                    'currency' => $this->order->currency->currency_code,
                    'customer' => $customer->id,
                    'setup_future_usage' => 'off_session',
                    'payment_method_types' => ['card'],
                    'description' => $this->order->id . ' Payment',
                    'metadata' => ['integration_check' => 'accept_a_payment', 'order_id' => $id]
                ]
            );

            $this->intent = $intent;
        }

        $customerDetail = [
            'email' => $client->email,
            'name' => $request->clientName,
            'line1' => $request->clientName,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
        ];

        $this->customerDetail = $customerDetail;

        $view = view('orders.stripe.stripe-payment', $this->data)->render();

        return Reply::dataOnly(['view' => $view, 'intent' => $this->intent]);
    }

    public function paymentFailed($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->status = 'failed';
        $order->save();

        $errorMessage = null;

        if (request()->gateway == 'Razorpay') {
            $errorMessage = ['code' => request()->errorMessage['code'], 'message' => request()->errorMessage['description']];
        }

        if (request()->gateway == 'Stripe') {
            $errorMessage = ['code' => request()->errorMessage['type'], 'message' => request()->errorMessage['message']];
        }

        /* make new payment entry with status=failed and other details */
        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->currency_id = $order->currency_id;
        $payment->amount = $order->total;
        $payment->gateway = request()->gateway;
        $payment->paid_on = now();
        $payment->status = 'failed';
        $payment->payment_gateway_response = $errorMessage;
        $payment->save();

        return Reply::error(__('messages.paymentFailed'));
    }

    public function makeInvoice($orderId)
    {
        /* Step1 -  Set order status paid */
        $order = Order::findOrFail($orderId);
        $order->status = 'completed';
        $order->save();

        if (!$order->invoice) {
            /* Step2 - make an invoice related to recently paid order_id */
            $invoice = new Invoice();
            $invoice->order_id = $orderId;
            $invoice->client_id = $order->client_id;
            $invoice->sub_total = $order->sub_total;
            $invoice->total = $order->total;
            $invoice->currency_id = $order->currency_id;
            $invoice->status = 'paid';
            $invoice->note = trim_editor($order->note);
            $invoice->issue_date = now();
            $invoice->send_status = 1;
            $invoice->invoice_number = Invoice::lastInvoiceNumber() + 1;
            $invoice->due_amount = 0;
            $invoice->save();

            /* Make invoice items */
            $orderItems = OrderItems::where('order_id', $order->id)->get();

            foreach ($orderItems as $item) {
                $invoiceItem = InvoiceItems::create(
                    [
                        'invoice_id' => $invoice->id,
                        'item_name' => $item->item_name,
                        'item_summary' => $item->item_summary,
                        'type' => 'item',
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'amount' => $item->amount,
                        'product_id' => $item->product_id,
                        'unit_id' => $item->unit_id,
                        'taxes' => $item->taxes
                    ]
                );

                // Save invoice item image
                if (isset($item->orderItemImage)) {
                    $invoiceItemImage = new InvoiceItemImage();
                    $invoiceItemImage->invoice_item_id = $invoiceItem->id;
                    $invoiceItemImage->external_link = $item->orderItemImage->external_link;
                    $invoiceItemImage->save();
                }

            }
        } else {
            $invoice = $order->invoice;
        }

        /* Step3 - make payment of recently created invoice_id */
        $payment = new Payment();
        /**
         * @phpstan-ignore-next-line
         */
        $payment->invoice_id = $invoice->id;
        $payment->order_id = $orderId;
        $payment->currency_id = $order->currency_id;
        $payment->amount = request()->paymentIntent['amount'] / 100;
        $payment->payload_id = request()->paymentIntent['id'];
        $payment->gateway = 'Stripe';
        $payment->paid_on = now();
        $payment->status = 'complete';
        $payment->save();

        return Reply::success(__('Order successful'));
    }

    public function changeStatus(Request $request)
    {
        $order = Order::findOrFail($request->orderId);

        if ($request->status == 'completed') {
            $invoice = $this->makeOrderInvoice($order);
            $this->makePayment($order->total, $invoice, 'complete');
        }

        /**
         * @phpstan-ignore-next-line
         */
        if ($request->status == 'refunded' && $order->invoice && !$order->invoice->credit_note && $order->status == 'completed') {
            $this->createCreditNote($order->invoice);
        }

        $order->status = $request->status;
        $order->save();

        return Reply::success(__('messages.orderStatusChanged'));
    }

    public function createCreditNote($invoice)
    {

        DB::beginTransaction();

        $clientId = null;

        if ($invoice->client_id) {
            $clientId = $invoice->client_id;
        } elseif (!is_null($invoice->project) && $invoice->project->client_id) {
            $clientId = $invoice->project->client_id;
        }

        $creditNote = new CreditNotes();

        $creditNote->project_id = ($invoice->project_id) ? $invoice->project_id : null;
        $creditNote->client_id = $clientId;
        $creditNote->cn_number = CreditNotes::count() + 1;
        $creditNote->invoice_id = $invoice->id;
        $creditNote->issue_date = now()->format(company()->date_format);
        $creditNote->sub_total = round($invoice->sub_total, 2);
        $creditNote->discount = round($invoice->discount, 2);
        $creditNote->discount_type = $invoice->discount_type;
        $creditNote->total = round($invoice->total, 2);
        $creditNote->adjustment_amount = round(0, 2);
        $creditNote->currency_id = $invoice->currency_id;
        $creditNote->save();

        if ($invoice) {

            $invoice->credit_note = 1;

            if ($invoice->status != 'paid') {
                $amount = round($invoice->total, 2);

                if (round($invoice->total, 2) > round($invoice->total - $invoice->getPaidAmount(), 2)) {
                    // create payment for invoice total
                    if ($invoice->status == 'partial') {
                        $amount = round($invoice->total - $invoice->getPaidAmount(), 2);
                    }

                    $invoice->status = 'paid';
                } else {
                    $amount = round($invoice->total, 2);
                    $invoice->status = 'partial';
                    $creditNote->status = 'closed';

                    if (round($invoice->total, 2) == round($invoice->total - $invoice->getPaidAmount(), 2)) {
                        if ($invoice->status == 'partial') {
                            $amount = round($invoice->total - $invoice->getPaidAmount(), 2);
                        }

                        $invoice->status = 'paid';
                    }
                }
            }

            $invoice->save();
        }

        DB::commit();

        foreach ($invoice->items as $key => $item) {
            $creditNoteItem = null;

            if (!is_null($item)) {
                $creditNoteItem = CreditNoteItem::create(
                    [
                        'credit_note_id' => $creditNote->id,
                        'item_name' => $item->item_name,
                        'type' => 'item',
                        'item_summary' => $item->item_summary,
                        'hsn_sac_code' => $item->hsn_sac_code,
                        'quantity' => $item->quantity,
                        'unit_price' => round($item->unit_price, 2),
                        'amount' => round($item->amount, 2),
                        'taxes' => $item->taxes,
                    ]
                );
            }

            $invoice_item_image_url = $item->invoiceItemImage ? (!empty($item->invoiceItemImage->external_link) ? $item->invoiceItemImage->external_link : $item->invoiceItemImage->file_url) : null;
            /* Invoice file save here */
            if ($creditNoteItem && $invoice_item_image_url) {
                CreditNoteItemImage::create(
                    [
                        'credit_note_item_id' => $creditNoteItem->id,
                        'external_link' => $invoice_item_image_url,
                    ]
                );
            }
        }

        // Log search
        $this->logSearchEntry($creditNote->id, $creditNote->cn_number, 'creditnotes.show', 'creditNote');

        return Reply::redirect(route('creditnotes.index'), __('messages.recordSaved'));
    }

    public function paymentView(Payment $payment)
    {
        $order = $payment->order;
        $client = $order->client;

        $this->price_all = $order->total;

        $rates = Currency::query()
            ->where(['company_id' => company()->id])
            ->get()
            ->pluck('exchange_rate', 'id')
            ->toArray();

        if (company()->currency->currency_code == 'USD') {
            $paymentDifference = Payment::query()
                ->select(DB::raw('SUM(CASE WHEN type = "debit" THEN amount/exchange_rate ELSE 0 END) AS debit_sum'))
                ->selectRaw('SUM(CASE WHEN type = "credit" THEN amount/exchange_rate ELSE 0 END) AS credit_sum')
                ->selectRaw('SUM(CASE WHEN type = "credit" THEN  amount/exchange_rate ELSE 0 END) AS paid_credit')
                ->selectRaw('SUM(CASE WHEN type = "debit" THEN  amount/exchange_rate ELSE 0 END) AS paid_debit')
                ->where('order_id', $order->id)
                ->where('payments.paid_for','client')
                ->first();

            $difference = ($order->total / $rates[$order->currency_id]) + $paymentDifference->debit_sum - $paymentDifference->credit_sum;
        }else{
            $paymentDifference = Payment::query()
                ->select(DB::raw('SUM(CASE WHEN type = "debit" THEN amount*exchange_rate ELSE 0 END) AS debit_sum'))
                ->selectRaw('SUM(CASE WHEN type = "credit" THEN amount*exchange_rate ELSE 0 END) AS credit_sum')
                ->selectRaw('SUM(CASE WHEN type = "credit" THEN  amount*exchange_rate ELSE 0 END) AS paid_credit')
                ->selectRaw('SUM(CASE WHEN type = "debit" THEN  amount*exchange_rate ELSE 0 END) AS paid_debit')
                ->where('order_id', $order->id)
                ->where(['paid_for' => 'client'])
                ->first();

            $difference = ($order->total * $rates[$order->currency_id]) + $paymentDifference->debit_sum - $paymentDifference->credit_sum;
        }


        $this->price_paid = currency_format(($payment->amount), $payment->currency_id);

        $this->price_left = currency_format($difference, company()->currency_id);
        $this->comment = $order->note;

        $this->client_name = $client?->firstname . ' ' . $client?->lastname;
        $this->client_mobile = $client?->mobile;


        $this->operator_name = $order->operator?->name;
        $this->operator_mobile = $order->operator?->mobile;

        $this->service_fee = $order->service_fee;
        $this->people = [
            'adults' => $order->adults_count ?? 0,
            'infants' => $order->infants_count ?? 0,
            'children' => $order->children_count ?? 0,
        ];
        $products = $order->items->pluck('product_id')->toarray();
        $products = Product::query()
            ->whereIn('id', $products)
            ->get()
            ->pluck('name')
            ->toarray();

        $this->services = [
            'visa' => in_array('visa', $products),
            'insurance' => in_array('insurance', $products),
            'transfer' => in_array('transfer', $products),
            'hotel' => in_array('hotel', $products),
            'air_ticket' => in_array('airticket', $products),
        ];
        $this->order = $order;
        return view('template', $this->data);
    }

    public function createPDF($orderId, Request $request)
    {
        $order = Order::query()->findOrFail($orderId);
        $this->date = '02.06.2023';
        $this->price_all = $order->total;

        $sum = '';

        foreach ($order->payments as $payment) {
            $sum = $sum . currency_format($payment->amount, company()->currency_id) . ' | ';
        }
        $this->price_paid = $sum;

        $this->price_left = currency_format(((float)$order->total - (float)$order->total_paid), company()->currency_id);
        $this->comment = $order->note;

        $this->client_name = $order->client?->name;
        $this->client_mobile = $order->client?->mobile;

        $this->operator_name = $order->operator?->name;
        $this->operator_mobile = $order->operator?->mobile;

        $this->service_fee = $order->service_fee ?? 0;
        $this->people = [
            'adults' => $order->adults_count ?? 0,
            'children' => $order->children_count ?? 0,
        ];
        $this->services = [
            'visa' => $order->visa,
            'insurance' => $order->insurance,
            'transfer' => $order->transfer,
            'hotel' => $order->hotel,
            'air_ticket' => $order->air_ticket

        ];
        $this->order = $order;

        return view('template', $this->data);
    }


    public function download($id)
    {
        $this->invoiceSetting = invoice_setting();

        $this->order = Order::with('client', 'unit')->findOrFail($id);

        $this->viewPermission = user()->permission('view_order');
        abort_403(!($this->viewPermission == 'all' || ($this->viewPermission == 'both' && ($this->order->added_by == user()->id || $this->order->client_id == user()->id)) || ($this->viewPermission == 'owned' && $this->order->client_id == user()->id) || ($this->viewPermission == 'added' && $this->order->added_by == user()->id)));

        App::setLocale($this->invoiceSetting->locale);
        Carbon::setLocale($this->invoiceSetting->locale);

        $pdfOption = $this->domPdfObjectForDownload($id);
        $pdf = $pdfOption['pdf'];
        $filename = $pdfOption['fileName'];

        return $pdf->download($filename . '.pdf');
    }

    public function domPdfObjectForDownload($id)
    {
        $this->invoiceSetting = invoice_setting();
        $this->order = Order::with('client', 'unit')->findOrFail($id);
        App::setLocale($this->invoiceSetting->locale);
        Carbon::setLocale($this->invoiceSetting->locale);

        $this->paidAmount = $this->order->total;

        $this->discount = 0;

        if ($this->order->discount > 0) {
            if ($this->order->discount_type == 'percent') {
                $this->discount = (($this->order->discount / 100) * $this->order->sub_total);
            } else {
                $this->discount = $this->order->discount;
            }
        }

        $taxList = array();

        $items = OrderItems::whereNotNull('taxes')->where('order_id', $this->order->id)->get();

        foreach ($items as $item) {

            foreach (json_decode($item->taxes) as $tax) {
                $this->tax = OrderItems::taxbyid($tax)->first();

                if (!isset($taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'])) {

                    $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = $item->amount * ($this->tax->rate_percent / 100);
                } else {
                    $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] + ($item->amount * ($this->tax->rate_percent / 100));
                }
            }
        }

        $this->taxes = $taxList;

        $this->settings = company();

        $this->invoiceSetting = invoice_setting();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('orders.pdf.' . $this->invoiceSetting->template, $this->data);
        $filename = $this->order->order_number;

        return [
            'pdf' => $pdf,
            'fileName' => $filename
        ];
    }

    public function getclients($id)
    {
        $client_data = Product::where('unit_id', $id)->get();
        $unitId = UnitType::where('id', $id)->first();
        return Reply::dataOnly(['status' => 'success', 'data' => $client_data, 'type' => $unitId]);
    }

    public function customCreate($leadId)
    {
        $this->lead = Lead::query()->where(['id' => $leadId])->first();
        $this->invoiceSetting = invoice_setting();
        $this->zero = '';
        $this->lastOrder = Order::lastOrderNumber() + 1;
        $this->client = User::query()->findOrFail($this->lead->client_id);
        $this->currencies = Currency::all();
        $this->partners = IntegrationPartner::all();

        $this->integration = Integration::query()
            ->where(['id' => $this->lead?->integration_id])
            ->first();

        $this->view = 'orders.ajax.custom_create';
        return view('orders.create', $this->data);
    }
}
