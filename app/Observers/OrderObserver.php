<?php

namespace App\Observers;

use App\Events\OrderUpdatedEvent;
use App\Models\CompanyAddress;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use App\Scopes\ActiveScope;
use App\Traits\UnitTypeSaveTrait;

class OrderObserver
{

    use UnitTypeSaveTrait;

    public function creating(Order $order)
    {
        $order->order_date = now();
        if (company()) {
            $order->added_by = user()->id;
            $order->company_id = company()->id;
        }

        $order->order_number = (int)Order::max('order_number') + 1;
    }

    public function created(Order $order)
    {
        if (isRunningInConsoleOrSeeding()) {
            return true;
        }

        if (!empty(request()->item_name)) {
            $itemsId = request()->item_ids;
            $itemsSummary = request()->item_summary;
            $cost_per_item = request()->cost_per_item;
            $hsn_sac_code = request()->hsn_sac_code;
            $quantity = request()->quantity;
            $unitId = request()->unit_id;
            $productId = request()->product_id;
            $amount = request()->amount;
            $tax = request()->taxes;
            $invoice_item_image_url = request()->invoice_item_image_url;

//            foreach (request()->item_name as $key => $item) :
//                if (!is_null($item)) {
//                    $orderItem = OrderItems::create(
//                        [
//                            'order_id' => $order->id,
//                            'item_name' => $item,
//                            'item_summary' => $itemsSummary[$key] ?: '',
//                            'type' => 'item',
//                            'unit_id' => (isset($unitId[$key]) && !is_null($unitId[$key])) ? $unitId[$key] : null,
//                            'product_id' => (isset($productId[$key]) && !is_null($productId[$key])) ? $productId[$key] : null,
//                            'hsn_sac_code' => (isset($hsn_sac_code[$key])) ? $hsn_sac_code[$key] : null,
//                            'quantity' => $quantity[$key],
//                            'unit_price' => round($cost_per_item[$key], 2),
//                            'amount' => round($amount[$key], 2),
//                            'taxes' => ($tax ? (array_key_exists($key, $tax) ? json_encode($tax[$key]) : null) : null)
//                            ]
//                    );
//
//                    // Save order image url
//                    if (isset($invoice_item_image_url[$key])) {
//                        OrderItemImage::create(
//                            [
//                                'order_item_id' => $orderItem->id,
//                                'external_link' => $invoice_item_image_url[$key] ?? ''
//                            ]
//                        );
//                    }
//
//                }
//
//                if (in_array('client', user_roles())) {
//                    OrderCart::where('client_id', user()->id)->where('product_id', $itemsId[$key])->delete();
//                }
//
//            endforeach;

        }

        // Notify client
//        $notifyUser = User::withoutGlobalScope(ActiveScope::class)->findOrFail($order->client_id);

//        if (request()->type && request()->type == 'send') {
//            event(new NewOrderEvent($order, $notifyUser));
//        }

        $this->updateLeadStatus($order);
    }

    public function saving(Order $order)
    {
        if (!isRunningInConsoleOrSeeding()) {

            if (is_null($order->company_address_id)) {
                $defaultCompanyAddress = CompanyAddress::where('is_default', 1)->first();
                $order->company_address_id = $defaultCompanyAddress ? $defaultCompanyAddress->id : null;
            }
        }

        $this->updateLeadStatus($order);
    }

    public function updated(Order $order)
    {
        // Send notification
        if (($order->isDirty('order_date') || $order->isDirty('sub_total') || $order->isDirty('total') || $order->isDirty('status') || $order->isDirty('currency_id') || $order->isDirty('show_shipping_address') || $order->isDirty('note') || $order->isDirty('last_updated_by')) && $order->added_by != null) {

            $clientId = $order->client_id ?: $order->added_by;

            // Notify client
            $notifyUser = User::withoutGlobalScope(ActiveScope::class)->findOrFail($clientId);

            event(new OrderUpdatedEvent($order, $notifyUser));

        }

        $this->updateLeadStatus($order);

    }

    public function deleting(Order $order)
    {

        $notificationModel = ['App\Notifications\NewOrder'];
        Notification::whereIn('type', $notificationModel)
            ->whereNull('read_at')
            ->where(
                function ($q) use ($order) {
                    $q->where('data', 'like', '{"id":' . $order->id . ',%');
                    $q->orWhere('data', 'like', '%,"task_id":' . $order->id . ',%');
                }
            )->delete();
    }

    protected function updateLeadStatus(Order $order)
    {
        // Find the lead with the matching client_id
        $lead = Lead::query()->where('client_id', $order->client_id)->where('company_id', $order->company_id)->first();

        if (!$lead) {
            return;
        }

        if ($order->total_paid > 0 && $order->total_paid < $order->total) {
            // Change lead status to 'Partially Paid'
            $lead->status_id = LeadStatus::query()->where('type', 'Частично оплачен')->first()->id;
        } elseif ($order->total_paid > 0 && $order->total_paid >= $order->total) {
            // Change lead status to 'Paid'
            $lead->status_id = LeadStatus::query()->where('type', 'Оплаченный')->first()->id;
        }

        $lead->save();
    }
}
