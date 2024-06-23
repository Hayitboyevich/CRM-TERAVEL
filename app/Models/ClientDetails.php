<?php

namespace App\Models;

use App\Scopes\ActiveScope;
use App\Traits\CustomFieldsTrait;
use App\Traits\HasCompany;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\ClientDetails
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $company_name
 * @property string|null $address
 * @property string|null $shipping_address
 * @property string|null $postal_code
 * @property string|null $state
 * @property string|null $city
 * @property string|null $office
 * @property string|null $website
 * @property string|null $note
 * @property string|null $linkedin
 * @property string|null $facebook
 * @property string|null $twitter
 * @property string|null $skype
 * @property string|null $gst_number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $category_id
 * @property int|null $sub_category_id
 * @property int|null $added_by
 * @property int|null $last_updated_by
 * @property-read mixed $extras
 * @property-read mixed $icon
 * @property-read User $user
 * @method static Builder|ClientDetails newModelQuery()
 * @method static Builder|ClientDetails newQuery()
 * @method static Builder|ClientDetails query()
 * @method static Builder|ClientDetails whereAddedBy($value)
 * @method static Builder|ClientDetails whereAddress($value)
 * @method static Builder|ClientDetails whereCategoryId($value)
 * @method static Builder|ClientDetails whereCity($value)
 * @method static Builder|ClientDetails whereCompanyName($value)
 * @method static Builder|ClientDetails whereCreatedAt($value)
 * @method static Builder|ClientDetails whereFacebook($value)
 * @method static Builder|ClientDetails whereGstNumber($value)
 * @method static Builder|ClientDetails whereId($value)
 * @method static Builder|ClientDetails whereLastUpdatedBy($value)
 * @method static Builder|ClientDetails whereLinkedin($value)
 * @method static Builder|ClientDetails whereNote($value)
 * @method static Builder|ClientDetails whereOffice($value)
 * @method static Builder|ClientDetails wherePostalCode($value)
 * @method static Builder|ClientDetails whereShippingAddress($value)
 * @method static Builder|ClientDetails whereSkype($value)
 * @method static Builder|ClientDetails whereState($value)
 * @method static Builder|ClientDetails whereSubCategoryId($value)
 * @method static Builder|ClientDetails whereTwitter($value)
 * @method static Builder|ClientDetails whereUpdatedAt($value)
 * @method static Builder|ClientDetails whereUserId($value)
 * @method static Builder|ClientDetails whereWebsite($value)
 * @property int|null $company_id
 * @property-read User|null $addedBy
 * @property-read Company|null $company
 * @method static Builder|ClientDetails whereCompanyId($value)
 * @property string|null $company_logo
 * @property int|null $quickbooks_client_id
 * @property-read mixed $image_url
 * @method static Builder|ClientDetails whereCompanyLogo($value)
 * @method static Builder|ClientDetails whereQuickbooksClientId($value)
 * @mixin Eloquent
 */
class ClientDetails extends BaseModel
{

    use CustomFieldsTrait, HasCompany;

    const CUSTOM_FIELD_MODEL = 'App\Models\ClientDetails';
    protected $fillable = ['company_name', 'user_id', 'address', 'postal_code', 'state', 'city', 'office', 'cell', 'website', 'note', 'skype', 'facebook', 'twitter', 'linkedin', 'gst_number', 'shipping_address', 'category_id', 'sub_category_id', 'company_logo', 'birthday', 'interest', 'auditory'];
    protected $default = ['id', 'company_name', 'address', 'website', 'note', 'skype', 'facebook', 'twitter', 'linkedin', 'gst_number', 'name', 'email', 'company_logo'];
    protected $table = 'client_details';
    protected $appends = ['image_url'];
    protected $with = ['company'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScope(ActiveScope::class);
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by', 'id');
    }

    public function getImageUrlAttribute()
    {
        return ($this?->company_logo) ? asset_url('client-logo/' . $this?->company_logo) : $this?->company?->logo_url;
    }

}
