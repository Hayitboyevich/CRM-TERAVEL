<?php

namespace Modules\KPI\Services\KPIItems;

use App\Models\Lead;
use Illuminate\Support\Facades\DB;
use Modules\KPI\Services\KPIAbstract;
use Modules\KPI\Services\KPIInterface;

class LeadCalculateKPIService extends KPIAbstract implements KPIInterface
{
    public static function calculate($agent_user_id)
    {
        $kpi = (new self)->getKPIItem($agent_user_id);
        $total_amount = self::getTotalAmount($agent_user_id);
        $percent = $kpi['percent'];
        $kpi_amount = $kpi['expected_amount'];

        return $percent / 100 * ($total_amount / $kpi_amount);
    }

    public static function getTotalAmount(int $userId)
    {
        $leads = Lead::query()
            ->select(['*', DB::raw('COUNT(leads.id) as totalClient')])
            ->join('lead_agents', 'lead_agents.id', '=', 'leads.agent_id')
            ->join('lead_status', 'lead_status.id', '=', 'leads.status_id')
            ->where(['lead_status.priority' => 7])
            ->where(['lead_agents.user_id' => $userId])
            ->get()
            ->pluck('totalClient')
            ->toArray();
        return $leads[0];
    }

    public function methodName()
    {
        return 'LeadCalculateKPIService';
    }
}