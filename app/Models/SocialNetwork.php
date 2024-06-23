<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialNetwork extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function events(): HasMany
    {
        return $this->hasMany(SocialEvent::class);
    }

}
