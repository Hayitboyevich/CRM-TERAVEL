<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Flight extends BaseModel
{
    use HasFactory, HasCompany;

    protected $casts = [
        'from_datetime' => 'datetime',
        'to_datetime' => 'datetime'
    ];
    protected $fillable = [
        'from_location',
        'from_code',
        'from_terminal',
        'from_datetime',

        'to_location',
        'to_code',
        'to_terminal',
        'to_datetime'
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

}
