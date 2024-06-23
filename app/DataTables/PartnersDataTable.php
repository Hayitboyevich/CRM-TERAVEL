<?php

namespace App\DataTables;

use App\Models\IntegrationPartner;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class PartnersDataTable extends BaseDataTable
{
    private $viewClientPermission;
    private $editClientPermission;
    private $deleteClientPermission;

    public function __construct()
    {
        parent::__construct();
        $this->viewClientPermission = user()->permission('view_clients');
        $this->editClientPermission = user()->permission('edit_clients');
        $this->deleteClientPermission = user()->permission('delete_clients');
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
//        $datatables->addColumn('countries', function ($row) {
//            $text = '';
//            $countries = json_decode($row?->countries, true);
//            foreach ($countries as $country) {
//                $text = $text . ' ' . $country;
//            }
//            return $text;
//        });
        $datatables->addColumn('action', function ($row) {
            $action = '<div class="row">
                <a class="btn btn-outline-warning mr-2" href="' . route('partners.edit', [$row->id]) . '"><i class="fa fa-edit"></i></a>
                <a class="btn btn-danger delete-table-row mr-2" href="javascript:;" data-partner-id="' . $row->id . '"><i class="fa fa-trash"></i>
            </a>';

            $action .= '</div>';
            return $action;
        });
        return $datatables;
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(IntegrationPartner $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('partners-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
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
//            Column::computed('action')
//                ->exportable(false)
//                ->printable(false)
//                ->width(60)
//                ->addClass('text-center'),
            Column::make('id'),
            Column::make('name')->title('Название группы'),
            Column::make('login')->title('Имя пользователя'),
            Column::make('type')->title('Веб-сайт'),
            Column::make('exchange_rate')->title('Обменный курс'),

            Column::make('action')

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Partners_' . date('YmdHis');
    }
}
