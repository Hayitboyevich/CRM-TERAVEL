<?php

namespace App\Http\Requests\LeadSetting;

use App\Http\Requests\CoreRequest;

class UpdateLeadStatus extends CoreRequest
{

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
        return [
            'time' => 'nullable',
            'type' => 'required|unique:lead_status,type,' . $this->route('lead_status_setting') . ',id,company_id,' . company()->id,
            'label_color' => 'required'
        ];
    }

}
