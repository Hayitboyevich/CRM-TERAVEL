<?php

namespace App\Models;

use App\Traits\HasCompany;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\LeadSource
 *
 * @property int $id
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $added_by
 * @property int|null $last_updated_by
 * @property-read mixed $icon
 * @property-read Collection|Lead[] $leads
 * @property-read int|null $leads_count
 * @method static Builder|LeadSource newModelQuery()
 * @method static Builder|LeadSource newQuery()
 * @method static Builder|LeadSource query()
 * @method static Builder|LeadSource whereAddedBy($value)
 * @method static Builder|LeadSource whereCreatedAt($value)
 * @method static Builder|LeadSource whereId($value)
 * @method static Builder|LeadSource whereLastUpdatedBy($value)
 * @method static Builder|LeadSource whereType($value)
 * @method static Builder|LeadSource whereUpdatedAt($value)
 * @property int|null $company_id
 * @property-read Company|null $company
 * @method static Builder|LeadSource whereCompanyId($value)
 * @mixin Eloquent
 */
class LeadSource extends BaseModel
{
    use HasCompany;

    protected $table = 'lead_sources';

    protected $guarded = ['id'];

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'source_id')->orderBy('column_priority');
    }

}
