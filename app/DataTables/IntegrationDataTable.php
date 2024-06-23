<?php

namespace App\DataTables;

use App\Models\Integration;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class IntegrationDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                $action = '<div class="task_view">
                <div class="dropdown">
                    <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
                        id="dropdownMenuLink-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-options-vertical icons"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->id . '" tabindex="0">';

                $action .= '<a href="' . route('integrations.show', [$row->id]) . '" class="dropdown-item"><i class="fa fa-eye mr-2"></i>' . __('app.view') . '</a>';

                $action .= '<a class="dropdown-item openRightModal" href="' . route('integrations.edit', [$row->id]) . '">
                            <i class="fa fa-edit mr-2"></i>
                            ' . trans('app.edit') . '
                        </a>';

                $action .= '</div>
                </div>
            </div>';

                return $action;
            })
            ->addColumn('action', function ($row) {
                $link = 'http://online.kompastour.uz/search_tour?TOWNFROMINC=' . $row->from_city_id .
                    '&STATEINC=' . $row->to_country_id . '&TOURINC=' . $row->tour_id .
                    '&PROGRAMGROUPINC=' . $row->program_type_id . '&CHECKIN_BEG=' . date('Ymd', strtotime($row->checkin_begin)) .
                    '&NIGHTS_FROM=' . $row->nights_count_from . '&CHECKIN_END=' . date('Ymd', strtotime($row->checkin_end)) .
                    '&NIGHTS_TILL=' . $row->nights_count_to . '&ADULT=2&CURRENCY=2&TOWNS=' . $row->to_city_id . '&STARS=' . $row->category_id .
                    '&HOTELS=' . $row->hotel_id . '&CHILD=' . $row->children_count . '&ADULT=' . $row->adults_count;
                $request = $this->request();
                $client_id = filter_var($request->getPathInfo(), FILTER_SANITIZE_NUMBER_INT);
                $editLead = '<a href=' . route('integrations.edit',
                        $row->id) . ' class="btn btn-outline-warning height-35 w-70"><i class="fas fa-pencil-alt"></i></a>';
                if ($row->lead) {


                    $leadBtn = '<a href=' . route('leads.show', [
                            'lead' => $row->lead->id]) . ' class="btn btn-outline-primary height-35 w-70"><i class="fas fa-eye"></i></a>';
                } else {
                    $leadBtn = '<a href=' . route('leadboards.attach', [
                            'clientId' => $client_id, 'integrationId' => $row->id]) . ' class="btn btn-primary height-35 w-70"><i class="fas fa-user-plus"></i></a>';
                }

                $action = '
                <div class="row">
                <div class="col-md-6">
                    ' . $leadBtn . '
                    ' . $editLead . '
                </div>
                <div class="col-md-6">
                    <a href=' . $link . 'target="_blank">Kompastour</a>
                </div>
                </div>';
                return $action;
            }
            )
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Integration $model)
    {
        $request = $this->request();
        $client_id = filter_var($request->getPathInfo(), FILTER_SANITIZE_NUMBER_INT);
        return $model->with('lead')->where(['user_id' => $client_id]);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('integration-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
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
//            Column::make('id'),
            Column::make('from_city_name')->title('Из города'),
//            Column::make('to_country_name')->title('В страну'),
            Column::make('to_city_name')->title('В страну'),
            Column::make('hotel_name')->title('Гостиница'),
//            Column::make('category_name')->title('Категория'),
            Column::make('checkin_begin')->title('С даты'),
            Column::make('checkin_end')->title('до даты'),
            Column::make('adults_count')->title('взрослых'),
            Column::make('children_count')->title('детей'),
            Column::make('budget')->title('Budget (UZS)'),
            Column::make('nights_count_from')->title('ночей от'),
            Column::make('nights_count_to')->title('ночей до'),

            Column::computed('action', __('app.action'))
                ->exportable(true)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->addClass('text-left'),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Integration_' . date('YmdHis');
    }
}
