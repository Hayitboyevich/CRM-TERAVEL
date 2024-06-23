<?php

namespace Modules\KPI\Services\KPIItems;

use App\Models\LeadLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\KPI\Services\KPIAbstract;
use Modules\KPI\Services\KPIInterface;

class DeadlineMetricKPIService extends KPIAbstract implements KPIInterface
{

    public static function calculate(int $agent_user_id): float
    {
        $kpi = (new self)->getKPIItem($agent_user_id);
        $total_amount = self::getDeadlines($agent_user_id);
        $percent = (int)$kpi['percent'];
        $kpi_amount = $kpi['expected_amount'];
        return $percent / 100 * ($total_amount / $kpi_amount);
    }

    public static function getDeadlines($user_id): ?int
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $startDate = Carbon::create($currentYear, $currentMonth, 1)->startOfMonth();
        $endDate = Carbon::create($currentYear, $currentMonth, 1)->endOfMonth();

        $t = LeadLog::query()
            ->select(['*', DB::raw('COUNT(lead_logs.lead_id) as qt')])
            ->join('leads', 'leads.id', '=', 'lead_logs.lead_id')
            ->join('lead_agents', 'lead_agents.id', '=', 'leads.agent_id')
            ->where(['lead_agents.user_id' => $user_id])
            ->where('lead_logs.created_at', '>=', $startDate)
            ->where('lead_logs.created_at', '<=', $endDate)
            ->get()
//            ->groupBy('lead_agents.user_id')
            ->pluck('qt')
            ->toArray();
        return $t[0] ?? 0;
    }

    public function methodName(): string
    {
        return 'DeadlineMetricKPIService';
    }


}