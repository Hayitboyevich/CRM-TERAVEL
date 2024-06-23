@extends('layouts.app')
@section('styles')
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="px-4 py-0 py-lg-3  border-top-0 admin-dashboard">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-3">
                        <a href="javascript:" id="totalClients">
                            <x-cards.widget :title="__('modules.dashboard.totalClients')"
                                            :value="$clients_count->total_clients . ' ( '.$clients_count->clients_this_month.' - в этом месяце)'"
                                            icon="users"/>
                        </a>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-3">
                        <a href="javascript:" id="totalClients">
                            <x-cards.widget :title="__('app.lead')"
                                            :value="$leads_count->total_leads . ' ( '.$leads_count->leads_this_month .' - в этом месяце)'"
                                            icon="users"/>
                        </a>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-3">
                        <a href="javascript:" id="totalClients">
                            <x-cards.widget :title="__('modules.dashboard.totalOrders')"
                                            :value="$orders_count->total_orders  . ' ( '.$orders_count->orders_this_month .' - в этом месяце)'"
                                            icon="users"/>
                        </a>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 mb-3">
                        <a href="javascript:" id="totalClients">
                            <x-cards.widget :title="__('modules.dashboard.totalProfit')"
                                            :value="currency_format($profit_plan->total_payment, $profit_plan->currency_id) . ' ( '.currency_format($profit_plan->payment_this_month, $profit_plan->currency_id) .' - в этом месяце)'"
                                            icon="users"/>
                        </a>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-3">
                        <a href="javascript:" id="totalClients">
                            <x-cards.widget :title="__('modules.dashboard.totalTravellers')"
                                            :value="$allTravellers->total_travellers  . ' ( '.$allTravellers->travellers_this_month .' - в этом месяце)'"
                                            icon="users"/>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                {{--                <ol class="list-group">--}}
                {{--                    @foreach($leadSource as $source)--}}
                {{--                        <li class="list-group-item">--}}
                {{--                            <span class="name">{{$source->type}}</span> ---}}
                {{--                            <span class="score ml-auto">1000</span>--}}
                {{--                        </li>--}}
                {{--                    @endforeach--}}
                {{--                </ol>--}}
                <div class=" bg-white rounded">
                    {!! $leadSourceChart->container() !!}
                </div>

            </div>


        </div>
        <div class=" bg-white rounded">
            {!! $chart->container() !!}
        </div>
    </div>
    <br>
    <br>

    <script src="{{ $chart->cdn() }}"></script>
    <script src="{{ $leadSourceChart->cdn() }}"></script>
    {{ $leadSourceChart->script() }}

    {{ $chart->script() }}
@endsection
