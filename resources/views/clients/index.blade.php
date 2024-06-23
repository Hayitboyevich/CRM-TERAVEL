@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatable_css')
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

        <!-- CLIENT START -->
        <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.client')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="client" id="client" data-live-search="true"
                        data-size="8">
                    <option value="all">@lang('app.all')</option>
                    @foreach ($clients as $client)
                        <x-user-option :user="$client"/>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- CLIENT END -->

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
        <!-- RESET END -->

        <!-- MORE FILTERS START -->
        <x-filters.more-filter-box>

            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('app.status')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" data-container="body" name="status" id="status">
                            <option value="all">@lang('app.all')</option>
                            <option value="active">@lang('app.active')</option>
                            <option value="deactive">@lang('app.inactive')</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('app.clientType')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" data-container="body" name="client_type"
                                id="client_type">
                            <option value="all">@lang('app.all')</option>
                            <option value="client">@lang('app.client')</option>
                            <option value="tourist">@lang('app.tourist')</option>
                        </select>
                    </div>
                </div>
            </div>

            <div>-------------------------------------------------
                <h5 align="center">@lang('app.byInterests')</h5>
            </div>

            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('app.country')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" id="country_id" data-live-search="true"
                                data-container="body" data-size="8">
                            <option value="all">@lang('app.all')</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}"
                                        data-content="<span class='flag-icon flag-icon-{{ strtolower($country->iso2) }} flag-icon-squared'></span> {{ $country->name }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('app.currency')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select id="currency_id"
                                class="select-picker form-control" data-live-search="true"
                                name="currency_name"
                                data-container="body" data-size="8">
                            <option value="all">@lang('app.all')</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->currency_code }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>

            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('app.date')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <div class="select-box d-flex pr-2 border-right-grey border-right-grey-sm-0">
                            <div class="select-status d-flex">
                                <input type="text" name="interest_daterange" id="interest_date" value="" placeholder="@lang('placeholders.dateRange')"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div>-------------------------------------------------
                <h5 align="center">@lang('app.byOrder')</h5>
            </div>
            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('app.country')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" id="order_country_id" data-live-search="true"
                                data-container="body" data-size="8">
                            <option value="all">@lang('app.all')</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}"
                                        data-content="<span class='flag-icon flag-icon-{{ strtolower($country->iso2) }} flag-icon-squared'></span> {{ $country->name }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('app.currency')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select id="order_currency_id"
                                class="select-picker form-control" data-live-search="true"
                                name="order_currency_id"
                                data-container="body" data-size="8">
                            <option value="all">@lang('app.all')</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->currency_code }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
            <div class="more-filter-items">
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <x-forms.text class="select-picker form-control" :fieldId="'order_price'"
                                      :fieldName="'order_price'" :fieldLabel="__('app.price')"
                        />
                    </div>
                </div>
            </div>

            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('app.date')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <div class="select-box d-flex pr-2 border-right-grey border-right-grey-sm-0">
                            <div class="select-status d-flex">
                                <input type="text" name="order_daterange" id="order_date" value="" placeholder="@lang('placeholders.dateRange')"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </x-filters.more-filter-box>
        <!-- MORE FILTERS END -->
    </x-filters.filter-box>

@endsection

