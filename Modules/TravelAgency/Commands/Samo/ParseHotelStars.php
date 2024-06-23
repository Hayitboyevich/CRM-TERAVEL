<?php

namespace Modules\TravelAgency\Commands\Samo;

use App\Models\HotelStar;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Modules\Location\Models\Location;

class ParseHotelStars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:hotel_stars';

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
        $user = User::query()->where('email', 'samo@mail.ru')->first();


        foreach ($states as $state) {

            $response = $client->get('http://parser1.kompastour.com/export/default.php', [
                'query' => [
                    'samo_action' => 'api',
                    'version' => '1.0',
                    'oauth_token' => 'e20aee434a314bff8e05a7ec1e58b472',
                    'type' => 'json',
                    'action' => 'SearchHotel_STARS',
                    'STATEINC' => $state->vendor_location_id,
                ]
            ]);


            $stars = json_decode($response->getBody(), true);

            $array = [];
            foreach ($stars['SearchHotel_STARS'] as $star) {
                if (is_array($star)) {
                    $array[] = [
                        'vendor_star_id' => $star['id'],
                        'name' => $star['name'],
                        'nameAlt' => $star['nameAlt'],
                        'vendor_user_id' => $user->id,
                    ];
                }
            }

            HotelStar::query()->upsert($array, ['vendor_star_id'], ['name', 'nameAlt', 'vendor_user_id']);

        }

    }
}
