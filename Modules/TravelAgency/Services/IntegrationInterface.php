<?php

namespace Modules\TravelAgency\Services;

interface IntegrationInterface
{
    public function getFromTowns(): array;

    public function getStates(int $from_city_id): array;

    public function getTowns(int    $state_inc, int $from_city_inc,
                             ?array $tours, ?int $program_group_inc,
                             ?int   $program_inc): array;

    public function getHotels(int    $state_inc, int $from_city_inc,
                              ?array $tours, ?string $tour_type,
                              ?int   $program_group_inc, ?int $program_inc): array;

    public function getCategories(): array;

    public function getTours(int     $state_inc, int $from_city_inc,
                             ?string $tour_type, ?int $tour_group): array;

    public function getPrograms(int $state_inc, int $from_city_inc): array;

    public function getCurrencyRate(int $from, int $to, string $dateFrom, string $dateTo);

    public function getCurrencyList();
}