<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\OrderItems
 *
 * @property int $id
 * @property int $order_id
 * @property string $item_name
 * @property string|null $item_summary
 * @property string $type
 * @property int $quantity
 * @property int $unit_price
 * @property float $amount
 * @property string|null $hsn_sac_code
 * @property string|null $taxes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|OrderItems newModelQuery()
 * @method static Builder|OrderItems newQuery()
 * @method static Builder|OrderItems query()
 * @method static Builder|OrderItems whereAmount($value)
 * @method static Builder|OrderItems whereCreatedAt($value)
 * @method static Builder|OrderItems whereHsnSacCode($value)
 * @method static Builder|OrderItems whereId($value)
 * @method static Builder|OrderItems whereItemName($value)
 * @method static Builder|OrderItems whereItemSummary($value)
 * @method static Builder|OrderItems whereOrderId($value)
 * @method static Builder|OrderItems whereQuantity($value)
 * @method static Builder|OrderItems whereTaxes($value)
 * @method static Builder|OrderItems whereType($value)
 * @method static Builder|OrderItems whereUnitPrice($value)
 * @method static Builder|OrderItems whereUpdatedAt($value)
 * @property int|null $product_id
 * @property-read OrderItemImage|null $orderItemImage
 * @property-read Product|null $product
 * @method static Builder|OrderItems whereProductId($value)
 * @property-read mixed $tax_list
 * @property int|null $product_id
 * @property int|null $unit_id
 * @property-read UnitType|null $unit
 * @method static Builder|OrderItems whereUnitId($value)
 * @mixin Eloquent
 */
class OrderItems extends BaseModel
{
    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',

    ];


    protected $fillable = [
        'country_id',
        'region_id',
        'hotel_id',
        'meal_id',
        'bed_type_id',
        'type_id',

        'order_id', 'product_id',
        'item_name', 'item_summary', 'type',
        'quantity', 'unit_price', 'amount', 'hsn_sac_code', 'taxes', 'unit_id',
        'partner_id',
        'unit_price',
        'from_city_id',
        'status' => 'pending',

        'nett_amount',
        'unit_net_price',
        'status',
        'nett_exchange_rate',
        'nett_currency_id',

        'currency_id',
        'exchange_rate',
        'date_from', 'date_to', 'infants_count', 'children_count', 'adults_count'
    ];
//    protected $casts = [
//        'date_from' => 'datetime',
//        'date_to' => 'datetime'
//    ];
    protected $with = ['orderItemImage', 'product'];

    public function region()
    {
        return $this->belongsTo(IntegrationTown::class, 'region_id');
    }

    public function mealType()
    {
        return $this->belongsTo(TourMealType::class, 'meal_id');
    }

    public function beadType()
    {
        return $this->belongsTo(TourBedType::class, 'bed_type_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function fromCity()
    {
        return $this->belongsTo(IntegrationCity::class, 'from_city_id');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function localCurrencyId($companyId)
    {
        return Currency::query()->where('company_id', $companyId)->where('currency_code', '=', 'UZS')->pluck('id')->first();
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(IntegrationPartner::class, 'partner_id');
    }

    public function orderItemImage(): HasOne
    {
        return $this->hasOne(OrderItemImage::class, 'order_item_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getTaxListAttribute()
    {
        $orderItem = OrderItems::findOrFail($this->id);
        $taxes = '';

        if ($orderItem && $orderItem->taxes) {
            $numItems = count(json_decode($orderItem->taxes));

            if (!is_null($orderItem->taxes)) {
                foreach (json_decode($orderItem->taxes) as $index => $tax) {
                    $tax = $this->taxbyid($tax)->first();
                    $taxes .= $tax->tax_name . ': ' . $tax->rate_percent . '%';

                    $taxes = ($index + 1 != $numItems) ? $taxes . ', ' : $taxes;
                }
            }
        }

        return $taxes;

    }

    public static function taxbyid($id)
    {
        return Tax::where('id', $id)->withTrashed();
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(UnitType::class, 'unit_id');
    }

    public function tourPackage(): BelongsTo
    {
        return $this->belongsTo(TourPackage::class, 'tour_package_id');
    }

}
