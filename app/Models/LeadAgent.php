<?php

namespace App\Models;

use App\Scopes\ActiveScope;
use App\Traits\HasCompany;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\LeadAgent
 *
 * @property int $id
 * @property int $user_id
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $added_by
 * @property int|null $last_updated_by
 * @property-read mixed $icon
 * @property-read User $user
 * @method static Builder|LeadAgent newModelQuery()
 * @method static Builder|LeadAgent newQuery()
 * @method static Builder|LeadAgent query()
 * @method static Builder|LeadAgent whereAddedBy($value)
 * @method static Builder|LeadAgent whereCreatedAt($value)
 * @method static Builder|LeadAgent whereId($value)
 * @method static Builder|LeadAgent whereLastUpdatedBy($value)
 * @method static Builder|LeadAgent whereStatus($value)
 * @method static Builder|LeadAgent whereUpdatedAt($value)
 * @method static Builder|LeadAgent whereUserId($value)
 * @property int|null $company_id
 * @property-read Company|null $company
 * @method static Builder|LeadAgent whereCompanyId($value)
 * @property-read Collection<int, Lead> $leads
 * @property-read int|null $leads_count
 * @property-read Collection<int, Lead> $leads
 * @mixin Eloquent
 */
class LeadAgent extends BaseModel
{
    use HasCompany;

    protected $table = 'lead_agents';
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withoutGlobalScope(ActiveScope::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

}
