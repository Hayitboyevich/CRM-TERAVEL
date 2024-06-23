@php
    //    $addProjectCategoryPermission = user()->permission('manage_project_category');
    //    $addEmployeePermission = user()->permission('add_employees');
    //    $addProjectFilePermission = user()->permission('add_project_files');
    //    $addPublicProjectPermission = user()->permission('create_public_project');
    //    $addProjectMemberPermission = user()->permission('add_project_members');
    //    $addProjectNotePermission = user()->permission('add_project_note');

@endphp

<link rel="stylesheet" href="{{ asset('vendor/css/dropzone.min.css') }}">

<div class="row">
    <div class="col-sm-12">
        <x-form id="save-project-data-form">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('app.integration') @lang('app.details')</h4>
                <input hidden name="user_id" value="{{$client->id}}" id="user_id">

                <input hidden name="from_city_name" id="from_city_name">
                <input hidden name="to_city_name" id="to_city_name">
                <input hidden name="hotel_name" id="hotel_name">
                <input hidden name="category_name" id="category_name">
                <input hidden name="tour_name" id="tour_name">

                <div class="row p-20">

                    <div class="col-md-4">
                        <x-forms.label class="my-3" fieldId="from_city_id"
                                       :fieldLabel="__('modules.integrations.fromCity')">
                        </x-forms.label>

                        <x-forms.input-group>
                            <select required class="form-control select-picker" name="from_city_id" id="from_city_id"
                                    data-live-search="true">
                                <option value="">--</option>
                                @foreach ($cities as $city)
                                    <option
                                            @if($city['name'] == 'Ташкент')
                                                selected
                                            @endif
                                            value="{{ $city["id"] }}">
                                        {{ mb_ucwords($city["name"]) }}
                                    </option>
                                @endforeach
                            </select>
                        </x-forms.input-group>
                    </div>

                    <div class="col-md-4">
                        <x-forms.label class="my-3" fieldId="to_country_id"
                                       :fieldLabel="__('modules.integrations.toCountry')">
                        </x-forms.label>

                        <x-forms.input-group>
                            <select required class="form-control select-picker" name="to_country_id" id="to_country_id"
                                    data-live-search="true">
                                <option value="">--</option>

                            </select>
                        </x-forms.input-group>
                    </div>
                    <div class="col-md-4">
                        <x-forms.label class="my-3" fieldId="tour_id"
                                       :fieldLabel="__('modules.integrations.tour')">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select required class="form-control select-picker" name="tour_id" id="tour_id"
                                    data-live-search="true">
                                <option value="">--</option>
                            </select>
                        </x-forms.input-group>
                    </div>
                    <div class="col-md-4">
                        <x-forms.label required class="my-3" fieldId="package_id"
                                       :fieldLabel="__('modules.integrations.packageId')">
                        </x-forms.label>

                        <x-forms.input-group>
                            <select class="form-control select-picker" name="package_id" id="package_id"
                                    data-live-search="true">
                                <option value="">--</option>
                            </select>
                        </x-forms.input-group>
                    </div>

                    <div class="col-md-4">
                        <x-forms.label class="my-3" fieldId="to_city_id"
                                       :fieldLabel="__('modules.integrations.toCity')">
                        </x-forms.label>

                        <x-forms.input-group>
                            <select required class="form-control select-picker" name="to_city_id" id="to_city_id"
                                    data-live-search="true">
                                <option value="">--</option>
                                @foreach ($cities as $city)
                                    <option
                                            @if($city['name'] == 'Ташкент')
                                                selected
                                            @endif
                                            value="{{ $city["id"] }}">
                                        {{ mb_ucwords($city["name"]) }}
                                    </option>
                                @endforeach
                            </select>
                        </x-forms.input-group>
                    </div>
                    <div class="col-md-4">
                        <x-forms.label class="my-3" fieldId="checkin_begin"
                                       :fieldLabel="__('modules.integrations.checkin_from')">
                        </x-forms.label>
                        <div class="input-group">
                            <input type="text" id="checkin_begin" name="checkin_begin"
                                   class="px-6 position-relative text-dark font-weight-normal form-control height-35 rounded p-0 text-left f-15"
                                   placeholder="@lang('placeholders.date')">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <x-forms.label class="my-3" fieldId="checkin_end"
                                       :fieldLabel="__('modules.integrations.checkin_to')">
                        </x-forms.label>
                        <div class="input-group">
                            <input type="text" id="checkin_end" name="checkin_end"
                                   class="px-6 position-relative text-dark font-weight-normal form-control height-35 rounded p-0 text-left f-15"
                                   placeholder="@lang('placeholders.date')">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <x-forms.label class="my-3" fieldId="category_id"
                                       :fieldLabel="__('modules.integrations.category')">
                        </x-forms.label>

                        <x-forms.input-group>
                            <select class="form-control select-picker" name="category_id" id="category_id"
                                    data-live-search="true">
                                <option value="">--</option>
                            </select>
                        </x-forms.input-group>
                    </div>

                    <div class="col-md-4">
                        <x-forms.label class="my-3" fieldId="hotel_id"
                                       :fieldLabel="__('modules.integrations.hotel')">
                        </x-forms.label>

                        <x-forms.input-group>
                            <select class="form-control select-picker" name="hotel_id" id="hotel_id"
                                    data-live-search="true">
                                <option value="">--</option>
                            </select>
                        </x-forms.input-group>
                    </div>
                    <div class="col-md-2">
                        <x-forms.label class="my-3" fieldId="nights_count_from"
                                       :fieldLabel="__('modules.integrations.nightsFrom')">
                        </x-forms.label>

                        <x-forms.input-group>
                            <select class="form-control select-picker" name="nights_count_from" id="nights_count_from"
                                    data-live-search="true">
                                <option value="">--</option>
                                @foreach(range(1, 10) as $n)
                                    <option value="{{ $n }}">{{ $n }}</option>
                                @endforeach
                            </select>
                        </x-forms.input-group>
                    </div>

                    <div class="col-md-2">
                        <x-forms.label class="my-3" fieldId="nights_count_to"
                                       :fieldLabel="__('modules.integrations.nightsTo')">
                        </x-forms.label>

                        <x-forms.input-group>
                            <select class="form-control select-picker" name="nights_count_to" id="nights_count_to"
                                    data-live-search="true">
                                <option value="">--</option>
                                @foreach(range(1, 10) as $n)
                                    <option value="{{ $n }}">{{ $n }}</option>
                                @endforeach
                            </select>
                        </x-forms.input-group>
                    </div>
                    <div class="col-md-2">
                        <x-forms.label class="my-3" fieldId="adults_count"
                                       :fieldLabel="__('modules.integrations.adults')">
                        </x-forms.label>

                        <x-forms.input-group>
                            <select class="form-control select-picker" name="adults_count" id="adults_count"
                                    data-live-search="true">
                                <option value="">--</option>
                                @foreach(range(1, 6) as $n)
                                    <option value="{{ $n }}">{{ $n }}</option>
                                @endforeach
                            </select>
                        </x-forms.input-group>
                    </div>
                    <div class="col-md-2">
                        <x-forms.label class="my-3" fieldId="children_count"
                                       :fieldLabel="__('modules.integrations.childs')">
                        </x-forms.label>

                        <x-forms.input-group>
                            <select class="form-control select-picker" name="children_count" id="children_count"
                                    data-live-search="true">
                                <option value="">--</option>
                                @foreach(range(1, 5) as $n)
                                    <option value="{{ $n }}">{{ $n }}</option>
                                @endforeach

                            </select>
                        </x-forms.input-group>
                    </div>
                    <div class="col-md-4">
                        <x-forms.text fieldId="budget" :fieldLabel="__('modules.client.budget')"
                                      fieldName="budget"
                                      fieldRequired="true" :fieldPlaceholder="__('placeholders.budget')"
                        >
                        </x-forms.text>
                    </div>
                </div>

                <x-form-actions>
                    <x-forms.button-primary id="save-project-form" class="mr-3" icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel :link="route('leads.show', $lead->id)"
                                           class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>

            </div>
        </x-form>

    </div>
