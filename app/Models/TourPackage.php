<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TourPackage extends BaseModel
{
    use HasFactory, HasCompany;

    protected $fillable = [

        'name',
        'description',
        'company_mobile',
        'company_id',
        'date_from',
        'date_to',

        'type_id',
        'quantity',

        'net_exchange_rate',
        'exchange_rate',
        'net_currency_id',
        'currency_id',
        'net_price',
        'price',

        'country_id',
        'region_id',
        'hotel_id',
        'room_type',
        'meal_id',

        'children_count',
        'infants_count',
        'adults_count',
        'hotel_name',
        'sold_quantity',
    ];
    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
    ];

    public function services()
    {
        return $this->belongsToMany(
            TourService::class,
            'package_services',
            'package_id', 'service_id'
        );
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'tour_package_id');
    }
}
