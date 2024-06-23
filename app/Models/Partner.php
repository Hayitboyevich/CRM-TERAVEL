<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name', 'link', 'countries'
    ];


}