@section('content')

    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        <!-- Add Task Export Buttons Start -->
        <div class="d-block d-lg-flex d-md-flex justify-content-between action-bar dd">

            <div id="table-actions" class="flex-grow-1 align-items-center">
                @if ($addClientPermission == 'all' || $addClientPermission == 'added' || $addClientPermission == 'both')
                    <x-forms.link-primary :link="route('clients.create')"
                                          class="mr-3 openRightModal float-left mb-2 mb-lg-0 mb-md-0" icon="plus">
                        @lang('app.add')
                        @lang('app.client')
                    </x-forms.link-primary>
                @endif

                @if ($addClientPermission == 'all' || $addClientPermission == 'added' || $addClientPermission == 'both')
                    <x-forms.link-secondary :link="route('clients.import')"
                                            class="mr-3 float-left mb-2 mb-lg-0 mb-md-0 d-sm-bloc" icon="file-upload">
                        @lang('app.importExcel')
                    </x-forms.link-secondary>
                @endif
            </div>

            <x-datatable.actions>
                <div class="select-status mr-3">
                    <select name="action_type" class="form-control select-picker" id="quick-action-type" disabled>
                        <option value="">@lang('app.selectAction')</option>
                        <option value="change-status">@lang('modules.tasks.changeStatus')</option>
                        <option value="delete">@lang('app.delete')</option>
                    </select>
                </div>
                <div class="select-status mr-3 d-none quick-action-field" id="change-status-action">
                    <select name="status" class="form-control select-picker">
                        <option value="deactive">@lang('app.inactive')</option>
                        <option value="active">@lang('app.active')</option>
                    </select>
                </div>
            </x-datatable.actions>


            <div class="btn-group ml-0 ml-lg-3 ml-md-3" role="group">
                <a href="{{ route('clients.index') }}" class="btn btn-secondary f-14 btn-active show-clients"
                   data-toggle="tooltip"
                   data-original-title="@lang('app.menu.clients')"><i class="side-icon bi bi-list-ul"></i></a>

                <a href="javascript:" class="btn btn-secondary f-14 show-unverified" data-toggle="tooltip"
                   data-original-title="@lang('modules.dashboard.verificationPending')"><i
                        class="side-icon bi bi-person-x"></i></a>
            </div>

        </div>
        <!-- Add Task Export Buttons End -->

        <!-- Task Box Start -->
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white table-responsive">

            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}

        </div>
        <!-- Task Box End -->
    </div>
    <!-- CONTENT WRAPPER END -->

@endsection

