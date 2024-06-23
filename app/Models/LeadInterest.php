<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadInterest extends Model
{
    use HasFactory;

    protected $table = 'lead_interests';

    protected $guarded = ['id'];

    public function leads()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
