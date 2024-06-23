<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsTemplate extends BaseModel
{
    use HasFactory, HasCompany;

    protected $table = 'sms_template';

    protected $fillable = [
        'name', 'content', 'status', 'company_id', 'created_at', 'updated_at'
    ];

}
