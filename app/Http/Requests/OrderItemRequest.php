<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderItemRequest extends FormRequest
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
            'from_city_id' => 'nullable',
            'country_id' => 'nullable',
            'region_id' => 'nullable',
            "hotel_id" => 'nullable',
            'type_id' => 'nullable',
            'meal_id' => 'nullable',
            'nights_count_from' => 'nullable',
            'nights_count_to' => 'nullable',

            'unit_net_price' => 'required',
            'nett_currency_id' => 'required|exists:currencies,id',
            'nett_exchange_rate' => 'required',


            'unit_price' => 'required',
            'currency_id' => 'required|exists:currencies,id',
            'exchange_rate' => 'required',

            'children_count' => 'nullable',
            'adults_count' => 'nullable',
            'infants_count' => 'nullable',

            'visa' => 'nullable',
            'transfer' => 'nullable',
            'airticket' => 'nullable',
            'insurance' => 'nullable',

            'date_from' => 'nullable',
            'date_to' => 'nullable',
            'hotel_name' => 'nullable',
            'room_type' => 'nullable',


            'partner_id' => 'nullable',

        ];
    }
}
