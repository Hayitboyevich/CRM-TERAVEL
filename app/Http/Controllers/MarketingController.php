<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use const _PHPStan_5473b6701\__;

class MarketingController extends AccountBaseController
{
    const YANDEX_OAUTH = 'y0_AgAAAABpJ9Y4AAujcgAAAAECwoPvAABgy2isModIP4vlxerPd-oW4-t-QA';

    public function index()
    {
        $this->pageTitle = __('app.metrica.title');

        $startDate = date('Y-m-d', strtotime('-30 day'));
        $endDate = date('Y-m-d'); // today's date

        if (request()->ajax()) {
            $startDate =  \Carbon\Carbon::parse(request()->startDate)->format('Y-m-d');
            $endDate =  \Carbon\Carbon::parse(request()->endDate)->format('Y-m-d');
        }

        $leadData = $this->stats('lead_created', $startDate, $endDate);
        $orderData = $this->stats('order_created', $startDate, $endDate);

        $leadData['labels'][] = __('app.metrica.orderCreated');
        $leadData['data'][] = $orderData['data'][0];

        if (request()->ajax()) {
            return response()->json($leadData);
        }

        $this->stats = $leadData;

        return view('marketing.lead-stats', $this->data);
    }

    public function stats($goalName, $startDate, $endDate)
    {
        $goalId = company()->metricaGoals->where('name', $goalName)->first()->goal_id ?? null;
        $counterId = company()->counter_id;

        if (!$goalId || !$counterId) {
            return redirect()->back()->with('error', 'Please set counter ID in company settings');
        }

        $metricsNames = [
            'ga:goal'.$goalId.'Abandons' => __('app.metrica.goalAbandons'),
            'ym:s:goal'.$goalId.'visits' => __('app.metrica.goalVisits'),
            'ym:s:goal'.$goalId.'reaches' => __('app.metrica.goalReaches'),
            'ym:s:users' => 'Users',
            'ym:s:visits' => 'Visits',
            'ym:s:pageviews' => 'Pageviews',
            'ym:s:avgVisitDurationSeconds' => 'Avg. visit duration',
            'ym:s:bounceRate' => 'Bounce rate',
            'ga:goal'.$goalId.'Completions' => 'Goal completions',
        ];

        $list_of_metrics = 'ym:s:goal'.$goalId.'reaches, ym:s:goal'.$goalId.'visits, ga:goal'.$goalId.'Abandons';

        $parameters = [
            'id' => $counterId,
            'metrics' => $list_of_metrics,
            'date1' => $startDate,
            'date2' => $endDate,
            'pretty' => 'true',
            'accuracy' => 'full',
        ];

        if ($goalName == 'lead_created' && request()->source != 'all') {
            $parameters['filters'] = "ym:s:UTMSource=='".request()->source."'";
        }

        $response =  Http::withHeaders([
            'Authorization' => 'OAuth ' . self::YANDEX_OAUTH
        ])->get('https://api-metrika.yandex.net/stat/v1/data', $parameters);

//        dd($response->json());


        $res_data = $response->json()['totals'];

        $labels = [];
        foreach (explode(',', $list_of_metrics) as $key => $metric) {
            $metric = trim($metric); // Trim spaces
            $labels[] = $metricsNames[$metric];
        }

        return $this->stats = [
            'labels' => array_values($labels),
            'data' => $res_data,
        ];
    }

}
