<?php

namespace App\Http\Controllers;

use App\Charts\LeadSourceChart;
use App\Charts\MonthlyUsersChart;
use App\DataTables\DashboardDataTable;
use App\Helper\Reply;
use App\Models\AttendanceSetting;
use App\Models\ClientDetails;
use App\Models\DashboardWidget;
use App\Models\EmployeeDetails;
use App\Models\Event;
use App\Models\Holiday;
use App\Models\IntegrationPartner;
use App\Models\Lead;
use App\Models\LeadAgent;
use App\Models\LeadSource;
use App\Models\Leave;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProjectTimeLog;
use App\Models\ProjectTimeLogBreak;
use App\Models\Task;
use App\Models\TaskboardColumn;
use App\Models\Ticket;
use App\Models\Traveler;
use App\Models\User;
use App\Traits\ClientDashboard;
use App\Traits\ClientPanelDashboard;
use App\Traits\CurrencyExchange;
use App\Traits\EmployeeDashboard;
use App\Traits\FinanceDashboard;
use App\Traits\HRDashboard;
use App\Traits\OverviewDashboard;
use App\Traits\ProjectDashboard;
use App\Traits\TicketDashboard;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Froiden\Envato\Traits\AppBoot;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use const _PHPStan_5473b6701\__;

class DashboardController extends AccountBaseController
{

