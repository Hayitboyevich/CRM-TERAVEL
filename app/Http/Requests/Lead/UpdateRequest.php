<?php

namespace App\Http\Requests\Lead;

use App\Http\Requests\CoreRequest;
use App\Traits\CustomFieldsRequestTrait;

class UpdateRequest extends CoreRequest
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
            'client_name' => 'required',
            'callback_time' => 'nullable|date_format:H:i',
            'callback_date' => 'nullable|date',
        ];

        $rules = $this->customFieldRules($rules);

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $callback_time = $this->input('callback_time');
            $callback_date = $this->input('callback_date');

            if (($callback_time && !$callback_date) || ($callback_date && !$callback_time)) {
                $validator->errors()->add('callback_date', 'Both callback time and date must be provided together.');
                $validator->errors()->add('callback_time', 'Both callback time and date must be provided together.');
            }
        });
    }

    public function attributes()
    {
        $attributes = [];

        $attributes = $this->customFieldsAttributes($attributes);

        return $attributes;
    }

}
