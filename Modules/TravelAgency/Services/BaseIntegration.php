<?php

namespace Modules\TravelAgency\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Modules\ExceptionSender\Handler\Logger;

abstract class BaseIntegration
{
    public abstract function integrationName(): string;

    public function getCurrencyRate(int $from, int $to, string $dateFrom, string $dateTo)
    {
        $response = $this->sendRequest('Currency_RATES', [
            'CURRENCY' => $from,
            'DATEBEG' => date('Ymd', strtotime($dateFrom)),
            'DATEEND' => date('Ymd', strtotime($dateTo)),
            'CURRENCYBASE' => $to,
        ]);
        if (empty($response) || Arr::get($response, "error")) {
            var_dump(Arr::get($response, "error"));
            return [];
        }

        return $response["Currency_RATES"];
    }

    public function sendRequest(string $action, ?array $params): array
    {
        Logger::send($this->baseUrl());
        $response = Http::asForm()->get($this->baseUrl(),
            array_merge($params, [
                'version' => $this->version(),
                'oauth_token' => $this->token(),
                'type' => 'json',
                'samo_action' => 'api',
                'action' => $action,
            ]));
        $result = ($response->status() >= 200 && $response->status() < 300) ? $response->body() : '[]';
        Logger::send($response->body());

        return json_decode($result, true);
    }

    public abstract function baseUrl(): string;

    public abstract function version(): string;

    public abstract function token(): string;

    public function getCurrencyList()
    {
        $response = $this->sendRequest('Currency_CURRENCIES', [
        ]);
        return Arr::get($response, 'Currency_CURRENCIES') ?? [];
    }
}