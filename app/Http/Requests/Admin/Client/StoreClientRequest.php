<?php

namespace App\Http\Requests\Admin\Client;

use App\Http\Requests\CoreRequest;
use App\Traits\CustomFieldsRequestTrait;
use Illuminate\Validation\Rule;

class StoreClientRequest extends CoreRequest
{
    use CustomFieldsRequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'firstname' => 'required',
            'lastname' => 'nullable',
            'fathername' => 'nullable',
            'source_id' => 'nullable',

            'email' => 'nullable|email|required_if:login,enable|email:rfc|unique:users,email,null,id,company_id,' . company()->id,
            'password' => 'nullable|required_if:login,enable|min:8',
            'slack_username' => 'nullable',
            'birthday' => 'nullable',
            'website' => 'nullable|url',
//            'country' => 'required_with:mobile',
            'interest' => 'nullable',
            'auditory' => 'nullable',
//            'mobile' => 'required|numeric|unique:users,mobile',
            'mobile' => [
                'required',
                'numeric',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('company_id', company()->id)->where('is_employee', '!=', 1);
                }),
            ],
        ];

        $rules = $this->customFieldRules($rules);

        return $rules;
    }

    public function messages()
    {
        return [
            'website.url' => 'The website format is invalid. Add https:// or http to url'
        ];
    }

    public function attributes()
    {
        $attributes = [];

        $attributes = $this->customFieldsAttributes($attributes);

        return $attributes;
    }

}
