<?php

namespace App\Http\Requests\Admin\Client;

use App\Http\Requests\CoreRequest;
use App\Traits\CustomFieldsRequestTrait;

class UpdateClientRequest extends CoreRequest
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
    public function rules()
    {
        $rules = [
            'email' => 'nullable|email|required_if:login,enable|unique:users,email,' . $this->route('client') . ',id,company_id,' . company()->id,
            'slack_username' => 'nullable|unique',
            'name' => 'nullable',
            'website' => 'nullable|url',
            'country_phonecode' => 'required_with:mobile',
            'password' => 'nullable|min:8',
            'mobile' => 'nullable|numeric',
            'birthday' => 'nullable',
            'gender' => 'required',
            'firstname' => 'nullable',
            'lastname' => 'nullable',
            'fathername' => 'nullable',
        ];

        $rules = $this->customFieldRules($rules);

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];

        $attributes = $this->customFieldsAttributes($attributes);

        return $attributes;
    }

}
