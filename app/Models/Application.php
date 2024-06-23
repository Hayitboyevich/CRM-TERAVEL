<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Application extends BaseModel
{
    protected $fillable = [
        'client_id',
        'company_id',
        'source_id',
        'hotel_id',
        'status_id',
        'agent_id',
        'partner_id',
        'added_by',
        'company_name',
        'order_number',
        'type_id',
        'country_id'
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(LeadStatus::class, 'status_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id', 'application_id');
    }

    public function clientDeadline(): BelongsTo
    {
        return $this->belongsTo(DeadlinePayment::class, 'id', 'application_id')
            ->where('type', 'client');
    }

    public function partnerDeadline(): BelongsTo
    {
        return $this->belongsTo(DeadlinePayment::class, 'id', 'application_id')
            ->where('type', 'partner');
    }

    public function travelers(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            Traveler::class,
            'application_id',
            'id',
            'id',
            'user_id',
        );
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(IntegrationPartner::class, 'partner_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(LeadAgent::class, 'agent_id');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class, 'source_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TourType::class, 'type_id');
    }
}
