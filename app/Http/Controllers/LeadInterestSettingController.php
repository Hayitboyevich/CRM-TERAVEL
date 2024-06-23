<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Http\Requests\LeadSetting\StoreLeadSource;
use App\Http\Requests\LeadSetting\UpdateLeadSource;
use App\Http\Requests\UpdateLeadInterest;
use App\Models\BaseModel;
use App\Models\LeadInterest;
use Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException;
use Illuminate\Http\Response;

class LeadInterestSettingController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('leads', $this->user->modules));
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
        $this->addPermission = user()->permission('add_lead_sources');
        abort_403(!in_array($this->addPermission, ['all', 'added']));

        return view('lead-settings.create-interest-modal');

    }

    /**
     * @param StoreLeadSource $request
     * @return array|void
     * @throws RelatedResourceNotFoundException
     */
    public function store(StoreLeadSource $request)
    {
        $this->addPermission = user()->permission('add_lead_sources');

        abort_403(!in_array($this->addPermission, ['all', 'added']));

        $source = new LeadInterest();
        $source->type = $request->type;
        $source->save();

        $leadInterest = LeadInterest::get();

        $options = BaseModel::options($leadInterest, $source, 'type');

        return Reply::successWithData(__('messages.recordSaved'), ['data' => $options]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */

    public function edit($id)
    {
        $this->source = LeadInterest::findOrFail($id);
        $this->editPermission = user()->permission('edit_lead_sources');
        abort_403(!($this->editPermission == 'all' || ($this->editPermission == 'added' && $this->source->added_by == user()->id)));

        return view('lead-settings.edit-interest-modal', $this->data);
    }

    /**
     * @param UpdateLeadSource $request
     * @param int $id
     * @return array|void
     * @throws RelatedResourceNotFoundException
     */
    public function update(UpdateLeadInterest $request, $id)
    {
        $this->editPermission = user()->permission('edit_lead_sources');
        $type = LeadInterest::findOrFail($id);
        abort_403(!($this->editPermission == 'all' || ($this->editPermission == 'added' && $this->source->added_by == user()->id)));

        $type->type = $request->type;
        $type->save();

        return Reply::success(__('messages.updateSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $type = LeadInterest::findOrFail($id);
        $this->deletePermission = user()->permission('delete_lead_sources');
        abort_403(!($this->deletePermission == 'all' || ($this->deletePermission == 'added' && $type->added_by == user()->id)));
        LeadInterest::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));
    }

}
