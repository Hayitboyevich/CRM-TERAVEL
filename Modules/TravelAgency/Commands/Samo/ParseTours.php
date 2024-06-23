<?php

namespace Modules\TravelAgency\Commands\Samo;

use DateTime;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Modules\Location\Models\Location;
use Modules\Parser\Jobs\Samo\ParseToursJob;

class ParseTours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:tours';

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
        $client = new Client();

        $states = Location::where('parent_id', '=', null)->get();
        $towns = Location::where('parent_id', '=', 37)->get();

        $today_date = Carbon::now()->format('Ymd');
        $year = date('Y');
        $date = new DateTime("$year-12-31");
        $last_day_of_year = $date->format('Ymd'); // last day of the cuurrent year
        $one_year_from_today = date('Ymd', strtotime('+1 year')); // the day after one year from today


        foreach ($towns as $town) {
            foreach ($states as $state) {
                $response = $client->get('http://parser1.kompastour.com/export/default.php', [
                    'query' => [
                        'samo_action' => 'api',
                        'version' => '1.0',
                        'oauth_token' => 'e20aee434a314bff8e05a7ec1e58b472',
                        'type' => 'json',
                        'action' => 'SearchTour_PRICES',
                        'TOWNFROMINC' => $town->vendor_location_id,
                        'STATEINC' => $state->vendor_location_id,
                        'CHECKIN_BEG' => $today_date,
                        'CHECKIN_END' => $one_year_from_today,
                        'NIGHTS_FROM' => 1,
                        'NIGHTS_TILL' => 14,
                        'ADULT' => 1,
                        'CURRENCY' => 2,
                    ]
                ]);


                $tours = json_decode($response->getBody(), true);

                $this->info('from ' . $town->name . ' to ' . $state->name . ' count of tour #' . count($tours['SearchTour_PRICES']['prices']));

                if (!empty($tours['SearchTour_PRICES']['prices'])) {
                    ParseToursJob::dispatch($tours, $town, $state)->onQueue('tours');
                }

            }
        }
    }
}
