<?php

namespace App\Observers;

use App\Models\Currency;

class CurrencyObserver
{

    public function creating(Currency $model)
    {
        if (company()) {
            $model->company_id = company()->id;
        }

        if ($model->currency_code == 'USD') {
            session(['company_usd_rate' => $model->exchange_rate]);
        }

        if ($model->currency_code == 'RUB') {
            session(['company_rub_rate' => $model->exchange_rate]);
        }
    }

    public function updated(Currency $model)
    {
        if (company()) {
            $model->company_id = company()->id;
        }

        if ($model->currency_code == 'USD') {
            session(['company_usd_rate' => $model->exchange_rate]);
        }

        if ($model->currency_code == 'RUB') {
            session(['company_rub_rate' => $model->exchange_rate]);
        }


    }

}
