<?php

namespace Modules\TravelAgency\Services;

use Illuminate\Http\Client\Response;

interface ApiServiceInterface
{
    public function getFromTowns(): Response;

    public function setType(string $type): void;

    public function getStates(int $from_city_id): Response;

    public function getTours(int $from_city_id, int $state_inc): Response;

    public function getProgramGroup(int $from_city_id, int $state_inc, ?int $tour_id): Response;

    public function getTowns(int $from_city_id, int $to_country_id): Response;

    public function getHotels(int $from_city_id, int $to_country_id): Response;

    public function getCategories(int $from_city_id, int $to_country_id): Response;

}
