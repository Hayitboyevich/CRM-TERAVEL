<?php

namespace App\Http\Controllers;

use App\DataTables\DebitDataTable;
use App\DataTables\DebitItemsDataTable;
use App\Models\ClientCategory;
use App\Models\ClientSubCategory;
use App\Models\ContractType;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Response;

class DebitController extends AccountBaseController
{
    public function items($client)
    {
        $this->pageTitle = __('app.clientDebits');
        $this->addClientPermission = 'all';
        $dataTable = new DebitItemsDataTable($client);
        return $dataTable->render('applications.debits.items', $this->data);

    }

    /**
     * client list
     *
     * @return Response
     */
    public function index(DebitDataTable $dataTable)
    {
        $this->pageTitle = __('app.clientDebits');
//        $viewPermission = user()->permission('view_applications');
//        $this->addClientPermission = user()->permission('add_application');
        $this->addClientPermission = 'added';
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

        return $dataTable->render('applications.debits.index', $this->data);
    }
}
