<?php

namespace App\Http\Controllers\Api;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Http\Requests\PassportScanRequest;
use App\Models\PassportScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PassportScanController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function scan(PassportScanRequest $request)
    {
        $image = $request->file('passport_image');

        $response = Http::attach(
            'selfie', file_get_contents($image->path()), $image->getClientOriginalName()
        )
            ->withHeaders([
                'X-Organization' => '09792dce-16fd-4de8-b797-569e1d0cea60',
                'X-Organization-Signature' => '69a3c3b062e7a64c0e0e32d8f676e65c6ec8ba648e9806d6329e135685c52429'

            ])
            ->attach(
                'front', file_get_contents($image->path()), $image->getClientOriginalName()
            )
            ->attach(
                'back', file_get_contents($image->path()), $image->getClientOriginalName()
            )
            ->post('http://92.204.253.20/api/v1/ocr/verification');

        if ($response->failed()) {
            return "Request failed. Status Code: " . $response->status() . $response->body();
        }

        $response = json_decode($response->body())->data->result;
        return response()->json($response);

        return Reply::error(__('messages.invoicePaymentExceedError'));
    }

    public function openScanner()
    {
        $this->view = 'applications.passport.scan';
        $this->pageTitle = 'Scanner';
//        $this->client_id = $client_id;

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }
        return view('applications.clients.template', $this->data);
    }

    public function increaseScanNumber(Request $request)
    {
        Log::info('Request: ' . $request->date);
        $companyId = company()->id;
        $date = $request->date;

        PassportScan::updateOrCreate(
            [
                'company_id' => $companyId,
                'date' => $date
            ],
            [
                'number' => DB::raw('number + 1')
            ]
        );

        return Reply::success(__('messages.recordSaved'));
    }


}
