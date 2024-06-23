<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyMetricaGoals extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'goal_id',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
