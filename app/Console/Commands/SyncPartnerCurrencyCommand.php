<?php

namespace App\Console\Commands;

use App\Models\IntegrationPartner;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Modules\TravelAgency\Services\Integrations\EasyBookingIntegration;
use Modules\TravelAgency\Services\Integrations\KompasIntegration;
use Modules\TravelAgency\Services\Integrations\PrestigeIntegration;

class SyncPartnerCurrencyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'partner:sync-currency';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $integration = new KompasIntegration();
        $list = $integration->getCurrencyRate(2, 10, now(), now());
        $usd = Arr::get($list, 0);
        $kompas_rate = Arr::get($usd, 'rate');
        var_dump(1);
        $integration = new EasyBookingIntegration();
        $list = $integration->getCurrencyRate(2, 10, now(), now());
        $cl = $integration->getCurrencyList();
        var_dump($cl);
        $usd = Arr::get($list, 0);
        $easyBooking_rate = Arr::get($usd, 'rate');
        var_dump(2);

        $integration = new PrestigeIntegration();
        $list = $integration->getCurrencyRate(2, 4, now(), now());
        $usd = Arr::get($list, 0);
        $prestige_rate = Arr::get($usd, 'rate');
        var_dump(3);

        IntegrationPartner::query()
            ->where('name', 'like', '%easybooking%')
            ->update([
                'exchange_rate' => $easyBooking_rate
            ]);
        IntegrationPartner::query()
            ->where('name', 'prestige')
            ->where('name', 'like', '%prestige%')
            ->update([
                'exchange_rate' => $prestige_rate
            ]);
        IntegrationPartner::query()
            ->where('name', 'like', '%kompastour%')
            ->update([
                'exchange_rate' => $kompas_rate
            ]);
    }
}