@push('scripts')
    @include('sections.datatable_js')
    <script>
        $(function () {
            // Initialize Date Range Picker for 'interest_date'
            $('#interest_date').daterangepicker({
                autoUpdateInput: false,
                locale: daterangeLocale,
                linkedCalendars: true,
                autoApply: false,
            }, function (start, end) {
                updateInterestDate(start, end);
            });

            // Event handler for applying date range on 'interest_date'
            $('#interest_date').on('apply.daterangepicker', function (event, picker) {
                updateInterestDate(picker.startDate, picker.endDate);
                $('#interest_date').val(picker.startDate.format('{{ companyOrGlobalSetting()->moment_date_format }}') +
                    '/' + picker.endDate.format('{{ companyOrGlobalSetting()->moment_date_format }}'));
                showTable();
            });

            // Function to update 'interest_date' input value
            function updateInterestDate(start, end) {
                $('#interest_date').val(start.format('{{ companyOrGlobalSetting()->moment_date_format }}') +
                    '/' + end.format('{{ companyOrGlobalSetting()->moment_date_format }}'));
            }

            // Initialize Date Range Picker for 'order_date'
            $('#order_date').daterangepicker({
                autoUpdateInput: false,
                locale: daterangeLocale,
                linkedCalendars: false,
                autoApply: false,
            }, function (start, end) {
                updateOrderDate(start, end);
            });

            // Event handler for applying date range on 'order_date'
            $('#order_date').on('apply.daterangepicker', function (event, picker) {
                updateOrderDate(picker.startDate, picker.endDate);
                $('#order_date').val(picker.startDate.format('{{ companyOrGlobalSetting()->moment_date_format }}') +
                    '/' + picker.endDate.format('{{ companyOrGlobalSetting()->moment_date_format }}'));
                showTable();
            });

            // Function to update 'order_date' input value
            function updateOrderDate(start, end) {
                $('#order_date').val(start.format('{{ companyOrGlobalSetting()->moment_date_format }}') +
                    '/' + end.format('{{ companyOrGlobalSetting()->moment_date_format }}'));
            }
        });


        $('#clients-table').on('preXhr.dt', function (e, settings, data) {

            const dateRangePicker = $('#datatableRange').data('daterangepicker');
            const interest_date = $('#interest_date').val();
            const order_date = $('#order_date').val();
            let startDate = $('#datatableRange').val();
            let endDate;

            if (startDate == '') {
                startDate = null;
                endDate = null;
            } else {
                startDate = dateRangePicker.startDate.format('{{ company()->moment_date_format }}');
                endDate = dateRangePicker.endDate.format('{{ company()->moment_date_format }}');
            }

            if (interest_date != null) {
                var interest_date_splitted = interest_date.split('/');
                if (interest_date_splitted.length === 2) {
                    var interestStartDate = moment(interest_date_splitted[0].trim(), "DD-MM-YYYY").format("yyyy-MM-DD");
                    var interestEndDate = moment(interest_date_splitted[1].trim(), "DD-MM-YYYY").format("yyyy-MM-DD");
                }
            }

            if (order_date != null) {
                var order_date_splitted = order_date.split('/');
                if (order_date_splitted.length === 2) {
                    var orderStartDate = moment(order_date_splitted[0].trim(), "DD-MM-YYYY").format("yyyy-MM-DD");
                    var orderEndDate = moment(order_date_splitted[1].trim(), "DD-MM-YYYY").format("yyyy-MM-DD");
                }
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
            const currency_id = $('#currency_id').val();
            const price = $('#price').val();
            const order_currency_id = $('#order_currency_id').val();
            const order_price = $('#order_price').val();
            const order_country_id = $('#order_country_id').val();
            const client_type = $('#client_type').val();

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
            data['currency_id'] = currency_id;
            data['price'] = price;
            data['order_currency_id'] = order_currency_id;
            data['order_price'] = order_price;
            data['order_country_id'] = order_country_id;
            data['client_type'] = client_type;
            data['interestStartDate'] = interestStartDate;
            data['interestEndDate'] = interestEndDate;
            data['orderStartDate'] = orderStartDate;
            data['orderEndDate'] = orderEndDate;

        });
        const showTable = () => {
            window.LaravelDataTables["clients-table"].draw(false);
        }

        $('#client, #status, #filter_category_id, #filter_sub_category_id, #project_id, #contract_type_id, #country_id, #verification, #currency_id, #price, #order_currency_id, #order_price, #order_country_id, #client_type, #interest_date, #order_date')
            .on('change keyup', function () {
                if ($('#status').val() !== "all") {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#client').val() !== "all") {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#filter_category_id').val() !== "all") {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#filter_sub_category_id').val() !== "all") {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#project_id').val() !== "all") {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#contract_type_id').val() !== "all") {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#country_id').val() !== "all") {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#verification').val() != 'all') {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#currency_id').val() != 'all') {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#price').val() != 'null') {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#order_currency_id').val() != 'all') {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#order_price').val() != 'null') {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#order_country_id').val() != 'null') {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#client_type').val() != 'null') {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#interest_date').val() != 'null') {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#order_date').val() != 'null') {
                    $('#reset-filters').removeClass('d-none');
                } else {
                    $('#reset-filters').addClass('d-none');
                }

                showTable();
            });

        $('#search-text-field').on('keyup', function () {
            if ($('#search-text-field').val() != "") {
                $('#reset-filters').removeClass('d-none');
                showTable();
            }
        });

        $('#reset-filters,#reset-filters-2').click(function () {
            $('#filter-form')[0].reset();
            $('.filter-box .select-picker').selectpicker("refresh");
            $('.show-unverified').removeClass("btn-active");
            $('.show-clients').addClass("btn-active");
            $('#reset-filters').addClass('d-none');
            showTable();
        });


        $('#quick-action-type').change(function () {
            const actionValue = $(this).val();
            if (actionValue !== '') {
                $('#quick-action-apply').removeAttr('disabled');

                if (actionValue === 'change-status') {
                    $('.quick-action-field').addClass('d-none');
                    $('#change-status-action').removeClass('d-none');
                } else {
                    $('.quick-action-field').addClass('d-none');
                }
            } else {
                $('#quick-action-apply').attr('disabled', true);
                $('.quick-action-field').addClass('d-none');
            }
        });

        $('#quick-action-apply').click(function () {
            const actionValue = $('#quick-action-type').val();
            if (actionValue == 'delete') {
                Swal.fire({
                    title: "@lang('messages.sweetAlertTitle')",
                    text: "@lang('messages.recoverRecord')",
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
                        applyQuickAction();
                    }
                });

            } else {
                applyQuickAction();
            }
        });

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
            var rowdIds = $("#clients-table input:checkbox:checked").map(function () {
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
            @if (!is_null(request('start')) && !is_null(request('end')))
            $('#datatableRange').val('{{ request('start') }}' +
                ' @lang("app.to") ' + '{{ request('end') }}');
            $('#datatableRange').data('daterangepicker').setStartDate("{{ request('start') }}");
            $('#datatableRange').data('daterangepicker').setEndDate("{{ request('end') }}");
            showTable();
            @endif
        });


        $('.btn-group .btn-secondary').click(function () {
            $('.btn-secondary').removeClass('btn-active');
            $(this).addClass('btn-active');
        });


    </script>
@endpush
