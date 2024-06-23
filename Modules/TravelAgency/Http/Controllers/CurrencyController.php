<?php

namespace Modules\TravelAgency\Http\Controllers;

use App\Domain\Articles\Models\Article;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\TravelAgency\Services\Integrations\EasyBookingIntegration;
use Modules\TravelAgency\Services\Integrations\KompasIntegration;
use Modules\TravelAgency\Services\Integrations\PrestigeIntegration;

class CurrencyController extends Controller
{
    public function __construct(
        public EasyBookingIntegration $easyBookingIntegration,
        public KompasIntegration      $kompasIntegration,
        public PrestigeIntegration    $prestigeIntegration
    )
    {
    }

    public function getCurrency(Request $request): int
    {
        $name = $request->name;
        $response = array();
        $currency_list = [];
//        $currency_list = cache()->remember("currency_listq", 24 * 60 * 60, function () use ($name) {
        switch ($name) {
            case 'Easybooking':
                {
                    $currency_list = $this->easyBookingIntegration->getCurrencyList();
                }
                break;
            case 'Prestige':
                {
                    $currency_list = $this->prestigeIntegration->getCurrencyList();
                }
                break;
            case 'Kompastour':
                {
                    $currency_list = $this->kompasIntegration->getCurrencyList();
                }
                break;
        }
//        });
        $currency_list = collect($currency_list)->pluck('id', 'name');
        $from = $currency_list[$request->from] ?? $currency_list['USD'];
        $to = $currency_list[$request->to] ?? $currency_list['UZS'];
        switch ($name) {
            case 'Easybooking':
                {
                    $response = $this->easyBookingIntegration->getCurrencyRate(from: $from, to: $to, dateFrom: now(), dateTo: now());
                }
                break;
            case 'Prestige':
                {
                    $response = $this->prestigeIntegration->getCurrencyRate(from: $from, to: $to, dateFrom: now(), dateTo: now());
                }
                break;
            case 'Kompastour':
                {
                    $response = $this->kompasIntegration->getCurrencyRate(from: $from, to: $to, dateFrom: now(), dateTo: now());
                }
                break;
        }
        return (int)$response[0]['rate'];
    }
}