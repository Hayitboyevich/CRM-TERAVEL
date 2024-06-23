<?php

namespace Modules\TravelAgency\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ApiService implements ApiServiceInterface
{
    private array $url = [
        'prestige' => 'http://parser1.kompastour.com/export/default.php?version=1.0&oauth_token=e20aee434a314bff8e05a7ec1e58b472&type=json&samo_action=api',
        'kompastour' => 'http://parser1.kompastour.com/export/default.php?samo_action=api&version=1.0&oauth_token=e20aee434a314bff8e05a7ec1e58b472&type=json',
        'easybooking' => 'https://tours.easybooking.uz/export/default.php?samo_action=api&version=1.0&oauth_token=230d507d578f434d96ed2055c824d5c0&type=json',
    ];
    private string $type;

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public final function getFromTowns(): Response
    {
        $url = $this->url[$this->type] . '&action=SearchTour_TOWNFROMS';
        return Http::asForm()
            ->get($url);
    }

    public final function getStates(int $from_city_id): Response
    {
        $url = $this->url[$this->type] . '&action=SearchTour_STATES&TOWNFROMINC=' . $from_city_id;
        return Http::asForm()
            ->get($url);
    }


    public final function getTours(int $from_city_id, int $state_inc): Response
    {

        $url = $this->url[$this->type] . '&action=SearchTour_TOURS&TOWNFROMINC=' . $from_city_id . '&STATEINC=' . $state_inc;
        return Http::asForm()
            ->get($url);
    }

    public final function getProgramGroup(int $from_city_id, int $state_inc, ?int $tour_id): Response
    {
        $url = $this->url[$this->type] . '&action=SearchTour_PROGRAM_GROUPS&TOWNFROMINC=' . $from_city_id . '&STATEINC=' . $state_inc . '&TOURINC=' . $tour_id;
        return Http::asForm()
            ->get($url);
    }

    public final function getTowns(int $from_city_id, int $to_country_id
    ): Response
    {
        $url = $this->url[$this->type] . '&action=SearchTour_TOWNS&TOWNFROMINC=' . $from_city_id
            . '&STATEINC=' . $to_country_id;

        return Http::asForm()
            ->get($url);
    }

    public final function getHotels(int $from_city_id, int $to_country_id): Response
    {
        $url = $this->url[$this->type] . '&action=SearchTour_HOTELS&TOWNFROMINC=' . $from_city_id
            . '&STATEINC=' . $to_country_id;

        return Http::asForm()
            ->get($url);
    }

    public final function getCategories(int $from_city_id, int $to_country_id): Response
    {
        $url = $this->url[$this->type] . '&action=SearchTour_STARS&TOWNFROMINC=' . $from_city_id
            . '&STATEINC=' . $to_country_id;

        return Http::asForm()
            ->get($url);
    }
}
