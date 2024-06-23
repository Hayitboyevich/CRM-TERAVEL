<?php

namespace Modules\TravelAgency\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\IntegrationCity;
use App\Models\IntegrationState;
use App\Models\IntegrationTown;
use App\Models\State;
use Illuminate\Http\Request;
use Modules\TravelAgency\Repositories\GetTourRepositoryInterface;

class DataController extends Controller
{
    public function __construct(public GetTourRepositoryInterface $getTourRepository)
    {
    }

    public function storeCountry(Request $request)
    {
        $countryName = $request->input('countryName');
        $country = IntegrationState::query()
            ->where('name', $countryName)
            ->first();

        if (!$country) {
            $country = new IntegrationState();
            $country->name = $countryName;
            $country->save();
        }
        return $country;
    }

    public function storeRegion(Request $request)
    {
        $regionName = $request->input('regionName');
        $countryId = $request->input('countryId');
        $region = IntegrationTown::query()
            ->where('name', $regionName)
            ->where('country_id', $countryId)
            ->first();

        if (!$region) {
            $region = new IntegrationTown();
            $region->name = $regionName;
            $region->country_id = $countryId;
            $region->save();
        }

        return $region;
    }

    public function storeCity(Request $request)
    {
        $cityName = $request->input('cityName');
        $city = IntegrationCity::query()
            ->where('name', $cityName)
            ->first();

        if (!$city) {
            $city = new IntegrationCity();
            $city->name = $cityName;
            $city->save();
        }
        return $city;
    }


    public function getFromCities()
    {
        $cities = IntegrationCity::query()->get();
        return $cities;

        return $this->getTourRepository->getFromCities();
    }

    public function getTours(Request $request)
    {
        $from_city_id = $request->input('from_city_id');
        $state_inc = $request->input('to_country_id');
        return $this->getTourRepository->getTours($from_city_id, $state_inc);
    }

    public function getStates(int $from_city_id)
    {
        $states = IntegrationState::query()
//            ->whereNotNull(['kompastour_id'])
            ->get();
        return $states;
        return $this->getTourRepository->getStates($from_city_id);
    }

    public function getRegions(Request $request)
    {
        $to_country_id = $request->input('to_country_id');
        return State::query()
            ->where('country_id', $to_country_id)
            ->get();
    }

    public function getCities(Request $request) {
        $searchTerm = $request->input('term', '');
        $cities = City::where('name', 'LIKE', "%{$searchTerm}%")
            ->take(50) // Limit the number of results
            ->get();
        return response()->json($cities);
    }

    public function getTowns(Request $request)
    {
        $from_city_id = $request->input('from_city_id');
        $to_country_id = $request->input('to_country_id');
        return IntegrationTown::query()
            ->where('country_id', $to_country_id)
//            ->whereNotNull('kompastour_id')
            ->get();

        return $this->getTourRepository->getTowns($from_city_id, $to_country_id);
    }

    public function getHotels(Request $request)
    {
        $from_city_id = $request->input('from_city_id');
        $to_country_id = $request->input('to_country_id');

        return $this->getTourRepository->getHotels($from_city_id, $to_country_id);
    }

    public function getCategories(Request $request)
    {
        $from_city_id = $request->input('from_city_id');
        $to_country_id = $request->input('to_country_id');

        return $this->getTourRepository->getCategories($from_city_id, $to_country_id);
    }

    public function getProgramGroups(Request $request)
    {
        $from_city_id = $request->input('from_city_id');
        $to_country_id = $request->input('to_country_id');
        $tour_id = $request->input('tour');
        return $this->getTourRepository->getProgramGroups($from_city_id, $to_country_id, $tour_id);
    }
}
