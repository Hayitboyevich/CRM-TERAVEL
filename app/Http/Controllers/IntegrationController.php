<?php

namespace App\Http\Controllers;

use App\DataTables\IntegrationDataTable;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\StoreIntegrationRequest;
use App\Http\Requests\UpdateIntegrationRequest;
use App\Models\Currency;
use App\Models\Integration;
use App\Models\IntegrationCity;
use App\Models\IntegrationState;
use App\Models\IntegrationTown;
use App\Models\Lead;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\User;
use App\Scopes\ActiveScope;
use App\Traits\ImportExcel;
use App\Traits\ProjectProgress;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\TravelAgency\Repositories\GetTourRepositoryInterface;
use Modules\TravelAgency\Services\IntegrationService;
use Throwable;

class IntegrationController extends AccountBaseController
{
    use ProjectProgress, ImportExcel;

    public function __construct(public GetTourRepositoryInterface $getTourRepository, public IntegrationService $integrationService)
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.integration';
        $this->middleware(function ($request, $next) {
//            abort_403(!in_array('projects', $this->user->modules));
            return $next($request);
        });
    }

    /**
     * XXXXXXXXXXX
     *
     * @return Response
     */
    public function index(IntegrationDataTable $dataTable)
    {
        $viewPermission = user()->permission('view_projects');
//        abort_403(!in_array($viewPermission, ['all', 'added', 'owned', 'both']));

        return $dataTable->render('integrations.index', $this->data);

    }


    /**
     * XXXXXXXXXXX
     *
     * @return array
     */
    public function applyQuickAction(Request $request)
    {
        switch ($request->action_type) {
            case 'delete':
                $this->deleteRecords($request);

                return Reply::success(__('messages.deleteSuccess'));
            case 'archive':
                $this->archiveRecords($request);

                return Reply::success(__('messages.projectArchiveSuccessfully'));

            default:
                return Reply::error(__('messages.selectAction'));
        }
    }

    protected function deleteRecords($request)
    {

        Project::withTrashed()->whereIn('id', explode(',', $request->row_ids))->forceDelete();

        $items = explode(',', $request->row_ids);

        foreach ($items as $item) {
            // Delete project files
            Files::deleteDirectory(ProjectFile::FILE_PATH . '/' . $item);
        }
    }

    /**
     * @param StoreIntegrationRequest $request
     * @return array|mixed
     * @throws Throwable
     */
    public function store(StoreIntegrationRequest $request)
    {
//        $this->addPermission = user()->permission('add_projects');
//        abort_403(!in_array($this->addPermission, ['all', 'added']));
        $data = $request->validated();
        $lead = Lead::query()
            ->where('client_id', $data['user_id'])
            ->where('integration_id', null)
            ->first();

        try {
            $integration = new Integration();
            $integration->fill($data);
            $integration->save();
            if ($lead) {
                $lead->integration_id = $integration->id;
                $lead->save();
            }
        } catch (Exception $e) {
            return Reply::error('Some error occurred when inserting the data. Please try again or contact support ' . $e->getMessage());
        }

        $redirectUrl = urldecode($request->redirect_url);

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
    public function create($client_id)
    {
//        $this->addPermission = user()->permission('add_projects');

//        abort_403(!in_array($this->addPermission, ['all', 'added']));

        $this->pageTitle = __('app.addIntegration');

        $this->lead = Lead::query()
            ->where('client_id', $client_id)
            ->where('integration_id', null)
            ->first();

//        $this->cities = $this->getTourRepository->getFromCities();

        $this->countries = IntegrationState::all();
        $this->client = User::withoutGlobalScope(ActiveScope::class)->findOrFail($client_id);

        $this->currencies = Currency::all();
        $this->redirectUrl = request()->redirectUrl;

        if (request()->ajax()) {
            $html = view('integrations.ajax.create', $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'integrations.ajax.create';

        return view('integrations.create', $this->data);

    }

    public function update($id, UpdateIntegrationRequest $request)
    {
        $this->integration = Integration::query()
            ->where(['id' => $id])
            ->first();

        $lead = $this->integration->lead;

        $this->editPermission = 'all';
        $this->pageTitle = __('app.update') . ' ' . __('app.integration');

        $data = $request->validated();
        $this->integration->update($data);

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('leads.show', $lead->id);
        }

        return Reply::successWithData(__('messages.updateSuccess'), ['redirectUrl' => $redirectUrl]);
    }

    public function edit($id)
    {
        $this->integration = Integration::query()->findOrFail($id);
        $this->editPermission = 'all';
        $this->pageTitle = __('app.update') . ' ' . __('app.integration');
//        $this->cities = $this->getTourRepository->getFromCities();
        $this->cities = IntegrationCity::query()->get();
        $this->toCities = IntegrationTown::query()->get();

//        $this->cities = $this->integrationService->getFromCities();
        $this->employees = '';

//        if ($this->editPermission == 'all' || $this->editProjectMembersPermission == 'all') {
        $this->employees = User::allEmployees(null, null, ($this->editPermission == 'all' ? 'all' : null));
//        }

        if (request()->ajax()) {
            $html = view('integrations.ajax.edit', $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'integrations.ajax.edit';

        return view('integrations.create', $this->data);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $this->project = Integration::findOrFail($id);
        $this->pageTitle = ucfirst('Integrations');

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'integrations.ajax.overview';
        return view('integrations.show', $this->data);
    }
}
