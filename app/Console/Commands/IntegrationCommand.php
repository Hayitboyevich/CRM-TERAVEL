<?php

namespace App\Console\Commands;

use App\Models\IntegrationCity;
use App\Models\IntegrationState;
use App\Models\IntegrationTown;
use Illuminate\Console\Command;
use Modules\TravelAgency\Services\Integrations\EasyBookingIntegration;
use Modules\TravelAgency\Services\Integrations\KompasIntegration;
use Modules\TravelAgency\Services\Integrations\PrestigeIntegration;

class IntegrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:integration-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //todo: fromTowns, states, towns, hotels, categories, tour, programms
        //todo: 1. fromTowns, states, towns,
        $cities = IntegrationCity::query()->where('id', '=', 3)->get();
        $states = IntegrationState::query()->get();
        $easybooking = new EasyBookingIntegration();
        $kompastour = new KompasIntegration();
        $prestige = new PrestigeIntegration();

        foreach ($cities as $city) {
            foreach ($states as $state) {
                $prestige_towns = $prestige->getTowns((int)$state->prestige_id, (int)$city->prestige_id, null, null, null);
                $kompastour_towns = $kompastour->getTowns((int)$state->kompastour_id, (int)$city->kompastour_id, null, null, null);
                $easybooking_towns = $easybooking->getTowns((int)$state->easybooking_id, (int)$city->easybooking_id, null, null, null);

                foreach ($prestige_towns as $town) {
                    $integration = IntegrationTown::query()
                        ->where('name', $town['name'])
                        ->first();
                    if (!$integration) {
                        $integration = new IntegrationTown();
                    }
                    $integration->prestige_id = $town['id'];
                    $integration->name = $town['name'];
                    $integration->country_id = $state->id;
                    $integration->save();
//                    IntegrationTown::query()
//                        ->updateOrCreate(['name' => $town['name']], [
//                            'prestige_id' => $town['id'],
//                            'name' => $town['name'],
//                            'country_id' => $state->id
//                        ]);
                }
                foreach ($kompastour_towns as $town) {
                    $integration = IntegrationTown::query()
                        ->where('name', $town['name'])
                        ->first();
                    if (!$integration) {
                        $integration = new IntegrationTown();
                    }
                    $integration->easybooking_id = $town['id'];
                    $integration->name = $town['name'];
                    $integration->country_id = $state->id;
                    $integration->save();
//
//                    IntegrationTown::query()
//                        ->updateOrCreate(['name' => $town['name']], [
//                            'kompastour_id' => $town['id'],
//                            'name' => $town['name'],
//                            'country_id' => $state->id
//                        ]);
                }
                foreach ($easybooking_towns as $town) {
                    $integration = IntegrationTown::query()
                        ->where('name', $town['name'])
                        ->first();
                    if (!$integration) {
                        $integration = new IntegrationTown();
                    }
                    $integration->kompastour_id = $town['id'];
                    $integration->name = $town['name'];
                    $integration->country_id = $state->id;
                    $integration->save();
//                    IntegrationTown::query()
//                        ->updateOrCreate(['name' => $town['name']], [
//                            'easybooking_id' => $town['id'],
//                            'name' => $town['name'],
//                            'country_id' => $state->id
//                        ]);
                }
            }
        }

    }
}
