<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schema extends BaseModel
{
    use HasCompany;

    protected $fillable = ['name', 'description', 'dimension', 'row_amount'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->company_id = company()->id;
        });
    }

    public function seats(): HasMany
    {
        return $this->hasMany(SchemaSeat::class)->orderBy('index');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
