<?php

namespace App\DataTables;

use App\Models\User;
use Carbon\Carbon;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use const _PHPStan_5473b6701\__;

class DebitDataTable extends BaseDataTable
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     */
    public function dataTable($query)
    {

        $datatables = datatables()->eloquent($query);
        $datatables->addIndexColumn();
        $datatables->addColumn('check', function ($row) {
            return `<input type="checkbox" class="select-table-row" id="datatable-row-' . $row->id . '"  name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">`;
        });
        $datatables->addColumn('action', function ($row) {
            $action = '<div class="task_view">

                    <div class="dropdown">';
//                        <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
//                            id="dropdownMenuLink-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
//                            <i class="icon-options-vertical icons"></i>
//                        </a>
//                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->id . '" tabindex="0">';
//
            $action .= '<a href="' . route('clients.show', [$row->id]) . '" class="dropdown-item"><i class="fa fa-eye mr-2"></i>' . __('app.view') . '</a>';


            $action .= '</div>
                    </div>
                </div>';

            return $action;
        });
        $datatables->addColumn('deadline', function ($row) {
            return $row?->paymentDeadline?->deadline ? date('d.m.Y', strtotime($row?->paymentDeadline?->deadline)) : '-';
        });
        $datatables->editColumn(
            'client_price',
            function ($row) {
                $price = $row->orders?->sum('total');
                return currency_format($price, company()->currency->id);
            });
        $datatables->editColumn(
            'client_paid',
            function ($row) {
                $price = $row->orders?->map(function ($item) {
                    return $item->total_paid;
                })->sum();
                return currency_format($price, company()->currency->id);

            });
        $datatables->editColumn(
            'debit_client',
            function ($row) {
                $total = $row->orders?->map(function ($item) {
                    return $item->total;
                })->sum();

                $paid = $row->orders?->map(function ($item) {
                    return $item->total_paid;
                })->sum();

                return currency_format(($total - $paid), company()->currency->id);

            });
        $datatables->addColumn(
            'client_name',
            function ($row) {
//                $client = $row?->client;
                return $row?->firstname .' '. $row?->lastname;
            });
        $datatables->editColumn(
            'created_at',
            function ($row) {
                return Carbon::parse($row->created_at)->translatedFormat($this->company->date_format);
            }
        );

        $datatables->smart(false);
        $datatables->setRowId(function ($row) {
            return 'row-' . $row->id;
        });

        return $datatables;
    }

    /**
     * @param User $model
     * @return User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function query(User $model)
    {
        $request = $this->request();

        $orders = $model->newQuery()
            ->select(['users.id as id',
                'users.name as name',
                'users.firstname as firstname',
                'users.lastname as lastname',

            ])
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->join('orders', 'orders.client_id', '=', 'users.id')
            ->where('roles.name', 'client')
            ->groupBy('users.id')
            ->havingRaw('SUM(orders.total) > 0');

        return $orders;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {
        return $this->setBuilder('debits-table', 2)
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["debits-table"].buttons().container()
                    .appendTo("#table-actions")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                  //
                }',
            ])
            ->buttons(Button::make(['extend' => 'excel', 'text' => '<i class="fa fa-file-export"></i> ' . trans('app.exportExcel')]));
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'check' => [
                'title' => '<input type="checkbox" name="select_alField as $customField) {
                    $data[] = [$customField->name => l_table" id="select-all-table" onclick="selectAllTable(this)">',
                'exportable' => false,
                'orderable' => false,
                'searchable' => false
            ],
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'visible' => !showId(), 'title' => '#'],
            Column::make('id'),
            Column::make('client_name')->title(__('app.name'))->orderable(false),
            Column::make('deadline')->title('Deadline по оплате')->orderable(false),
            Column::make('client_price')->title(__('app.price'))->orderable(false),
            Column::make('client_paid')->title(__('app.payment'))->orderable(false),
            Column::make('deadline')->title('Оплата от клиента за выбранный период')->orderable(false),
            Column::make('debit_client')->title(__('app.clientDebits'))->orderable(false),
        ];

    }
}
