<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderApplication extends BaseModel
{
    public function partner(): BelongsTo
    {
        return $this->belongsTo(IntegrationPartner::class, 'partner_id');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class, 'source_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(LeadAgent::class, 'agent_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TourType::class, 'type_id');
    }
}
