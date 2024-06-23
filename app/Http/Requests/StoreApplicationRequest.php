<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
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
            "from_city_id" => 'nullable',
            "country_id" => 'nullable',
            "region_id" => 'nullable',
            "hotel_name" => 'nullable',
            "room_type" => 'nullable',
            "bed_type" => 'nullable',
            "meal_type" => 'nullable',
            "partner_id" => 'nullable',

            "unit_nett_price" => 'required',
            "nett_currency_id" => 'required',
            "nett_exchange_rate" => 'required',
            "unit_price" => 'required',
            "currency_id" => 'required',
            "exchange_rate" => 'required',

            "date_from" => 'nullable',
            "date_to" => 'nullable',
            "adults_count" => 'nullable',
            "infants_count" => 'nullable',
            "children_count" => 'nullable',
            "visa" => 'nullable',
            "transfer" => 'nullable',
        ];
    }
}
