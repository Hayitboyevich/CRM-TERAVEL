@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatable_css')
@endpush
@section('filter-section')

    <x-filters.filter-box>
        <!-- DATE START -->
        <div class="select-box d-flex pr-2 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.duration')</p>
            <div class="select-status d-flex">
                <input type="text"
                       class="position-relative text-dark form-control border-0 p-2 text-left f-14 f-w-500 border-additional-grey"
                       id="datatableRange" placeholder="@lang('placeholders.dateRange')">
            </div>
        </div>
        <!-- DATE END -->

        <x-filters.more-filter-box>

            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('app.status')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" name="source" id="source" data-live-search="true"
                                data-size="8">
                            <option value="all" data-content="@lang('app.all')">@lang('app.all')</option>

                            <option value="instagram" data-content="<i class='fa fa-circle mr-2 text-red'></i> Instagram ">Instagram</option>

                            <option value="telegram" data-content="<i class='fa fa-circle mr-2 text-info'></i> Telegram ">Telegram</option>

                        </select>
                    </div>
                </div>
            </div>

        </x-filters.more-filter-box>
        <!-- MORE FILTERS END -->

    </x-filters.filter-box>

@endsection

@section('content')

    <div style="width: 80%; margin: auto;">
        <canvas id="barChart"></canvas>
    </div>

@endsection

@push('scripts')
    <script src="{{asset('vendor/jquery/chart.js')}}"></script>
    <script src="{{ asset('vendor/jquery/daterangepicker.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            var datatableRange = $('#datatableRange');
            datatableRange.on('apply.daterangepicker', function (ev, picker) {
                var startDate = picker.startDate.format();
                var endDate = picker.endDate.format();

                // Prepare data for AJAX request
                var data = {
                    startDate: startDate,
                    endDate: endDate,
                    source: $('#source').val(),
                };

                // Send AJAX POST request to lead.stats route
                $.ajax({
                    type: 'GET',
                    url: "{{route('marketing.index')}}",
                    data: data,

                    success: function (response) {
                        // Update the chart data
                        myChart.data.datasets[0].data = response.data;

                        // Update the chart labels
                        myChart.data.labels = response.labels;

                        // Update the chart
                        myChart.update();
                    },
                    error: function (xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });

            });

            $('#source').on('change', function () {
                var datatableRange = $('#datatableRange');
                var startDate = datatableRange.data('daterangepicker').startDate.format();
                var endDate = datatableRange.data('daterangepicker').endDate.format();

                console.log(startDate, endDate);
                var source = $(this).val();

                // Prepare data for AJAX request
                var data = {
                    source: source,
                    startDate: startDate,
                    endDate: endDate,
                };

                // Send AJAX POST request to lead.stats route
                $.ajax({
                    type: 'GET',
                    url: "{{route('marketing.index')}}",
                    data: data,

                    success: function (response) {
                        // Update the chart data
                        myChart.data.datasets[0].data = response.data;

                        // Update the chart labels
                        myChart.data.labels = response.labels;

                        // Update the chart
                        myChart.update();
                    },
                    error: function (xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            });
        });

        $(function () {
            var start = moment().subtract(89, 'days');
            var end = moment();

            $('#datatableRange').daterangepicker({
                autoUpdateInput: false,
                locale: daterangeLocale,
                linkedCalendars: false,
                startDate: start,
                endDate: end,
                showDropdowns: true,
                ranges: daterangeConfig
            }, cb);

        });

        var ctx = document.getElementById('barChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($stats['labels']),
                datasets: [{
                    label: 'Data',
                    data: @json($stats['data']),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    </script>
@endpush

