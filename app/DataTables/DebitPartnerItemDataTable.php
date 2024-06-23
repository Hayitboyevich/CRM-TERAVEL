<?php

namespace App\DataTables;

use App\Models\OrderItems;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class DebitPartnerItemDataTable extends BaseDataTable
{
    private ?int $partner_id;

    public function __construct($id)
    {
        parent::__construct();
        $this->partner_id = $id;

    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable($query): EloquentDataTable
    {
        $datatables = datatables()->eloquent($query);
        $datatables->addIndexColumn();
        $datatables->addColumn('check', function ($row) {
            return '<input type="checkbox" class="select-table-row" id="datatable-row-' . $row->id . '"  name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
        });
        $datatables->editColumn('name', function ($row) {
            if ($row->order && $row->order->name == 'Пакетный тур') {
                $packageItem = $row->order->items->where('item_name', 'Пакетный тур')->first();
                if ($packageItem) {
                    $countryName = $packageItem->country ? $packageItem->country->name : '';
                    $fromCity = $packageItem->fromCity ? $packageItem->fromCity->name : '';
                    return '<span>Пакетный тур</span><br>(<span>' . $fromCity  . '</span> - <span>' . $countryName . '</span>)';
                } else {
                    // Handle the case where $packageItem is null
                    return '<span>Пакетный тур</span><br>(<span>Unknown City</span> - <span>Unknown Country</span>)';
                }
            }
            return $row->order ? $row->order->name : null;
        });


        $datatables->editColumn('id', function ($row) {
            return $row?->order?->application_id;
        });
        $datatables->editColumn('client_price', function ($row) {
            $val = $row->order->net_price / currency_get_by_id($row->order->currency_id)->exchange_rate;
            return currency_format($val, company()->currency_id);
        });
        $datatables->editColumn('client_paid', function ($row) {
            if ($row->order && $row->order->payments) {
                $totalPaid = $row->order->payments->reduce(function ($carry, $payment) {
                    return $carry + ($payment->amount / $payment->exchange_rate);
                }, 0);
                return currency_format($totalPaid, company()->currency_id);
            }
            return null;
        });

        $datatables->editColumn('debit_client', function ($row) {
            if ($row->order && $row->order->payments) {
                $totalPaid = $row->order->payments->reduce(function ($carry, $payment) {
                    return $carry + ($payment->amount / $payment->exchange_rate);
                }, 0);
                $val = $row->order->net_price / currency_get_by_id($row->order->currency_id)->exchange_rate;
                return currency_format(($val - $totalPaid), company()->currency_id);
            }
            return null;
        });

        $datatables->smart(false);
        $datatables->rawColumns(['check', 'name']);
        $datatables->setRowId(function ($row) {
            return 'row-' . $row?->order?->application_id;
        });

        return $datatables;
    }


    /**
     * Get the query source of dataTable.
     */
    public function query(OrderItems $model): QueryBuilder
    {
        return $model->query()
            ->with('order')
            ->where('partner_id', $this->partner_id);
    }

    /**
     * Optional method if you want to use the html builder.
     */
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
    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [

            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'visible' => !showId(), 'title' => '#'],
            Column::make('id'),
            Column::make('name')->title('Название заказа')->orderable(false),
            Column::make('client_price')->title('Стоимость')->orderable(false),
            Column::make('client_paid')->title('Оплата')->orderable(false),
////            Column::make('deadline')->title('Оплата от клиента за выбранный период')->orderable(false),
            Column::make('debit_client')->title('Долг')->orderable(false),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'DebitPartnerItem_' . date('YmdHis');
    }
}
