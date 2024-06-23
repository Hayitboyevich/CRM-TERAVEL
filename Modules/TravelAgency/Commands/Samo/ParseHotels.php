<?php

namespace Modules\TravelAgency\Commands\Samo;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Modules\Location\Models\Location;
use Modules\Parser\Jobs\Samo\ParseHotelsJob;

class ParseHotels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:hotels';

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

        foreach ($states as $state) {

            $response = $client->get('http://parser1.kompastour.com/export/default.php', [
                'query' => [
                    'samo_action' => 'api',
                    'version' => '1.0',
                    'oauth_token' => 'e20aee434a314bff8e05a7ec1e58b472',
                    'type' => 'json',
                    'action' => 'SearchHotel_HOTELS',
                    'STATEINC' => $state->vendor_location_id,
                ]
            ]);


            $hotels = json_decode($response->getBody(), true);

            ParseHotelsJob::dispatch($hotels, $state->id)->onQueue('hotels');
        }

    }
}
