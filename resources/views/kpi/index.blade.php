@extends('layouts.app')
@push('datatable-styles')
    @include('sections.datatable_css')
@endpush

@push('styles')
    <!-- Drag and Drop CSS -->
    <link rel='stylesheet' href="{{ asset('vendor/css/dragula.css') }}" type='text/css'/>
    <link rel='stylesheet' href="{{ asset('vendor/css/drag.css') }}" type='text/css'/>
    <link rel="stylesheet" href="{{ asset('vendor/css/bootstrap-colorpicker.css') }}"/>
@endpush

@section('filter-section')

    <x-filters.filter-box>
        <!-- DATE START -->
        <div class="select-box d-flex pr-2 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('modules.client.addedOn')</p>
            <div class="select-status d-flex">
                <input type="text"
                       class="position-relative text-dark form-control border-0 p-2 text-left f-14 f-w-500 border-additional-grey"
                       id="datatableRange" placeholder="@lang('placeholders.dateRange')">
            </div>
        </div>
        <!-- DATE END -->

        <!-- SEARCH BY TASK START -->
        <div class="task-search d-flex  py-1 px-lg-3 px-0 border-right-grey align-items-center">
            <form class="w-100 mr-1 mr-lg-0 mr-md-1 ml-md-1 ml-0 ml-lg-0">
                <div class="input-group bg-grey rounded">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-0 bg-additional-grey">
                            <i class="fa fa-search f-13 text-dark-grey"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control f-14 p-1 border-additional-grey" id="search-text-field"
                           placeholder="@lang('app.startTyping')">
                </div>
            </form>
        </div>
        <!-- SEARCH BY TASK END -->

        <!-- RESET START -->
        <div class="select-box d-flex py-1 px-lg-2 px-md-2 px-0">
            <x-forms.button-secondary class="btn-xs d-none" id="reset-filters" icon="times-circle">
                @lang('app.clearFilters')
            </x-forms.button-secondary>
        </div>

    </x-filters.filter-box>

@endsection
@section('content')

    <div class="content-wrapper">
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <a href="{{ route('projects.index'). '?projects=all' }}">
                    <x-cards.widget title="По срокам"
                                    :value="'('.$criteria[1]['percent'] .'%)  ' . $criteria[1]['expected_amount']"
                                    icon="layer-group">
                    </x-cards.widget>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <a href="{{ route('projects.index'). '?projects=all' }}">
                    <x-cards.widget title="По доход"
                                    :value="'('.$criteria[2]['percent'] .'%)  $' . $criteria[2]['expected_amount']"
                                    icon="layer-group">
                    </x-cards.widget>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <a href="{{ route('projects.index'). '?projects=all' }}">
                    <x-cards.widget title="Постоянный клиент"
                                    :value="'('.$criteria[3]['percent'] .'%)' . $criteria[3]['expected_amount']"
                                    icon="layer-group">
                    </x-cards.widget>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <a href="{{ route('projects.index'). '?projects=all' }}">
                    <x-cards.widget title="Потенциальные клиенты"
                                    :value="'('.$criteria[0]['percent'] .'%)' . $criteria[0]['expected_amount']"
                                    icon="layer-group">
                    </x-cards.widget>
                </a>
            </div>

        </div>
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white table-responsive">

            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}

        </div>
    </div>

@endsection

@push('scripts')
    @include('sections.datatable_js')
    <script src="{{ asset('vendor/jquery/dragula.js') }}"></script>
    <script>

        const showTable = () => {
            window.LaravelDataTables["kpi-table"].draw(false);
        }

        $('body').on('click', '.verify-user', function () {
            const id = $(this).data('user-id');
            Swal.fire({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.approvalWarning')",
                icon: 'warning',
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "@lang('app.approve')",
                cancelButtonText: "@lang('app.cancel')",
                customClass: {
                    confirmButton: 'btn btn-primary mr-3',
                    cancelButton: 'btn btn-secondary'
                },
                showClass: {
                    popup: 'swal2-noanimation',
                    backdrop: 'swal2-noanimation'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('clients.approve', ':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {
                            '_token': token
                        },
                        success: function (response) {
                            if (response.status == "success") {
                                showTable();
                            }
                        }
                    });
                }
            });
        });

        $('body').on('click', '.delete-table-row', function () {
            const id = $(this).data('user-id');
            var url = "{{ route('clients.finance_count', ':id') }}";
            url = url.replace(':id', id)
            var token = "{{ csrf_token() }}";
            $.easyAjax({
                type: 'GET',
                url: url,
                data: {
                    '_token': token
                },
                success: function (response) {
                    if (response.status == "success") {
                        Swal.fire({
                            title: "@lang('messages.sweetAlertTitle')",
                            text: response.deleteClient + "@lang('messages.recoverRecord')",
                            icon: 'warning',
                            showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonText: "@lang('messages.confirmDelete')",
                            cancelButtonText: "@lang('app.cancel')",
                            customClass: {
                                confirmButton: 'btn btn-primary mr-3',
                                cancelButton: 'btn btn-secondary'
                            },
                            showClass: {
                                popup: 'swal2-noanimation',
                                backdrop: 'swal2-noanimation'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                var url = "{{ route('clients.destroy', ':id') }}";
                                url = url.replace(':id', id);
                                $.easyAjax({
                                    type: 'POST',
                                    url: url,
                                    data: {
                                        '_token': token,
                                        '_method': 'DELETE'
                                    },
                                    success: function (response) {
                                        if (response.status == "success") {
                                            showTable();
                                        }
                                    }
                                });
                            }
                        });
                    }
                }
            });
        });

        const applyQuickAction = () => {
            var rowdIds = $("#kpi-table input:checkbox:checked").map(function () {
                return $(this).val();
            }).get();

            const url = "{{ route('clients.apply_quick_action') }}?row_ids=" + rowdIds;

            $.easyAjax({
                url: url,
                container: '#quick-action-form',
                type: "POST",
                disableButton: true,
                buttonSelector: "#quick-action-apply",
                data: $('#quick-action-form').serialize(),
                success: function (response) {
                    if (response.status == 'success') {
                        showTable();
                        resetActionButtons();
                        deSelectAll();
                        $('#quick-action-form').hide();
                    }
                }
            })
        };

        $('.show-unverified').click(function () {
            $('#verification').val('no');

            $('#verification').selectpicker('refresh');
            $(this).addClass('btn-active')
            $('#reset-filters').removeClass('d-none');
            showTable();
        });

        $(document).ready(function () {
            showTable();
        });

        $('.btn-group .btn-secondary').click(function () {
            $('.btn-secondary').removeClass('btn-active');
            $(this).addClass('btn-active');
        });
    </script>

@endpush
