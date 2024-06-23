<?php

namespace App\Http\Controllers\Applications;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Application;
use App\Models\BankAccount;
use App\Models\Currency;
use App\Models\IntegrationPartner;
use App\Models\Payment;
use App\Models\Role;
use Exception;
use Illuminate\Support\Facades\DB;
use Mollie\Api\Types\PaymentStatus;

class CustomPaymentController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create(Application $application, $type)
    {
        $this->pageTitle = "Payments";
        $this->currencies = Currency::all();
        $this->partners = IntegrationPartner::all();

        $this->application = $application;
        $this->bankDetails = BankAccount::query()->get();
        $this->type = $type;
        $this->view = 'applications.payments.create';

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }
        return view($this->view, $this->data);
    }

    public function store(Application $application, StorePaymentRequest $request)
    {
        if (!$application->order) {
            throw new Exception('Пожалуйста, сначала добавьте заказ!');
        }

        $data = $request->validated();

//        $data['status'] = auth()->user()->hasRole('finance') ? 'complete' : 'pending'; // TODO: remove this line
        $data['status'] = 'pending';
        $data['customer_id'] = $application->client_id;
        $data['company_id'] = company()->id;
        $data['order_id'] = $application->order->id;
        $data['paid_on'] = strtotime($data['payment_date'] . ' ' . $data['payment_time']);
        unset($data['payment_date']);
        unset($data['payment_time']);

        $payment = new Payment();
        $payment->fill($data);
        DB::beginTransaction();
        try {
            $payment->save();
            if(auth()->user()->hasRole('finance') || auth()->user()->hasRole('admin'))
            {
                $payment->status = 'complete';
                $payment->save();
            }


        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('applications.edit', $application->id);
        }

        if ($request->has('ajax_create')) {
            return Reply::successWithData(__('messages.recordSaved'), ['orderData' => $payment, 'redirectUrl' => $redirectUrl]);
        }
        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);
    }

}
