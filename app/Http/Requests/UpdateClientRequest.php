<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
            'firstname' => 'nullable',
            'lastname' => 'nullable',
            'fathername' => 'nullable',
            'gender' => 'nullable',
            'birthday' => 'nullable',
            'country_phonecode' => 'nullable',
            'mobile' => 'nullable',
            'foreign' => 'nullable',
            'passport' => 'nullable',
            'foreign_passport_image' => 'nullable',
            'passport_image' => 'nullable'

        ];
    }
}
