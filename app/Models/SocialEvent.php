<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialEvent extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function social(): BelongsTo
    {
        return $this->belongsTo(SocialNetwork::class, 'social_network_id');
    }

}
