<?php

namespace Modules\TravelAgency\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\IntegrationCity;
use App\Models\IntegrationState;
use Illuminate\Http\Request;
use Modules\TravelAgency\Services\Integrations\EasyBookingIntegration;
use Modules\TravelAgency\Services\Integrations\KompasIntegration;
use Modules\TravelAgency\Services\Integrations\PrestigeIntegration;

class TravelController extends Controller
{
    public function __construct(
        public EasyBookingIntegration $easyBooking,
        public KompasIntegration      $kompas,
        public PrestigeIntegration    $prestige,

    )
    {
    }

    public function getFromCities($integrationName): array
    {
        $cities = IntegrationCity::query()->get();
        return $cities;
        return $this->getTourRepository->getFromCities();
    }

    public function getTours($integrationName, Request $request): array
    {
        $from_city_id = $request->input('from_city_id');
        $state_inc = $request->input('to_country_id');
        return $this->getTourRepository->getTours($from_city_id, $state_inc);
    }

    public function getStates($integrationName, Request $request): array
    {
        $from_city_id = $request->input('from_city_id');

        $states = IntegrationState::query()
//            ->whereNotNull(['kompastour_id'])
            ->get();
        return $this->getTourRepository->getStates($from_city_id);
    }

    public function getTowns($integrationName, Request $request): array
    {
        $from_city_id = $request->input('from_city_id');
        $to_country_id = $request->input('to_country_id');

        return $this->getTourRepository->getTowns($from_city_id, $to_country_id);
    }

    public function getHotels(Request $request): array
    {
        $from_city_id = $request->input('from_city_id');
        $to_country_id = $request->input('to_country_id');

        return $this->getTourRepository->getHotels($from_city_id, $to_country_id);
    }

    public function getCategories(Request $request): array
    {
        $from_city_id = $request->input('from_city_id');
        $to_country_id = $request->input('to_country_id');

        return $this->getTourRepository->getCategories($from_city_id, $to_country_id);
    }

    public function getProgramGroups($integrationName, Request $request): array
    {
        $from_city_id = $request->input('from_city_id');
        $to_country_id = $request->input('to_country_id');
        $tour_id = $request->input('tour');

        return $this->getTourRepository->getProgramGroups($from_city_id, $to_country_id, $tour_id);
    }
}