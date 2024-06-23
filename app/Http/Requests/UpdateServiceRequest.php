<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
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
            'description' => 'nullable',
            'bed_type_id' => 'nullable',
            'country_id' => 'nullable',
            'region_id' => 'nullable',
            'hotel_id' => 'nullable',
            'meal_id' => 'nullable',
            'children_count' => 'nullable',
            'adults_count' => 'nullable',
            'infants_count' => 'nullable',

            'type_id' => 'nullable',
            'partner_id' => 'nullable',
            'schema_id' => 'nullable',

            'date_from' => 'nullable',
            'date_to' => 'nullable',
            'room_type_id' => 'nullable',

            'exchange_rate' => 'nullable',
            'net_exchange_rate' => 'nullable',
            'price' => 'nullable',
            'net_price' => 'nullable',
            'currency_id' => 'nullable',
            'net_currency_id' => 'nullable',

            'name' => 'nullable',
        ];
    }
}
