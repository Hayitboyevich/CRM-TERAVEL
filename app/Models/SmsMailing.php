<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsMailing extends BaseModel
{
    use HasFactory, HasCompany;

    protected $casts = [
        'user_id'
    ];
    protected $fillable = [
        'user_id',
        'message',
        'status',
        'delivery_date',
        'company_id'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->company_id = company()->id;
        });
    }


}
