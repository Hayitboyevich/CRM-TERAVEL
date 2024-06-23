<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IntegrationPartner extends Model
{
    use HasFactory, HasCompany;

    protected $fillable = [
        'name',
        'company_id',
        'login',
        'password',
        'type',
        'exchange_rate'
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'partner_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItems::class, 'partner_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'partner_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'partner_id')
            ->where(['paid_for' => 'partner'])
            ->where(['status' => 'complete']);

    }
}
