<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Http\Requests\Settings\UpdateOrganisationSettings;
use App\Models\Company;
use App\Models\CompanyMetricaGoals;
use App\Models\GlobalSetting;
use App\Traits\CurrencyExchange;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class SettingsController extends AccountBaseController
{

    use CurrencyExchange;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.accountSettings';
        $this->activeSettingMenu = 'company_settings';
        $this->middleware(function ($request, $next) {
            abort_403(user()->permission('manage_company_setting') !== 'all');

            return $next($request);
        });
    }

    /**
     * XXXXXXXXXXX
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('company-settings.index', $this->data);
    }

    // phpcs:ignore
    public function update(UpdateOrganisationSettings $request, $id)
    {
        $setting = \company();
        $setting->company_name = $request->company_name;
        $setting->company_email = $request->company_email;
        $setting->company_phone = $request->company_phone;
        $setting->website = $request->website;
        $setting->counter_id = $request->counter_id;
        $setting->save();

        if ($request->has('lead_created_goal_id')) {
            CompanyMetricaGoals::query()->updateOrCreate([
                'company_id' => $setting->id,
                'name' => 'lead_created'
            ], [
                'goal_id' => $request->lead_created_goal_id,

            ]);
        }

        if ($request->has('order_created_goal_id')) {
            CompanyMetricaGoals::query()->updateOrCreate([
                'company_id' => $setting->id,
                'name' => 'order_created'
            ],
            [
                'goal_id' => $request->order_created_goal_id,

            ]);
        }

        return Reply::success(__('messages.updateSuccess'));
    }

    // Remove in v 5.2.5
    public function hideWebhookAlert()
    {
        $this->company->show_new_webhook_alert = false;
        $this->company->saveQuietly();
        session()->forget('company');

        return Reply::success('Webohook alert box has been removed permanently');
    }

    public function companyRegister()
    {
        $globalSetting = GlobalSetting::first();

        App::setLocale($globalSetting->locale);
        Carbon::setLocale($globalSetting->locale);
        setlocale(LC_TIME, $globalSetting->locale . '_' . mb_strtoupper($globalSetting->locale));

        return view('auth.register', ['globalSetting' => $globalSetting]);
    }

}
