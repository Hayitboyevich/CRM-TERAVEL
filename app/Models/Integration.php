<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Integration extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'from_city_id',
        'from_city_name',

        'to_country_id',
        'to_country_name',

        'to_city_id',
        'to_city_name',

        'checkin_begin',
        'checkin_end',

        'category_id',
        'category_name',

        'hotel_id',
        'hotel_name',

        'meal_id',
        'meal_name',

        'cost_min',
        'cost_max',

        'tour_id',
        'tour_name',

        'program_type_id',
        'nights_count_from',
        'nights_count_to',
        'budget',
        'user_id',
        'adults_count',
        'children_count',
    ];

    public function fromCity(): BelongsTo
    {
        return $this->belongsTo(IntegrationCity::class, 'from_city_id');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(IntegrationState::class, 'to_country_id');
    }

    public final function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'id', 'integration_id');
    }
}
