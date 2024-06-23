<?php

namespace App\Observers;

use App\Models\OrderItems;
use App\Models\TourPackage;
use Illuminate\Support\Facades\Log;

class OrderItemsObserver
{
    /**
     * Handle the OrderItems "created" event.
     */
    public function created(OrderItems $orderItems): void
    {
        //
    }

    /**
     * Handle the OrderItems "updated" event.
     */
    public function updated(OrderItems $orderItems): void
    {
        //
    }

    /**
     * Handle the OrderItems "deleted" event.
     */
    public function deleted(OrderItems $orderItems): void
    {
        if (!$orderItems->tour_package_id) {
            return;
        }

        // Use decrement to reduce database queries
        TourPackage::where('id', $orderItems->tour_package_id)
            ->where('sold_quantity', '>', 0)
            ->decrement('sold_quantity');
    }

    /**
     * Handle the OrderItems "restored" event.
     */
    public function restored(OrderItems $orderItems): void
    {
        //
    }

    /**
     * Handle the OrderItems "force deleted" event.
     */
    public function forceDeleted(OrderItems $orderItems): void
    {
    }
}
