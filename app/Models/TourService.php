<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourService extends BaseModel
{
    use HasFactory, HasCompany;

    protected $fillable = [
        'country_id',
        'name',
        'description',
        'hotel_id',

        'region_id',
        'type_id',
        'product_id',
        'partner_id',
        'schema_id',
        'date_from',
        'date_to',
        'meal_id',
        'room_type_id',


        'exchange_rate',
        'net_exchange_rate',

        'currency_id',
        'net_currency_id',
        'price',
        'net_price',
        'company_id',
        
        'children_count',
        'adults_count',
        'infants_count',


    ];
    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date'
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(IntegrationPartner::class, 'partner_id');
    }

    public function schema(): BelongsTo
    {
        return $this->belongsTo(Schema::class, 'schema_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'type_id');
    }
}
