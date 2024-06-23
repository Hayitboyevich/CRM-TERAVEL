<?php

namespace App\Models;

use App\Scopes\ActiveScope;
use App\Traits\HasCompany;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int|null $client_id
 * @property string $order_date
 * @property float $sub_total
 * @property float $total
 * @property float $due_amount
 * @property string $status
 * @property int|null $currency_id
 * @property string $show_shipping_address
 * @property string|null $note
 * @property int|null $added_by
 * @property int|null $last_updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $client
 * @property-read ClientDetails|null $clientdetails
 * @property-read Currency|null $currency
 * @property-read Collection|OrderItems[] $items
 * @property-read Collection|Invoice[] $invoice
 * @property-read int|null $items_count
 * @property-read Collection|Payment[] $payment
 * @property-read int|null $payment_count
 * @property-read Project $project
 * @property-read Collection|Invoice[] $recurrings
 * @property-read int|null $recurrings_count
 * @method static Builder|Order newModelQuery()
 * @method static Builder|Order newQuery()
 * @method static Builder|Order query()
 * @method static Builder|Order whereAddedBy($value)
 * @method static Builder|Order whereClientId($value)
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereCurrencyId($value)
 * @method static Builder|Order whereDueAmount($value)
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order whereLastUpdatedBy($value)
 * @method static Builder|Order whereNote($value)
 * @method static Builder|Order whereOrderDate($value)
 * @method static Builder|Order whereShowShippingAddress($value)
 * @method static Builder|Order whereStatus($value)
 * @method static Builder|Order whereSubTotal($value)
 * @method static Builder|Order whereTotal($value)
 * @method static Builder|Order whereUpdatedAt($value)
 * @property mixed $order_number
 * @property float $discount
 * @property string $discount_type
 * @method static Builder|Order whereDiscount($value)
 * @method static Builder|Order whereDiscountType($value)
 * @property int|null $company_id
 * @property int|null $company_address_id
 * @property-read CompanyAddress|null $address
 * @property-read Company|null $company
 * @property int|null $unit_id
 * @method static Builder|Order whereCompanyAddressId($value)
 * @method static Builder|Order whereCompanyId($value)
 * @method static Builder|Order whereOrderNumber($value)
 * @property-read UnitType $unit
 * @property int|null $unit_id
 * @property string|null $custom_order_number
 * @property-read mixed $original_order_number
 * @method static Builder|Order whereCustomOrderNumber($value)
 * @mixin Eloquent
 */
class Order extends BaseModel
{
    protected $appends = ['original_order_number'];

    use HasCompany;

    protected $fillable = [
        "client_id",
        "total_paid",
        "net_price",
        "name",
        'company_id',
        'order_date',

        "hotel",
        "visa",
        "air_ticket",
        "transfer",
        "insurance",

        "service_fee",
        "adults_count",
        "children_count",
        "total",
        "status",
        "currency_id",
        'package_id',
        'service_id',

        "note",
        "nights_count_from",
        "nights_count_to",
        "created_at",
        'partner_id',
        'application_id'
    ];

    public function tourPackages(): HasMany
    {
        return $this->hasMany(OrderTourPackage::class, 'order_id');
    }

    public static function lastOrderNumber()
    {
        return (int)Order::max('order_number');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(IntegrationPartner::class, 'partner_id');
    }

    public function paymentDeadline(): HasOne
    {
        return $this->hasOne(DeadlinePayment::class, 'application_id', 'application_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id')->withoutGlobalScope(ActiveScope::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id', 'id');
    }

    public function clientdetails(): BelongsTo
    {
        return $this->belongsTo(ClientDetails::class, 'client_id', 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }

    public function payment(): HasMany
    {
        return $this->hasMany(Payment::class, 'invoice_id')->orderBy('paid_on', 'desc');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'order_id');
    }

    public function clientPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'order_id')
            ->where(['paid_for' => 'client']);
    }

    public function partnerPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'order_id')
            ->where(['paid_for' => 'partner']);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'order_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(CompanyAddress::class, 'company_address_id');
    }

    /*
    public function getOrderNumberAttribute()
    {
        return Str::upper(__('app.order')) . '#' .$this->attributes['order_number'];
    }
    */

    public function unit(): BelongsTo
    {
        return $this->belongsTo(UnitType::class, 'unit_id');
    }

    public function getOriginalOrderNumberAttribute()
    {
        $orderSettings = (company()) ? company()->invoiceSetting : $this->company->invoiceSetting;
        $zero = '';

        if ($orderSettings && (strlen($this->attributes['order_number']) < $orderSettings->order_digit)) {
            $condition = $orderSettings->order_digit - strlen($this->attributes['order_number']);

            for ($i = 0; $i < $condition; $i++) {
                $zero = '0' . $zero;
            }
        }

        return $zero . $this->attributes['order_number'];
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function getOrderNumberAttribute($value)
    {
        if (is_null($value)) {
            return '';
        }

        $orderSettings = (company()) ? company()->invoiceSetting : $this->company->invoiceSetting;
        $zero = '';

        if ($orderSettings && (strlen($value) < $orderSettings->order_digit)) {
            $condition = $orderSettings->order_digit - strlen($value);

            for ($i = 0; $i < $condition; $i++) {
                $zero = '0' . $zero;
            }
        }

        $orderPrefix = $orderSettings ? $orderSettings->order_prefix . $orderSettings->order_number_separator . $zero . $value : $zero . $value;

        return $orderPrefix;
    }

}
