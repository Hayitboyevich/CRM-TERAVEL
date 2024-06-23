<?php

namespace Modules\TravelAgency\Repositories;

use Modules\TravelAgency\Services\ApiServiceInterface;

class GetTourRepository implements GetTourRepositoryInterface
{

    public function __construct(private ApiServiceInterface $apiService)
    {
    }

    public function getFromCities(string $type = "kompastour"): array
    {
        $this->apiService->setType($type);
        $response = $this->apiService->getFromTowns();
        $response = json_decode($response->body(), true);
        return $response['SearchTour_TOWNFROMS'];
    }

    public final function getStates(int $from_city_id, string $type = 'kompastour'): array
    {
        $this->apiService->setType($type);
        $response = $this->apiService->getStates($from_city_id);
        $response = json_decode($response->body(), true);
        return $response['SearchTour_STATES'];
    }

    public final function getProgramGroups(int $from_city_id, int $state_inc, ?int $tour_id, string $type = 'kompastour'): array
    {
        $this->apiService->setType($type);
        $response = $this->apiService->getProgramGroup($from_city_id, $state_inc, $tour_id);
        $response = json_decode($response->body(), true);
        return $response['SearchTour_PROGRAM_GROUPS'];
    }

    public final function getTowns(
        int      $from_city_id,
        int      $to_country_id
        , string $type = 'kompastour'

    ): array
    {
        $this->apiService->setType($type);
        $response = $this->apiService->getTowns($from_city_id, $to_country_id);
        $response = json_decode($response->body(), true);
        return $response['SearchTour_TOWNS'];
    }

    public function getHotels(int $from_city_id, int $state_inc, string $type = 'kompastour'): array
    {
        $this->apiService->setType($type);
        $response = $this->apiService->getHotels($from_city_id, $state_inc);
        $response = json_decode($response->body(), true);
        return $response['SearchTour_HOTELS'];
    }

    public function getCategories(int $from_city_id, int $state_inc, string $type = 'kompastour'): array
    {
        $this->apiService->setType($type);
        $response = $this->apiService->getCategories($from_city_id, $state_inc);
        $response = json_decode($response->body(), true);
        return $response['SearchTour_STARS'];
    }

    public final function getTours(int $from_city_id, int $state_inc, string $type = 'kompastour'): array
    {
        $this->apiService->setType($type);
        $response = $this->apiService->getTours($from_city_id, $state_inc);
        $response = json_decode($response->body(), true);
        return $response['SearchTour_TOURS'];
    }
}
