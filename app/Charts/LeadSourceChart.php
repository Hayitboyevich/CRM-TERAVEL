<?php

namespace App\Charts;

use App\Models\Lead;
use ArielMejiaDev\LarapexCharts\DonutChart;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeadSourceChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): DonutChart
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $leads = Lead::query()
            ->select(['*', DB::raw('COUNT(leads.id) as qt')])
            ->leftJoin('lead_sources as ls', 'ls.id', '=', 'leads.source_id')
//            ->whereYear('leads.created_at', $currentYear)
//            ->whereMonth('leads.created_at', $currentMonth)
            ->where('leads.company_id', company()->id)
            ->groupBy('leads.source_id')
            ->pluck('qt', 'ls.type')
            ->toArray();

        $names = array_keys($leads);
        $values = array_values($leads);
        if (!empty($names) && $names[0] == '') {
            $names[0] = 'Неизвестный';

        }
        return $this->chart->donutChart()
            ->setTitle(__('app.leadFrom'))
//            ->setSubtitle('Season ' . date('M', strtotime(now())))
            ->addData($values)
            ->setLabels($names);
    }
}
