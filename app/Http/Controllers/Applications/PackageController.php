<?php

namespace App\Http\Controllers\Applications;

use App\DataTables\PackageDataTable;
use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Http\Requests\StorePackageRequest;
use App\Models\City;
use App\Models\ClientCategory;
use App\Models\ClientSubCategory;
use App\Models\ContractType;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Hotel;
use App\Models\IntegrationCity;
use App\Models\IntegrationPartner;
use App\Models\IntegrationState;
use App\Models\IntegrationTown;
use App\Models\PackageService;
use App\Models\Product;
use App\Models\Project;
use App\Models\Schema;
use App\Models\TourBedType;
use App\Models\TourMealType;
use App\Models\TourPackage;
use App\Models\TourService;
use App\Models\TourType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use const _PHPStan_5473b6701\__;

class PackageController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
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
        $packages = TourPackage::whereIn('id', explode(',', $request->row_ids))->get();
        $packages->each(function ($package) {
            $this->deletePackage($package);
        });
        return true;
    }

    private function deletePackage(TourPackage $application)
    {
        $application->delete();
    }

    public function search(Request $request)
    {
        $q = $request->q;
        $users = TourPackage::query()
            ->where('company_id', company()->id)
            ->where('name', 'like', '%' . $q . '%')
            ->whereColumn('sold_quantity', '<', 'quantity')
            ->limit(5)
            ->get();
        return response()->json($users);
    }

    public function index(PackageDataTable $dataTable)
    {
        $this->pageTitle = 'Тур пакет';
        if (!request()->ajax()) {
            $this->clients = User::allClients();
            $this->subcategories = ClientSubCategory::all();
            $this->categories = ClientCategory::all();
            $this->projects = Project::all();
            $this->contracts = ContractType::all();
            $this->countries = countries();
            $this->totalClients = count($this->clients);
        }

        return $dataTable->render('applications.packages.index', $this->data);
    }

    public function store(StorePackageRequest $request)
    {
        $data = $request->validated();
        $data['date_from'] = date('Y-m-d', strtotime($data['date_from']));
        $data['date_to'] = date('Y-m-d', strtotime($data['date_to']));
        $data['company_id'] = company()->id;
        $data['left_quantity'] = $data['quantity'];

        $services = Arr::get($data, 'services');
        unset($data['services']);
        $package = TourPackage::create($data);

        if ($services) {
            foreach ($services as $service_id) {
                PackageService::query()->create([
                    'service_id' => $service_id,
                    'package_id' => $package->id,
                ]);
            }
        }

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('packages.index');
        }

        return Reply::successWithData(__('messages.updateSuccess'), ['redirectUrl' => $redirectUrl]);

    }

    public function create()
    {
        $this->pageTitle = __('app.createPackage');
        $this->services = TourService::query()->get();
        $this->cities = City::select('id', 'name')->paginate(20);
        $this->currencyCode = company()->currency->currency_code;
        $this->currencies = Currency::query()->where('company_id', company()->id)->get();
        $this->countries = Country::all();
        $this->partners = IntegrationPartner::all();
        $this->mealTypes = TourMealType::all();
        $this->products = Product::all();

        $this->view = 'applications.packages.create';

        if (request()->ajax()) {

            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('applications.packages.template', $this->data);
    }

    public function edit($id)
    {
        $this->package = TourPackage::query()->findOrFail($id);
        $this->pageTitle = __('app.editPackage');
        $this->partners = IntegrationPartner::all();
        $this->tourTypes = TourType::all();
        $this->products = Product::all();
        $this->schemas = Schema::all();
        $this->products = Product::all();
        $this->services = TourService::query()->get();
        $this->hotels = Hotel::all();

        $this->countries = Country::all();
        $this->cities = City::all();
        $this->mealTypes = TourMealType::all();
        $this->bedTypes = TourBedType::all();
        $this->fromCities = IntegrationCity::all();
        $this->currencyCode = company()->currency->currency_code;
        $this->currencies = Currency::query()->where(['company_id' => company()->id])->get();
        $this->view = 'applications.packages.edit';
        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        return view('applications.packages.template', $this->data);
    }

    public function update(StorePackageRequest $request, $id)
    {
        $package = TourPackage::findOrFail($id);
        $data = $request->validated();
        $data["date_from"] = date('Y-m-d', strtotime($data["date_from"]));
        $data["date_to"] = date('Y-m-d', strtotime($data["date_to"]));
        PackageService::query()->where('package_id', $package->id)->delete();

        if (isset($data["services"])) {
            $data["services"] = [];
            foreach ($data["services"] as $service) {
                PackageService::query()->create(
                    [
                        'package_id' => $package->id,
                        'service_id' => $service
                    ]
                );
            }
            unset($data['services']);
        }

        $package->fill($data);
        $package->save();

        $redirectUrl = urldecode($request->redirect_url);

        if ($redirectUrl == '') {
            $redirectUrl = route('packages.index');
        }

        return Reply::successWithData(__('messages.updateSuccess'), ['redirectUrl' => $redirectUrl]);
    }
}
