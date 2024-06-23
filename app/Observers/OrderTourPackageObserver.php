<?php

namespace App\Observers;

use App\Models\OrderTourPackage;
use App\Models\TourPackage;

class OrderTourPackageObserver
{
    /**
     * Handle the OrderTourPackage "created" event.
     */
    public function created(OrderTourPackage $orderTourPackage): void
    {
        //
    }

    /**
     * Handle the OrderTourPackage "updated" event.
     */
    public function updated(OrderTourPackage $orderTourPackage): void
    {
        //
    }

    /**
     * Handle the OrderTourPackage "deleted" event.
     */
    public function deleted(OrderTourPackage $orderTourPackage): void
    {
        $tourPackage = TourPackage::where('id', $orderTourPackage->tour_package_id)->first();
        if ($tourPackage && $tourPackage->sold_quantity > 0) {
            $tourPackage->update([
                'sold_quantity' => $tourPackage->sold_quantity - 1,
            ]);
        }

    }

    /**
     * Handle the OrderTourPackage "restored" event.
     */
    public function restored(OrderTourPackage $orderTourPackage): void
    {
        //
    }

    /**
     * Handle the OrderTourPackage "force deleted" event.
     */
    public function forceDeleted(OrderTourPackage $orderTourPackage): void
    {
        //
    }
}
