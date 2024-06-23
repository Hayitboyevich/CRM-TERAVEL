<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Http\Requests\LeadSetting\StoreLeadStatus;
use App\Http\Requests\LeadSetting\UpdateLeadStatus;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\UserLeadboardSetting;
use Exception;
use Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\ActivityHistory\Entities\ActivityHistory;

class LeadStatusSettingController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
//        $this->middleware(function ($request, $next) {
//            abort_403(!in_array('leads', $this->user->modules));
//            return $next($request);
//        });
    }

    /**
     * @param StoreLeadStatus $request
     * @return array
     * @throws RelatedResourceNotFoundException
     */
    public function store(StoreLeadStatus $request)
    {
        $maxPriority = LeadStatus::max('priority');

        $status = new LeadStatus();
        $status->type = $request->type;
        $status->label_color = $request->label_color;
        $status->priority = ($maxPriority + 1);
        $status->save();

        return Reply::success(__('messages.recordSaved'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $this->status = LeadStatus::findOrFail($id);
        $this->maxPriority = LeadStatus::max('priority');

        return view('lead-settings.edit-status-modal', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function editDeadline($leadId)
    {
        $this->leadId = $leadId;
        return view('lead-settings.edit-deadline-modal', $this->data);
    }

    public function updateDeadline($leadId, Request $request)
    {
        $lead = Lead::query()->findOrFail($leadId);
        try {
            ActivityHistory::query()->create([
                'module_id' => $lead->id,
                'info' => ActivityHistory::MESSAGE_EXTENDED_LEAD . $request->note,
                'module_name' => 'leads',
            ]);
            $lead->note = $lead->note . ' <p>' . $request->note . '</p>';
            $lead->save();
        } catch (Exception $exception) {
            return Reply::error(__('messages.error'));

        }
        return Reply::success(__('messages.updateSuccess'));

    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('lead-settings.create-status-modal', $this->data);
    }

    public function statusUpdate($id)
    {
        $allLeadStatus = LeadStatus::select('id', 'default')->get();

        foreach ($allLeadStatus as $leadStatus) {
            if ($leadStatus->id == $id) {
                $leadStatus->default = '1';
            } else {
                $leadStatus->default = '0';
            }

            $leadStatus->save();
        }

        return Reply::success(__('messages.updateSuccess'));
    }

    public function statusUpdateClientFrom($id)
    {
        $allLeadStatus = LeadStatus::select('id', 'client_from')->get();

        foreach ($allLeadStatus as $leadStatus) {
            if ($leadStatus->id == $id) {
                $leadStatus->client_from = '1';
            } else {
                $leadStatus->client_from = '0';
            }

            $leadStatus->save();
        }

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
        $defaultLeadStatus = LeadStatus::where('default', 1)->first();
        Lead::where('status_id', $id)->update(['status_id' => $defaultLeadStatus->id]);

        $board = LeadStatus::findOrFail($id);

        $otherColumns = LeadStatus::where('priority', '>', $board->priority)
            ->orderBy('priority', 'asc')
            ->get();

        foreach ($otherColumns as $column) {
            $pos = LeadStatus::where('priority', $column->priority)->first();
            $pos->priority = ($pos->priority - 1);
            $pos->save();
        }

        UserLeadboardSetting::where('board_column_id', $id)->delete();
        LeadStatus::destroy($id);

        return Reply::success(__('messages.deleteSuccess'));
    }

    /**
     * @param UpdateLeadStatus $request
     * @param int $id
     * @return array
     * @throws RelatedResourceNotFoundException
     */
    public function update(UpdateLeadStatus $request, $id)
    {
        $type = LeadStatus::findOrFail($id);
        $oldPosition = $type->priority;
        $newPosition = $request->priority;

        if ($oldPosition < $newPosition) {

            LeadStatus::where('priority', '>', $oldPosition)
                ->where('priority', '<=', $newPosition)
                ->orderBy('priority', 'asc')
                ->decrement('priority');

        } else if ($oldPosition > $newPosition) {

            LeadStatus::where('priority', '<', $oldPosition)
                ->where('priority', '>=', $newPosition)
                ->orderBy('priority', 'asc')
                ->increment('priority');
        }

        $type->type = $request->type;
        $type->time = $request->time;
        $type->label_color = $request->label_color;
        $type->priority = $request->priority;
        $type->save();

        return Reply::success(__('messages.updateSuccess'));
    }

}
