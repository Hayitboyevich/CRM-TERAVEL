<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeadlinePayment extends Model
{
//    use HasFactory;
    protected $fillable = [
        'application_id',
        'percent',
        'deadline',
        'amount',
        'type'
    ];
    protected $casts = [
        'deadline' => 'date'
    ];
}
