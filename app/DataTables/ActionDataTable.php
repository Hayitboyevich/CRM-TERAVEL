<?php

namespace App\DataTables;

use App\Models\Action;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Modules\ActivityHistory\Entities\ActivityHistory;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class ActionDataTable extends BaseDataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $datatables = datatables()->eloquent($query);
        $datatables->addIndexColumn();

        $datatables->editColumn(
            'created_at',
            function ($row) {
                return Carbon::parse($row->created_at)->format('Y-m-d H:i:s');
            }
        );


        $datatables->editColumn(
            'user_id',
            function ($row) {
                return $row->user ? $row->user->name : '';
            }
        );
        $datatables ->rawColumns(['user_id']);
        return $datatables;
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ActivityHistory $model): QueryBuilder
    {
        $request = $this->request();
        $model = $model->newQuery()->where('company_id', company()->id)
                    ->where('module_name', ActivityHistory::LOGIN_MODULE_NAME)
                    ->with('user');

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->company->date_format, $request->startDate)->toDateString();
            $model = $model->where(DB::raw('DATE(activity_histories.`created_at`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->company->date_format, $request->endDate)->toDateString();
            $model = $model->where(DB::raw('DATE(activity_histories.`created_at`)'), '<=', $endDate);
        }

        if ($request->clientID !== 'all' && $request->clientID !== null) {
            $clientId = $request->clientID;
            $model->where('user_id', $clientId);
        }

        return $model;

    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('action-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
            ->buttons([
                Button::make('create'),
                Button::make('export'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('user_id'),
            Column::make('info'),
            Column::make('ip'),
            Column::make('module_name'),
            Column::make('created_at'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Action_' . date('YmdHis');
    }
}
