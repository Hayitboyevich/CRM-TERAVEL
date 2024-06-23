<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkFlowRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           'social_network_id' => 'required',
           'social_event_id' => 'required',
           'condition_id' => 'required',
           'verify_id' => 'required',
           'text' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'company_token' => company()->hash,
        ]);
    }
}
