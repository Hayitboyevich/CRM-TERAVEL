<?php

namespace App\Http\Controllers\Applications;

use App\DataTables\DebitPartnerItemDataTable;
use App\DataTables\PartnerDebitDataTable;
use App\Http\Controllers\AccountBaseController;
use App\Models\ClientCategory;
use App\Models\ClientSubCategory;
use App\Models\ContractType;
use App\Models\Project;
use App\Models\User;

class PartnerDebitsController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function items($partner)
    {
        $this->pageTitle = __('app.debtFromPartners');
        $this->addClientPermission = 'all';
        $dataTable = new DebitPartnerItemDataTable($partner);
        return $dataTable->render('applications.partner-debits.items', $this->data);

    }

    public function index(PartnerDebitDataTable $dataTable)
    {
        $this->pageTitle = __('app.debtFromPartners');
//        $viewPermission = user()->permission('view_clients');
//        $this->addClientPermission = user()->permission('add_clients');

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

        return $dataTable->render('applications.partner-debits.index', $this->data);
    }

}
