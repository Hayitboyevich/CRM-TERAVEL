<?php

namespace App\Http\Requests\SuperAdmin\FaqCategory;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;
use App\Models\Company;

class UpdateRequest extends SuperAdminBaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = [
            'name' => 'required',
        ];

        return $data;
    }

}
