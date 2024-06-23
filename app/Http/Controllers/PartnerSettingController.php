<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Http\Requests\StorePartnerReqeust;
use App\Models\IntegrationPartner;
use Illuminate\Http\Response;

class PartnerSettingController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
//            abort_403(!in_array('partner', $this->user->modules));
            return $next($request);
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->addPermission = user()->permission('add_partner');
        abort_403(!in_array($this->addPermission, ['all', 'added']));


        return view('partner-settings.create-partner-modal', $this->data);
    }

    public function store(StorePartnerReqeust $request)
    {
        $this->addPermission = user()->permission('add_lead_agent');
        abort_403(!in_array($this->addPermission, ['all', 'added']));
        $data = $request->validated();
        $partner = new IntegrationPartner();
        $data['company_id'] = company()->id;
        $partner->fill($data);
        $partner->save();

        $partners = IntegrationPartner::query()->get();

        $list = '<option value="">--</option>';

        foreach ($partners as $partner) {

            $list .= '<option value="' . $partner->id . '"> ' . $partner->name . ' </option>';
        }

        return Reply::successWithData(__('messages.recordSaved'), ['data' => $list]);

    }


}
