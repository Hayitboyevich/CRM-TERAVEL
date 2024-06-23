<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Traveler extends BaseModel
{
    protected $fillable = ['user_id', 'application_id'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
