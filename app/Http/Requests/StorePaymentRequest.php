<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'payment_date' => 'nullable',
            'payment_time' => 'nullable',
            'type' => 'nullable',
            'payment_type' => 'nullable',
            'amount' => 'nullable',
            'currency_id' => 'nullable',
            'exchange_rate' => 'nullable',
            'bank_account_id' => 'required',
            'paid_for' => 'nullable',
            'partner_id' => 'nullable'
        ];
    }
}
