<?php

namespace App\DataTables;

use App\Models\Kpi;
use App\Models\LeadAgent;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\KPI\Services\KPIFactory;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class KpiDataTable extends BaseDataTable
{

    public function __construct(private KPIFactory $factory)
    {
        parent::__construct();
        
    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $datatables = datatables()->eloquent($query);
        $datatables->addColumn(
            'name',
            function ($row) {
                return $row->user?->name;
            }
        );
        $datatables->addColumn(
            'deadline_on_time',
            function ($row) {
                return $this->factory->calculateByModule($row->user_id, 'DeadlineMetricKPIService') . '%';
            }
        );
        $datatables->addColumn(
            'completed_leads',
            function ($row) {
                return $this->factory->calculateByModule($row->user_id, 'LeadCalculateKPIService') . '%';
            }
        );
        $datatables->addColumn(
            'profit',
            function ($row) {
                return $this->factory->calculateByModule($row->user_id, 'ProfitKPIService') . '%';
            }
        );
        $datatables->addColumn(
            'regular_customer',
            function ($row) {
                return $this->factory->calculateByModule($row->user_id, 'RegularCustomerKPIService') . '%';
            }
        );
        return $datatables;
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(LeadAgent $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('kpi-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle();
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name')->title('Имя оператора'),
            Column::make('deadline_on_time')->title('По срокам'),
            Column::make('completed_leads')->title('Потенциальные клиенты'),
            Column::make('profit')->title('По доходам'),
            Column::make('regular_customer')->title('Постоянный клиент'),

//            Column::make('completed'),
//            Column::make('expected'),
//            Column::make('percentage')
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Kpi_' . date('YmdHis');
    }
}
