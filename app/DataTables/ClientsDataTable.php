<?php

namespace App\DataTables;

use App\Models\ClientDetails;
use App\Models\CustomField;
use App\Models\CustomFieldGroup;
use App\Models\Lead;
use App\Models\LeadInterest;
use App\Models\Traveler;
use App\Models\User;
use App\Scopes\ActiveScope;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class ClientsDataTable extends BaseDataTable
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
            return '<input type="checkbox" class="select-table-row" id="datatable-row-' . $row->id . '"  name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
        });
        $datatables->addColumn('action', function ($row) {

            $action = '<div class="task_view">

                    <div class="dropdown">
                        <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
                            id="dropdownMenuLink-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-options-vertical icons"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->id . '" tabindex="0">';

            $action .= '<a href="' . route('clients.show', [$row->id]) . '" class="dropdown-item"><i class="fa fa-eye mr-2"></i>' . __('app.view') . '</a>';

            if (in_array('admin', user_roles()) && !$row->admin_approval) {
                $action .= '<a href="javascript:;" class="dropdown-item verify-user" data-user-id="' . $row->id . '"><i class="fa fa-check mr-2"></i>' . __('app.approve') . '</a>';
            }

            if ($this->editClientPermission == 'all' || ($this->editClientPermission == 'added' && user()->id == $row->added_by) || ($this->editClientPermission == 'both' && user()->id == $row->added_by)) {
                $action .= '<a class="dropdown-item openRightModal" href="' . route('clients.edit', [$row->id]) . '">
                                <i class="fa fa-edit mr-2"></i>
                                ' . trans('app.edit') . '
                            </a>';
            }

            if ($this->deleteClientPermission == 'all' || ($this->deleteClientPermission == 'added' && user()->id == $row->added_by) || ($this->deleteClientPermission == 'both' && user()->id == $row->added_by)) {
                $action .= '<a class="dropdown-item delete-table-row" href="javascript:;" data-user-id="' . $row->id . '">
                                <i class="fa fa-trash mr-2"></i>
                                ' . trans('app.delete') . '
                            </a>';
            }

            $action .= '</div>
                    </div>
                </div>';

            return $action;
        });

        $datatables->addColumn('client_name', function ($row) {
            return ucfirst($row->name);
        });

        $datatables->addColumn('added_by', function ($row) {
            return ($row->clientDetails && $row->clientDetails->addedBy) ? $row->clientDetails->addedBy->name : '--';
        });
        $datatables->editColumn(
            'name',
            function ($row) {
                return view('components.client', [
                    'user' => $row
                ]);
            }
        );
        $datatables->editColumn(
            'created_at',
            function ($row) {
                return Carbon::parse($row->created_at)->translatedFormat($this->company->date_format);
            }
        );
        $datatables->editColumn(
            'status',
            function ($row) {
                if ($row->status == 'active') {
                    return ' <i class="fa fa-circle mr-1 text-light-green f-10"></i>' . __('app.active');
                } else {
                    return '<i class="fa fa-circle mr-1 text-red f-10"></i>' . __('app.inactive');
                }
            }
        );
        $datatables->smart(false);
        $datatables->setRowId(function ($row) {
            return 'row-' . $row->id;
        });

        // Add Custom Field to datatable
        $customFieldColumns = CustomField::customFieldData($datatables, ClientDetails::CUSTOM_FIELD_MODEL, 'clientDetails');

        $datatables->rawColumns(array_merge(['name', 'action', 'status', 'check'], $customFieldColumns));

        return $datatables;
    }

    /**
     * @param User $model
     * @return User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function query(User $model)
    {
        $request = $this->request();
        $users = $model->withoutGlobalScope(ActiveScope::class)
            ->with('session', 'clientDetails', 'clientDetails.addedBy')
            ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', '=', 'client')
            ->select('users.id', 'users.name', 'users.mobile', 'users.mobile', 'users.image', 'users.created_at', 'users.status', 'users.admin_approval');

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->company->date_format, $request->startDate)->toDateString();

            $users = $users->where(DB::raw('DATE(users.`created_at`)'), '>=', $startDate);
        }

        if ($request->client_type === 'tourist') {
            $travelers = Traveler::query()->groupBy('user_id')->pluck('user_id');
            $users = $users->whereIn('users.id', $travelers);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->company->date_format, $request->endDate)->toDateString();
            $users = $users->where(DB::raw('DATE(users.`created_at`)'), '<=', $endDate);
        }

        if ($request->status != 'all' && $request->status != '') {
            $users = $users->where('users.status', $request->status);
        }

        if ($request->client != 'all' && $request->client != '') {
            $users = $users->where('users.id', $request->client);
        }

        if (!is_null($request->category_id) && $request->category_id != 'all') {
            $users = $users->where('client_details.category_id', $request->category_id);
        }

        if (!is_null($request->sub_category_id) && $request->sub_category_id != 'all') {
            $users = $users->where('client_details.sub_category_id', $request->sub_category_id);
        }

        if (!is_null($request->project_id) && $request->project_id != 'all') {
            $users->whereHas('projects', function ($query) use ($request) {
                return $query->where('id', $request->project_id);
            });
        }

        if (!is_null($request->contract_type_id) && $request->contract_type_id != 'all') {
            $users->whereHas('contracts', function ($query) use ($request) {
                return $query->where('contracts.contract_type_id', $request->contract_type_id);
            });
        }

        if (!is_null($request->country_id) && $request->country_id != 'all') {
            $users = $users->whereHas('lead.leadInterest', function ($query) use ($request) {
                $query->where('country_id', $request->country_id);
            });
        }

        if (!is_null($request->currency_id) && $request->currency_id != 'all' && !is_null($request->price)) {
            $users = $users->whereHas('lead.leadInterest', function ($query) use ($request) {
                $query->where('price', '<=', $request->price)->where('currency_id', $request->currency_id);
            })->with(['lead.leadInterest' => function ($query) use ($request) {
                $query->where('price', '<=', $request->price)->where('currency_id', $request->currency_id);
            }]);
        }

        if (!is_null($request->interestStartDate) && $request->interestStartDate != 'null' && $request->interestStartDate != '' &&
            !is_null($request->interestEndDate) && $request->interestEndDate != 'null' && $request->interestEndDate != '') {

            // Parsing start and end dates
            $interestStartDate = $request->interestStartDate;
            $interestEndDate = $request->interestEndDate;

            // Filtering based on the parsed dates
            $users = $users->whereHas('lead.leadInterest', function ($query) use ($interestStartDate, $interestEndDate) {
                $query->where('desired_date_from', '>=', $interestStartDate)
                    ->where('desired_date_from', '<=', $interestEndDate);
            });

        }

        if (!is_null($request->order_currency_id) && $request->order_currency_id != 'all' && !is_null($request->order_price)) {
            $users = $users->whereHas('orders', function ($query) use ($request) {
                $query->where('currency_id', $request->order_currency_id)
                    ->where('total', '<=', $request->order_price);
            })->with(['orders' => function ($query) use ($request) {
                $query->where('currency_id', $request->order_currency_id)
                    ->where('total', '<=', $request->order_price);
            }]);
        }

        if (!is_null($request->order_country_id) && $request->order_country_id != 'all') {
            $users = $users->whereHas('orders.items', function ($query) use ($request) {
                $query->where('country_id', $request->order_country_id);
            })->with(['orders.items' => function ($query) use ($request) {
                $query->where('country_id', $request->order_country_id);
            }]);
        }

        Log::info($request->orderStartDate);
        if (!is_null($request->orderStartDate) && $request->orderStartDate != 'null' && $request->orderStartDate != '' &&
            !is_null($request->orderEndDate) && $request->orderEndDate != 'null' && $request->orderEndDate != '') {

            // Parsing start and end dates
            $orderStartDate = $request->orderStartDate;
            $orderEndDate = $request->orderEndDate;

            // Filtering based on the parsed dates
            $users = $users->whereHas('orders', function ($query) use ($orderStartDate, $orderEndDate) {
                $query->where('order_date', '>=', $orderStartDate)
                    ->where('order_date', '<=', $orderEndDate);
            });

        }

        if ($request->verification != 'all') {

            if ($request->verification == 'yes') {
                $users->where('users.admin_approval', 1);
            } elseif ($request->verification == 'no') {
                $users->where('users.admin_approval', 0);
            }
        }

        if ($this->viewClientPermission == 'added' || $this->viewClientPermission == 'both') {
            $users = $users->where('client_details.added_by', user()->id);
        }

        if ($request->searchText != '') {
            $users = $users->where(function ($query) {
                $query->where('users.name', 'like', '%' . request('searchText') . '%')
                    ->orWhere('users.email', 'like', '%' . request('searchText') . '%')
                    ->orWhere('users.mobile', 'like', '%' . request('searchText') . '%');
            });
        }

//        dd($users->toSql());
        return $users;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {
        return $this->setBuilder('clients-table', 2)
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["clients-table"].buttons().container()
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
            __('app.name') => ['data' => 'name', 'name' => 'name', 'exportable' => false, 'title' => __('app.name')],
            __('app.customers') => ['data' => 'client_name', 'name' => 'users.name', 'visible' => false, 'title' => __('app.customers')],
            __('app.addedBy') => ['data' => 'added_by', 'name' => 'added_by', 'visible' => false, 'title' => __('app.addedBy')],
            __('app.mobile') => ['data' => 'mobile', 'name' => 'mobile', 'visible' => true, 'title' => __('app.mobile')],
            __('app.status') => ['data' => 'status', 'name' => 'status', 'title' => __('app.status')],
            __('app.createdAt') => ['data' => 'created_at', 'name' => 'created_at', 'title' => __('app.createdAt')]
        ];

        $action = [
            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->addClass('text-right pr-20')
        ];

        return array_merge($data, $action);
    }

}
