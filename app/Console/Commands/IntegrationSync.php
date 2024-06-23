<?php

namespace App\Console\Commands;

use App\Models\IntegrationCity;
use App\Models\IntegrationState;
use App\Models\IntegrationStates;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class IntegrationSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:integration-sync';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get states, towns for integrations';
    private string $base_url = 'http://travel.olcha.uz:4950/api/';

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle()
    {

        $integrations = Http::get($this->base_url . 'getIntegrations');
        $integrations = json_decode($integrations->body(), true);

        DB::beginTransaction();
        try {
            foreach ($integrations["data"] as $integration) {
                $towns = $this->getTowns($integration["id"]);

                foreach ($towns as $town) {
                    $integrationCity = IntegrationCity::query()
                        ->where(['name' => $town["name"]])
                        ->first();

                    if (!$integrationCity instanceof IntegrationCity) {
                        $integrationCity = new IntegrationCity();
                    }

                    $integrationCity->name = $town["name"] ?? $integrationCity->name;
                    $integrationCity->easybooking_id = Arr::get($town, "easybooking_id") ?? $integrationCity->easybooking_id;
                    $integrationCity->prestige_id = Arr::get($town, "prestige_id") ?? $integrationCity->prestige_id;
                    $integrationCity->kompastour_id = Arr::get($town, "kompastour_id") ?? $integrationCity->kompastour_id;

                    $integrationCity->save();

                }
                $states = $this->getStates($integration["id"]);
                foreach ($states as $state) {
                    $integrationState = IntegrationState::query()
                        ->where(['name' => $state["name"]])
                        ->first();

                    if (!$integrationState instanceof IntegrationState) {
                        $integrationState = new IntegrationState();
                    }
                    $integrationState->name = $state["name"];
                    $integrationState->easybooking_id = Arr::get($state, "easybooking_id");
                    $integrationState->prestige_id = Arr::get($state, "prestige_id");
                    $integrationState->kompastour_id = Arr::get($state, "kompastour_id");

                    $integrationState->save();
                }
            }
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        DB::commit();

    }

    function getTowns(int $integration_id): array
    {
        $response = Http::get($this->base_url . 'get/towns/' . $integration_id);
        $response = $response->body();
        return json_decode($response, true)["data"];
    }

    function getStates(int $integration_id): array
    {
        $response = Http::get($this->base_url . 'get/states/' . $integration_id);
        $response = $response->body();
        return json_decode($response, true)["data"];
    }
}
