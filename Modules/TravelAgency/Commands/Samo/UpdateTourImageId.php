<?php

namespace Modules\TravelAgency\Commands\Samo;

use App\Models\HotelStar;
use App\Models\User;
use Illuminate\Console\Command;
use Modules\Hotel\Models\Hotel;
use Modules\Tour\Models\Tour;

class UpdateTourImageId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:tour_image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get all hotels
        $user = User::query()->where('email', 'samo@mail.ru')->first();

        $tours = Tour::query()->where('vendor_user_id', $user->id)->get();

        foreach ($tours as $tour) {
            $hotel = Hotel::where('vendor_hotel_id', $tour->vendor_hotel_id)->first();
            if ($hotel) {
                $tour->image_id = $hotel->image_id;
                $tour->banner_image_id = $hotel->banner_image_id;
                $tour->gallery = $hotel->gallery;
                $tour->save();
                $this->info("Updated tour #{$tour->id} image  with {$hotel->image_id}");
            }
        }

        $this->info('Tour images updated successfully.');
    }
}
