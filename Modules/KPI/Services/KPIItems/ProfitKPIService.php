<?php

namespace Modules\KPI\Services\KPIItems;

use App\Models\Lead;
use Illuminate\Support\Facades\DB;
use Modules\KPI\Services\KPIAbstract;
use Modules\KPI\Services\KPIInterface;

class ProfitKPIService extends KPIAbstract implements KPIInterface
{
    public static function calculate(int $agent_user_id)
    {
        $kpi = (new self)->getKPIItem($agent_user_id);
        $total_amount = self::getTotalAmount($agent_user_id);
        $percent = $kpi['percent'];

        $kpi_amount = $kpi['expected_amount'];
        return $percent / 100 * ($total_amount / $kpi_amount);
    }

    public static function getTotalAmount(int $userId): float
    {
        $leads = Lead::query()
            ->select(['*',
                DB::raw('SUM(leads.value) as totalPrice'),
                DB::raw('SUM(orders.net_price) as neatPrice'),
                DB::raw('(SUM(leads.value) - SUM(orders.net_price)) as qt')
            ])
            ->join('lead_status', 'lead_status.id', '=', 'leads.status_id')
            ->join('lead_agents', 'lead_agents.id', '=', 'leads.agent_id')
            ->join('orders', 'orders.lead_id', '=', 'orders.id')
            ->where(['lead_agents.user_id' => $userId])
            ->whereNotIn('lead_status.priority', [1, 2, 3, 4, 8])
            ->groupBy('agent_id')
            ->get()
            ->pluck('qt');

        return $leads[0] ?? 0;
    }

    public function methodName()
    {
        return 'ProfitKPIService';
    }
}