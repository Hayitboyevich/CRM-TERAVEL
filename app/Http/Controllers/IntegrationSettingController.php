<?php

namespace App\Http\Controllers;

use App\DataTables\IntegrationCredentialDataTable;
use App\Helper\Reply;
use App\Models\IntegrationCredential;
use Illuminate\Http\Request;

class IntegrationSettingController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.integrationSettings';
        $this->activeSettingMenu = 'integration_settings';

        $this->middleware(function ($request, $next) {

            return $next($request);
        });
    }

    public function index(IntegrationCredentialDataTable $dataTable)
    {
        return $dataTable->render('integration-settings.index', $this->data);

    }

    public function edit($id)
    {
        $this->setting = IntegrationCredential::findOrFail($id);
        return view('integration-settings.edit', $this->data);
    }

    public function update(Request $request, $id)
    {

        $setting = IntegrationCredential::findOrFail($id);
        $setting->update($request->all());


        session()->forget('message_setting');
        return Reply::success(__('messages.updateSuccess'));
    }

}
