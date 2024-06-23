<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateIntegrationRequest extends FormRequest
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
            'client_id' => 'nullable|integer',

            'from_city_id' => 'nullable|integer',
            'from_city_name' => 'nullable|string',

            'to_country_id' => 'nullable|integer',
            'to_country_name' => 'nullable|string',

            'to_city_id' => 'nullable|integer',
            'to_city_name' => 'nullable|string',

            'tour_id' => 'nullable|integer',
            'tour_name' => 'nullable|string',

            'hotel_id' => 'nullable|integer',
            'hotel_name' => 'nullable|string',

            'category_id' => 'nullable|integer',
            'category_name' => 'nullable|string',

            'program_group' => 'nullable|integer',

            'checkin_begin' => 'nullable|date',
            'checkin_end' => 'nullable|date',
            'program_type_id' => 'nullable|integer',

            'nights_count_from' => 'nullable|integer',
            'nights_count_to' => 'nullable|integer',
            'children_count' => 'nullable|integer',
            'adults_count' => 'nullable|integer',


            'budget' => 'nullable|integer',
            'user_id' => 'nullable|integer',
        ];
    }
}
