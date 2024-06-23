<?php

namespace App\Models;

use App\BaseModel;
use App\GlobalCurrency;
use App\Models\Company;

class Package extends BaseModel
{
    protected $table = 'packages';

    protected $guarded = ['id'];
    protected $appends = [
        'formatted_annual_price',
        'formatted_monthly_price'
    ];

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' GB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' MB';
        } else {
            $bytes = '0 MB';
        }

        return $bytes;
    }

    public function currency()
    {
        return $this->belongsTo(GlobalCurrency::class, 'currency_id')->withTrashed();
    }

    function getFormattedAnnualPriceAttribute()
    {
        $global = global_setting();

        return currency_format($this->annual_price, $global->currency->id);
    }

    function getFormattedMonthlyPriceAttribute()
    {
        $global = global_setting();
        return currency_format($this->monthly_price, $global->currency->id);
    }

}
