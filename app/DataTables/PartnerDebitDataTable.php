<?php

namespace App\DataTables;

use App\Models\IntegrationPartner;
use App\Models\User;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use const _PHPStan_5473b6701\__;

class PartnerDebitDataTable extends BaseDataTable
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


//        $datatables->addColumn('deadline', function ($row) {
//            return $row?->paymentDeadline?->deadline ? date('d.m.Y', strtotime($row?->paymentDeadline?->deadline)) : '-';
//        });
        $datatables->addColumn(
            'client_name',
            function ($row) {
                return $row?->name;
            });
        $datatables->editColumn(
            'partner_price',
            function ($row) {
                $application_price = $row->orderItems?->map(function ($item) {
                    return $item->nett_amount / $item->exchange_rate;
                })->sum();

                return currency_format($application_price, company()->currency_id);
            });
        $datatables->editColumn(
            'partner_paid',
            function ($row) {
                $amount = cache()->remember('debit_partner_paid', 0.1, function () use ($row) {
                    $debit = $row?->payments->where('paid_for', 'partner')->where('type', 'debit')->map(function ($payment) {
                        return ($payment->amount /  $payment->exchange_rate);
                    })->sum();

                    $credits = $row?->payments->where('paid_for', 'partner')->where('type', 'credit')->map(function ($payment) {
                        return ($payment->amount /  $payment->exchange_rate);
                    })->sum();
                    return ($debit - $credits);
                });
                return currency_format($amount, company()->currency_id);
            });
        $datatables->editColumn(
            'debit_partner',
            function ($row) {
                $price = $row->orderItems?->map(function ($item) {
                    return $item->nett_amount / $item->exchange_rate;
                })->sum();

                $paid = $row->payments?->map(function ($payment) {
                    $amount = $payment->amount / $payment->exchange_rate;

                    if ($payment->type == "debit") {
                        return (-1) * $amount;
                    }
                    return $amount;
                })->sum();
                return currency_format(($price - $paid), company()->currency_id);
            });
        $datatables->editColumn(
            'created_at',
            function ($row) {
                return $row->created_at?->format('d.m.Y');
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
    public function query(IntegrationPartner $model)
    {
        $orders = $model->newQuery()
            ->join('orders', 'orders.partner_id', '=', 'integration_partners.id')
            ->select('integration_partners.*', \DB::raw('SUM(orders.total) as total_orders'))
            ->groupBy('integration_partners.id')
            ->havingRaw('total_orders > 0');

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
            Column::make('id')->orderable(false),
            Column::make('client_name')->title(__('app.name'))->orderable(false),
//            Column::make('deadline')->title('Deadline по оплате')->orderable(false),
            Column::make('partner_price')->title(__('app.price'))->orderable(false),
            Column::make('partner_paid')->title(__('app.payment'))->orderable(false),
//            Column::make('deadline')->title('Оплата от клиента за выбранный период')->orderable(false),
            Column::make('debit_partner')->title(__('app.debtFromPartners'))->orderable(false),

        ];

    }
}
