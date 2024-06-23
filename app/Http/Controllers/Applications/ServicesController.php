<?php

namespace App\Http\Controllers\Applications;

use App\DataTables\TourServicesDataTable;
use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\ClientCategory;
use App\Models\ClientSubCategory;
use App\Models\ContractType;
use App\Models\Currency;
use App\Models\Hotel;
use App\Models\IntegrationCity;
use App\Models\IntegrationPartner;
use App\Models\IntegrationState;
use App\Models\IntegrationTown;
use App\Models\Product;
use App\Models\Project;
use App\Models\Schema;
use App\Models\TourBedType;
use App\Models\TourMealType;
use App\Models\TourService;
use App\Models\TourType;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class ServicesController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.services';

    }

    public function search(Request $request)
    {
        $q = $request->q;
        $services = TourService::query()
            ->select(['*', 'products.name', 'tour_services.id as id', 'tour_services.name as product_name'])
            ->join('products', 'products.id', '=', 'tour_services.type_id')
            ->where('tour_services.company_id', company()->id)
            ->where('tour_services.name', 'like', '%' . $q . '%')
            ->orWhere('products.name', 'like', '%' . $q . '%')
            ->orWhere('tour_services.description', 'like', '%' . $q . '%')
            ->limit(5)
            ->get();
        return response()->json($services);
    }

    /**
     * XXXXXXXXXXX
     *
     * @return Response
     */
    public function applyQuickAction(Request $request)
    {
        switch ($request->action_type) {
            case 'delete':
                $this->deleteRecords($request);
                return Reply::success(__('messages.deleteSuccess'));
            default:
                return Reply::error(__('messages.selectAction'));
        }
    }

    protected function deleteRecords($request)
    {
        $services = TourService::whereIn('id', explode(',', $request->row_ids))->get();
        $services->each(function ($service) {
            $this->deleteService($service);
        });
        return true;
    }

    private function deleteService(TourService $service)
    {
        $service->delete();
    }

    /**
     * client list
     *
     * @return Response
     */
    public function index(TourServicesDataTable $dataTable)
    {
//        $viewPermission = user()->permission('view_services');
//        $this->addClientPermission = user()->permission('add_services');

//        abort_403(!in_array($viewPermission, ['all', 'added', 'both']));

        if (!request()->ajax()) {
            $this->clients = User::allClients();
            $this->subcategories = ClientSubCategory::all();
            $this->categories = ClientCategory::all();
            $this->projects = Project::all();
            $this->contracts = ContractType::all();
            $this->countries = countries();
            $this->totalClients = count($this->clients);
        }

        return $dataTable->render('applications.services.index', $this->data);
    }

    /**
     * XXXXXXXXXXX
     *
     * @return Response
     */
    public function update(UpdateServiceRequest $request, TourService $service)
    {
        $data = $request->validated();
        $data["date_from"] = date('Y-m-d', strtotime($data["date_from"]));
        $data["date_to"] = date('Y-m-d', strtotime($data["date_to"]));

        $service->fill($data);
        $service->save();

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('clients.index');
        }

        return Reply::successWithData(__('messages.updateSuccess'), ['redirectUrl' => $redirectUrl]);
    }

    /**
     * XXXXXXXXXXX
     *
     * @return array
     * @throws Throwable
     */
    public function store(StoreServiceRequest $request)
    {
        $data = $request->validated();
        $data['company_id'] = company()->id;
        $data['date_from'] = date('Y-m-d', strtotime($data["date_from"]));
        $data['date_to'] = date('Y-m-d', strtotime($data["date_to"]));
        $service = TourService::query()->create($data);

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('services.index');
        }

        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);
    }

    /**
     * XXXXXXXXXXX
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application
     */
    public function create()
    {
        $this->view = 'applications.services.create';
        $this->pageTitle = 'Создать услуги';
        $this->countries = IntegrationState::all();
        $this->partners = IntegrationPartner::all();
        $this->tourTypes = TourType::all();
        $this->products = Product::query()->where('company_id', company()->id)->get();

        $this->schemas = Schema::all();
        $this->hotels = Hotel::all();

        $this->countries = IntegrationState::all();
        $this->cities = IntegrationTown::all();
        $this->mealTypes = TourMealType::all();
        $this->bedTypes = TourBedType::all();
        $this->fromCities = IntegrationCity::all();
        $this->currencyCode = company()->currency->currency_code;
        $this->currencies = Currency::query()->where(['company_id' => company()->id])->get();

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('applications.services.template', $this->data);
    }

    /**
     * XXXXXXXXXXX
     *
     * @return Response
     */
    public function edit(TourService $service)
    {
        $this->service = $service;
        $this->pageTitle = 'Редактировать услуги';
        $this->countries = IntegrationState::all();
        $this->partners = IntegrationPartner::all();
        $this->tourTypes = TourType::all();
        $this->products = Product::query()->where('company_id', company()->id)->get();
        $this->schemas = Schema::all();
        $this->hotels = Hotel::all();

        $this->countries = IntegrationState::all();
        $this->cities = IntegrationTown::all();
        $this->mealTypes = TourMealType::all();
        $this->bedTypes = TourBedType::all();
        $this->fromCities = IntegrationCity::all();
        $this->currencyCode = company()->currency->currency_code;
        $this->currencies = Currency::query()->where(['company_id' => company()->id])->get();
        $this->view = 'applications.services.edit';
        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('applications.services.template', $this->data);

    }

}
