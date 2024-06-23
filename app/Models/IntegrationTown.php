<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationTown extends Model
{
    use HasFactory;

    protected $fillable = [
        'prestige_id', 'kompastour_id', 'easybooking_id', 'name', 'country_id'
    ];
}
