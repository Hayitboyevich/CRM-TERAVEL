<?php

namespace App\DataTables;

use App\Models\Schema;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class LocationSchemasDataTable extends BaseDataTable
{
    private $deleteSchemaPermission;
    private $editSchemaPermission;
    private $viewSchemaPermission;

    public function __construct()
    {
        parent::__construct();
        $this->viewSchemaPermission = user()->permission('view_schema');
        $this->deleteSchemaPermission = user()->permission('delete_schema');
        $this->editSchemaPermission = user()->permission('edit_schema');
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     */
    public function dataTable($query)
    {
        $datatables = datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $action = '<div class="task_view">

                <div class="dropdown">
                    <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
                        id="dropdownMenuLink-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-options-vertical icons"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->id . '" tabindex="0">';

                $action .= '</div>
                </div>
            </div>';
                return $action;
            });
        $datatables->editColumn('client', function ($row) {
            return $row->client?->name;
        });
        $datatables->setRowId(function ($row) {
            return 'row-' . $row->id;
        });

        return $datatables;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $model = Schema::query();
//            ->where('company_id', company()->id);

        return $model;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {
        return $this->setBuilder('schema-table', 0)
            ->parameters([
                'initComplete' => 'function () {
                    window.LaravelDataTables["schema-table"].buttons().container()
                    .appendTo( "#table-actions")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("#schema-table .select-picker").selectpicker();

                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    });

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
            __('app.id') => ['data' => 'id', 'name' => 'id', 'visible' => false, 'title' => __('app.id')],
            Column::make('name')->title('Name')->orderable(false),
            Column::make('description')->title('Описание')->orderable(false),

            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->orderable(false)
                ->searchable(false)
                ->width(150)
                ->addClass('text-right pr-20')
        ];

    }
}
