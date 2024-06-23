<?php

namespace App\Models;

use App\Traits\CustomFieldsTrait;
use App\Traits\HasCompany;
use Database\Factories\LeadFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * App\Models\Lead
 *
 * @property int $id
 * @property int|null $client_id
 * @property int|null $source_id
 * @property int|null $status_id
 * @property int $column_priority
 * @property int|null $agent_id
 * @property string|null $company_name
 * @property string|null $website
 * @property string|null $address
 * @property string|null $salutation
 * @property string $client_name
 * @property string $client_email
 * @property string|null $mobile
 * @property string|null $cell
 * @property string|null $office
 * @property string|null $city
 * @property string|null $state
 * @property string|null $country
 * @property string|null $postal_code
 * @property string|null $note
 * @property string $next_follow_up
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property float|null $value
 * @property float|null $total_value
 * @property int|null $currency_id
 * @property int|null $category_id
 * @property int|null $added_by
 * @property int|null $last_updated_by
 * @property-read User|null $client
 * @property-read Currency|null $currency
 * @property-read Collection|LeadFiles[] $files
 * @property-read int|null $files_count
 * @property-read Collection|LeadFollowUp[] $follow
 * @property-read int|null $follow_count
 * @property-read LeadFollowUp|null $followup
 * @property-read mixed $extras
 * @property-read mixed $icon
 * @property-read mixed $image_url
 * @property-read LeadAgent|null $leadAgent
 * @property-read LeadSource|null $leadSource
 * @property-read LeadStatus|null $leadStatus
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static LeadFactory factory(...$parameters)
 * @method static Builder|Lead newModelQuery()
 * @method static Builder|Lead newQuery()
 * @method static Builder|Lead query()
 * @method static Builder|Lead whereAddedBy($value)
 * @method static Builder|Lead whereAddress($value)
 * @method static Builder|Lead whereAgentId($value)
 * @method static Builder|Lead whereCategoryId($value)
 * @method static Builder|Lead whereCell($value)
 * @method static Builder|Lead whereCity($value)
 * @method static Builder|Lead whereClientEmail($value)
 * @method static Builder|Lead whereClientId($value)
 * @method static Builder|Lead whereClientName($value)
 * @method static Builder|Lead whereColumnPriority($value)
 * @method static Builder|Lead whereCompanyName($value)
 * @method static Builder|Lead whereCountry($value)
 * @method static Builder|Lead whereCreatedAt($value)
 * @method static Builder|Lead whereCurrencyId($value)
 * @method static Builder|Lead whereId($value)
 * @method static Builder|Lead whereLastUpdatedBy($value)
 * @method static Builder|Lead whereMobile($value)
 * @method static Builder|Lead whereNextFollowUp($value)
 * @method static Builder|Lead whereNote($value)
 * @method static Builder|Lead whereOffice($value)
 * @method static Builder|Lead wherePostalCode($value)
 * @method static Builder|Lead whereSalutation($value)
 * @method static Builder|Lead whereSourceId($value)
 * @method static Builder|Lead whereState($value)
 * @method static Builder|Lead whereStatusId($value)
 * @method static Builder|Lead whereUpdatedAt($value)
 * @method static Builder|Lead whereValue($value)
 * @method static Builder|Lead whereWebsite($value)
 * @property string|null $hash
 * @property-read LeadCategory|null $category
 * @method static Builder|Lead whereHash($value)
 * @property int|null $company_id
 * @property-read Company|null $company
 * @method static Builder|Lead whereCompanyId($value)
 * @property-read Collection<int, Product> $products
 * @property-read int|null $products_count
 * @property-read Collection<int, Product> $products
 * @property-read Collection<int, Product> $products
 * @property-read Collection<int, Product> $products
 * @property-read Collection<int, Product> $products
 * @property-read Collection<int, Product> $products
 * @property-read Collection<int, Product> $products
 * @property-read Collection<int, Product> $products
 * @mixin Eloquent
 */
class Lead extends BaseModel
{

    use Notifiable, HasFactory;
    use CustomFieldsTrait;
    use HasCompany;

//    use HasTimestamps;

    const CUSTOM_FIELD_MODEL = 'App\Models\Lead';
    protected $casts = [
        'interest_ids' => 'array',
        'callback_at' => 'datetime',
    ];

    protected $fillable = [
        'client_id',
        'order_number',
        'type_id',
        'status_id',
        'client_name',
        'company_id',
        'currency_id',
        'mobile',
        'client_email',
        'integration_id',
        'source_id',
        'added_by',
        'note',
        'value',
        'agent_id',
        'partner_id',
        'client_name',
    ];

    protected $table = 'leads';
    protected $appends = ['image_url'];

    public static function allLeads()
    {
        $viewLeadPermission = user()->permission('view_lead');

        $leads = Lead::select('*')
            ->orderBy('client_name', 'asc');

        if (!isRunningInConsoleOrSeeding()) {

            if ($viewLeadPermission == 'added') {
                $leads->where('added_by', user()->id);
            }
        }

        return $leads->get();
    }

    public function partner()
    {
        return $this->belongsTo(IntegrationPartner::class, 'partner_id');
    }


    public function type(): BelongsTo
    {
        return $this->belongsTo(TourType::class, 'type_id');
    }

    public function tripHistory()
    {
        return $this->hasMany(LeadTrip::class, 'lead_id', 'id');
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param \Illuminate\Notifications\Notification $notification
     * @return string
     */
    // phpcs:ignore

    public function getImageUrlAttribute()
    {
        $gravatarHash = md5(strtolower(trim($this->client_email)));

        return 'https://www.gravatar.com/avatar/' . $gravatarHash . '.png?s=200&d=mp';
    }


    public function routeNotificationForMail($notification)
    {
        return $this->client_email;
    }


    public function leadAgent(): BelongsTo
    {
        return $this->belongsTo(LeadAgent::class, 'agent_id');
    }

    public function note(): BelongsTo
    {
        return $this->belongsTo(LeadNote::class, 'lead_id');
    }

    public function leadSource(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class, 'source_id');
    }

    public function leadInterest(): HasOne
    {
        return $this->hasOne(LeadInterest::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(LeadCategory::class, 'category_id');
    }

    public function leadStatus(): BelongsTo
    {
        return $this->belongsTo(LeadStatus::class, 'status_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }


    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class, 'integration_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'lead_products')->using(LeadProduct::class);
    }

    public function follow()
    {
        if (user()) {
            $viewLeadFollowUpPermission = user()->permission('view_lead_follow_up');

            if ($viewLeadFollowUpPermission == 'all') {
                return $this->hasMany(LeadFollowUp::class);

            } elseif ($viewLeadFollowUpPermission == 'added') {
                return $this->hasMany(LeadFollowUp::class)->where('added_by', user()->id);

            } else {
                return null;
            }
        }

        return $this->hasMany(LeadFollowUp::class);
    }

    public function followup(): HasOne
    {
        return $this->hasOne(LeadFollowUp::class, 'lead_id')->orderBy('created_at', 'desc');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'id', 'lead_id');
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'lead_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(LeadFiles::class)->orderBy('created_at', 'desc');
    }

    public function addedBy()
    {
        $addedBy = User::findOrFail($this->added_by);

        return $addedBy ?: null;
    }

}
