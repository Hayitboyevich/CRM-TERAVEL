<?php

namespace Modules\TravelAgency\Services\Integrations;

use Illuminate\Support\Arr;
use Modules\TravelAgency\Services\BaseIntegration;
use Modules\TravelAgency\Services\IntegrationInterface;

class EasyBookingIntegration extends BaseIntegration implements IntegrationInterface
{

    public function integrationName(): string
    {
        return 'easybooking';
    }

    public function token(): string
    {
        return '230d507d578f434d96ed2055c824d5c0';
    }

    public function version(): string
    {
        return '1.0';
    }

    public function getFromTowns(): array
    {
        return $this->sendRequest('SearchTour_TOWNFROMS', [
        ])["SearchTour_TOWNFROMS"];
    }

    public function getStates(int $from_city_id): array
    {
        // TODO: Implement getStates() method.
    }

    public function getTowns(int    $state_inc, int $from_city_inc,
                             ?array $tours, ?int $program_group_inc, ?int $program_inc): array
    {
        $response = $this->sendRequest('SearchTour_TOWNS', [
            'STATEINC' => $state_inc,
            'TOWNFROMINC' => $from_city_inc
        ]);
        return Arr::get($response, 'SearchTour_TOWNS') ?? [];
    }

    public function getHotels(int $state_inc, int $from_city_inc, ?array $tours, ?string $tour_type, ?int $program_group_inc, ?int $program_inc): array
    {
        // TODO: Implement getHotels() method.
    }

    public function getCategories(): array
    {
        // TODO: Implement getCategories() method.
    }

    public function getTours(int $state_inc, int $from_city_inc, ?string $tour_type, ?int $tour_group): array
    {
        // TODO: Implement getTours() method.
    }

    public function getPrograms(int $state_inc, int $from_city_inc): array
    {
        // TODO: Implement getPrograms() method.
    }


    public function baseUrl(): string
    {
        return 'https://tours.easybooking.uz/export/default.php';
    }


}