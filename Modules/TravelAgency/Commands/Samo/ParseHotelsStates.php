<?php

namespace Modules\TravelAgency\Commands\Samo;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Modules\Location\Models\Location;
use Modules\Location\Models\LocationVendor;
use Modules\Location\Models\Vendor;
use Modules\Parser\Client\Curl;

class ParseHotelsStates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:hotelStats';

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

        $response = $client->get('http://parser1.kompastour.com/export/default.php', [
            'query' => [
                'samo_action' => 'api',
                'version' => '1.0',
                'oauth_token' => 'e20aee434a314bff8e05a7ec1e58b472',
                'type' => 'json',
                'action' => 'SearchHotel_STATES',
            ]
        ]);

        $hotelStates = json_decode($response->getBody(), true);

        $array = [];
        foreach ($hotelStates as $hotelState) {
            foreach ($hotelState as $item) {
                $array[] = [
                    'name' => $item['name'],
                    'nameAlt' => $item['nameAlt'],
                    'vendor_location_id' => $item['id'],
                    'parent_id' => null,
                    'vendor_user_id' => $user->id,
                    'status' => 'publish'
                ];
            }

        }
        Location::query()->upsert($array, ['vendor_location_id', 'vendor_user_id', 'name'], ['nameAlt', 'status']);

    }
}
