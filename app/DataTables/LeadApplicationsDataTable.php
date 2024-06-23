<?php

namespace App\DataTables;

use App\Models\Application;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class LeadApplicationsDataTable extends BaseDataTable
{
    private string $viewPermission;
    private string $editPermission;
    private string $deletePermission;
    private $clientId;

    public function __construct($clientId)
    {
        parent::__construct();
        $this->viewPermission = user()->permission('view_clients');
        $this->editPermission = user()->permission('edit_clients');
        $this->deletePermission = user()->permission('delete_clients');
        $this->clientId = $clientId;
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
        $currency = company()->currency_id;
        $exchange_rate = company()->currency->exchange_rate;

        $datatables->addColumn('check', function ($row) {
            return '<input type="checkbox" class="select-table-row" id="datatable-row-' . $row->id . '"  name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
        });
        $datatables->editColumn('status', function ($row) {
            return $row->status?->type ?? '-';
        });
        $datatables->editColumn('created_at', function ($row) {
            return $row->created_at?->format('d.m.Y');
        });
        $datatables->editColumn('partner', function ($row) {
            return $row?->partner?->name ?? '-';
        });
        $datatables->editColumn('name', function ($row) {
            return $row->client ? ($row->client?->firstname . ' ' . $row->client?->lastname) : '-';
        });
        $datatables->editColumn('country', function ($row) {
            return Arr::get($row->order?->items, 0)?->country?->name ?? ' - ';
        });
        $datatables->editColumn('departure_time', function ($row) {
            $date = Arr::get($row?->order?->items, 0);
            return Arr::get($date, 'date_from')?->format('d.m.Y') ?? '-';
        });
        $datatables->editColumn('landing_time', function ($row) {
            $date = Arr::get($row?->order?->items, 0);
            return Arr::get($date, 'date_to')?->format('d.m.Y') ?? '-';
        });
        //partners

        $datatables->editColumn('partner_price', function ($row) use ($currency, $exchange_rate) {
            $value = cache()->remember('partner_price' . $row->id, 0.2, function () use ($row) {
                return ($row?->order?->net_price ?? 0);
            });
            return currency_format($value * $exchange_rate, $currency);
        });
        $datatables->editColumn('partner_total_paid', function ($row) use ($currency, $exchange_rate) {

            return currency_format($row?->order?->net_price_paid * $exchange_rate, $currency);
        });

        $datatables->editColumn('partner_debit', function ($row) use ($currency, $exchange_rate) {
            $order = $row?->order;
            return currency_format(($order?->net_price - $order?->net_price_paid) * $exchange_rate, $currency);
        });
        //clients
        $datatables->editColumn('client_price', function ($row) use ($currency, $exchange_rate) {

            return currency_format(($row?->order?->total ?? 0) * $exchange_rate, $currency);
        });
        $datatables->editColumn('total_paid', function ($row) use ($currency, $exchange_rate) {
            return currency_format($row->order?->total_paid * $exchange_rate, $currency);
        });
        $datatables->editColumn('client_debit', function ($row) use ($currency, $exchange_rate) {

            return currency_format((($row?->order?->total - $row?->order?->total_paid) * $exchange_rate), $currency);
        });


        $datatables->editColumn('payment_deadline', function ($row) {
            return $row->clientDeadline?->deadline?->format('d.m.Y') ?? '-';
        });
        $datatables->editColumn('partner_payment_deadline', function ($row) {
            return $row->partnerDeadline?->deadline?->format('d.m.Y') ?? '-';
        });
        $datatables->editColumn('agent_name', function ($row) {
            return $row->agent?->user?->name ?? '-';
        });
        $datatables->editColumn('passengers_count', function ($row) {
            return $row->travelers?->count() ?? 0;
        });
//
        $datatables->editColumn('type', function ($row) {
            return $row->type?->name ?? '-';
        });
        $datatables->smart(false);

        $datatables->setRowId(function ($row) {
            return 'row-' . $row->id;
        });

        // Add Custom Field to datatable
        $datatables->rawColumns(array_merge(['status', 'check', 'action'], []));

        return $datatables;

    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Application $model): QueryBuilder
    {
        return $model->newQuery()
            ->where('company_id', company()->id)
            ->where('client_id', $this->clientId)
            ->with('client.payments');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->setBuilder('applications-table', 1)
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["applications-table"].buttons().container()
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
            Column::make('id'),
            Column::make('created_at')->title('Дата создания')->orderable(false),
            Column::make('departure_time')->title('Дата отправления / начала')->orderable(false),
            Column::make('landing_time')->title('Дата возвращения / окончания')->orderable(false),
            Column::make('type')->title('Тип заявки')->orderable(false),
            Column::make('payment_deadline')->title('Dead-line по оплате')->orderable(false),
            Column::make('partner_payment_deadline')->title('Dead-line по оплате с партнером')->orderable(false),
            Column::make('country')->title('Страна')->orderable(false),
            Column::make('name')->title('Заказчик (покупатель)')->orderable(false),
            Column::make('passengers_count')->title('Кол-во тур.')->orderable(false),
            Column::make('agent_name')->title('Менеджер')->orderable(false),

            Column::make('status')->title('Статус')->orderable(false),
//            Column::make('visa')->title('Виза'),
            Column::make('partner')->title('Туроператор ')->orderable(false),
            Column::make('order_number')->title('Бронь')->orderable(false),

            Column::make('client_price')->title('Цена клиента')->orderable(false),
            Column::make('total_paid')->title('Оплачено клиентом')->orderable(false),
            Column::make('client_debit')->title('Задолж. клиента')->orderable(false),

            Column::make('partner_price')->title('К оплате партнерам')->orderable(false),
            Column::make('partner_total_paid')->title('Оплачено партнерам')->orderable(false),
//            Column::make('partner_add')->title('Доплатить партнерам'),

//            Column::make('partner')->title('План. прибыль (%)'),
//            Column::make('partner')->title('Факт. прибыль'),
//            Column::make('partner')->title('Факт. прибыль (%)'),

//            Column::make('action')
        ];
//        $action = [
//            Column::computed('action', __('app.action'))
//                ->exportable(false)
//                ->printable(false)
//                ->orderable(false)
//                ->searchable(false)
//                ->addClass('text-right pr-20')
//        ];
//        return array_merge($data, CustomFieldGroup::customFieldsDataMerge(new ClientDetails()), $action);

        return $data;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Applications_' . date('YmdHis');
    }
}
