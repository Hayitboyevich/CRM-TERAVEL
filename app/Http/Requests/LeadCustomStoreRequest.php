<?php

namespace App\Http\Requests;

use App\Traits\CustomFieldsRequestTrait;

class LeadCustomStoreRequest extends CoreRequest
{
    use CustomFieldsRequestTrait;

    /**
     * Determine if the user is authorized to make this request.
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
        $rules = array();

//        $rules['name'] = 'required';

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
