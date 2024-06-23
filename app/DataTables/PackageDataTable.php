<?php

namespace App\DataTables;

use App\Models\Package;
use App\Models\TourPackage;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class PackageDataTable extends BaseDataTable
{
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
            return '<input type="checkbox" class="select-table-row" id="datatable-row-' . $row->id . '"  name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
        });

        $datatables->addColumn('action', function ($row) {
            $action = '<div class="task_view">

                    <div class="dropdown">';
            $action .= '<a href="' . route('clients.show', [$row->id]) . '" class="dropdown-item"><i class="fa fa-eye mr-2"></i>' . __('app.view') . '</a>';
            $action .= '</div>
                    </div>
                </div>';

            return $action;
        });
        $datatables->editColumn('price', function ($row) {
            return currency_format($row->net_price, $row->net_currency_id) . ' / ' . currency_format($row->price, $row->currency_id);
        });
        $datatables->editColumn('id', function ($row) {
            return $row->id;
        });
        $datatables->editColumn('date', function ($row) {
            return date('d.m.Y', strtotime($row?->date_from)) . ' - ' . date('d.m.Y', strtotime($row?->date_to));
        });
        $datatables->editColumn('quantity', function ($row) {
            return $row->quantity-$row->sold_quantity . ' из ' . $row->quantity;
        });
        $datatables->smart(false);
        $datatables->setRowId(function ($row) {
            return 'row-' . $row->id;
        });
        $datatables->rawColumns(array_merge(['status', 'check', 'action'], []));

        return $datatables;

    }

    /**
     * Get the query source of dataTable.
     */
    public function query(TourPackage $model): QueryBuilder
    {
        return $model->newQuery()->where('company_id', company()->id);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html()
    {
        return $this->setBuilder('package-table', 2)
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["package-table"].buttons().container()
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
            Column::make('date')->title('Даты')->orderable(false),
            Column::make('name')->title('Название')->orderable(false),
            Column::make('description')->title('Описание')->orderable(false),
            Column::make('quantity')->title('Осталось мест')->orderable(false),
            Column::make('price')->title('Себестоимость / Стоимость')->orderable(false),
//            Column::make('price')->title('Заявки'),

        ];

    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Package' . date('YmdHis');
    }
}
