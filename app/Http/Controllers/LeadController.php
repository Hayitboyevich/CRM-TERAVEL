<?php

namespace App\Http\Controllers;

use App\DataTables\LeadFollowupDataTable;
use App\DataTables\LeadGDPRDataTable;
use App\DataTables\LeadNotesDataTable;
use App\DataTables\LeadsDataTable;
use App\DataTables\ProposalDataTable;
use App\Helper\Reply;
use App\Http\Requests\Admin\Employee\ImportProcessRequest;
use App\Http\Requests\Admin\Employee\ImportRequest;
use App\Http\Requests\CommonRequest;
use App\Http\Requests\FollowUp\StoreRequest as FollowUpStoreRequest;
use App\Http\Requests\Lead\StoreRequest;
use App\Http\Requests\Lead\UpdateRequest;
use App\Http\Requests\LeadCustomStoreRequest;
use App\Imports\LeadImport;
use App\Jobs\ImportLeadJob;
use App\Models\Application;
use App\Models\Country;
use App\Models\Currency;
use App\Models\GdprSetting;
use App\Models\Integration;
use App\Models\Lead;
use App\Models\LeadAgent;
use App\Models\LeadCategory;
use App\Models\LeadCustomForm;
use App\Models\LeadFollowUp;
use App\Models\LeadInterest;
use App\Models\LeadNote;
use App\Models\LeadProduct;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\LeadTrip;
use App\Models\Product;
use App\Models\PurposeConsent;
use App\Models\PurposeConsentLead;
use App\Models\Role;
use App\Models\User;
use App\Traits\ImportExcel;
use Carbon\Carbon;
use Exception;
use Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\ActivityHistory\Entities\ActivityHistory;

