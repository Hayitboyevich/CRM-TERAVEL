<?php

namespace App\Http\Controllers;


use App\DataTables\KpiDataTable;
use App\Helper\Reply;
use App\Models\KpiItem;

class KpiController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Kpi';
    }

    public function index(KpiDataTable $dataTable)
    {
        $this->criteria = KpiItem::query()
            ->where('company_id', company()->id)
            ->get()->toArray();
        return $dataTable->render('kpi.index', $this->data);
    }

    public function edit($id)
    {
        if (request()->ajax()) {
            $html = view('kpi.ajax.edit', $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'kpi.ajax.edit';

        return view('kpi.create', $this->data);
    }
}
