<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Workflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_network_id',
        'social_event_id',
        'condition_id',
        'verify_id',
        'text',
        'company_token'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function social(): BelongsTo
    {
        return $this->belongsTo(SocialNetwork::class, 'social_network_id');
    }

    public function verify(): BelongsTo
    {
        return $this->belongsTo(Verify::class);
    }

    public function socialEvent(): BelongsTo
    {
        return $this->belongsTo(SocialEvent::class);
    }

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }

}
