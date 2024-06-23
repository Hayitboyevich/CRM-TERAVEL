<?php

namespace Modules\TravelAgency\Repositories;

interface GetTourRepositoryInterface
{
    public function getFromCities(): array;

    public function getStates(int $from_city_id): array;

    public function getTours(int $from_city_id, int $state_inc, string $type): array;

    public function getHotels(int $from_city_id, int $state_inc, string $type): array;

    public function getCategories(int $from_city_id, int $state_inc, string $type): array;

    public function getProgramGroups(int $from_city_id, int $state_inc, ?int $tour_id, string $type): array;

}
