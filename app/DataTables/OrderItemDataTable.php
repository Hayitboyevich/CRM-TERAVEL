<?php

namespace App\DataTables;

use App\Models\OrderItems;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class OrderItemDataTable extends BaseDataTable
{
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
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
        $datatables->addColumn('date', function ($row) {
            return date('d.m.Y', strtotime($row?->date_from)) . ' - ' . date('d.m.Y', strtotime($row?->date_to));
        });
        $datatables->addColumn('price', function ($row) {
            return currency_format($row->nett_amount * $row->nett_exchange_rate, $row->nett_currency_id) . ' / ' . currency_format($row->amount * $row->exchange_rate, $row->currency_id);
        });
        $datatables->editColumn('description', function ($row) {
            return $row->item_name;
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
     * Get the query source of dataTable.
     */
    public function query(OrderItems $model): QueryBuilder
    {
        $request = $this->request();
        $users = $model->query();
        return $users;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html()
    {
        return $this->setBuilder('order-items-table', 2)
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["order-items-table"].buttons().container()
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
            Column::make('item_name')->title('Поставщик'),
            Column::make('date')->title('Даты'),
            Column::make('description')->title('Описание'),
            Column::make('item_name')->title('Схема мест'),
            Column::make('price')->title('Стоимость'),
        ];

    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'OrderItem_' . date('YmdHis');
    }
}
