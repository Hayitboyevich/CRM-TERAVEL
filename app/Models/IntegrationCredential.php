<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationCredential extends Model
{
    use HasFactory, HasCompany;

    protected $fillable = [
        'login',
        'password',
        'type',
        'name',
        'company_id'
    ];
}
