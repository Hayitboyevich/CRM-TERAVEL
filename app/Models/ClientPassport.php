<?php

namespace App\Models;
/*
* @property \Illuminate\Support\Carbon $passport_given_date
 *
 */

use App\Services\DataCast;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientPassport extends BaseModel
{
    public $dates = [
        'given_date:Y-m-d',
        'date_of_birth:Y-m-d',
        'date_of_expiry:Y-m-d'
    ];
    protected $fillable = [
        'client_id',
        'passport_type',
        'passport_serial_number',
        'first_name',
        'last_name',

        'date_of_birth',
        'date_of_expiry',
        'nationality',
        'gender',
        'place_of_birth',

        'given_date',
        'given_by',
        'given_department',
        'personal_number',
        'stir',
        'residence_id',
        'living_country_id',
        'expire_date',
        'departure_time',
        'arrival_time',
        'nights'
    ];
    protected $casts = [
        'given_date' => DataCast::class,
        'date_of_birth' => DataCast::class,
        'date_of_expiry' => DataCast::class,
    ];

    protected $guarded = [];
//    protected $casts = [
//        'passport_given_date' => 'datetime'
//    ];
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'passport_residence_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'client_id');
    }
}
