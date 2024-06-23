@extends('layouts.app')

@push('styles')
    <script src="{{ asset('vendor/jquery/frappe-charts.min.iife.js') }}"></script>
    <script src="{{ asset('vendor/jquery/Chart.min.js') }}"></script>
@endpush

@section('content')
    <div class="px-4 py-0 py-lg-4  border-top-0 admin-dashboard">
        <div class="col-xl-12  col mb-4">
            <div class="row">
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </div>
@endsection
@php
    $ec = count($employees);
    $employees = json_encode($employees->pluck('name')->toArray());
    $employees = htmlspecialchars_decode($employees);
    $result= [];
    foreach ($sales as $sale){
        $result[] = $sale?->count() ?? 0;
    }
    $required = ($ec - count($result));
    for ($i=0;$i<$required;$i++){
        $result[] = 0;
    }
    $sales = json_encode($result);
    $sales = htmlspecialchars_decode($sales);
@endphp
@push('scripts')
    <script>
        //bar
        var ctxB = document.getElementById("barChart").getContext('2d');
        var myBarChart = new Chart(ctxB, {
            type: 'bar',
            data: {
                labels: <?php echo $employees ?>,
                datasets: [{
                    label: '# Sales',
                    data: <?php echo $sales ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
@endpush