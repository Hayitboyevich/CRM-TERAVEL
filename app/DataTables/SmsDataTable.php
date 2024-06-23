<?php

namespace App\DataTables;

use App\Models\Sms;
use App\Models\SmsMailing;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class SmsDataTable extends BaseDataTable
{
    private string $viewSmsPermission;
    private string $editSmsPermission;
    private string $deleteSmsPermission;

    public function __construct()
    {
        parent::__construct();
        $this->viewSmsPermission = user()->permission('view_sms');
        $this->editSmsPermission = user()->permission('edit_sms');
        $this->deleteSmsPermission = user()->permission('delete_sms');
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
            return '<input type="checkbox" class="select-table-row" id="datatable-row-' . $row->id . '"  name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
        });

        $datatables->editColumn(
            'created_at',
            function ($row) {
                return Carbon::parse($row->created_at)->translatedFormat(company()->date_format);
            }
        );
        $datatables->editColumn(
            'client_name',
            function ($row) {
                return $row->user?->name;
            }
        );

        $datatables->addColumn('action', function ($row) {

            $action = '<div class="task_view">

                    <div class="dropdown">
                        <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
                            id="dropdownMenuLink-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-options-vertical icons"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->id . '" tabindex="0">';

            $action .= '<a href="' . route('sms.show', [$row->id]) . '" class="dropdown-item"><i class="fa fa-eye mr-2"></i>' . __('app.view') . '</a>';

//            if ($this->editClientPermission == 'all' || ($this->editClientPermission == 'added' && user()->id == $row->added_by) || ($this->editClientPermission == 'both' && user()->id == $row->added_by)) {
            $action .= '<a class="dropdown-item" href="' . route('sms.edit', [$row->id]) . '">
                                <i class="fa fa-edit mr-2"></i>
                                ' . trans('app.edit') . '
                            </a>';
//            }

//            if ($this->deleteClientPermission == 'all' || ($this->deleteClientPermission == 'added' && user()->id == $row->added_by) || ($this->deleteClientPermission == 'both' && user()->id == $row->added_by)) {
            $action .= '<a class="dropdown-item delete-table-row" href="javascript:;" data-sms-id="' . $row->id . '">
                                <i class="fa fa-trash mr-2"></i>
                                ' . trans('app.delete') . '
                            </a>';
//            }

            $action .= '</div>
                    </div>
                </div>';

            return $action;
        });
        $datatables->addIndexColumn();
        $datatables->smart(false);
        $datatables->setRowId(function ($row) {
            return 'row-' . $row->id;
        });
        $datatables->rawColumns(['name', 'action', 'status', 'check']);

        return $datatables;
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(SmsMailing $model): QueryBuilder
    {
        return $model
            ->newQuery()
            ->where('company_id', company()->id);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html()
    {
        return $this->setBuilder('sms-table', 2)
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["sms-table"].buttons().container()
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
        $data = [
            'check' => [
                'title' => '<input type="checkbox" name="select_alField as $customField) {
                    $data[] = [$customField->name => l_table" id="select-all-table" onclick="selectAllTable(this)">',
                'exportable' => false,
                'orderable' => false,
                'searchable' => false
            ],
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'visible' => !showId(), 'title' => '#'],
            __('app.id') => ['data' => 'id', 'name' => 'id', 'title' => __('app.id'), 'visible' => showId()],

            Column::make('message')->title('Сообщение'),
            Column::make('status')->title('Cтатус'),
            Column::make('delivery_date')->title('Дата отправки'),
            Column::make('created_at')->title('Создан в'),

            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->addClass('text-right pr-20')
        ];

        return $data;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Sms_' . date('YmdHis');
    }
}
