<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class PassportScan extends BaseModel
{
    protected $table = 'passport_scan';

    protected $fillable = [
        'company_id',
        'number',
        'date',
    ];
}
