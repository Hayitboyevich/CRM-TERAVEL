<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\BankAccount;
use App\Models\Currency;
use App\Models\OfflinePaymentMethod;
use App\Models\PaymentGatewayCredentials;

class PartnerPaymentController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.payments';
        $this->middleware(function ($request, $next) {
//            abort_403(!in_array('payments', $this->user->modules));
            return $next($request);
        });
    }

    public function create()
    {
        $this->addPermission = user()->permission('add_payments');
        abort_403(!in_array($this->addPermission, ['all', 'added']));

        $this->pageTitle = __('modules.payments.addPayment');
        $this->viewBankAccountPermission = user()->permission('view_bankaccount');


        $this->currencyCode = company()->currency->currency_code;
        $this->exchangeRate = company()->currency->exchange_rate;
        $bankAccountQuery = BankAccount::query();

        if ($this->viewBankAccountPermission == 'added') {
            $bankAccountQuery = $bankAccountQuery->where('added_by', user()->id);
            /* @phpstan-ignore-line */
        }

        $bankAccounts = $bankAccountQuery->get();
        $this->bankDetails = $bankAccounts;

        $this->currencies = Currency::all();
        $this->offlineMethod = OfflinePaymentMethod::all();

        $this->paymentGateway = PaymentGatewayCredentials::first();
        $this->linkPaymentPermission = user()->permission('link_payment_bank_account');
        $this->companyCurrency = Currency::where('id', company()->currency_id)->first();

        if (request()->ajax()) {
            $html = view('payments.ajax.create', $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'partner-payments.ajax.create';

        return view('partner-payments.create', $this->data);
    }

    public function store()
    {

    }
}