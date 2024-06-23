<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePackageRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'nullable',
            'company_mobile' => 'nullable',
            'date_from' => 'nullable',
            'date_to' => 'nullable',
            'services' => 'nullable',
            'type_id' => 'nullable|exists:products,id',
            'quantity' => 'nullable',
            'net_exchange_rate' => 'nullable',
            'exchange_rate' => 'nullable',
            'net_currency_id' => 'nullable',
            'currency_id' => 'nullable',
            'net_price' => 'nullable',
            'price' => 'nullable',
            'hotel_name' => 'nullable',

            'country_id' => 'nullable',
            'region_id' => 'nullable',
            'hotel_id' => 'nullable',

            'room_type' => 'nullable',
            'meal_id' => 'nullable',
            'adults_count' => 'nullable',
            'children_count' => 'nullable',
            'infants_count' => 'nullable',

        ];
    }
}
