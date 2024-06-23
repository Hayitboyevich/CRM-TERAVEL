<?php

namespace App\DataTables;

use App\Models\Application;
use App\Models\IntegrationState;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Exceptions\Exception;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class DashboardDataTable extends BaseDataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @throws Exception
     */
    private $viewPermission;
    private $editPermission;
    private $deletePermission;

    public function __construct()
    {
        parent::__construct();
        $this->viewPermission = user()->permission('view_statistics');
        $this->editPermission = user()->permission('edit_statistics');
        $this->deletePermission = user()->permission('delete_statistics');
    }

    public function dataTable($query)
    {
        $datatables = datatables()->eloquent($query);

//        $datatables->addIndexColumn();
        $datatables->editColumn(
            'created_at',
            function ($row) {
                return Carbon::parse($row->created_at)->translatedFormat($this->company->date_format);
            }
        );
        $datatables->addColumn(
            'order_name',
            function ($row) {
                return $row->order_name;
            }
        );
        $datatables->editColumn(
            'agent_name',
            function ($row) {
                return $row->agent_name;
            }
        );
        $datatables->editColumn(
            'total',
            function ($row) {
                return currency_format($row->order_total, $row->order_currency_id);
            }
        );
        $datatables->editColumn(
            'netto_price',
            function ($row) {
                return currency_format($row->order_netto_price, $row->order_currency_id);
            }
        );
        $datatables->editColumn(
            'total_paid',
            function ($row) {
                return currency_format($row->order_total_paid, $row->order_currency_id);
            }
        );
        $datatables->addColumn(
            'client_name',
            function ($row) {
                return $row->client_firstname . ' ' . $row->client_lastname;
            }
        );
        $datatables->editColumn(
            'partner',
            function ($row) {
                return $row->partner_name;
            }
        );
        $datatables->addColumn(
            'country',
            function ($row) {
                return IntegrationState::query()->where('id', $row->order_country_id)->first()?->name ?? '';
            }
        );
//        $datatables->editColumn(
//            'service_rate',
//            function ($row) {
//                $rate = $row->leadAgent?->user?->employee->first()?->service_rate ?? 0;
//                return currency_format(($row->order?->total_paid * $rate / 100), company()->currency_id) . '  (' . ($rate . '%)');
//            }
//        );


        return $datatables;
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Application $model): QueryBuilder
    {
        $request = $this->request();
        $sales = $model
            ->with('order', 'agent', 'client')
            ->leftJoin('orders', 'orders.application_id', '=', 'applications.id')
            ->leftJoin('users', 'users.id', '=', 'applications.client_id')
            ->leftJoin('integration_partners', 'integration_partners.id', '=', 'applications.partner_id')
            ->leftJoin('lead_agents', 'lead_agents.id', '=', 'applications.agent_id')
            ->leftJoin('users as lg', 'lg.id', '=', 'lead_agents.user_id')
            ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
            ->select(['lg.firstname as agent_name', 'users.firstname as client_firstname',
                'users.lastname as client_lastname',
                'applications.company_id',
                'orders.name as order_name',
                'orders.total as order_total',
                'orders.net_price as order_netto_price',
                'orders.total_paid as order_total_paid',

                'orders.currency_id as order_currency_id',

                'orders.id as order_id', 'applications.created_at',
                'users.mobile',
                'integration_partners.name as partner_name',

                'orders.partner_id as partner_id',
                'order_items.country_id as order_country_id',])
            ->where(['applications.company_id' => company()->id])
//            ->whereNotNull(['orders.id'])
            ->orderBy('applications.created_at', 'DESC');

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->startDate)->toDateString();
            $sales = $sales->where(DB::raw('DATE(orders.created_at)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->endDate)->toDateString();
            $sales = $sales->where(DB::raw('DATE(orders.created_at)'), '<=', $endDate);
        }

        if ($request->client != 'all' && $request->client != '') {
            $sales = $sales->where('users.id', $request->client);
        }
        if ($request->operator != 'all' && $request->operator != '') {
            $sales = $sales->where('lg.id', $request->operator);
        }
        if ($request->partner != 'all' && $request->partner != '') {
            $sales = $sales->where('orders.partner_id', $request->partner);
        }
        return $sales;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->setBuilder('stats-table')
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["stats-table"].buttons().container()
                    .appendTo("#table-actions")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                  //
                }',
            ])
            ->buttons(Button::make(['extend' => 'excel', 'text' => '<i class="fa fa-file-export"></i> ' . trans('app.exportExcel')]));

    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('created_at')->title(__('app.createdAt')),
            Column::make('client_name')->title(__('app.client')),
            Column::make('order_name')->title(__('app.name')),
            Column::make('country')->title(__('app.country')),
            Column::make('total')->title(__('app.total')),
            Column::make('netto_price')->title(__('app.netPrice')),
            Column::make('total_paid')->title(__('app.clientPayments')),
            Column::make('mobile')->title(__('app.phone')),
            Column::make('partner')->title(__('app.partner')),
            Column::make('agent_name')->title(__('app.manager')),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Dashboard_' . date('YmdHis');
    }
}
