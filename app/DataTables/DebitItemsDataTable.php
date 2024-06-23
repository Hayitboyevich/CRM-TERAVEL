<?php

namespace App\DataTables;

use App\Models\Application;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class DebitItemsDataTable extends BaseDataTable
{
    private $client_id;

    public function __construct($id)
    {
        parent::__construct();
        $this->client_id = $id;

    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {

        $datatables = datatables()->eloquent($query);
        $datatables->addIndexColumn();
        $datatables->addColumn('check', function ($row) {
            return `<input type="checkbox" class="select-table-row" id="datatable-row-' . $row->id . '"  name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">`;
        });


        $datatables->editColumn('order_name', function ($row) {
            if ($row->order && $row->order->name == 'Пакетный тур') {
                $countryName = optional($row->order->items->where('item_name', 'Пакетный тур')->first()->country)->name;
                $fromCity = optional($row->order->items->where('item_name', 'Пакетный тур')->first()->fromCity)->name;
                if ($countryName && $fromCity)
                return '<span>Пакетный тур</span><br>(<span>' . $fromCity  . '</span> - <span>' . $countryName . '</span>)';
            }
            return $row->order ? $row->order->name : null;
        });
        $datatables->editColumn('client_price', function ($row) {
            return currency_format($row->order?->total, $row->order?->currency_id);
        });
        $datatables->editColumn(
            'client_paid',
            function ($row) {
                return currency_format($row->order?->total_paid, $row->order?->currency_id);

            });
        $datatables->editColumn(
            'debit_client',
            function ($row) {
                return currency_format(($row->order?->total - $row->order?->total_paid), $row->order?->currency_id);

            });

        $datatables->smart(false);
        $datatables->rawColumns(['check', 'order_name']);
        $datatables->setRowId(function ($row) {
            return 'row-' . $row?->order?->application_id;
        });

        return $datatables;
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Application $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('order')
            ->where('applications.client_id', $this->client_id);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html()
    {
        return $this->setBuilder('debititems-table', 2)
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["debititems-table"].buttons().container()
                    .appendTo("#table-actions")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                  //
                }',
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [

            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'visible' => !showId(), 'title' => '#'],
            Column::make('id'),
            Column::make('order_name')->title('Название заказа')->orderable(false),
            Column::make('client_price')->title('Стоимость клиенту')->orderable(false),
            Column::make('client_paid')->title('Оплата от клиента')->orderable(false),
//            Column::make('deadline')->title('Оплата от клиента за выбранный период')->orderable(false),
            Column::make('debit_client')->title('Долг клиента')->orderable(false),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'DebitItems_' . date('YmdHis');
    }
}