class LeadController extends AccountBaseController
{
    use ImportExcel;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.lead';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('leads', $this->user->modules));
            return $next($request);
        });
    }

    public function updateNote($id, Request $request)
    {
        $lead = Lead::query()->where(['id' => $id])->first();
        $lead->note = $request->note;
        $lead->save();
        return "success";
    }

    public function index(LeadsDataTable $dataTable)
    {
        $this->viewLeadPermission = $viewPermission = user()->permission('view_lead');

        abort_403(!in_array($viewPermission, ['all', 'added', 'both', 'owned']));

        if (!request()->ajax()) {
            $this->totalLeads = Lead::get();
            $this->categories = LeadCategory::get();
            $this->sources = LeadSource::get();
            $this->status = LeadStatus::get();

            $this->totalClientConverted = $this->totalLeads->filter(function ($value, $key) {
                return $value->client_id != null;
            });

            $this->totalLeads = $this->totalLeads->count();
            $this->totalClientConverted = $this->totalClientConverted->count();

            $this->pendingLeadFollowUps = LeadFollowUp::where(DB::raw('DATE(next_follow_up_date)'), '<=', now()->format('Y-m-d'))
                ->join('leads', 'leads.id', 'lead_follow_up.lead_id')
                ->where('leads.next_follow_up', 'yes')
                ->groupBy('lead_follow_up.lead_id')
                ->get();
            $this->pendingLeadFollowUps = $this->pendingLeadFollowUps->count();

            $this->viewLeadAgentPermission = user()->permission('view_lead_agents');


            $this->leadAgents = LeadAgent::with('user')->whereHas('user', function ($q) {
                $q->where('status', 'active');
            });

            $this->leadAgents = $this->leadAgents->where(function ($q) {
                if ($this->viewLeadAgentPermission == 'all') {
                    $this->leadAgents = $this->leadAgents;
                } elseif ($this->viewLeadAgentPermission == 'added') {
                    $this->leadAgents = $this->leadAgents->where('added_by', user()->id);
                } elseif ($this->viewLeadAgentPermission == 'owned') {
                    $this->leadAgents = $this->leadAgents->where('user_id', user()->id);
                } elseif ($this->viewLeadAgentPermission == 'both') {
                    $this->leadAgents = $this->leadAgents->where('added_by', user()->id)->orWhere('user_id', user()->id);
                } else {
                    // This is $this->viewLeadAgentPermission == 'none'
                    $this->leadAgents = [];
                }
            })->get();

        }

        return $dataTable->render('leads.index', $this->data);

    }

    public function show($id)
    {
        $this->lead = Lead::query()->with(['client', 'leadAgent', 'tripHistory', 'leadAgent.user', 'leadStatus', 'products'])->findOrFail($id)->withCustomFields();
        $leadAgentId = ($this->lead->leadAgent != null) ? $this->lead->leadAgent->user->id : 0;

        $this->viewPermission = user()->permission('view_lead');
        $this->histories = ActivityHistory::query()
            ->where(['module_id' => $id])
            ->get();
        abort_403(!(
            $this->viewPermission == 'all'
            || ($this->viewPermission == 'added' && $this->lead->added_by == user()->id)
            || ($this->viewPermission == 'owned' && $this->lead->leadAgent->user->id == user()->id)
            || ($this->viewPermission == 'both' && ($this->lead->added_by == user()->id || $leadAgentId == user()->id))
        ));

        $this->application = Application::query()->where(['client_id' => $this->lead->client_id])->exists();
        $this->pageTitle = ucfirst($this->lead->client_name);

        $this->categories = LeadCategory::all();

        $this->productNames = $this->lead->products->pluck('name')->toArray();

        $this->leadFormFields = LeadCustomForm::with('customField')->where('status', 'active')->where('custom_fields_id', '!=', 'null')->get();

        $this->leadId = $id;
        $this->integration = $this->lead?->integration;

        $this->payments = $this->lead?->order?->payments->groupBy('create_at');
        if ($this->lead->getCustomFieldGroupsWithFields()) {
            $this->fields = $this->lead->getCustomFieldGroupsWithFields()->fields;
        }

        $this->deleteLeadPermission = user()->permission('delete_lead');
        $this->view = 'leads.ajax.profile';
        $this->interests = $this->lead->leadInterest?->first();

        $tab = request('tab');
        switch ($tab) {
            case 'files':
                $this->view = 'leads.ajax.files';
                break;
            case 'follow-up':
                return $this->leadFollowup();
            case 'proposals':
                return $this->proposals();
            case 'notes':
                return $this->notes();
            case 'gdpr':

                $this->consents = PurposeConsent::with(['lead' => function ($query) use ($id) {
                    $query->where('lead_id', $id)
                        ->orderBy('created_at', 'desc');
                }])->get();

                $this->gdpr = GdprSetting::first();

                return $this->gdpr();
            default:
                $this->view = 'leads.ajax.profile';
                break;
        }

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->activeTab = $tab ?: 'profile';
        return view('leads.show', $this->data);

    }

    public function leadFollowup()
    {
        $tab = request('tab');
        $this->activeTab = $tab ?: 'overview';
        $this->view = 'leads.ajax.follow-up';
        $dataTable = new LeadFollowupDataTable();

        return $dataTable->render('leads.show', $this->data);
    }

    public function proposals()
    {
        $viewPermission = user()->permission('view_lead_proposals');

        abort_403(!in_array($viewPermission, ['all', 'added']));

        $tab = request('tab');
        $this->activeTab = $tab ?: 'overview';
        $this->view = 'leads.ajax.proposal';
        $dataTable = new ProposalDataTable();

        return $dataTable->render('leads.show', $this->data);
    }

    public function notes()
    {
        $dataTable = new LeadNotesDataTable();
        $viewPermission = user()->permission('view_lead');

        abort_403(!($viewPermission == 'all' || $viewPermission == 'added' || $viewPermission == 'both'));

        $tab = request('tab');
        $this->activeTab = $tab ?: 'profile';

        $this->view = 'leads.ajax.notes';

        return $dataTable->render('leads.show', $this->data);
    }

    public function gdpr()
    {
        $dataTable = new LeadGDPRDataTable();
        $tab = request('tab');
        $this->activeTab = $tab ?: 'gdpr';
        $this->view = 'leads.ajax.gdpr';
        return $dataTable->render('leads.show', $this->data);
    }

    public function attachIntegration($client_id, $integration_id, Request $request)
    {
        $lead = Lead::query()
            ->where(['client_id' => $client_id])
            ->where(['integration_id' => null])
            ->where(['order_id' => null])
            ->first();

        if (!$lead) {
            $agent = LeadAgent::query()->firstOrCreate(
                ['user_id' => auth()->id()],
                [
                    'user_id' => auth()->id(),
                    'status' => 'enabled',
                ]
            )->first();

            $client = User::query()
                ->where(['id' => $client_id])
                ->first();

            $lead = new Lead();
            $lead->client_id = $client->id;
            $lead->added_by = user()->id;
            $lead->agent_id = $agent->id;

            $lead->client_name = $client->firstname . ' ' . $client->lastname;

            $lead->mobile = $client->mobile;
            $lead->status_id = 1;
        }
        $lead->integration_id = $integration_id;
        $lead->save();

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('leadboards.index');
        }

        return redirect()->to($redirectUrl)->with('success', __('messages.updatedSuccessfully'));
    }

    /**
     * @param StoreRequest $request
     * @return array|void
     * @throws RelatedResourceNotFoundException
     */
    public function customStore(LeadCustomStoreRequest $request)
    {
        $this->addPermission = user()->permission('add_lead');

        abort_403(!in_array($this->addPermission, ['all', 'added']));
        $status = LeadStatus::query()
            ->where('company_id', company()->id)
            ->where('type', 'Несортированный')
            ->first();
        DB::beginTransaction();
        try {
            $agent = LeadAgent::query()
                ->where(['user_id' => auth()->id()])
                ->first();

            if (!$agent) {
                $agent = new LeadAgent();
                $agent->user_id = auth()->id();
                $agent->status = 'enabled';
                $agent->save();
            }

            $integration = new Integration();
            $integration->user_id = $request->client_id;
            $integration->save();

            $lead = new Lead();
            $lead->company_name = $request->company_name;

            $lead->cell = $request->cell;
            $lead->client_name = $request->firstname . ' ' . $request->lastname;
            $lead->client_id = $request->client_id;
            $lead->mobile = $request->mobile;
            $lead->agent_id = $agent->id;
            $lead->added_by = auth()->id();
            $lead->currency_id = company()->currency_id;
            $lead->integration_id = $integration->id;
            $lead->value = 0;
            $lead->status_id = $status->id;
            $lead->save();

        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();


        // To add custom fields data
        if ($request->custom_fields_data) {
            $lead->updateCustomFieldData($request->custom_fields_data);
        }

        // Log search
        $this->logSearchEntry($lead->id, $lead->client_name, 'leads.show', 'lead');

        if ($lead->client_email) {
            $this->logSearchEntry($lead->id, $lead->client_email, 'leads.show', 'lead');
        }

        if (!is_null($lead->company_name)) {
            $this->logSearchEntry($lead->id, $lead->company_name, 'leads.show', 'lead');
        }

        $redirectUrl = urldecode($request->redirect_url);


        if ($redirectUrl == '') {
            $redirectUrl = route('leadboards.index', $lead->client_id);
        }

        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);

    }

    /**
     * @param StoreRequest $request
     * @return array|void
     * @throws RelatedResourceNotFoundException
     */
    public function store(StoreRequest $request)
    {
        $this->addPermission = user()->permission('add_lead');
        $leadInterests = Arr::get(collect(\request()->all())->filter()->all(), 'lead_interests');
        if (isset($foreign['desired_date_from'])) {
            $leadInterests['desired_date_from'] = date('Y-m-d', strtotime($foreign['desired_date_from']));
        }
        if (isset($foreign['desired_date_to'])) {
            $leadInterests['desired_date_to'] = date('Y-m-d', strtotime($foreign['desired_date_to']));
        }

        abort_403(!in_array($this->addPermission, ['all', 'added']));
        DB::beginTransaction();
        try {
            if ($request->input('mobile') !== null){
                $mobile = str_replace('+998', '', $request->input('mobile'));
                $client = User::query()->where('mobile', $mobile)->where('company_id', 1)->first();

                if ($client === null) {
                    $client = User::query()->create([
                        'name' => $request->input('client_name'),
                        'mobile' => $mobile,
                        'password' => bcrypt($mobile),
                        'company_id' => 1,
                    ]);
                }
            }

            $role = Role::where('name', 'client')
                ->where('company_id', company()->id) // Assuming company_id is provided in request
                ->first();

            $client->roles()->sync($role->id);


            $agent = LeadAgent::query()
                ->where(['user_id' => auth()->id()])
                ->first();

            if (!$agent) {
                $agent = new LeadAgent();
                $agent->user_id = auth()->id();
                $agent->status = 'enabled';
                $agent->save();
            }
            $lead = new Lead();
            $lead->company_name = $request->company_name;

            $lead->cell = $request->cell;
            $lead->office = $request->office;
            $lead->city = $request->city;
            $lead->state = $request->state;
            $lead->country = $request->country;
            $lead->postal_code = $request->postal_code;
            $lead->salutation = $request->salutation;
            $lead->client_name = $request->client_name;
            $lead->client_email = $request->client_email;
            $lead->client_id = $client->id ?? null;
            $lead->mobile = $request->mobile;
            $lead->note = trim_editor($request->note);
            $lead->next_follow_up = $request->next_follow_up ?? 'yes';
            $lead->agent_id = $agent->id;
            $lead->source_id = $request->source_id;

            $lead->category_id = $request->category_id;
            $lead->status_id = $request->status;
            $lead->value = ($request->value) ?: 0;
            $lead->currency_id = $this->company->currency_id;
            $lead->save();
            if (!is_null($request->product_id)) {

                $products = $request->product_id;

                foreach ($products as $product) {
                    $leadProduct = new LeadProduct();
                    $leadProduct->lead_id = $lead->id;
                    $leadProduct->product_id = $product;
                    $leadProduct->save();
                }
            }

            $lead_id = $lead->latest()->first()->id;

            $note_detail = trim_editor($request->note);

            if ($note_detail != '') {
                $lead_notes = new LeadNote();
                $lead_notes->lead_id = $lead_id;
                $lead_notes->title = 'Note';
                $lead_notes->details = $note_detail;
                $lead_notes->save();

            }
            if ($request->trip) {
                for ($i = 0; $i < count($request->trip["country_id"]); $i++) {
                    $leadTrip = new LeadTrip();
                    $leadTrip->country = $request->trip["country_id"][$i];
                    $leadTrip->budget = $request->trip["budget"][$i];
                    $leadTrip->members_count = $request->trip["members_count"][$i];
                    $leadTrip->trip_service = $request->trip["trip_service"][$i];
                    $leadTrip->lead_id = $lead_id;
                    $leadTrip->save();
                }
            }

            $leadInterests['lead_id'] = $lead_id;
            $leadInterests['company_id'] = company()->id;
            LeadInterest::query()->create($leadInterests);



        } catch (Exception $exception) {
            throw $exception;
            DB::rollBack();
        }
        DB::commit();


        // To add custom fields data
        if ($request->custom_fields_data) {
            $lead->updateCustomFieldData($request->custom_fields_data);
        }

        // Log search
        $this->logSearchEntry($lead->id, $lead->client_name, 'leads.show', 'lead');

        if ($lead->client_email) {
            $this->logSearchEntry($lead->id, $lead->client_email, 'leads.show', 'lead');
        }

        if (!is_null($lead->company_name)) {
            $this->logSearchEntry($lead->id, $lead->company_name, 'leads.show', 'lead');
        }

        $redirectUrl = urldecode($request->redirect_url);

        if ($request->add_more == 'true') {
            $html = $this->create();

            return Reply::successWithData(__('messages.recordSaved'), ['html' => $html, 'add_more' => true]);
        }

        if ($redirectUrl == '') {
            $redirectUrl = route('leadboards.index');
        }

        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->addPermission = user()->permission('add_lead');
        abort_403(!in_array($this->addPermission, ['all', 'added']));

        $defaultStatus = LeadStatus::where('default', '1')->first();
        $this->columnId = ((request('column_id') != '') ? request('column_id') : $defaultStatus->id);
        $this->leadAgents = LeadAgent::with('user')->whereHas('user', function ($q) {
            $q->where('status', 'active');
        })->get();

        $this->leadAgentArray = $this->leadAgents->pluck('user_id')->toArray();

        if ((in_array(user()->id, $this->leadAgentArray))) {
            $this->myAgentId = $this->leadAgents->filter(function ($value, $key) {
                return $value->user_id == user()->id;
            })->first()->id;
        }

        $lead = new Lead();

        if ($lead->getCustomFieldGroupsWithFields()) {
            $this->fields = $lead->getCustomFieldGroupsWithFields()->fields;
        }

        $this->products = Product::all();
        $this->sources = LeadSource::all();
        $this->interests = LeadInterest::all();

        $this->status = LeadStatus::all();
        $this->categories = LeadCategory::all();
        $this->countries = countries();
        $this->pageTitle = __('modules.lead.createTitle');
        $this->salutations = ['mr', 'mrs', 'miss', 'dr', 'sir', 'madam'];
        $this->currencies = Currency::query()->where('company_id', company()->id)->get();
        $this->countries = Country::all();

        if (request()->ajax()) {
            $html = view('leads.ajax.create', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'leads.ajax.create';
        return view('leads.create', $this->data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $this->lead = Lead::with('currency', 'leadAgent', 'leadAgent.user', 'products')->findOrFail($id)->withCustomFields();

        $this->productIds = $this->lead->products->pluck('id')->toArray();
        $this->editPermission = user()->permission('edit_lead');

        abort_403(!($this->editPermission == 'all'
            || ($this->editPermission == 'added' && ($this->lead->added_by == user()->id || !is_null($this->lead->added_by)))
            || ($this->editPermission == 'owned' && !is_null($this->lead->agent_id) && user()->id == $this->lead->leadAgent->user->id)
            || ($this->editPermission == 'both' && ((!is_null($this->lead->agent_id) && user()->id == $this->lead->leadAgent->user->id)
                    || user()->id == $this->lead->added_by)
            )));

        $this->leadAgents = LeadAgent::with('user')->whereHas('user', function ($q) {
            $q->where('status', 'active');
        })->get();

        if ($this->lead->getCustomFieldGroupsWithFields()) {
            $this->fields = $this->lead->getCustomFieldGroupsWithFields()->fields;
        }
//        $this->interests = LeadInterest::all();

//        $this->products = Product::all();
        $this->sources = LeadSource::all();
        $this->status = LeadStatus::all();
//        $this->categories = LeadCategory::all();
        $this->countries = countries();
        $this->pageTitle = __('modules.lead.updateTitle');

        if (request()->ajax()) {
            $html = view('leads.ajax.edit', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'leads.ajax.edit';
        return view('leads.create', $this->data);

    }

    /**
     * @param CommonRequest $request
     * @return array
     */
    public function changeStatus(CommonRequest $request)
    {
        $lead = Lead::findOrFail($request->leadID);
        $this->editPermission = user()->permission('edit_lead');

        abort_403(!($this->editPermission == 'all' || ($this->editPermission == 'added' && $lead->added_by == user()->id)));

        $lead->status_id = $request->statusID;
        $lead->save();

        return Reply::success(__('messages.recordSaved'));
    }

    public function applyQuickAction(Request $request)
    {
        switch ($request->action_type) {
            case 'delete':
                $this->deleteRecords($request);
                return Reply::success(__('messages.deleteSuccess'));
            case 'change-status':
                $this->changeBulkStatus($request);
                return Reply::success(__('messages.updateSuccess'));
            case 'change-agent':
                if ($request->agent_id == '') {
                    return Reply::error(__('messages.noAgentAdded'));
                }

                $this->changeAgentStatus($request);
                return Reply::success(__('messages.updateSuccess'));
            default:
                return Reply::error(__('messages.selectAction'));
        }
    }

    public final function deleteRecords(Request $request): void
    {
        abort_403(user()->permission('delete_lead') != 'all');

        Lead::query()->whereIn('id', explode(',', $request->row_ids))->delete();
    }

    public final function changeBulkStatus(Request $request): void
    {
        abort_403(user()->permission('edit_lead') != 'all');

        Lead::query()->whereIn('id', explode(',', $request->row_ids))->update(['status_id' => $request->status]);
    }

    /**
     * @param UpdateRequest $request
     * @param int $id
     * @return array|void
     * @throws RelatedResourceNotFoundException
     */
    public function update(UpdateRequest $request, $id)
    {
        $lead = Lead::query()->with('leadAgent', 'leadAgent.user')->findOrFail($id);
        $this->editPermission = user()->permission('edit_lead');

        abort_403(!($this->editPermission == 'all'
            || ($this->editPermission == 'added' && ($lead->added_by == user()->id || !is_null($lead->added_by)))
            || ($this->editPermission == 'owned' && !is_null($lead->agent_id) && user()->id == $lead->leadAgent->user->id)
            || ($this->editPermission == 'both' && ((!is_null($lead->agent_id) && user()->id == $lead->leadAgent->user->id)
                    || user()->id == $lead->added_by)
            )));

        if ($request->has('agent_id')) {
            $lead->agent_id = $request->agent_id;
        } else {
            $agent = LeadAgent::query()->firstOrCreate(
                ['user_id' => $lead->id],
                [
                    'user_id' => user()->id,
                    'status' => 'enabled'
                ]
            );

            $lead->agent_id = $agent->id;

        }
        $lead->address = $request->address;
        $lead->client_name = $request->client_name;

        $lead->mobile = $request->mobile;
        $lead->source_id = $request->source_id;
        $lead->next_follow_up = $request->next_follow_up;
        $lead->status_id = $request->status;
        $lead->category_id = $request->category_id;
        $lead->value = $request->value;
        $lead->note = trim_editor($request->note);
        $lead->currency_id = $this->company->currency_id;
        $lead->cell = $request->cell;
        $lead->office = $request->office;
        $lead->city = $request->city;
        $lead->state = $request->state;
        $lead->callback_at = $request->callback_date ? date('Y-m-d H:i:s', strtotime($request->callback_date . ' ' . $request->callback_time)) : null;
        if ($request->callback_date) {
            $lead->notified = 0;
        }
        $lead->departure_time = date('Y-m-d', strtotime($request->departure_time));
        $lead->landing_time = date('Y-m-d', strtotime($request->landing_time));

        $lead->country = $request->country;
        $lead->interest_ids = $request->interest_ids;

        $lead->postal_code = $request->postal_code;
        $lead->save();
        $client = $lead->client;
        if ($client) {
            $client->mobile = $lead->mobile;
            $client->name = $request->client_name;

            $client->save();
        }

        $lead->products()->sync($request->product_id);

        // To add custom fields data
        if ($request->custom_fields_data) {
            $lead->updateCustomFieldData($request->custom_fields_data);
        }

        return Reply::successWithData(__('messages.updateSuccess'), ['redirectUrl' => route('leadboards.index')]);

    }

    protected function changeAgentStatus($request)
    {
        abort_403(user()->permission('edit_lead') != 'all');

        $leads = Lead::with('leadAgent')->whereIn('id', explode(',', $request->row_ids))->get();

        foreach ($leads as $key => $lead) {
            $lead->agent_id = $request->agent_id;
            $lead->save();
        }
    }

    /**
     *
     * @param int $leadID
     * @return void
     */
    public function followUpCreate($leadID)
    {
        $this->addPermission = user()->permission('add_lead_follow_up');

        abort_403(!in_array($this->addPermission, ['all', 'added']));

        $this->leadID = $leadID;
        $this->lead = Lead::findOrFail($leadID);

        return view('leads.followup.create', $this->data);

    }

    /**
     * @param FollowUpStoreRequest $request
     * @return array|void
     * @throws RelatedResourceNotFoundException
     */
    public function followUpStore(FollowUpStoreRequest $request)
    {

        $this->lead = Lead::findOrFail($request->lead_id);

        $this->addPermission = user()->permission('add_lead_follow_up');

        abort_403(!in_array($this->addPermission, ['all', 'added']));

        if ($this->lead->next_follow_up != 'yes') {
            return Reply::error(__('messages.leadFollowUpRestricted'));
        }

        $followUp = new LeadFollowUp();
        $followUp->lead_id = $request->lead_id;

        $followUp->next_follow_up_date = Carbon::createFromFormat($this->company->date_format . ' ' . $this->company->time_format, $request->next_follow_up_date . ' ' . $request->start_time)->format('Y-m-d H:i:s');

        $followUp->remark = $request->remark;

        $followUp->send_reminder = $request->send_reminder;
        $followUp->remind_time = $request->remind_time;
        $followUp->remind_type = $request->remind_type;
        $followUp->status = 'incomplete';

        $followUp->save();

        return Reply::success(__('messages.recordSaved'));

    }

    public function editFollow($id)
    {
        $this->follow = LeadFollowUp::findOrFail($id);
        $this->editPermission = user()->permission('edit_lead_follow_up');
        abort_403(!($this->editPermission == 'all' || ($this->editPermission == 'added' && $this->follow->added_by == user()->id)));

        return view('leads.followup.edit', $this->data);
    }

    public function updateFollow(FollowUpStoreRequest $request)
    {
        $this->lead = Lead::findOrFail($request->lead_id);

        $followUp = LeadFollowUp::findOrFail($request->id);
        $this->editPermission = user()->permission('edit_lead_follow_up');

        abort_403(!($this->editPermission == 'all'
            || ($this->editPermission == 'added' && $followUp->added_by == user()->id)
        ));

        if ($this->lead->next_follow_up != 'yes') {
            return Reply::error(__('messages.leadFollowUpRestricted'));
        }


        $followUp->lead_id = $request->lead_id;

        $followUp->next_follow_up_date = Carbon::createFromFormat($this->company->date_format . ' ' . $this->company->time_format, $request->next_follow_up_date . ' ' . $request->start_time)->format('Y-m-d H:i:s');

        $followUp->remark = $request->remark;
        $followUp->send_reminder = $request->send_reminder;
        $followUp->status = $request->status;
        $followUp->remind_time = $request->remind_time;
        $followUp->remind_type = $request->remind_type;

        $followUp->save();

        return Reply::success(__('messages.updateSuccess'));

    }

    public function deleteFollow($id)
    {
        $followUp = LeadFollowUp::findOrFail($id);
        $this->deletePermission = user()->permission('delete_lead_follow_up');
        abort_403(!($this->deletePermission == 'all' || ($this->deletePermission == 'added' && $followUp->added_by == user()->id)));

        LeadFollowUp::destroy($id);

        return Reply::success(__('messages.deleteSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $lead = Lead::with('leadAgent', 'leadAgent.user')->findOrFail($id);
        $this->deletePermission = user()->permission('delete_lead');

        abort_403(!($this->deletePermission == 'all'
            || ($this->deletePermission == 'added' && $lead->added_by == user()->id)
            || ($this->deletePermission == 'owned' && !is_null($lead->agent_id) && user()->id == $lead->leadAgent->user->id)
            || ($this->deletePermission == 'both' && ((!is_null($lead->agent_id) && user()->id == $lead->leadAgent->user->id)
                    || user()->id == $lead->added_by)
            )));

        Lead::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));

    }

    public function consent(Request $request)
    {
        $leadId = $request->leadId;
        $this->consentId = $request->consentId;
        $this->leadId = $leadId;

        $this->consent = PurposeConsent::with(['lead' => function ($query) use ($request) {
            $query->where('lead_id', $request->leadId)
                ->orderBy('created_at', 'desc');
        }])
            ->where('id', $request->consentId)
            ->first();

        return view('leads.gdpr.consent-form', $this->data);
    }

    public function saveLeadConsent(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $consent = PurposeConsent::findOrFail($request->consent_id);

        if ($request->consent_description && $request->consent_description != '') {
            $consent->description = trim_editor($request->consent_description);
            $consent->save();
        }

        // Saving Consent Data
        $newConsentLead = new PurposeConsentLead();
        $newConsentLead->lead_id = $lead->id;
        $newConsentLead->purpose_consent_id = $consent->id;
        $newConsentLead->status = trim($request->status);
        $newConsentLead->ip = $request->ip();
        $newConsentLead->updated_by_id = $this->user->id;
        $newConsentLead->additional_description = $request->additional_description;
        $newConsentLead->save();

        return $request->status == 'agree' ? Reply::success(__('messages.consentOptIn')) : Reply::success(__('messages.consentOptOut'));
    }

    public function importLead()
    {
        $this->pageTitle = __('app.importExcel') . ' ' . __('app.menu.lead');

        $this->addPermission = user()->permission('add_lead');
        abort_403(!in_array($this->addPermission, ['all', 'added']));

        if (request()->ajax()) {
            $html = view('leads.ajax.import', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'leads.ajax.import';

        return view('leads.create', $this->data);
    }

    public function importStore(ImportRequest $request)
    {
        $this->importFileProcess($request, LeadImport::class);

        $view = view('leads.ajax.import_progress', $this->data)->render();

        return Reply::successWithData(__('messages.importUploadSuccess'), ['view' => $view]);
    }

    public function importProcess(ImportProcessRequest $request)
    {
        $batch = $this->importJobProcess($request, LeadImport::class, ImportLeadJob::class);

        return Reply::successWithData(__('messages.importProcessStart'), ['batch' => $batch]);
    }

    public function changeFollowUpStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $leadFollowUp = LeadFollowUp::find($id);

        if (!is_null($leadFollowUp)) {
            $leadFollowUp->status = $status;
            $leadFollowUp->save();
        }

        return Reply::success(__('messages.leadStatusChangeSuccess'));

    }

}
