<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\SmsSetting;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class SmsSettingController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.smsSetting';
        $this->activeSettingMenu = 'sms_settings';
        $this->middleware(function ($request, $next) {
            abort_403(!(user()->permission('manage_sms_settings') == 'all'));
            return $next($request);
        });
    }

    public function index()
    {
        $this->settings = SmsSetting::query()->first()->toArray();
        $this->template = SmsTemplate::query()->get()->keyBy('name')->toArray();

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('sms-settings.index', $this->data);
    }

    public function update(Request $request)
    {
        $settings = Arr::get($request->all(), 'settings');
        $templates = Arr::get($request->all(), 'template');
        $company_id = company()->id;

        $updateData = [
            'url' => $settings['sms_url'] ?? null,
            'username' => $settings['sms_username'] ?? null,
            'created_at' => now(),
            'updated_at' => now()
        ];

        if (!empty($settings['sms_password'])) {
            $updateData['password'] = Hash::make($settings['sms_password']);
        }

        SmsSetting::query()->updateOrCreate(
            ['company_id' => $company_id],
            $updateData
        );

        // saving sms template
        $templateKeys = [
            'before_flight' => 'before_flight_status',
            'after_land' => 'after_land_status',
            'before_birthday' => 'before_birthday_status'
        ];

        foreach ($templateKeys as $templateName => $statusKey) {
            $content = $templates[$templateName] ?? null;
            $status = $request->has("template.$statusKey") && $templates[$statusKey] === 'on' ? 1 : 0;

            SmsTemplate::updateOrCreate(
                ['company_id' => $company_id, 'name' => $templateName],
                ['content' => $content, 'status' => $status, 'updated_at' => now()]
            );
        }


        return Reply::success(__('messages.updateSuccess'));
    }

}
