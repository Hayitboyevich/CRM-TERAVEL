<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ApplicationOrderNumber extends BaseModel
{
    protected $table = 'application_order_numbers';

    protected $guarded = [];
}
