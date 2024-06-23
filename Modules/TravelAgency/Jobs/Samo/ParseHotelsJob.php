<?php

namespace Modules\TravelAgency\Jobs\Samo;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Hotel\Models\Hotel;

class ParseHotelsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    private $state_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $state_id)
    {
        $this->data = $data;
        $this->state_id = $state_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::query()->where('email', 'samo@mail.ru')->first();


        $array = [];
        foreach ($this->data as $datas) {
            foreach ($datas as $hotel) {
                $array[] = [
                    'title' => $hotel['name'],
                    'star_rate_id' => $hotel['starGroupList'],
                    'map_lat' => $hotel['latitude'],
                    'map_lng' => $hotel['longitude'],
                    'vendor_hotel_id' => $hotel['id'],
                    'vendor_user_id' => $user->id,
                    'location_id' => $this->state_id,
                    'status' => 'publish'
                ];
            }
        }

        Hotel::query()->upsert($array, ['vendor_hotel_id', 'vendor_user_id', 'title'], ['star_rate_id', 'map_lat', 'map_lng', 'created_at', 'updated_at', 'location_id', 'status']);
    }
}
