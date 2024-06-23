<?php

namespace App\Models;

use App\Traits\HasCompany;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Payment
 *
 * @property int $id
 * @property string $date
 * @property string $exchange_rate
 * @property string $total
 * @property string $project_name
 * @property int|null $project_id
 * @property int|null $invoice_id
 * @property float $amount
 * @property string|null $gateway
 * @property string|null $transaction_id
 * @property int|null $currency_id
 * @property string|null $plan_id
 * @property string|null $customer_id
 * @property string|null $client_id
 * @property string|null $event_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $paid_on
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $offline_method_id
 * @property string|null $bill
 * @property int|null $added_by
 * @property int|null $last_updated_by
 * @property int|null $default_currency_id
 * @property-read Currency|null $currency
 * @property-read mixed $file_url
 * @property-read mixed $icon
 * @property-read mixed $paid_date
 * @property-read mixed $total_amount
 * @property-read Invoice|null $invoice
 * @property-read OfflinePaymentMethod|null $offlineMethod
 * @property-read Project|null $project
 * @method static Builder|Payment newModelQuery()
 * @method static Builder|Payment newQuery()
 * @method static Builder|Payment query()
 * @method static Builder|Payment whereAddedBy($value)
 * @method static Builder|Payment whereAmount($value)
 * @method static Builder|Payment whereBill($value)
 * @method static Builder|Payment whereCreatedAt($value)
 * @method static Builder|Payment whereCurrencyId($value)
 * @method static Builder|Payment whereCustomerId($value)
 * @method static Builder|Payment whereEventId($value)
 * @method static Builder|Payment whereGateway($value)
 * @method static Builder|Payment whereId($value)
 * @method static Builder|Payment whereInvoiceId($value)
 * @method static Builder|Payment whereLastUpdatedBy($value)
 * @method static Builder|Payment whereOfflineMethodId($value)
 * @method static Builder|Payment wherePaidOn($value)
 * @method static Builder|Payment wherePlanId($value)
 * @method static Builder|Payment whereProjectId($value)
 * @method static Builder|Payment whereRemarks($value)
 * @method static Builder|Payment whereStatus($value)
 * @method static Builder|Payment whereTransactionId($value)
 * @method static Builder|Payment whereUpdatedAt($value)
 * @property int|null $order_id
 * @property string|null $payment_gateway_response null = success
 * @method static Builder|Payment completed()
 * @method static Builder|Payment whereOrderId($value)
 * @method static Builder|Payment wherePaymentGatewayResponse($value)
 * @property int|null $credit_notes_id
 * @property string|null $payload_id
 * @property-read CreditNotes|null $creditNote
 * @property-read Order|null $order
 * @method static Builder|Payment whereCreditNotesId($value)
 * @method static Builder|Payment wherePayloadId($value)
 * @property int|null $company_id
 * @property-read Company|null $company
 * @method static Builder|Payment whereCompanyId($value)
 * @property int|null $bank_account_id
 * @property int|null $quickbooks_payment_id
 * @property-read OfflinePaymentMethod|null $offlineMethods
 * @property-read Collection<int, BankTransaction> $transactions
 * @property-read int|null $transactions_count
 * @method static Builder|Payment whereBankAccountId($value)
 * @method static Builder|Payment whereDefaultCurrencyId($value)
 * @method static Builder|Payment whereExchangeRate($value)
 * @method static Builder|Payment whereQuickbooksPaymentId($value)
 * @property-read Collection<int, BankTransaction> $transactions
 * @property-read Collection<int, BankTransaction> $transactions
 * @property-read Collection<int, BankTransaction> $transactions
 * @property-read Collection<int, BankTransaction> $transactions
 * @property-read Collection<int, BankTransaction> $transactions
 * @property-read Collection<int, BankTransaction> $transactions
 * @property-read Collection<int, BankTransaction> $transactions
 * @mixin Eloquent
 */
class Payment extends BaseModel
{

    use HasCompany;

    const FILE_PATH = 'payment-receipt';
    const TYPE_DEBIT = "debit";
    const TYPE_CREDIT = "credit";

    protected $casts = [
        'paid_on' => 'datetime',
        'payment_gateway_response' => 'object'
    ];

    protected $appends = ['total_amount', 'paid_date', 'file_url'];
    protected $with = ['currency', 'order'];
    protected $guarded = ['id'];

    public function client()
    {
        if (!is_null($this->project_id) && $this->project->client_id) {
            return $this->project->client;
        }

        if ($this->invoice_id != null) {
            if ($this->invoice->client_id) {
                return $this->invoice->client;
            }

            if (!is_null($this->invoice->project_id) && $this->invoice->project->client_id) {
                return $this->invoice->project->client;
            }
        }

        return null;
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function creditNote(): BelongsTo
    {
        return $this->belongsTo(CreditNotes::class, 'credit_notes_id');
    }

    public function payment()
    {
        return $this->belongsTo(IntegrationPartner::class, 'partner_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id')->withTrashed();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(BankTransaction::class, 'payment_id');
    }

    public function offlineMethod(): BelongsTo
    {
        return $this->belongsTo(OfflinePaymentMethod::class, 'offline_method_id');
    }

    public function getTotalAmountAttribute()
    {
        return (!is_null($this->amount) && !is_null($this->currency_id)) ? $this->amount : '';
    }

    public function getPaidDateAttribute()
    {
        return !is_null($this->paid_on) ? Carbon::parse($this->paid_on)->format('d F, Y H:i A') : '';
    }

    public function getFileUrlAttribute()
    {
        return asset_url_local_s3(Payment::FILE_PATH . '/' . $this->bill);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'complete');
    }

    public function offlineMethods(): BelongsTo
    {
        return $this->belongsTo(OfflinePaymentMethod::class, 'offline_method_id');
    }

}