    use AppBoot, CurrencyExchange, OverviewDashboard, EmployeeDashboard, ProjectDashboard, ClientDashboard, HRDashboard, TicketDashboard, FinanceDashboard, ClientPanelDashboard;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.dashboard';
        $this->middleware(function ($request, $next) {
            $this->viewOverviewDashboard = user()->permission('view_overview_dashboard');
            $this->viewProjectDashboard = user()->permission('view_project_dashboard');
            $this->viewClientDashboard = user()->permission('view_client_dashboard');
            $this->viewHRDashboard = user()->permission('view_hr_dashboard');
            $this->viewTicketDashboard = user()->permission('view_ticket_dashboard');
            $this->viewFinanceDashboard = user()->permission('view_finance_dashboard');

            return $next($request);
        });

    }

    public function customDashboard(DashboardDataTable $dataTable)
    {
        $this->clients = User::allClients();
        $this->operators = User::allEmployees();
        $this->partners = IntegrationPartner::all();

        $this->statistics = \App\Models\Application::query()
            ->selectRaw(
                'COUNT(applications.id) as client_count, SUM(orders.total)-SUM(COALESCE(orders.total_paid, 0)) as debt,
            (SUM(orders.total)-SUM(orders.net_price)) as income, (SUM(orders.net_price) - SUM(COALESCE(orders.net_price_paid, 0))) as partner_debt'
            )
            ->join('orders', 'orders.application_id', '=', 'applications.id')
            ->where(['applications.company_id' => company()->id])
//            ->whereNotNull(['orders.application_id'])
            ->first();
//        dd($this->statistics);
        $this->pageTitle = __('app.statistics');
        return $dataTable->render('dashboard.custom-dashboard', $this->data);
    }

    public function statistics(MonthlyUsersChart $chart, LeadSourceChart $leadSourceChart)
    {
        $this->pageTitle = __('app.statistics');
        $currentMonth = Carbon::now()->month;
        $leadAgent = LeadAgent::query()
            ->where('company_id', company()->id)
            ->where('user_id', user()->id)
            ->first();

        // All Clients
        $allClients = ClientDetails::query()
            ->selectRaw('COUNT(id) as total_clients')
            ->selectRaw('COUNT(CASE WHEN MONTH(created_at) = ? THEN 1 END) as clients_this_month', [$currentMonth])
            ->where('company_id', company()->id)
            ->first();
        $this->clients_count = (object)$allClients;


        // All Leads for the current user
        if ($leadAgent !== null) {
            $leads = Lead::query()
                ->selectRaw('COUNT(id) as total_leads')
                ->selectRaw('COUNT(CASE WHEN MONTH(created_at) = ? THEN 1 END) as leads_this_month', [$currentMonth])
                ->where('company_id', company()->id);
            if (in_array('admin', user_roles()) || in_array('demosuperadmin', user_roles())) {
                $leads = $leads->first();
            }else{
                $leads = $leads->where('added_by', user()->id)->first();
            }

            $this->leads_count = (object)$leads;
        } else {
            $this->leads_count = (object)[
                'total_leads' => 0,
                'leads_this_month' => 0
            ];
        }

        // All Orders for the current user
        $this->orders_count = \App\Models\Application::query()
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('COUNT(CASE WHEN MONTH(created_at) = ? THEN 1 END) as orders_this_month', [$currentMonth])
            ->where('company_id', company()->id);

        if (in_array('admin', user_roles()) || in_array('demosuperadmin', user_roles())) {
            $this->orders_count = $this->orders_count->first();
        }else{
            $this->orders_count = $this->orders_count->where('added_by', user()->id)->first();
        }


        // All Payments
        $this->allPayments = Payment::query()
            ->selectRaw('SUM(amount*exchange_rate) as total_payment')
            ->selectRaw('SUM(CASE WHEN MONTH(created_at) = ? THEN amount*exchange_rate END) as payment_this_month', [$currentMonth])
            ->get();

        $this->profit_plan = Order::query()
            ->select('currency_id')
            ->selectRaw('(SUM(total) - SUM(net_price)) as total_payment')
            ->selectRaw('SUM(CASE WHEN MONTH(created_at) = ? THEN (total  - net_price) END) as payment_this_month', [$currentMonth])
            ->where('company_id', company()->id);

        if (in_array('admin', user_roles()) || in_array('demosuperadmin', user_roles())) {
            $this->profit_plan = $this->profit_plan->first();
        }else{
            $this->profit_plan = $this->profit_plan->where('added_by', user()->id)->first();
        }

        // All Travellers
        $this->allTravellers = Traveler::query()
            ->selectRaw('COUNT(travelers.id) as total_travellers')
            ->selectRaw('COUNT(CASE WHEN MONTH(travelers.created_at) = ? THEN 1 END) as travellers_this_month', [$currentMonth])
            ->join('applications', 'applications.id', '=', 'travelers.application_id')
            ->where('applications.company_id', company()->id);

        if (in_array('admin', user_roles()) || in_array('demosuperadmin', user_roles())) {
            $this->allTravellers = $this->allTravellers->first();
        }else{
            $this->allTravellers = $this->allTravellers->where('applications.agent_id', $leadAgent->id)->first();
        }

        $this->income = Order::query()
            ->select(['*',
                DB::raw('(SUM(total_paid) - SUM(net_price)) as profit')
            ])
            ->get();
        $this->leadSource = LeadSource::query()
            ->select(['*', DB::raw('COUNT(leads.id) as lead_count')])
            ->join('leads', 'leads.source_id', '=', 'lead_sources.id')
            ->where(['lead_sources.company_id' => company()->id])
            ->where(['leads.company_id' => company()->id])
            ->groupBy('lead_sources.id')
            ->get();
        $this->leads = Lead::query()
            ->selectRaw('lead_status.id, leads.company_id, lead_status.type, leads.status_id, COUNT(leads.id) as leads_count')
            ->join('lead_status', 'lead_status.id', '=', 'leads.status_id')
            ->where(['leads.company_id' => company()->id])
            ->groupBy('lead_status.type')
            ->get()
            ->pluck('leads.status_id', 'leads_count');
        $this->chart = $chart->build();
        $this->leadSourceChart = $leadSourceChart->build();
        return view('dashboard.dashboard', $this->data);
    }

    /**
     * @return array|Application|Factory|View|Response|mixed|void
     */
    public function index()
    {
        $this->isCheckScript();
        if (in_array('employee', user_roles())) {
            return $this->employeeDashboard();
        }

        if (in_array('client', user_roles())) {
            return $this->clientPanelDashboard();
        }
    }

    public function widget(Request $request, $dashboardType)
    {
        $data = $request->all();
        unset($data['_token']);
        DashboardWidget::where('status', 1)->where('dashboard_type', $dashboardType)->update(['status' => 0]);

        foreach ($data as $key => $widget) {
            DashboardWidget::where('widget_name', $key)->where('dashboard_type', $dashboardType)->update(['status' => 1]);
        }

        return Reply::success(__('messages.updateSuccess'));
    }

    public function checklist()
    {
        if (in_array('admin', user_roles())) {
            $this->isCheckScript();

            return view('dashboard.checklist', $this->data);
        }
    }

    /**
     * @return array|Response
     */
    public function memberDashboard()
    {
        abort_403(!in_array('employee', user_roles()));

        return $this->employeeDashboard();
    }

    public function advancedDashboard()
    {
        if (in_array('admin', user_roles()) || $this->sidebarUserPermissions['view_overview_dashboard'] == 4
            || $this->sidebarUserPermissions['view_project_dashboard'] == 4
            || $this->sidebarUserPermissions['view_client_dashboard'] == 4
            || $this->sidebarUserPermissions['view_hr_dashboard'] == 4
            || $this->sidebarUserPermissions['view_ticket_dashboard'] == 4
            || $this->sidebarUserPermissions['view_finance_dashboard'] == 4) {

            $tab = request('tab');

            switch ($tab) {
                case 'project':
                    $this->projectDashboard();
                    break;
                case 'client':
                    $this->clientDashboard();
                    break;
                case 'hr':
                    $this->hrDashboard();
                    break;
                case 'ticket':
                    $this->ticketDashboard();
                    break;
                case 'finance':
                    $this->financeDashboard();
                    break;
                default:
                    if (in_array('admin', user_roles()) || $this->sidebarUserPermissions['view_overview_dashboard'] == 4) {
                        $this->activeTab = $tab ?: 'overview';
                        $this->overviewDashboard();

                    } elseif ($this->sidebarUserPermissions['view_project_dashboard'] == 4) {
                        $this->activeTab = $tab ?: 'project';
                        $this->projectDashboard();

                    } elseif ($this->sidebarUserPermissions['view_client_dashboard'] == 4) {
                        $this->activeTab = $tab ?: 'client';
                        $this->clientDashboard();

                    } elseif ($this->sidebarUserPermissions['view_hr_dashboard'] == 4) {
                        $this->activeTab = $tab ?: 'hr';
                        $this->hrDashboard();

                    } elseif ($this->sidebarUserPermissions['view_finance_dashboard'] == 4) {
                        $this->activeTab = $tab ?: 'finance';
                        $this->ticketDashboard();

                    } else if ($this->sidebarUserPermissions['view_ticket_dashboard'] == 4) {
                        $this->activeTab = $tab ?: 'finance';
                        $this->financeDashboard();
                    }
                    break;
            }

            if (request()->ajax()) {
                $html = view($this->view, $this->data)->render();

                return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
            }

            if (!isset($this->activeTab)) {
                $this->activeTab = $tab ?: 'overview';
            }

            return view('dashboard.admin', $this->data);
        }
    }

    public function accountUnverified()
    {
        return view('dashboard.unverified', $this->data);
    }

    public function weekTimelog()
    {
        $now = now(company()->timezone);
        $attndcSetting = AttendanceSetting::first();
        $this->timelogDate = $timelogDate = Carbon::parse(request()->date);
        $this->weekStartDate = $now->copy()->startOfWeek($attndcSetting->week_start_from);
        $this->weekEndDate = $this->weekStartDate->copy()->addDays(7);
        $this->weekPeriod = CarbonPeriod::create($this->weekStartDate, $this->weekStartDate->copy()->addDays(6)); // Get All Dates from start to end date

        $this->dateWiseTimelogs = ProjectTimeLog::dateWiseTimelogs($timelogDate->toDateString(), user()->id);
        $this->dateWiseTimelogBreak = ProjectTimeLogBreak::dateWiseTimelogBreak($timelogDate->toDateString(), user()->id);

        $this->weekWiseTimelogs = ProjectTimeLog::weekWiseTimelogs($this->weekStartDate->copy()->toDateString(), $this->weekEndDate->copy()->toDateString(), user()->id);
        $this->weekWiseTimelogBreak = ProjectTimeLogBreak::weekWiseTimelogBreak($this->weekStartDate->toDateString(), $this->weekEndDate->toDateString(), user()->id);

        $html = view('dashboard.employee.week_timelog', $this->data)->render();

        return Reply::dataOnly(['html' => $html]);
    }

    public function privateCalendar()
    {
        if (request()->filter) {
            $employee_details = EmployeeDetails::where('user_id', user()->id)->first();
            $employee_details->calendar_view = (request()->filter != false) ? request()->filter : null;
            $employee_details->save();
            session()->forget('user');
        }

        $startDate = Carbon::parse(request('start'));
        $endDate = Carbon::parse(request('end'));

        // get calendar view current logined user
        $calendar_filter_array = explode(',', user()->employeeDetails->calendar_view);

        $eventData = array();

        if (!is_null(user()->permission('view_events')) && user()->permission('view_events') != 'none') {

            if (in_array('events', $calendar_filter_array)) {
                // Events
                $model = Event::with('attendee', 'attendee.user');

                $model->where(function ($query) {
                    $query->whereHas('attendee', function ($query) {
                        $query->where('user_id', user()->id);
                    });
                    $query->orWhere('added_by', user()->id);
                });

                $model->whereBetween('start_date_time', [$startDate->toDateString(), $endDate->toDateString()]);

                $events = $model->get();


                foreach ($events as $event) {
                    $eventData[] = [
                        'id' => $event->id,
                        'title' => ucfirst($event->event_name),
                        'start' => $event->start_date_time,
                        'end' => $event->end_date_time,
                        'event_type' => 'event',
                        'extendedProps' => ['bg_color' => $event->label_color, 'color' => '#fff', 'icon' => 'fa-calendar']
                    ];
                }
            }

        }

        if (!is_null(user()->permission('view_holiday')) && user()->permission('view_holiday') != 'none') {
            if (in_array('holiday', $calendar_filter_array)) {
                // holiday
                $holidays = Holiday::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])->get();

                foreach ($holidays as $holiday) {
                    $eventData[] = [
                        'id' => $holiday->id,
                        'title' => ucfirst($holiday->occassion),
                        'start' => $holiday->date,
                        'end' => $holiday->date,
                        'event_type' => 'holiday',
                        'extendedProps' => ['bg_color' => '#1d82f5', 'color' => '#fff', 'icon' => 'fa-star']
                    ];
                }
            }

        }

        if (!is_null(user()->permission('view_tasks')) && user()->permission('view_tasks') != 'none') {

            if (in_array('task', $calendar_filter_array)) {
                // tasks
                $completedTaskColumn = TaskboardColumn::completeColumn();
                $tasks = Task::with('boardColumn')
                    ->where('board_column_id', '<>', $completedTaskColumn->id)
                    ->whereHas('users', function ($query) {
                        $query->where('user_id', user()->id);
                    })
                    ->where(function ($q) use ($startDate, $endDate) {
                        $q->whereBetween(DB::raw('DATE(tasks.`due_date`)'), [$startDate->toDateString(), $endDate->toDateString()]);

                        $q->orWhereBetween(DB::raw('DATE(tasks.`start_date`)'), [$startDate->toDateString(), $endDate->toDateString()]);
                    })->get();

                foreach ($tasks as $task) {
                    $eventData[] = [
                        'id' => $task->id,
                        'title' => ucfirst($task->heading),
                        'start' => $task->start_date,
                        'end' => $task->due_date ?: $task->start_date,
                        'event_type' => 'task',
                        'extendedProps' => ['bg_color' => $task->boardColumn->label_color, 'color' => '#fff', 'icon' => 'fa-list']
                    ];
                }
            }
        }

        if (!is_null(user()->permission('view_tickets')) && user()->permission('view_tickets') != 'none') {

            if (in_array('tickets', $calendar_filter_array)) {
                // tickets
                $tickets = Ticket::where('user_id', user()->id)
                    ->whereBetween(DB::raw('DATE(tickets.`updated_at`)'), [$startDate->toDateTimeString(), $endDate->endOfDay()->toDateTimeString()])->get();

                foreach ($tickets as $key => $ticket) {
                    $eventData[] = [
                        'id' => $ticket->ticket_number,
                        'title' => ucfirst($ticket->subject),
                        'start' => $ticket->updated_at,
                        'end' => $ticket->updated_at,
                        'event_type' => 'ticket',
                        'extendedProps' => ['bg_color' => '#1d82f5', 'color' => '#fff', 'icon' => 'fa-ticket-alt']
                    ];
                }
            }

        }

        if (!is_null(user()->permission('view_leave')) && user()->permission('view_leave') != 'none') {

            if (in_array('leaves', $calendar_filter_array)) {
                // approved leaves of all emoloyees with employee name
                $leaves = Leave::join('leave_types', 'leave_types.id', 'leaves.leave_type_id')
                    ->where('leaves.status', 'approved')
                    ->select('leaves.id', 'leaves.leave_date', 'leaves.status', 'leave_types.type_name', 'leave_types.color', 'leaves.leave_date', 'leaves.duration', 'leaves.status', 'leaves.user_id')
                    ->with('user')
                    ->whereBetween(DB::raw('DATE(leaves.`leave_date`)'), [$startDate->toDateString(), $endDate->toDateString()])
                    ->get();

                foreach ($leaves as $leave) {
                    $duration = ($leave->duration == 'half day') ? '( ' . __('app.halfday') . ' )' : '';

                    $eventData[] = [
                        'id' => $leave->id,
                        'title' => $duration . ' ' . ucfirst($leave->user->name),
                        'start' => $leave->leave_date->toDateString(),
                        'end' => $leave->leave_date->toDateString(),
                        'event_type' => 'leave',
                        /** @phpstan-ignore-next-line */
                        'extendedProps' => ['name' => 'Leave : ' . ucfirst($leave->user->name), 'bg_color' => $leave->color, 'color' => '#fff', 'icon' => 'fa-plane-departure']
                    ];
                }
            }
        }

        return $eventData;
    }

}