</div>

<script>
    const url = '{{ route('integrations.store', $client->id) }}';
    console.log(url);

    $(document).ready(function () {
        const childrenCount = $('children_count').val();

        let from_city_id = $('#from_city_id').val();
        const getStatesUrl = '{{url('api')}}/' + from_city_id + '/states';
        console.log(getStatesUrl);

        $.ajax({
            url: getStatesUrl, // Replace with your API endpoint
            method: 'GET',
            success: function (response) {
                $('#package_id').val('');
                $.each(response, function (index, option) {
                    $('#to_country_id').append($('<option>', {
                        value: option.id,
                        text: option.name
                    }));
                });
                $('#to_country_id').selectpicker('refresh');
            },
            error: function () {
                console.error('API request failed');
            }
        });


        $('#hotel_id').change(function () {
            $('#hotel_id').selectpicker('refresh');
        });
        $('#to_country_id').change(function () {
            $('#to_country_id').selectpicker('refresh');
        });
        $('#to_city_id').change(function () {
            $('#to_city_id').selectpicker('refresh');
        });
        $('#from_city_id').change(function () {
            $('#from_city_id').selectpicker('refresh');
        });
        $('#category_id').change(function () {
            $('#category_id').selectpicker('refresh');
        });
        $('#tour_id').change(function () {
            $('#tour_id').selectpicker('refresh');
        });

        $('#to_country_id').change(function () {
            $('#to_country_id').selectpicker('refresh');
            console.log($("#to_country_id option:selected").selectpicker().text());
            $("#to_country_name").val($(this).val());
            let to_country_id = $(this).val();
            let from_city_id = $('#from_city_id').val();


            $.ajax({
                url: '{{route('get-programs')}}', // Replace with your API endpoint
                method: 'GET',
                data: {
                    to_country_id: to_country_id,
                    from_city_id: from_city_id,
                },
                success: function (response) {
                    $('#package_id').val('');
                    $.each(response, function (index, option) {
                        $('#package_id').append($('<option>', {
                            value: option.id,
                            text: option.name
                        }));
                    });
                    $('#package_id').selectpicker('refresh');
                },
                error: function () {
                    console.error('API request failed');
                }
            });
            $.ajax({
                url: '{{route('get-hotels')}}', // Replace with your API endpoint
                method: 'GET',
                data: {
                    to_country_id: to_country_id,
                    from_city_id: from_city_id
                },
                success: function (response) {
                    $('#hotel_id').val('');
                    $.each(response, function (index, option) {
                        $('#hotel_id').append($('<option>', {
                            value: option.id,
                            text: option.name
                        }));
                    });
                    $('#hotel_id').selectpicker('refresh');
                },
                error: function () {
                    console.error('API request failed');
                }
            });
            $.ajax({
                url: `{{ route('get-categories') }}`,
                method: 'GET',
                data: {
                    to_country_id: to_country_id,
                    from_city_id: from_city_id
                },
                success: function (response) {
                    $('#category_id').val('');
                    $.each(response, function (index, option) {
                        $('#category_id').append($('<option>', {
                            value: option.id,
                            text: option.name
                        }));
                    });
                    $('#category_id').selectpicker('refresh');
                },
                error: function () {
                    console.error('API request failed');
                }
            });
            $.ajax({
                url: '{{route('get-tours') }}', // Replace with your API endpoint
                method: 'GET',
                data: {
                    to_country_id: to_country_id,
                    from_city_id: from_city_id
                },
                success: function (response) {
                    $('#tour_id').val('');
                    $.each(response, function (index, option) {
                        $('#tour_id').append($('<option>', {
                            value: option.id,
                            text: option.name
                        }));
                    });
                    $('#tour_id').selectpicker('refresh');
                },
                error: function () {
                    console.error('API request failed');
                }
            });
            {{--$.ajax({--}}
            {{--    url: '{{route('get-towns')}}', // Replace with your API endpoint--}}
            {{--    method: 'GET',--}}
            {{--    data: {--}}
            {{--        to_country_id: to_country_id,--}}
            {{--        from_city_id: from_city_id--}}
            {{--    },--}}
            {{--    success: function (response) {--}}
            {{--        $('#to_city_id').val('');--}}
            {{--        $.each(response, function (index, option) {--}}
            {{--            $('#to_city_id').append($('<option>', {--}}
            {{--                value: option.id,--}}
            {{--                text: option.name--}}
            {{--            }));--}}
            {{--        });--}}
            {{--        $('#to_city_id').selectpicker('refresh');--}}
            {{--    },--}}
            {{--    error: function () {--}}
            {{--        console.error('API request failed');--}}
            {{--    }--}}
            {{--});--}}

        });
        $('#from_city_id').change(function () {
            $('#from_city_id').selectpicker('refresh');

            let from_city_id = $(this).val();
            const getStatesUrl = '{{url('api')}}/' + from_city_id + '/states';
            console.log(getStatesUrl);
            $.ajax({
                url: getStatesUrl, // Replace with your API endpoint
                method: 'GET',
                success: function (response) {
                    $('#package_id').val('');
                    $.each(response, function (index, option) {
                        $('#to_country_id').append($('<option>', {
                            value: option.id,
                            text: option.name
                        }));
                    });
                    $('#to_country_id').selectpicker('refresh');
                },
                error: function () {
                    console.error('API request failed');
                }
            });
        });
        const dp1 = datepicker('#checkin_begin', {
            position: 'bl',
            onSelect: (instance, date) => {
                dp2.setMin(date);
            },
            ...datepickerConfig
        });
        const dp2 = datepicker('#checkin_end', {
            position: 'bl',
            onSelect: (instance, date) => {
                dp1.setMax(date);
            },
            ...datepickerConfig
        });

        init(RIGHT_MODAL);
    })
    $('#save-project-form').click(function () {

        $("#to_country_name").val($("#to_country_id option:selected").selectpicker().text());
        $("#from_city_name").val($("#from_city_id option:selected").selectpicker().text());

        $("#to_city_name").val($("#to_city_id option:selected").selectpicker().text());
        $("#hotel_name").val($("#hotel_id option:selected").selectpicker().text());

        $("#category_name").val($("#category_id option:selected").selectpicker().text());
        $("#tour_name").val($("#tour_id option:selected").selectpicker().text());

        let data = $('#save-project-data-form').serialize();

        console.log(data);

        $.easyAjax({
            url: url,
            container: '#save-project-data-form',
            type: "POST",
            disableButton: true,
            blockUI: true,
            file: true,
            buttonSelector: "#save-project-form",
            data: data,
            success: function (response) {
                if (response.status === 'success') {
                    if ($(MODAL_XL).hasClass('show')) {
                        $(MODAL_XL).modal('hide');
                        window.location.reload();
                    } else if (typeof response.redirectUrl !== 'undefined') {
                        window.location.href = response.redirectUrl;
                    } else if (response.add_more === true) {

                        var right_modal_content = $.trim($(RIGHT_MODAL_CONTENT).html());
                        if (right_modal_content.length) {

                            $(RIGHT_MODAL_CONTENT).html(response.html.html);
                            $('#add_more').val(false);
                        } else {
                            $('#add_more').val(false);
                        }
                    }

                    if (typeof showTable !== 'undefined' && typeof showTable === 'function') {
                        showTable();
                    }
                }

                console.log(response);
            }
        });
    });


</script>
<script src="{{ asset('js/integrations.js') }}"></script>
