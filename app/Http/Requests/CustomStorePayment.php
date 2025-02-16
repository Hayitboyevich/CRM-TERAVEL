<?php

namespace App\Http\Requests;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;

class CustomStorePayment extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {

        $rules = [
            'paid_on' => 'required',
            'offline_methods' => 'required_if:gateway,==,Offline',
        ];

        if (request('invoice_id') != '') {
            $invoice = Invoice::findOrFail(request('invoice_id'));

            if ($invoice->amountDue() == 0) {
                $rules['amount'] = 'required|numeric';

            } else {
                $rules['amount'] = 'required|numeric|min:1';
            }
        } else {
            $rules['amount'] = 'required|numeric|min:1';
        }


        if ($this->transaction_id) {

            // It need to be unique for all the company
            $rules['transaction_id'] = 'unique:payments,transaction_id';
        }

        if (request('default_client') != '') {
            $rules['invoice_id'] = 'required_without:project_id';
            $rules['project_id'] = 'required_without:invoice_id';
        }


        return $rules;
    }

    public function attributes()
    {
        return [
            'invoice_id' => __('app.invoice'),
            'project_id' => __('app.project'),
        ];
    }
}
