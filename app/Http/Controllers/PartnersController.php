<?php

namespace App\Http\Controllers;

use App\DataTables\PartnersDataTable;
use App\Helper\Reply;
use App\Http\Requests\StorePartnerReqeust;
use App\Models\IntegrationPartner;
use App\Models\IntegrationState;
use App\Models\User;
use Illuminate\Http\Response;

class PartnersController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'modules.partner.partnerDetails';
        $this->middleware(function ($request, $next) {
//            abort_403(!in_array('clients', $this->user->modules));
            return $next($request);
        });
    }


    /**
     * client list
     *
     * @return Response
     */
    public function index(PartnersDataTable $dataTable)
    {
//        $viewPermission = user()->permission('view_partner');
        $this->addPartnerPermission = 'all';

//        abort_403(!in_array($viewPermission, ['all', 'added', 'both']));

        if (!request()->ajax()) {
            $this->partners = IntegrationPartner::query()->get();
        }

        return $dataTable->render('partners.index', $this->data);
    }

    public function store(StorePartnerReqeust $request)
    {
        $data = $request->validated();
        $data['company_id'] = company()->id;
//        $data['countries'] = json_encode($data['countries']);
        IntegrationPartner::query()->create($data);
        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('partners.index');
        }

        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);

    }

    public function create()
    {
        $this->addPermission = 'all';
//        abort_403(!in_array($this->addPermission, User::ALL_ADDED_BOTH));

        $this->pageTitle = __('app.addPartner');
        $this->countries = IntegrationState::query()->get();
        if (request()->ajax()) {
            $html = view('partners.ajax.create', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }
        $this->view = 'partners.ajax.create';
        return view('partners.create', $this->data);
    }

    public function update(IntegrationPartner $partner, StorePartnerReqeust $request)
    {
        $data = $request->validated();
//        $data['countries'] = json_encode($data['countries']);
        $partner->fill($data);
        $partner->save();
//        Partner::query()->create($data);
        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('partners.index');
        }

        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);

    }

    public function edit($id)
    {
        $this->editPermission = user()->permission('edit_partner');
        $this->countries = IntegrationState::query()->get();
        $this->partner = IntegrationPartner::query()->findOrFail($id);
        abort_403(!in_array($this->editPermission, User::ALL_ADDED_BOTH));

        if (request()->ajax()) {
            $html = view('partners.ajax.edit', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }
        $this->view = 'partners.ajax.edit';
        return view('partners.create', $this->data);
    }

    public function destroy($id)
    {
        IntegrationPartner::query()->findOrFail($id)->delete();
        return Reply::success(__('messages.recordDeleted'));
    }
}
