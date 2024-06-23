<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CustomUpdateOrderRequest extends FormRequest
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
            "client_id" => 'nullable',
            "total_paid" => "nullable",
            "net_price" => "nullable",
            "item_name" => "nullable",
            "hotel" => "nullable",
            "visa" => "nullable",
            "air_ticket" => "nullable",
            "transfer" => "nullable",
            "insurance" => "nullable",
            "service_fee" => "nullable",
            "adults_count" => "nullable",
            "children_count" => "nullable",
            'nights_count_from' => 'nullable',
            'nights_count_to' => 'nullable',
            "total" => "nullable",
            "status" => "nullable",
            "currency_id" => "nullable",
            "note" => "nullable",
            "created_at" => 'nullable',
            'partner_id' => 'nullable'
        ];
    }
}
