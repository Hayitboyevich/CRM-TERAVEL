<?php

namespace App\Models;

use App\Traits\CustomFieldsTrait;
use App\Traits\HasCompany;
use Database\Factories\ProductFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property string $price
 * @property string|null $taxes
 * @property int $allow_purchase
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $description
 * @property int|null $unit_id
 * @property int|null $category_id
 * @property int|null $sub_category_id
 * @property int|null $added_by
 * @property int|null $last_updated_by
 * @property string|null $hsn_sac_code
 * @property-read mixed $icon
 * @property-read mixed $total_amount
 * @property-read Tax $tax
 * @method static ProductFactory factory(...$parameters)
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product whereAddedBy($value)
 * @method static Builder|Product whereAllowPurchase($value)
 * @method static Builder|Product whereCategoryId($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereHsnSacCode($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereLastUpdatedBy($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product wherePrice($value)
 * @method static Builder|Product whereSubCategoryId($value)
 * @method static Builder|Product whereTaxes($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @property-read ProductCategory|null $category
 * @property string|null $image
 * @property-read mixed $image_url
 * @method static Builder|Product whereImage($value)
 * @property int $downloadable
 * @property string|null $downloadable_file
 * @property string|null $default_image
 * @property-read Collection|ProductFiles[] $files
 * @property-read int|null $files_count
 * @property-read mixed $download_file_url
 * @property-read mixed $extras
 * @property-read ProductSubCategory|null $subCategory
 * @method static Builder|Product whereDefaultImage($value)
 * @method static Builder|Product whereDownloadable($value)
 * @method static Builder|Product whereDownloadableFile($value)
 * @property int|null $company_id
 * @property-read Company|null $company
 * @property-read mixed $tax_list
 * @method static Builder|Product whereCompanyId($value)
 * @property-read Collection<int, Lead> $leads
 * @property-read int|null $leads_count
 * @property-read UnitType|null $unit
 * @method static Builder|Product whereUnitId($value)
 * @property-read Collection<int, Lead> $leads
 * @property-read Collection<int, Lead> $leads
 * @property-read Collection<int, Lead> $leads
 * @property-read Collection<int, Lead> $leads
 * @property-read Collection<int, Lead> $leads
 * @property-read Collection<int, Lead> $leads
 * @property-read Collection<int, OrderItems> $orderItem
 * @property-read int|null $order_item_count
 * @property-read Collection<int, Lead> $leads
 * @property-read Collection<int, OrderItems> $orderItem
 * @mixin Eloquent
 */
class Product extends BaseModel
{

    use HasCompany;
    use HasFactory, CustomFieldsTrait;

    const FILE_PATH = 'products';
    const CUSTOM_FIELD_MODEL = 'App\Models\Product';
    protected $table = 'products';
    protected $fillable = ['name', 'price', 'description', 'taxes', 'partner_id'];
    protected $appends = ['total_amount', 'image_url', 'download_file_url'];
    protected $with = ['tax'];

    public function getImageUrlAttribute()
    {
        if (app()->environment(['development', 'demo']) && str_contains($this->default_image, 'http')) {
            return $this->default_image;
        }

        return ($this->default_image) ? asset_url_local_s3(Product::FILE_PATH . '/' . $this->default_image) : '';
    }

    public function getDownloadFileUrlAttribute()
    {
        return ($this->downloadable_file) ? asset_url_local_s3(Product::FILE_PATH . '/' . $this->downloadable_file) : null;
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class)->withTrashed();
    }

    public function leads(): BelongsToMany
    {
        return $this->belongsToMany(Lead::class, 'lead_products');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(UnitType::class, 'unit_id');
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(ProductSubCategory::class, 'sub_category_id');
    }

    public function getTotalAmountAttribute()
    {

        if (!is_null($this->price) && !is_null($this->tax)) {
            return (int)$this->price + ((int)$this->price * ((int)$this->tax->rate_percent / 100));
        }

        return '';
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'type_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(ProductFiles::class, 'product_id')->orderBy('id', 'desc');
    }

    public function getTaxListAttribute()
    {
        $productItem = Product::findOrFail($this->id);
        $taxes = '';

        if ($productItem && $productItem->taxes) {
            $numItems = count(json_decode($productItem->taxes));

            if (!is_null($productItem->taxes)) {
                foreach (json_decode($productItem->taxes) as $index => $tax) {
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

    public function orderItem(): HasMany
    {
        return $this->hasMany(OrderItems::class, 'product_id');

    }

}
