<?php

namespace Modules\TravelAgency\Commands\Samo;

use App\Models\HotelsUrl;
use App\Models\User;
use Illuminate\Console\Command;
use Modules\Location\Models\LocationVendor;
use Modules\TravelAgency\Jobs\Samo\ParseHotelPhotosJob;

class ParseHotelPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:photos';

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
        $user = User::query()->where('email', 'samo@mail.ru')->first();
        $hotelUrls = HotelsUrl::query()->where('vendor_user_id', '=', $user->id)->get();
        foreach ($hotelUrls as $hotelUrl) {
            ParseHotelPhotosJob::dispatch($hotelUrl->href)->onQueue('photos');
        }

    }
}
