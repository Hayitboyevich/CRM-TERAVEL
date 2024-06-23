<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Http\Requests\DeadlinePaymentRequest;
use App\Models\Application;
use App\Models\DeadlinePayment;

class DeadlinePaymentController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();

    }

    public function create($type, Application $application)
    {
        $this->application = $application;
        $this->type = $type;

        $this->deadline = DeadlinePayment::query()
            ->where('application_id', $application->id)
            ->where('type', $this->type)
            ->first();

        $this->pageTitle = 'Deadline';
        $this->view = 'applications.payments.deadline-payment';

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('applications.payments.template', $this->data);
    }

    public function update(Application $application, DeadlinePaymentRequest $request)
    {
        $data = $request->validated();
        $data['application_id'] = $application->id;
        if ($data['deadline']) {
            $data['deadline'] = date('Y-m-d', strtotime($data['deadline']));
        }

        DeadlinePayment::query()->updateOrCreate([
            'application_id' => $application->id,
            'type' => $data['type']
        ], $data);

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('applications.edit', $application->id);
        }

        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);
    }
}
