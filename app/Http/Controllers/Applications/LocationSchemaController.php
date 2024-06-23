<?php

namespace App\Http\Controllers\Applications;

use App\DataTables\LocationSchemasDataTable;
use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Http\Requests\CreateSchemaRequest;
use App\Http\Requests\UpdateSchemaRequest;
use App\Models\ClientDetails;
use App\Models\LanguageSetting;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\PurposeConsent;
use App\Models\Schema;
use App\Models\SchemaSeat;
use App\Models\UniversalSearch;
use App\Models\User;
use App\Scopes\ActiveScope;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;

class LocationSchemaController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.schema';
        $this->middleware(function ($request, $next) {
//            abort_403(!in_array('schema', $this->user->modules));
            return $next($request);
        });
    }

    /**
     * client list
     *
     * @return Response
     */
    public function index(LocationSchemasDataTable $dataTable)
    {
//        $viewPermission = user()->permission('view_schema');
//        $this->addSchemaPermission = user()->permission('add_schema');
        $this->addSchemaPermission = 'all';
//
//        abort_403(!in_array($viewPermission, ['all', 'added', 'both']));

        return $dataTable->render('schemas.index', $this->data);
    }

    /**
     * XXXXXXXXXXX
     *
     * @return Response
     */
    public function update(UpdateSchemaRequest $request, $id)
    {
        $schema = Schema::findOrFail($id);
        $schema->name = $request->name;
        $schema->description = $request->description;
        $schema->dimension = $request->dimension;
        $schema->row_amount = $request->rows;

        $schema->save();

        $cells = $request->get('cells');
        $dimensions = explode('x', $request->get('dimension'));
        $dimensions = (array_sum($dimensions) + 1);

        $row_check = 1;
        $dimensions_check = 0;
        $data = [];

        foreach ($cells as $key => $cell) {
            if (!is_null($cell)) {
                $data[] = [
                    'schema_id' => $schema->id,
                    'row' => $row_check,
                    'cell' => $cell,
                    'index' => $key,
                ];
            }
            $dimensions_check++;
            if ($dimensions_check == $dimensions) {
                $row_check++;
                $dimensions_check = 0;
            }
        }

        $schemas = SchemaSeat::where('schema_id', $schema->id)->get();
        foreach ($schemas as $item) {
            if (!in_array($item->index, array_column($data, 'index'))) {
                $item->delete();
            }
        }


        foreach ($data as $item) {
            $seat = SchemaSeat::where('schema_id', $schema->id)->where('index', $item['index'])->first();
            if (!is_null($seat)) {
                $seat->cell = $item['cell'];
                $seat->row = $item['row'];
                $seat->save();
            } else {
                SchemaSeat::create($item);
            }
        }

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('clients.index');
        }

        return Reply::successWithData(__('messages.updateSuccess'), ['redirectUrl' => $redirectUrl]);
    }

    /**
     * XXXXXXXXXXX
     *
     * @return Response
     */
    public function create()
    {
//        $this->addPermission = user()->permission('add_schema');
//
//        abort_403(!in_array($this->addPermission, User::ALL_ADDED_BOTH));
//
        $this->view = 'schemas.ajax.create';

//        if (request()->ajax()) {
//
//            $html = view($this->view, $this->data)->render();
//
//            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
//        }


        return view('schemas.create', $this->data);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $this->client = User::withoutGlobalScope(ActiveScope::class)->with(['clientDetails', 'clientDetails.addedBy'])->findOrFail($id);
        $this->clientLanguage = LanguageSetting::where('language_code', $this->client->locale)->first();
        $this->viewPermission = user()->permission('view_clients');
        $this->viewDocumentPermission = user()->permission('view_client_document');

        if (!$this->client->hasRole('client')) {
            abort(404);
        }

//        abort_403(!($this->viewPermission == 'all'
//            || ($this->viewPermission == 'added' && $this->client->clientDetails->added_by == user()->id)
//            || ($this->viewPermission == 'both' && $this->client->clientDetails->added_by == user()->id)));

        $this->pageTitle = ucfirst($this->client->name);

        $this->clientStats = $this->clientStats($id);
        $this->projectChart = $this->projectChartData($id);
        $this->invoiceChart = $this->invoiceChartData($id);

        $this->earningTotal = Payment::leftJoin('invoices', 'invoices.id', '=', 'payments.invoice_id')
            ->leftJoin('projects', 'projects.id', '=', 'payments.project_id')
            ->where(function ($q) use ($id) {
                $q->where('invoices.client_id', $id);
                $q->orWhere('projects.client_id', $id);
            })->sum('amount');

        $this->view = 'clients.ajax.profile';

        $tab = request('tab');

        switch ($tab) {
            case 'projects':
                return $this->projects();
            case 'integration':
                return $this->integration();
            case 'invoices':
                return $this->invoices();
            case 'payments':
                return $this->payments();
            case 'estimates':
                return $this->estimates();
            case 'creditnotes':
                return $this->creditnotes();
            case 'contacts':
                return $this->contacts();
            case 'documents':
                abort_403(!($this->viewDocumentPermission == 'all'
                    || ($this->viewDocumentPermission == 'added' && $this->client->clientDetails->added_by == user()->id)
                    || ($this->viewDocumentPermission == 'owned' && $this->client->clientDetails->user_id == user()->id)
                    || ($this->viewDocumentPermission == 'both' && ($this->client->clientDetails->added_by == user()->id || $this->client->clientDetails->user_id == user()->id))));

                $this->view = 'clients.ajax.documents';
                break;
            case 'notes':
                return $this->notes();
            case 'tickets':
                return $this->tickets();
            case 'gdpr':
                $this->client = User::withoutGlobalScope(ActiveScope::class)->findOrFail($id);
                $this->consents = PurposeConsent::with(['user' => function ($query) use ($id) {
                    $query->where('client_id', $id)
                        ->orderBy('created_at', 'desc');
                }])->get();

                return $this->gdpr();
            default:
                $this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();

                if (!is_null($this->clientDetail)) {
                    $this->clientDetail = $this->clientDetail->withCustomFields();

                    if ($this->clientDetail->getCustomFieldGroupsWithFields()) {
                        $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
                    }
                }

                $this->view = 'clients.ajax.profile';
                break;
        }

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->activeTab = $tab ?: 'profile';

        return view('clients.show', $this->data);

    }

    /**
     * XXXXXXXXXXX
     *
     * @return array
     * @throws Throwable
     */
    public final function store(CreateSchemaRequest $request)
    {
        $schema = Schema::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'dimension' => $request->get('dimension'),
            'row_amount' => $request->get('rows'),
        ]);

        $cells = $request->get('cells');
        $dimensions = explode('x', $request->get('dimension'));
        $dimensions = (array_sum($dimensions) + 1);

        $row_check = 1;
        $dimensions_check = 0;
        $data = [];

        foreach ($cells as $key => $cell) {
            if (!is_null($cell)) {
                $data[] = [
                    'schema_id' => $schema->id,
                    'row' => $row_check,
                    'cell' => $cell,
                    'index' => $key,
                ];
            }
            $dimensions_check++;
            if ($dimensions_check == $dimensions) {
                $row_check++;
                $dimensions_check = 0;
            }
        }

        $schema->seats()->createMany($data);

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('schema.index');
        }

        if ($request->has('ajax_create')) {

            return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);
        }

        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);
    }

    /**
     * XXXXXXXXXXX
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application
     */
    public function edit($id)
    {
        $this->schema = Schema::with('seats')->where('id', $id)->first();
        $this->editPermission = user()->permission('edit_schema');


        abort_403(!$this->editPermission == 'all');

        $this->pageTitle = "Обновить схему";

        if (request()->ajax()) {
            $html = view('schemas.ajax.edit', $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }


        $this->view = 'schemas.ajax.edit';

        return view('schemas.create', $this->data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->client = User::withoutGlobalScope(ActiveScope::class)->with('clientDetails')->findOrFail($id);
        $this->deletePermission = user()->permission('delete_clients');

        abort_403(
            !($this->deletePermission == 'all'
                || ($this->deletePermission == 'added' && $this->client->clientDetails->added_by == user()->id)
                || ($this->deletePermission == 'both' && $this->client->clientDetails->added_by == user()->id)
            )
        );

        $this->deleteClient($this->client);

        return Reply::success(__('messages.deleteSuccess'));
    }

    private function deleteClient(User $user)
    {
        $universalSearches = UniversalSearch::where('searchable_id', $user->id)->where('module_type', 'client')->get();

        if ($universalSearches) {
            foreach ($universalSearches as $universalSearch) {
                UniversalSearch::destroy($universalSearch->id);
            }
        }


        Notification::whereNull('read_at')
            ->where(function ($q) use ($user) {
                $q->where('data', 'like', '{"id":' . $user->id . ',%');
                $q->orWhere('data', 'like', '%,"name":' . $user->name . ',%');
                $q->orWhere('data', 'like', '%,"user_one":' . $user->id . ',%');
            })->delete();

        $user->delete();
    }
}
