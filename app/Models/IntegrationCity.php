<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'kompastour_id',
        'easybooking_id',
        'prestige_id'
    ];
}
