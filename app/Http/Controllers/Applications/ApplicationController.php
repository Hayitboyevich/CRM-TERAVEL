<?php

namespace App\Http\Controllers\Applications;

use App\DataTables\ApplicationsDataTable;
use App\DataTables\LeadApplicationsDataTable;
use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Http\Requests\OrderItemRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Http\Requests\UpdateOrderItemRequest;
use App\Models\Application;
use App\Models\ApplicationOrderNumber;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Hotel;
use App\Models\Integration;
use App\Models\IntegrationCity;
use App\Models\IntegrationPartner;
use App\Models\IntegrationState;
use App\Models\IntegrationTown;
use App\Models\Lead;
use App\Models\LeadAgent;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\Order;
use App\Models\OrderApplication;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\Schema;
use App\Models\SchemaSeat;
use App\Models\TourBedType;
use App\Models\TourMealType;
use App\Models\TourPackage;
use App\Models\TourService;
use App\Models\TourType;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Mollie\Api\Types\OrderStatus;
use const _PHPStan_5473b6701\__;

class ApplicationController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('app.order');

    }

    public function createSchema(Application $application, Schema $schema)
    {
        $this->schema = $schema;
        $this->application = $application;
        $this->pageTitle = __('app.schema');
        return view('applications.schemas.modal', $this->data);
    }

    public function bookSchema(Application $application, Schema $schema, Request $request)
    {
        $seat_ids = $request->seat_ids;
        $t = SchemaSeat::query()
            ->where('schema_id', $schema->id)
            ->whereIn('cell', $seat_ids)
            ->update(
                [
                    'application_id' => $application->id
                ]
            );
        return Reply::success(__('messages.recordSaved'));

    }

    public function update(int $applicationId, UpdateApplicationRequest $request)
    {
        $data = $request->validated();
        $application = Application::query()->findOrFail($applicationId);
        $application->fill($data);
        $application->order?->update($data);

        $application->save();

        return Reply::success(__('messages.recordSaved'));

    }

    public function changeOrderStatus(Request $request)
    {
        $order = OrderItems::findOrFail($request->orderId);

//        if ($request->status == 'completed') {
//            $invoice = $this->makeOrderInvoice($order);
//            $this->makePayment($order->total, $invoice, 'complete');
//        }

        /**
         * @phpstan-ignore-next-line
         */
//        if ($request->status == 'refunded' && $order->invoice && !$order->invoice->credit_note && $order->status == 'completed') {
//            $this->createCreditNote($order->invoice);
//        }

        $order->status = $request->status;
        $order->save();

        return Reply::success(__('messages.orderStatusChanged'));
    }

    public function addViaLead(Lead $lead)
    {
        $user = User::query()->findOrFail($lead->client_id);

        $application = new Application();
        $application->status_id = LeadStatus::query()->where('priority', 2)->first()->id;
        $application->company_id = company()->id;
//        $application->source_id = $lead?->source_id ?? null;
        $application->agent_id = LeadAgent::query()->where('user_id', auth()->id())->first()->id;
        $application->client_id = $user->id;
        $application->save();

        // Generate the redirect URL
        $redirectUrl = route('applications.edit', $application->id);

        return Reply::successWithData(__('messages.recordSaved'), [
            'application' => $application,
            'redirectUrl' => $redirectUrl  // Include the redirect URL in the response
        ]);
    }


    /**
     * XXXXXXXXXXX
     *
     * @return Response
     */
    public function applyQuickAction(Request $request)
    {
        switch ($request->action_type) {
            case 'delete':
                $this->deleteRecords($request);
                return Reply::success(__('messages.deleteSuccess'));
            case 'change-status':
                $this->changeStatus($request);
                return Reply::success(__('messages.updateSuccess'));
            default:
                return Reply::error(__('messages.selectAction'));
        }
    }

    protected function deleteRecords($request)
    {
//        abort_403(user()->permission('delete_application') !== 'all');
        $mails = Application::whereIn('id', explode(',', $request->row_ids))->get();
        $mails->each(function ($user) {
            $this->deleteApplication($user);
        });
        return true;
    }

    private function deleteApplication(Application $application)
    {
        $application->delete();
    }

    public function addPackage(Application $application, TourPackage $package)
    {
        DB::transaction(function () use ($application, $package) {
            try {
                if (!$application->order) {
                    $order = new Order();
                    $order->application_id = $application->id;
                } else {
                    $order = $application->order;
                }

                $order->company_id = company()->id;
//                $order->client_id = $application->client_id;
                $order->currency_id = $package->currency_id;

                $price = $package->price / $package->exchange_rate;
                $netPrice = $package->net_price / $package->exchange_rate;

                $order->total = $order->total + $price;
                $order->net_price = $order->net_price + $netPrice;

                $order->partner_id = $package->partner_id;
                $order->client_id = $application?->client_id;

                $order->save();
                $order->tourPackages()->create(['tour_package_id' => $package->id, 'company_id' => company()->id, 'order_id' => $order->id]);


                $orderItem = new OrderItems();
                $orderItem->order_id = $order->id;
                $orderItem->tour_package_id = $package->id;
                $orderItem->schema_id = $package->schema_id;
                $orderItem->date_from = $package->date_from;
                $orderItem->date_to = $package->date_to;
                $orderItem->item_name = '' . $package->name;

                $orderItem->nett_currency_id = $package->net_currency_id;
                $orderItem->nett_exchange_rate = $package->net_exchange_rate;
                $orderItem->unit_net_price = $package->net_price;
                $orderItem->nett_amount = $package->net_price;

                $orderItem->exchange_rate = $package->exchange_rate;
                $orderItem->currency_id = $package->currency_id;
                $orderItem->unit_price = $package->price;
                $orderItem->amount = $package->price;

                $orderItem->partner_id = $package->partner_id;
                $orderItem->quantity = 1;
                $orderItem->unit_id = 1;

                $orderItem->region_id = $package->region_id;
                $orderItem->country_id = $package->country_id;

                $orderItem->adults_count = $package->adults_count;
                $orderItem->children_count = $package->children_count;
                $orderItem->infants_count = $package->infants_count;

                $package->sold_quantity++;
                $package->save();

                $orderItem->product_id = Product::query()
                    ->where('company_id', company()->id)
                    ->where('name', 'tourpackage')
                    ->first()?->id;
                $orderItem->save();

            } catch (Exception $e) {
                dd($e);
            }
        });
//        dd($package);
        return redirect()->route('applications.edit', $application->id);
    }

    public function addService(Application $application, TourService $service)
    {
        $product = Product::query()
            ->where('company_id', company()->id)
            ->where('name', 'tourservice')
            ->first();
        $usdExchangeRate = Currency::query()
            ->where('company_id', company()->id)
            ->where('currency_code', 'USD')
            ->first()->exchange_rate;
        DB::transaction(function () use ($application, $service, $product, $usdExchangeRate) {
//            try {
            if (!$application->order) {
                $order = new Order();
                $order->application_id = $application->id;
            } else {
                $order = $application->order;
            }

            $order->company_id = company()->id;
            $order->client_id = $application->client_id;
            $order->service_id = $service->id;

            $order->currency_id = $service->currency_id;
            $order->total = $order->total + ($service->price ?? 0);
            $order->net_price = $order->net_price + $service->net_price;
            $order->partner_id = $service->partner_id;
            $order->name = $product->description;
            $order->save();

            $orderItem = new OrderItems();
            $orderItem->order_id = $order->id;
            $orderItem->schema_id = $service->schema_id;

            $orderItem->date_from = $service->date_from;
            $orderItem->date_to = $service->date_to;
            $orderItem->item_name = '' . $service?->product?->description;

            $orderItem->nett_currency_id = $service->net_currency_id;
            $orderItem->nett_exchange_rate = $service->net_exchange_rate;


            $orderItem->exchange_rate = $service->exchange_rate;
            $orderItem->currency_id = $service->currency_id;
            $orderItem->partner_id = $service->partner_id;
            $orderItem->quantity = 1;
            $orderItem->unit_id = 1;

            $price = $service->price / $service->exchange_rate;
            $netPrice = $service->net_price / $service->net_exchange_rate;

            $orderItem->unit_net_price = $netPrice;
            $orderItem->nett_amount = $netPrice;

            $orderItem->unit_price = $price;
            $orderItem->amount = $price;

            $orderItem->adults_count = $service->adults_count;
            $orderItem->infants_count = $service->infants_count;
            $orderItem->children_count = $service->children_count;

            $orderItem->product_id = $product->id;
            $orderItem->save();


//            } catch (Exception $e) {
//                dd($e);
//                throw $e;
//            }
        });
        return redirect()->route('applications.edit', $application->id);

    }

    public function findService(Application $application)
    {
        $this->application = $application;
        $this->view = 'applications.services.find-service';
        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('applications.packages.template', $this->data);
    }

    public function findPackage(Application $application)
    {
        $this->application = $application;
        $this->view = 'applications.packages.find-package';
        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('applications.packages.template', $this->data);
    }

    public function index(ApplicationsDataTable $dataTable)
    {

        $this->pageTitle = __('app.order');

        return $dataTable->render('applications.index', $this->data);
    }

    public function indexLeadApplication($clientId)
    {
        $dataTable = new LeadApplicationsDataTable($clientId);
        $this->pageTitle = __('app.clientOrders');

        return $dataTable->render('applications.index', $this->data);
    }

    public function storePackage(Application $application, OrderItemRequest $request)
    {
        $data = $request->validated();
        $partner_id = Arr::get($data, 'partner_id');

        $data['visa'] = Arr::get($data, 'visa') === 'on' ?? false;
        $data['insurance'] = Arr::get($data, 'insurance') === 'on' ?? false;
        $data['transfer'] = Arr::get($data, 'transfer') === 'on' ?? false;
        $data['airticket'] = Arr::get($data, 'airticket') === 'on' ?? false;
        $data['status'] = OrderStatus::STATUS_PENDING;

        $data['client_id'] = $application->client_id;
        $startDate = Carbon::parse(Arr::get($data, 'fromData'));
        $endDate = Carbon::parse(Arr::get($data, 'toDate'));
        $nightsCount = $endDate->diffInDays($startDate);
        $data['nights_count_from'] = $nightsCount;
        $data['nights_count_to'] = $nightsCount;
        $products = Product::query()
            ->where('company_id', company()->id)
            ->get()->pluck('id', 'name')->all();
        DB::beginTransaction();
        try {
            $peopleCount = (intval(Arr::get($data, 'adults_count')) + intval(Arr::get($data, 'babies_count')) + intval(Arr::get($data, 'children_count')));

            $integration = Integration::query()->create($data);
            $data['integration_id'] = $integration->id;
            $data['total'] = $data['unit_price'];
            $data['net_price'] = $data['unit_net_price'];
            $order = $application->order;

            $price = $data['total'] / $data['exchange_rate'];
            $netPrice = $data['net_price'] / $data['nett_exchange_rate'];

            if (company()->currency->currency_code != 'USD') {
                $price = $data['total'] * $data['exchange_rate'];
                $netPrice = $data['net_price'] * $data['nett_exchange_rate'];
            }

            if (!$application->order) {
                $order = new Order();
                $order->application_id = $application->id;
                $order->name = 'Пакетный тур';
                $data['total'] = $price;
                $data['net_price'] = $netPrice;
            } else {
                $data['total'] = $order->total + $price;
                $data['net_price'] = $order->net_price + $netPrice;
            }
            $temp = $data['currency_id'];
            $netTemp = $data['nett_currency_id'];

            $data['currency_id'] = Currency::query()
                ->where('currency_code', company()->currency->currency_code)
                ->where('company_id', company()->id)->first()->id;

            $data['nett_currency_id'] = $data['currency_id'];
            $data['client_id'] = $application->client_id;
            $order->fill($data);

            $order->save();

            $data['currency_id'] = $temp;
            $data['nett_currency_id'] = $netTemp;


            if ($partner_id) {
                $item['partner_id'] = $partner_id;
            }
            $item['order_id'] = $order->id;
            $item['product_id'] = $products['tourpackage'] ?? null;
            $item['item_name'] = 'Пакетный тур';

            $item['unit_price'] = $data['unit_price'];

            $item['quantity'] = $peopleCount;
            $item['amount'] = $data['unit_price'];

            $item['from_city_id'] = $data['from_city_id'];
            $item['country_id'] = $data['country_id'];
            $item['region_id'] = $data['region_id'];
            $item['hotel_name'] = $data['hotel_name'];

            $item['nett_amount'] = $data['unit_net_price'];
            $item['unit_net_price'] = $data['unit_net_price'];

            $item['nett_currency_id'] = $data['nett_currency_id'];
            $item['currency_id'] = $data['currency_id'];
            $item['nett_exchange_rate'] = $data['nett_exchange_rate'];
            $item['exchange_rate'] = $data['exchange_rate'];
            $item['unit_id'] = 1;

            $item['date_from'] = date('Y-m-d', strtotime($data['date_from']));
            $item['date_to'] = date('Y-m-d', strtotime($data['date_to']));

            $item['children_count'] = $data['children_count'];
            $item['adults_count'] = $data['adults_count'];
            $item['infants_count'] = $data['infants_count'];

            $item['type_id'] = $data['type_id'] ?? null;
            $item['status'] = 'pending';
            OrderItems::query()
                ->create($item);

            unset($item['nett_amount']);
            unset($item['unit_net_price']);
            unset($item['unit_price']);

            unset($item['partner_id']);
            if ($data['visa']) {
                $item['order_id'] = $order->id;
                $item['product_id'] = $products['visa'] ?? null;
                $item['item_name'] = 'Visa';
                $item['quantity'] = 1;
                $item['amount'] = 0;
                $item['unit_price'] = 0;
                $item['unit_id'] = 1;

                OrderItems::query()
                    ->create($item);
            }
            if ($data['transfer']) {
                $item['order_id'] = $order->id;
                $item['product_id'] = $products['transfer'] ?? null;
                $item['item_name'] = 'Transfer';
                $item['quantity'] = 1;
                $item['amount'] = 0;
                $item['unit_price'] = 0;
                $item['unit_id'] = 1;

                OrderItems::query()
                    ->create($item);
            }
            if ($data['airticket']) {
                $item['order_id'] = $order->id;
                $item['product_id'] = $products['airticket'] ?? null;
                $item['item_name'] = 'Авиабилет';
                $item['quantity'] = 1;
                $item['amount'] = 0;
                $item['unit_price'] = 0;
                $item['unit_id'] = 1;

                OrderItems::query()
                    ->create($item);
            }
            if ($data['insurance']) {
                $item['order_id'] = $order->id;
                $item['product_id'] = $products['insurance'] ?? null;
                $item['item_name'] = 'Insurance';
                $item['quantity'] = 1;
                $item['amount'] = 0;
                $item['unit_price'] = 0;
                $item['unit_id'] = 1;

                OrderItems::query()
                    ->create($item);
            }
            $item['order_id'] = $order->id;
            $item['product_id'] = $products['hotel'] ?? null;
            $item['item_name'] = 'Отель';
            $item['quantity'] = 1;
            $item['amount'] = 0;
            $item['unit_price'] = 0;
            $item['unit_id'] = 1;

            OrderItems::query()
                ->create($item);

        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();


        $redirectUrl = urldecode($request->redirect_url);
        if ($redirectUrl == '') {
            $redirectUrl = route('applications.edit', $application->id);
        }
        if ($request->has('ajax_create')) {
            return Reply::successWithData(__('messages.recordSaved'), ['orderData' => $order, 'redirectUrl' => $redirectUrl]);

        }
        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);

    }

    public function create()
    {
        $application = new Application();
        $application->status_id = LeadStatus::query()->where('priority', 2)->first()->id;
        $application->company_id = company()->id;
        $agent = LeadAgent::query()
            ->firstOrCreate(['user_id' => auth()->id()], ['user_id' => auth()->id()]);

        $application->agent_id = $agent->id;

        $application->save();


        return redirect()->route('applications.edit', $application->id);
    }

    public function createPackage(Application $application)
    {
        $this->pageTitle = __('app.addPackage');
        $this->partners = IntegrationPartner::query()->where('company_id', company()->id)->get();
        $this->countries = Country::query()->get();
        $this->cities = City::select('id', 'name')->paginate(20);
        $this->mealTypes = TourMealType::query()->get();
        $this->bedTypes = TourBedType::query()->get();
        $this->hotels = Hotel::query()->where('company_id', company()->id)->get();

        $this->currencyCode = company()->currency->currency_code;
        $this->currencies = Currency::query()->where('company_id', company()->id)->get();
        $this->application = $application;

        $this->view = 'applications.packages.application-create';
        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('applications.packages.template', $this->data);
    }

    public function updatePackage(OrderItems $orderItem, OrderItemRequest $request)
    {
        $data = $request->validated();
        $data['date_from'] = date('Y-m-d', strtotime($data['date_from']));
        $data['date_to'] = date('Y-m-d', strtotime($data['date_to']));

        $usd = currency_get('USD');
        //get old prices
        $oldUnitPrice = $orderItem->unit_price;
        $oldExchangeRate = $orderItem->exchange_rate;
        $oldNetExchangeRate = $orderItem->nett_exchange_rate;

        $oldNetUnitPrice = $orderItem->unit_net_price;
        //calculate old people count
        $oldItems = $orderItem->children_count + $orderItem->infants_count + $orderItem->adults_count;


        //calculate new people count
        $newItems = Arr::get($data, 'children_count') +
            Arr::get($data, 'infants_count') +
            Arr::get($data, 'adults_count');


        //calculate new price
        $newUnitPrice = $data['unit_price'];
        $newNetUnitPrice = $data['unit_net_price'];


        $orderItem->fill($data);
        //calculate total changed amount
        $orderItem->amount = $orderItem->amount - $oldUnitPrice * $oldItems + $newUnitPrice * $newItems;
        $orderItem->nett_amount = $orderItem->nett_amount - $oldNetUnitPrice * $oldItems + $newNetUnitPrice * $newItems;
        //save process
        DB::beginTransaction();
        try {
            $orderItem->save();
            $order = $orderItem->order;
//        dd($order->total, $order->net_price, $orderItem->amount, $orderItem->nett_amount, $oldUnitPrice, $oldNetUnitPrice, $newUnitPrice, $newNetUnitPrice);
//            dd($order->total, $oldUnitPrice, $newUnitPrice);
            if ($data["currency_id"] != $usd->id) {
                $newOrderPrice = ($oldUnitPrice * $oldItems - $newUnitPrice * $newItems) / $data["exchange_rate"];
            } else {
                $newOrderPrice = ($oldUnitPrice * $oldItems * $oldExchangeRate - $newUnitPrice * $newItems / $data["exchange_rate"]);
            }
            if ($data["currency_id"] != $usd->id) {
                $newOrderNetPrice = ($oldNetUnitPrice * $oldItems - $newNetUnitPrice * $newItems) / $data["nett_exchange_rate"];
            } else {
                $newOrderNetPrice = ($oldNetUnitPrice * $oldItems * $oldNetExchangeRate - $newNetUnitPrice * $newItems / $data["nett_exchange_rate"]);

            }

            $order->total = $order->total - $newOrderPrice;
            $order->net_price = $order->net_price - $newOrderNetPrice;

//            dd($order->total, $order->net_price, $orderItem->amount, $orderItem->nett_amount, $oldUnitPrice, $oldNetUnitPrice, $newUnitPrice, $newNetUnitPrice);

            $order->save();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();


        $redirectUrl = urldecode($request->redirect_url);
        if ($redirectUrl == '') {
            $redirectUrl = route('applications.edit', $orderItem->order?->application_id);
        }
        if ($request->has('ajax_create')) {
            return Reply::successWithData(__('messages.recordSaved'), ['orderData' => $orderItem, 'redirectUrl' => $redirectUrl]);

        }
        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);

    }

    public function editPackage(Application $application, OrderItems $orderItem)
    {
        $this->pageTitle = __('app.editPackage');
        $this->partners = IntegrationPartner::all();
        $this->countries = IntegrationState::all();
        $this->cities = IntegrationTown::all();
        $this->hotels = Hotel::all();

        $this->mealTypes = TourMealType::all();
        $this->bedTypes = TourBedType::all();
        $this->fromCities = IntegrationCity::all();
        $this->currencyCode = company()->currency->currency_code;
        $this->currencies = Currency::query()->where('company_id', company()->id)->get();
        $this->application = $application;
        $this->orderItem = $orderItem;
        $this->view = 'applications.packages.application-edit';
        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('applications.packages.template', $this->data);
    }

    public function createService()
    {
        $this->pageTitle = __('app.addPackage');
        if (request()->ajax()) {
            $html = view('applications.services.create', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'applications.services.create';
        return view('applications.services.template', $this->data);

    }

    public function storeOrder(OrderItemRequest $request)
    {
        $application = new OrderApplication();
        $application->agent_id = $request->agent_id;
        $application->type_id = $request->type_id;
        $application->source_id = $request->source_id;
        $application->partner_id = $request->partner_id;
        $application->save();

        return Reply::success(__('messages.recordSaved'));
    }

    public function storeOrderNumber(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'order_number' => 'required',
                'partner_id' => 'required',
                'application_id' => 'required'
            ]);

            $orderNumber = ApplicationOrderNumber::create([
                'order_number' => $validatedData['order_number'],
                'partner_id' => $validatedData['partner_id'],
                'company_id' => company()->id,
                'application_id' => $validatedData['application_id'],
            ]);

            return response()->json(['orderNumber' => $orderNumber]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function showDocument($id)
    {
        $application = Application::with('client', 'travelers', 'order.items')->findOrFail($id);
        foreach ($application->order->items as $item) {
            $region = $item->region->name;
            $date_from = $item->date_from;
            $date_to = $item->date_to;
            $hotel_name = $item->hotel?->name;
            $bed_type = $item->beadType->name;
            $meal_type = $item->mealType?->name;
            $from_city = $item->fromCity->name;
            break;
        }
        $this->birthday = $application?->client?->birthday?->format('d.m.Y');
        $this->item_names = $application->order->items->pluck('item_name')->toArray();
        $this->from_city = $from_city;
        $this->meal_type = $meal_type;
        $this->region = $region;
        $this->bed_type = $bed_type;
        $this->date_from = date('d.m.Y', strtotime($date_from));
        $this->date_to = (!empty($date_to) ? date('d.m.Y', strtotime($date_to)) : '');
        $this->nights = (!empty($date_to) ? Carbon::parse($date_from)->diffInDays(Carbon::parse($date_to)) : '');
        $this->hotel_name = $hotel_name;
        $this->client = $application->client;
        $this->client_full_name = $application->client->firstname . ' ' . $application->client->lastname . ' ' . $application->client->fathername;

        $this->current_date = Carbon::now()->format('d.m.Y');
        $order = $application->order;
        $this->travelers = $application->travelers;

        $exchange_rate = Currency::query()
            ->where('company_id', company()->id)
            ->where('currency_code', currency_get_by_id(company()->currency->id)->currency_code)->first()->exchange_rate;
        $this->total_price = $order?->total / $exchange_rate;
        $this->html_data = view('applications.templates.agreement', $this->data)->render();

        $this->view = 'applications.templates.document';

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }
        return view($this->view, $this->data);

//        return view('applications.templates.document', $this->data);
    }

    public function fetchOrderNumbers(Request $request, $applicationId)
    {
        $orderNumbers = ApplicationOrderNumber::join('integration_partners', 'application_order_numbers.partner_id', '=', 'integration_partners.id')
            ->where('application_order_numbers.application_id', $applicationId)
            ->select('application_order_numbers.*', 'integration_partners.name as partner_name')
            ->get();

        // Return the order numbers as JSON response
        return response()->json(['orderNumbers' => $orderNumbers]);
    }

    public function deleteOrderNumber(Request $request)
    {
        // Validate the request data
        $request->validate([
            'order_number_id' => 'required|exists:application_order_numbers,id',
        ]);

        // Retrieve the order number ID from the request
        $orderNumberId = $request->input('order_number_id');

        // Find the order number by ID and delete it
        $orderNumber = ApplicationOrderNumber::find($orderNumberId);
        $orderNumber->delete();

        // Return a success response
        return response()->json(['message' => 'Order number deleted successfully']);
    }

    public function download(Request $request)
    {
        $this->doc_data = $request->data;


        $pdf = app('dompdf.wrapper');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'times-new-roman']);
        $pdf->loadView('applications.templates.' . 'doc', $this->data);
        $filename = 'doc';

        return $pdf->download($filename . '.pdf');
    }

    public function edit(Application $application)
    {
        $this->pageTitle = __('app.editOrder');
        $this->leadAgents = LeadAgent::all();
        $this->sources = LeadSource::all();
        $this->tourTypes = TourType::all();

        $this->partners = IntegrationPartner::query()
            ->where('company_id', company()->id)
            ->get();

        $this->clientDeadline = $application->clientDeadline;
        $this->partnerDeadline = $application->partnerDeadline;

        $this->application = $application;
        $order = $this->application?->order;
        $this->order = $order;

        $this->orderNetPrice = 0;
        $this->orderPrice = 0;
        $this->partnerPrice = 0;
        $this->clientPrice = 0;

        $this->usd_exchange_rate = Currency::query()
            ->where('company_id', company()->id)
            ->where('currency_code', 'USD')->first()->exchange_rate;

        $this->exchange_rate = Currency::query()
            ->where('company_id', company()->id)
            ->where('currency_code', currency_get_by_id(company()->currency->id)->currency_code)->first()->exchange_rate;


        if ($order) {
            $this->orderPrice = $order->total;
            $this->orderNetPrice = $order->net_price;

        }

        $this->partnerPrice = $order?->net_price / $this->exchange_rate;
        $this->clientPrice = $order?->total / $this->exchange_rate;

        $countries = Country::all();
        $this->clientData = [
            "address" => $this?->application?->client?->localPassport?->place_of_birth ?? null,
            "addressLiving" => $countries->where('id', $this?->application?->client?->localPassport?->living_country_id)->first()->name ?? null,
            'birthday' => $this->application?->client?->birthday,
            "birthdayCertificate" => [
                "number" => '',
                "issueDate" => '',
                "authority" => ""
            ],
            "birthdayPlace" => $this->application?->address,
            "email" => $this->application?->client?->email,
            "inn" => '',
            "internationalPassport" => [
                "authority" => $this?->application?->client?->foreignPassport?->passport_given_by ?? null,
                "expire" => $this?->application?->client?->foreignPassport?->date_of_expiry ?? null,
                "issueDate" => $this?->application?->client?->foreignPassport?->given_date ?? null,
                "name" => $this?->application?->client?->foreignPassport?->first_name ?? null,
                "number" => substr($this?->application?->client?->foreignPassport?->passport_serial_number, 2),
                "serial" => substr($this?->application?->client?->foreignPassport?->passport_serial_number, 0, 2),
                "surname" => $this?->application?->client?->foreignPassport?->last_name ?? null,
            ],
            "nationalPassport" => [
                "authority" => $this?->application?->client?->localPassport?->passport_given_by ?? null,
                "issueDate" => $this?->application?->client?->localPassport?->given_date ?? null,
                "number" => substr($this?->application?->client?->localPassport?->passport_serial_number, 2),
                "serial" => substr($this?->application?->client?->localPassport?->passport_serial_number, 0, 2),
            ],
            "nationality" => $this?->application?->client?->localPassport?->nationality ?? null,
            "nationalityEng" => $this?->application?->client?->localPassport?->nationality ?? null,
            "phones" => [
                "home" => '',
                "main" => '',
                "mobile" => $this->application?->client?->mobile,
            ],
            "name" => $this->application?->client?->firstname,
            "secondName" => $this->application?->client?->fathername,
            "surname" => $this->application?->client?->lastname,
            "sex" => $this->application?->client?->gender,
            "title" => $this->application?->client?->salutation,
        ];

        $passengersData = [];
        foreach ($this->application?->travelers as $traveler) {
            $passengersData[] = [
                "address" => $traveler?->localPassport?->place_of_birth ?? null,
                "addressLiving" => $countries->where('id', $traveler?->localPassport?->living_country_id)->first()->name ?? null,
                'birthday' => $traveler?->birthday ?? null,
                "birthdayCertificate" => [
                    "number" => '',
                    "issueDate" => '',
                    "authority" => ""
                ],
                "birthdayPlace" => $traveler?->localPassport?->place_of_birth,
                "email" => $traveler?->email,
                "inn" => '',
                "internationalPassport" => [
                    "authority" => $traveler?->foreignPassport?->passport_given_by,
                    "expire" => $traveler?->foreignPassport?->date_of_expiry,
                    "issueDate" => $traveler?->foreignPassport?->given_date,
                    "name" => $traveler?->foreignPassport?->first_name,
                    "number" => substr($traveler?->foreignPassport?->passport_serial_number, 2),
                    "serial" => substr($traveler?->foreignPassport?->passport_serial_number, 0, 2),
                    "surname" => $traveler?->foreignPassport?->last_name,
                ],
                "nationalPassport" => [
                    "authority" => $traveler?->localPassport?->passport_given_by,
                    "issueDate" => $traveler?->localPassport?->given_date,
                    "number" => substr($traveler?->localPassport?->passport_serial_number, 2),
                    "serial" => substr($traveler?->localPassport?->passport_serial_number, 0, 2),
                ],
                "nationality" => $traveler?->localPassport?->nationality ?? null,
                "nationalityEng" => $traveler?->localPassport?->nationality ?? null,
                "phones" => [
                    "home" => '',
                    "main" => '',
                    "mobile" => $traveler?->mobile,
                ],
                "name" => $traveler?->firstname,
                "secondName" => $traveler?->fathername,
                "surname" => $traveler?->lastname,
                "sex" => $traveler?->gender,
                "title" => $traveler?->localPassport?->salutation,
            ];

        }
        $this->passengersData = $passengersData;


        $this->view = 'applications.ajax.edit';
        return view('applications.create', $this->data);
    }

    public function destroy($id)
    {
        $application = Application::findOrFail($id);
        $application->delete();

        return response()->json(['success' => true]);
    }

}
