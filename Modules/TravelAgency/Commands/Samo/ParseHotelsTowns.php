<?php

namespace Modules\TravelAgency\Commands\Samo;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Modules\Location\Models\Location;
use Modules\Location\Models\LocationVendor;
use Modules\Location\Models\Vendor;
use Modules\Parser\Client\Curl;

class ParseHotelsTowns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:hotelTowns';

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
        $user = User::query()->where('email', 'samo@mail.ru')->first();

        $states = Location::where('parent_id', '=', null)->get();

        $array = [];
        foreach ($states as $state) {
            $response = $client->get('http://parser1.kompastour.com/export/default.php', [
                'query' => [
                    'samo_action' => 'api',
                    'version' => '1.0',
                    'oauth_token' => 'e20aee434a314bff8e05a7ec1e58b472',
                    'type' => 'json',
                    'action' => 'SearchHotel_TOWNS',
                    'STATEINC' => $state->vendor_location_id,
                ]
            ]);

            $hotelTowns = json_decode($response->getBody(), true);


            foreach ($hotelTowns['SearchHotel_TOWNS'] as $hotelTown) {
                if (is_array($hotelTown)) {
                    $array[] = [
                        'name' => $hotelTown['name'],
                        'nameAlt' => $hotelTown['nameAlt'],
                        'vendor_location_id' => $hotelTown['id'],
                        'parent_id' => $state->id,
                        'vendor_user_id' => $user->id,

                    ];
                }
            }

        }

        Location::query()->upsert($array, ['vendor_location_id', 'vendor_user_id', 'name'], ['nameAlt', 'parent_id']);

    }
}
