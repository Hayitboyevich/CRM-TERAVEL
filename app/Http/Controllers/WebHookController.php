<?php

namespace App\Http\Controllers;

use App\DataTables\TasksDataTable;
use App\Helper\Reply;
use App\Http\Requests\WorkFlowRequest;
use App\Models\Condition;
use App\Models\Event;
use App\Models\SocialEvent;
use App\Models\SocialNetwork;
use App\Models\Verify;
use App\Models\Workflow;
use Illuminate\Http\Request;

class WebHookController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.webhook';
        $this->activeSettingMenu = 'webhook';
    }

    public function index(TasksDataTable $dataTable)
    {

        $this->workflows = Workflow::all();

        return $dataTable->render('workflow.index', $this->data);
    }

    public function create()
    {
        if (request()->via && request()->via == 'webhook') {
            $this->socials = SocialNetwork::all();
            $this->events = SocialEvent::all();
            $this->conditions = Condition::all();
            $this->verifies = Verify::all();
            return view('workflow.create', $this->data);
        }

    }

    public function store(WorkFlowRequest $request)
    {
        Workflow::create([
           'social_network_id' => $request->get('social_network_id'),
           'social_event_id' => $request->get('social_event_id'),
           'condition_id' => $request->get('condition_id'),
           'verify_id' => $request->get('verify_id'),
           'text' => $request->get('text'),
            'company_token' => $request->get('company_token'),
        ]);

        return Reply::success('messages.recordSaved');
    }

    public function edit($id)
    {
        $this->workflow = Workflow::findOrFail($id);
        $this->socials = SocialNetwork::all();
        $this->events = SocialEvent::all();
        $this->conditions = Condition::all();
        $this->verifies = Verify::all();

        return view('workflow.edit', $this->data);
    }

    public function update(WorkFlowRequest $request, $id)
    {

        $workflow = Workflow::findOrFail($id);
        $workflow->social_network_id = $request->get('social_network_id');
        $workflow->social_event_id = $request->get('social_event_id');
        $workflow->condition_id = $request->get('condition_id');
        $workflow->verify_id = $request->get('verify_id');
        $workflow->text = $request->get('text');
        $workflow->company_token = $request->get('company_token');
        $workflow->save();

        return Reply::success('messages.updateSuccess');

    }

    public function destroy($id)
    {
        Workflow::destroy($id);

        return Reply::success('messages.deleteSuccess');
    }
}
