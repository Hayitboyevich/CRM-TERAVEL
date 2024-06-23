<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsSetting extends BaseModel
{
    use HasFactory, HasCompany;

    protected $fillable = [
        'name', 'username', 'password', 'url', 'company_id', 'created_at', 'updated_at'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->company_id = company()->id;
        });
    }
}
