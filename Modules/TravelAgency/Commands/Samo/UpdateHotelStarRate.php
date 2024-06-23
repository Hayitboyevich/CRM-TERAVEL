<?php

namespace Modules\TravelAgency\Commands\Samo;

use App\Models\HotelStar;
use App\Models\User;
use Illuminate\Console\Command;
use Modules\Hotel\Models\Hotel;

class UpdateHotelStarRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:star_rate';

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

        $hotels = Hotel::query()->where('vendor_user_id', $user->id)->get();

        foreach ($hotels as $hotel) {
            // Get media file with matching name
            $star_rate = HotelStar::where('vendor_star_id', $hotel->star_rate_id)->first();
            // If there is a match, update hotel's gallery with media file ID
            if ($star_rate) {
                $hotel->star_rate = $star_rate->name;
                $hotel->save();
                $this->info("Updated hotel #{$hotel->id} with star rate {$star_rate->name}");
            }
        }

        $this->info('Hotel star rates updated successfully.');
    }
}
