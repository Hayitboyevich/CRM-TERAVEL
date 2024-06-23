<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class StoreTravellerRequest extends FormRequest
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
            'mobile' =>  [
                'nullable',
                Rule::unique('users', 'mobile')->where(function ($query) {
                    return $query->where('mobile', '<>', 'unknown')
                        ->whereNotNull('mobile');
                }),
            ],
            'country_phonecode' => 'required',
            'firstname' => 'nullable',
            'lastname' => 'nullable',
            'fathername' => 'nullable',
            'gender' => 'nullable',
            'birthday' => 'nullable',
            'foreign' => 'nullable',
            'passport' => 'nullable',
            'foreign_passport_image' => 'nullable',
            'passport_image' => 'nullable',
            'foreign.passport_serial_number' => 'nullable',
            'passport.passport_serial_number' => 'nullable|numeric',
            'passport.stir' => 'nullable|numeric',

        ];

    }

    public function messages()
    {
        return [
            'mobile.required' => 'The mobile number is required.',
            'mobile.unique' => 'The mobile number has already been taken.',
            'mobile.numeric' => 'The mobile number must be a numeric value.',
            'country_phonecode.required' => 'The country phone code is required.',
            'foreign.passport_serial_number.numeric' => 'The passport serial number must be a numeric value.',
            'passport.passport_serial_number.numeric' => 'The passport serial number must be a numeric value.',
            'passport.stir.numeric' => 'The passport STIR must be a numeric value.',
        ];
    }
}
