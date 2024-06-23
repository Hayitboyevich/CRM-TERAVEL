<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hotel extends BaseModel
{
    use HasFactory, HasCompany;

    protected $guarded = [];
}
