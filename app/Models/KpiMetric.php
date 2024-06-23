<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class KpiMetric extends BaseModel
{
    use HasFactory;

    public function agent()
    {
        return $this->belongsTo(LeadAgent::class, 'agent_id');
    }

    public function item()
    {
        return $this->belongsTo(KpiItem::class, 'item_id');
    }
}
