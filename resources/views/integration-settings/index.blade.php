@extends('layouts.app')

@push('styles')
    <style>
        .form_custom_label {
            justify-content: left;
        }

        .client {
            margin: auto;
        }

    </style>
@endpush

@section('content')

    <!-- SETTINGS START -->
    <div class="w-100 d-flex ">

        <x-setting-sidebar :activeMenu="$activeSettingMenu"/>

        <x-setting-card>
            <x-slot name="header">
                <div class="s-b-n-header" id="tabs">
                    <h2 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                        @lang($pageTitle)</h2>
                </div>
            </x-slot>

            <div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-4 ">
                <div class="d-flex flex-column w-tables rounded mt-3 bg-white table-responsive">

                    {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}

                </div>
            </div>

        </x-setting-card>

    </div>
    <!-- SETTINGS END -->
@endsection


@push('scripts')
    @include('sections.datatable_js')

    <script>
        const showTable = () => {
            window.LaravelDataTables["integrationcredential-table"].draw(false);
        }
        $(document).ready(function () {
            $('#integrationcredential-table').on('click', 'tbody tr', function () {
                console.log($(event.target).attr('id'));
                if (!$(event.target).is(':checkbox')) {
                    const id = $(this)[0].id;
                    var url = '{{ route("integration-settings.edit", ":id") }}'.replace(':id', id);
                    window.location.href = url;
                }

            });

            $('#integrationcredential-table').on('preXhr.dt', function (e, settings, data) {
                const dateRangePicker = $('#datatableRange').data('daterangepicker');
                let startDate = $('#datatableRange').val();

                let endDate;

                if (startDate == '') {
                    startDate = null;
                    endDate = null;
                } else {
                    startDate = dateRangePicker.startDate.format('{{ company()->moment_date_format }}');
                    endDate = dateRangePicker.endDate.format('{{ company()->moment_date_format }}');
                }

                const status = $('#status').val();
                const client = $('#client').val();
                const category_id = $('#filter_category_id').val();
                const sub_category_id = $('#filter_sub_category_id').val();
                const project_id = $('#project_id').val();
                const contract_type_id = $('#contract_type_id').val();
                const country_id = $('#country_id').val();
                const verification = $('#verification').val();
                const searchText = $('#search-text-field').val();
                data['startDate'] = startDate;
                data['endDate'] = endDate;
                data['status'] = status;
                data['client'] = client;
                data['category_id'] = category_id;
                data['sub_category_id'] = sub_category_id;
                data['project_id'] = project_id;
                data['contract_type_id'] = contract_type_id;
                data['country_id'] = country_id;
                data['verification'] = verification;
                data['searchText'] = searchText;
            });

        });


    </script>
@endpush
