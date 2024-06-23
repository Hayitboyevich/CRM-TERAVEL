<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\GlobalSetting;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class UpdateExchangeRates extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-exchange-rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the exchange rates for all the currencies in currencies table.';


    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        $company = Company::where('id', 1)->with(['currencies', 'currency'])->first();
        $globalSetting = GlobalSetting::first();

        $currencyApiKey = ($globalSetting->currency_converter_key) ?: config('app.currency_converter_key');
        $currencyApiKeyVersion = $globalSetting->currency_key_version;

        $client = new Client();

        $result = array();
        $response = $client->request('GET', 'https://cbu.uz/uz/arkhiv-kursov-valyut/json/');
        $response = json_decode($response->getBody(), true);

        foreach ($response as $item) {
            $result[$item['Ccy']] = $item['Rate'];
        }

        $result['UZS'] = 1;
        $t = $result['UZS'] / $result['USD'];
        foreach ($company->currencies as $currency) {
            $one_uzs_cur = $t * $result[$currency->currency_code];
            $currency->exchange_rate = $one_uzs_cur;
            $currency->saveQuietly();
        }

    }

}
