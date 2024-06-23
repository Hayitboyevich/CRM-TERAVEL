<?php

namespace Modules\TravelAgency\Jobs\Samo;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Location\Models\LocationVendor;
use Modules\Location\Models\Vendor;

class ParseHotelTownsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $locations = [];

        $vendor = Vendor::query()->where('name', 'Like', 'samo')->first();

        foreach ($this->data['items']['item'] as $item) {
            $location = [
                'location_vendor_id' => $item['id'],
                'name' => $item['name'],
                'vendor_id' => $vendor->id
            ];

            array_push($locations, $location);


        }

        LocationVendor::query()->insert($locations);


    }
}
