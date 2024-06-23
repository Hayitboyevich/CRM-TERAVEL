<?php

namespace App\Charts;

use App\Models\Lead;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Arr;
use const _PHPStan_5473b6701\__;

class MonthlyUsersChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build()
    {

//        $statuses = LeadStatus::query()
//            ->whereIn('priority', [2, 3, 4, 5, 6, 7, 8])
//            ->get()
//            ->pluck('id')
//            ->toArray();
        $this->allSales = Lead::query()
            ->selectRaw('lead_agents.user_id, leads.company_id, leads.status_id, users.name as agent_name,
            COUNT(leads.id) as client_count')
            ->join('lead_agents', 'leads.agent_id', '=', 'lead_agents.id')
            ->join('users', 'lead_agents.user_id', '=', 'users.id')
            ->join('lead_status', 'lead_status.id', '=', 'leads.status_id')
            ->where(['leads.company_id' => company()->id])
            ->whereIn('lead_status.priority', [2, 3, 4, 5, 6, 7, 8])
            ->groupBy('leads.agent_id')
            ->orderBy('leads.agent_id', 'DESC')
            ->get()
            ->pluck('client_count', 'agent_name')
            ->toArray();
        $this->sales = Lead::query()
            ->selectRaw('lead_agents.user_id, leads.company_id, leads.status_id, users.name as agent_name,
            COUNT(leads.id) as client_count')
            ->join('lead_agents', 'leads.agent_id', '=', 'lead_agents.id')
            ->join('users', 'lead_agents.user_id', '=', 'users.id')
            ->join('lead_status', 'lead_status.id', '=', 'leads.status_id')
            ->where(['leads.company_id' => company()->id])
            ->whereIn('lead_status.priority', [2, 4, 5])
            ->groupBy('leads.agent_id')
            ->orderBy('leads.agent_id', 'DESC')
            ->get()
            ->pluck('client_count', 'agent_name')
            ->toArray();

        $this->documentGiven = Lead::query()
            ->selectRaw('lead_agents.user_id, leads.company_id, leads.status_id, COUNT(leads.id) as client_count, users.name as agent_name')
            ->join('lead_agents', 'leads.agent_id', '=', 'lead_agents.id')
            ->join('users', 'lead_agents.user_id', '=', 'users.id')
            ->join('lead_status', 'lead_status.id', '=', 'leads.status_id')
            ->where(['leads.company_id' => company()->id])
            ->whereIn('lead_status.priority', [7])
            ->groupBy('leads.agent_id')
            ->orderBy('leads.agent_id', 'DESC')
            ->get()
            ->pluck('client_count', 'agent_name')
            ->toArray();

        $this->paid = Lead::query()
            ->selectRaw('lead_agents.user_id, leads.company_id, leads.status_id, COUNT(leads.id) as client_count, users.name as agent_name')
            ->join('lead_agents', 'leads.agent_id', '=', 'lead_agents.id')
            ->join('users', 'lead_agents.user_id', '=', 'users.id')
            ->join('lead_status', 'lead_status.id', '=', 'leads.status_id')
            ->where(['leads.company_id' => company()->id])
            ->whereIn('lead_status.priority', [6])
            ->groupBy('leads.agent_id')
            ->orderBy('leads.agent_id', 'DESC')
            ->get()
            ->pluck('client_count', 'agent_name')
            ->toArray();

        $this->inProcess = Lead::query()
            ->selectRaw('lead_agents.user_id, leads.company_id, leads.status_id, COUNT(leads.id) as client_count, users.name as agent_name')
            ->join('lead_agents', 'leads.agent_id', '=', 'lead_agents.id')
            ->join('users', 'lead_agents.user_id', '=', 'users.id')
            ->join('lead_status', 'lead_status.id', '=', 'leads.status_id')
            ->where(['leads.company_id' => company()->id])
            ->whereIn('lead_status.priority', [3])
            ->groupBy('leads.agent_id')
            ->orderBy('leads.agent_id', 'DESC')
            ->get()
            ->pluck('client_count', 'agent_name')
            ->toArray();
        $this->canceled = Lead::query()
            ->selectRaw('lead_agents.user_id, leads.company_id, leads.status_id, COUNT(leads.id) as client_count, users.name as agent_name')
            ->join('lead_agents', 'leads.agent_id', '=', 'lead_agents.id')
            ->join('users', 'lead_agents.user_id', '=', 'users.id')
            ->join('lead_status', 'lead_status.id', '=', 'leads.status_id')
            ->where(['leads.company_id' => company()->id])
            ->whereIn('lead_status.priority', [8])
            ->groupBy('leads.agent_id')
            ->orderBy('leads.agent_id', 'DESC')
            ->get()
            ->pluck('client_count', 'agent_name')
            ->toArray();
        $agents = array_keys($this->allSales);

        $all = [];
        $documentGiven = [];
        $paid = [];
        $inProcess = [];
        $canceled = [];

        foreach ($this->allSales as $index => $sale) {
            $all[] = Arr::get($this->sales, $index);
            $documentGiven[] = Arr::get($this->documentGiven, $index);
            $paid[] = Arr::get($this->paid, $index);
            $inProcess[] = Arr::get($this->inProcess, $index);
            $canceled[] = Arr::get($this->canceled, $index);
        }
        $all = array_values($all);
        $documentGiven = array_values($documentGiven);
        $paid = array_values($paid);
        $inProcess = array_values($inProcess);
        $canceled = array_values($canceled);
        return $this->chart->barChart()
            ->setTitle(__('app.agentStats'))
            ->setSubtitle('')
            ->addData('Другой', $all)
            ->addData('Документ выдан', $documentGiven)
            ->addData('В процессе', $inProcess)
            ->addData('Отменено', $canceled)
            ->addData('Оплаченный', $paid)
            ->setXAxis($agents);
    }
}
