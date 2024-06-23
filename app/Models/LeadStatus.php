<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\LeadStatus
 *
 * @property int $id
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $priority
 * @property int $default
 * @property string $label_color
 * @property-read mixed $icon
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lead[] $leads
 * @property-read int|null $leads_count
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus whereDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus whereLabelColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus whereUpdatedAt($value)
 * @property int|null $company_id
 * @property-read \App\Models\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus whereCompanyId($value)
 * @mixin \Eloquent
 */
class LeadStatus extends BaseModel
{

    use HasCompany;

    const CLIENT_FROM_TARGET_STATUS = 'Клиенты из Target';
    const UNSORTED_STATUS = 'Несортированный';
    const IN_PROGRESS_STATUS = 'В процессе';
    const NEED_TO_CONTACT_STATUS = 'Нужно выйти на связь !!!';
    const PARTIALLY_PAID_STATUS = 'Частично оплачен';
    const PAID_STATUS = 'Оплачен';
    const DOCUMENTS_ISSUED_STATUS = 'Документы выданы';
    const NOT_QUALITY_LEAD_STATUS = 'Не качественный ЛИД';

    protected $table = 'lead_status';

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'status_id')->orderBy('column_priority');
    }

    public function userSetting(): HasOne
    {
        return $this->hasOne(UserLeadboardSetting::class, 'board_column_id')->where('user_id', user()->id);
    }

    public function getLocalizedNameAttribute()
    {
        $locale = app()->getLocale(); // Get the current locale
        $column = 'name_' . $locale; // Construct the column name based on the locale
        return $this->{$column} ?? $this->name_en; // Fallback to English if localized name is null
    }

}
