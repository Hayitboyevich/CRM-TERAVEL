<?php

namespace App\Http\Controllers;

use App\DataTables\ActionDataTable;
use App\Models\Project;
use App\Models\User;

class ActionController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.actions';
    }

    public function index(ActionDataTable $dataTable)
    {
        if (!request()->ajax()) {
            $this->projects = Project::allProjects();

            if (in_array('client', user_roles())) {
                $this->clients = User::allEmployees();
            } else {
                $this->clients = User::allEmployees();
            }
        }

        return $dataTable->render('actions.index', $this->data);
    }
}
