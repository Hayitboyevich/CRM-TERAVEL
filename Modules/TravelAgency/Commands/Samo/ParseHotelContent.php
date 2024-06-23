<?php

namespace Modules\TravelAgency\Commands\Samo;

use App\Models\HotelsUrl;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Location\Models\LocationVendor;
use Modules\TravelAgency\Jobs\Samo\ParseHotelsContentJob;

class ParseHotelContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:hotel_content';

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
        try {
            $user = User::query()->where('email', 'samo@mail.ru')->first();
            $hotelUrls = HotelsUrl::query()->where('vendor_user_id', '=', $user->id)->get();
            foreach ($hotelUrls as $hotelUrl) {
                ParseHotelsContentJob::dispatch($hotelUrl->href)->onQueue('content');
            }
        } catch (Exception $e) {
            Log::error('Error occurred in ParseHotelContent handle() method: ' . $e->getMessage());
        }
    }
}
