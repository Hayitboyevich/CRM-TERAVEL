@php
    $addProjectCategoryPermission = user()->permission('manage_project_category');
    $addClientPermission = user()->permission('add_clients');
    $editProjectMemberPermission = user()->permission('edit_project_members');
    $addEmployeePermission = user()->permission('add_employees');
    $addProjectMemberPermission = user()->permission('add_project_members');
    $addProjectMemberPermission = user()->permission('add_project_members');
    $createPublicProjectPermission = user()->permission('create_public_project');

@endphp

{{--<link rel="stylesheet" href="{{ asset('vendor/css/dropzone.min.css') }}">--}}

<div class="row">
    <div class="col-sm-12">
        <x-form id="save-project-data-form" method="PUT">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                </h4>
                <input hidden name="from_city_name" id="from_city_name">
                <input hidden name="to_city_name" id="to_city_name">
                <input hidden name="hotel_name" id="hotel_name">
                <input hidden name="to_country_name" id="to_country_name">

                <input hidden name="category_name" id="category_name">
                <input hidden name="tour_name" id="tour_name">

                <div class="row p-20">

                    <div class="col-md-4">
                        <x-forms.label class="my-3" fieldId="from_city_id"
                                       :fieldLabel="__('modules.integrations.fromCity')">
                        </x-forms.label>

                        <x-forms.input-group>
                            <select required class="form-control select-picker" name="from_city_id"
                                    id="from_city_id"
                                    data-live-search="true">
                                <option value="">--</option>
                                @foreach ($cities as $city)
                                    <option
                                            @if($city->name == 'Ташкент')
                                                selected
                                            @endif
                                            value="{{ $city->id }}">
                                        {{ mb_ucwords($city->name) }}
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
                            <select required class="form-control select-picker" name="to_country_id"
                                    id="to_country_id"
                                    data-live-search="true">
                                <option selected
                                        value="{{$integration->to_country_id ?? ""}}">{{$integration->to_country_name ?? "--"}}</option>

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

                                {{--                                @foreach ($toCities as $toCity)--}}
                                {{--                                    <option--}}
                                {{--                                            value="{{ $toCity->id }}">--}}
                                {{--                                        {{ mb_ucwords($toCity->name) }}--}}
                                {{--                                    </option>--}}
                                {{--                                @endforeach--}}

                            </select>
                        </x-forms.input-group>
                    </div>
                    {{--                    <div class="col-md-4">--}}
                    {{--                        <x-forms.label class="my-3" fieldId="hotel_id"--}}
                    {{--                                       :fieldLabel="__('modules.integrations.hotel')">--}}
                    {{--                        </x-forms.label>--}}

                    {{--                        <x-forms.input-group>--}}
                    {{--                            <select class="form-control select-picker" name="hotel_id" id="hotel_id"--}}
                    {{--                                    data-live-search="true">--}}
                    {{--                                <option--}}
                    {{--                                        value="{{$integration->hotel_id ?? ""}}">{{$integration->hotel_id ?? ""}}</option>--}}
                    {{--                            </select>--}}
                    {{--                        </x-forms.input-group>--}}
                    {{--                    </div>--}}
                    {{--                    <div class="col-md-4">--}}
                    {{--                        <x-forms.label class="my-3" fieldId="category_id"--}}
                    {{--                                       :fieldLabel="__('modules.integrations.category')">--}}
                    {{--                        </x-forms.label>--}}

                    {{--                        <x-forms.input-group>--}}
                    {{--                            <select class="form-control select-picker" name="category_id" id="category_id"--}}
                    {{--                                    data-live-search="true">--}}
                    {{--                                <option--}}
                    {{--                                        value="{{$integration->category_id ?? ""}}">{{$integration->category_name ?? ""}}</option>--}}
                    {{--                            </select>--}}
                    {{--                        </x-forms.input-group>--}}
                    {{--                    </div>--}}
                    {{--                    <div class="col-md-4">--}}
                    {{--                        <x-forms.label class="my-3" fieldId="tour_id"--}}
                    {{--                                       :fieldLabel="__('modules.integrations.tour')">--}}
                    {{--                        </x-forms.label>--}}
                    {{--                        <x-forms.input-group>--}}
                    {{--                            <select required class="form-control select-picker" name="tour_id" id="tour_id"--}}
                    {{--                                    data-live-search="true">--}}
                    {{--                                <option--}}
                    {{--                                        value="{{$integration->tour_id ?? ""}}">{{$integration->tour_name ?? "--"}}</option>--}}
                    {{--                            </select>--}}
                    {{--                        </x-forms.input-group>--}}
                    {{--                    </div>--}}
                    {{--                    <div class="col-md-4">--}}
                    {{--                        <x-forms.label required class="my-3" fieldId="package_id"--}}
                    {{--                                       :fieldLabel="__('modules.integrations.packageId')">--}}
                    {{--                        </x-forms.label>--}}

                    {{--                        <x-forms.input-group>--}}
                    {{--                            <select class="form-control select-picker" name="package_id" id="package_id"--}}
                    {{--                                    data-live-search="true">--}}
                    {{--                                <option--}}
                    {{--                                        value="{{$integration->package_id ?? ""}}">{{$integration->package_name ?? "--"}}</option>--}}
                    {{--                            </select>--}}
                    {{--                        </x-forms.input-group>--}}
                    {{--                    </div>--}}


                    <div class="col-md-4">
                        <x-forms.label class="my-3" fieldId="checkin_begin"
                                       :fieldLabel="__('modules.integrations.checkin_from')">
                        </x-forms.label>
                        <div class="input-group">
                            <input type="text" id="checkin_begin" name="checkin_begin"
                                   value="{{$integration->checkin_begin}}"
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
                                   value="{{$integration->checkin_end}}"
                                   class="px-6 position-relative text-dark font-weight-normal form-control height-35 rounded p-0 text-left f-15"
                                   placeholder="@lang('placeholders.date')">
                        </div>
                    </div>


                    <div class="col-md-2">
                        <x-forms.label class="my-3" fieldId="nights_count_from"
                                       :fieldLabel="__('modules.integrations.nightsFrom')">
                        </x-forms.label>

                        <x-forms.input-group>
                            <select class="form-control select-picker" name="nights_count_from"
                                    id="nights_count_from"
                                    data-live-search="true">
                                <option
                                        value="">--
                                </option>
                                @foreach(range(1, 10) as $n)
                                    <option {{$integration->nights_count_from == $n ? "selected" : ""}} value="{{ $n }}">{{ $n }}</option>
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
                                <option
                                        value="">--
                                </option>
                                @foreach(range(1, 10) as $n)
                                    <option {{$integration->nights_count_to == $n ? "selected" : ""}} value="{{ $n }}">{{ $n }}</option>
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
                                    <option {{$integration->adults_count == $n ? "selected" : ""}} value="{{ $n }}">{{ $n }}</option>
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
                                @foreach(range(1, 6) as $n)
                                    <option {{$integration->children_count == $n ? "selected" : ""}} value="{{ $n }}">{{ $n }}</option>
                                @endforeach
                            </select>
                        </x-forms.input-group>
                    </div>
                </div>

                <x-form-actions>
                    <x-forms.button-primary id="save-project-form" class="mr-3" icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel :link="route('leadboards.index')"
                                           class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>
            </div>
        </x-form>
    </div>


</div>


<script src="{{ asset('vendor/jquery/dropzone.min.js') }}"></script>
<script>
    const url = "{{route('integrations.update', $integration->id)}}";

    const childrenCount = $('#children_count').val();

    const hotelId = $('#hotel_id');
    const categoryId = $('#category_id');
    const fromCityId = $('#from_city_id');
    const toCountryId = $('#to_country_id');
    const toCityId = $('#to_city_id');
    const tourId = $('#tour_id');
    const nightsCountFrom = $('#nights_count_from');
    const nightsCountTo = $('#nights_count_to');
    const adultsCount = $('#adults_count');
    const budget = $('#budget');
    const saveProjectForm = $('#save-project-form');
    const packageId = $('#package_id');
    let from_city_id = fromCityId.val();

    const getStatesUrl = '{{url('api')}}/' + from_city_id + '/states';

    function setupData(to_country_id, from_city_id) {
        {{--$.ajax({--}}
        {{--    url: '{{route('get-programs')}}', // Replace with your API endpoint--}}
        {{--    method: 'GET',--}}
        {{--    data: {--}}
        {{--        to_country_id: to_country_id,--}}
        {{--        from_city_id: from_city_id,--}}
        {{--        type: 'kompastour'--}}
        {{--    },--}}
        {{--    success: function (response) {--}}
        {{--        packageId.val('');--}}
        {{--        $.each(response, function (index, option) {--}}
        {{--            packageId.append($('<option>', {--}}
        {{--                value: option.id,--}}
        {{--                text: option.name--}}
        {{--            }));--}}
        {{--        });--}}
        {{--        packageId.selectpicker('refresh');--}}
        {{--    },--}}
        {{--    error: function () {--}}
        {{--        console.error('API request failed');--}}
        {{--    }--}}
        {{--});--}}
        {{--$.ajax({--}}
        {{--    url: '{{route('get-hotels')}}', // Replace with your API endpoint--}}
        {{--    method: 'GET',--}}
        {{--    data: {--}}
        {{--        to_country_id: to_country_id,--}}
        {{--        from_city_id: from_city_id,--}}
        {{--        type: 'kompastour'--}}

        {{--    },--}}
        {{--    success: function (response) {--}}
        {{--        hotelId.val('');--}}
        {{--        $.each(response, function (index, option) {--}}
        {{--            hotelId.append($('<option>', {--}}
        {{--                value: option.id,--}}
        {{--                text: option.name,--}}
        {{--                {{$integration->hotel_id ? "selected: option.id === ".$integration->hotel_id : ""}}--}}
        {{--            }));--}}
        {{--        });--}}
        {{--        hotelId.selectpicker('refresh');--}}
        {{--    },--}}
        {{--    error: function () {--}}
        {{--        console.error('API request failed');--}}
        {{--    }--}}
        {{--});--}}

        {{--$.ajax({--}}
        {{--    url: `{{ route('get-categories') }}`,--}}
        {{--    method: 'GET',--}}
        {{--    data: {--}}
        {{--        to_country_id: to_country_id,--}}
        {{--        from_city_id: from_city_id,--}}
        {{--        type: 'kompastour'--}}

        {{--    },--}}
        {{--    success: function (response) {--}}
        {{--        categoryId.val('');--}}
        {{--        $.each(response, function (index, option) {--}}
        {{--            categoryId.append($('<option>', {--}}
        {{--                value: option.id,--}}
        {{--                text: option.name,--}}
        {{--                {{$integration->category_id ? "selected: option.id === ".$integration->category_id : ""}}--}}

        {{--            }));--}}
        {{--        });--}}
        {{--        categoryId.selectpicker('refresh');--}}
        {{--    },--}}
        {{--    error: function () {--}}
        {{--        console.error('API request failed');--}}
        {{--    }--}}
        {{--});--}}
        {{--$.ajax({--}}
        {{--    url: '{{route('get-tours') }}', // Replace with your API endpoint--}}
        {{--    method: 'GET',--}}
        {{--    data: {--}}
        {{--        to_country_id: to_country_id,--}}
        {{--        from_city_id: from_city_id,--}}
        {{--        type: 'kompastour'--}}

        {{--    },--}}
        {{--    success: function (response) {--}}
        {{--        tourId.val('');--}}
        {{--        $.each(response, function (index, option) {--}}
        {{--            $('#tour_id').append($('<option>', {--}}
        {{--                value: option.id,--}}
        {{--                text: option.name--}}
        {{--            }));--}}
        {{--        });--}}
        {{--        tourId.selectpicker('refresh');--}}
        {{--    },--}}
        {{--    error: function () {--}}
        {{--        console.error('API request failed');--}}
        {{--    }--}}
        {{--});--}}
        $.ajax({
            url: '{{route('get-towns')}}', // Replace with your API endpoint
            method: 'GET',
            data: {
                to_country_id: to_country_id,
                from_city_id: from_city_id,
                type: 'kompastour'

            },
            success: function (response) {
                toCityId.val('');
                toCityId.empty();
                toCityId.append($('<option>', {
                    value: '',
                    text: '--'
                }));
                $.each(response, function (index, option) {
                    toCityId.append($('<option>', {
                        value: option.id,
                        text: option.name
                    }));
                });
                toCityId.selectpicker('refresh');
            },
            error: function () {
                console.error('API request failed');
            }
        });
        toCityId.selectpicker('refresh');
    }

    $(document).ready(function () {
        $.ajax({
            url: getStatesUrl, // Replace with your API endpoint
            method: 'GET',
            success: function (response) {
                packageId.val('');
                console.log(response);
                $.each(response, function (index, option) {
                    toCountryId.append($('<option>', {
                        value: option.id,
                        text: option.name,
                        type: 'kompastour',

                        {{$integration->to_country_id ? "selected: option.id === ".$integration->to_country_id : ""}}

                    }));
                });
                toCountryId.selectpicker('refresh');
            },
            error: function () {
                console.error('API request failed');
            }
        });

        if (toCountryId.val()) {
            setupData(toCountryId.val(), from_city_id)
        }

        toCountryId.change(function () {
            toCountryId.selectpicker('refresh');

            toCountryId.val($(this).val());
            let to_country_id = $(this).val();
            let from_city_id = fromCityId.val();

            setupData(to_country_id, from_city_id)
        });
        fromCityId.change(function () {
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
        // hotelId.change(function () {
        //     hotelId.selectpicker('refresh');
        // });
        toCountryId.change(function () {
            toCountryId.selectpicker('refresh');
        });
        toCityId.change(function () {
            $('#to_city_id').selectpicker('refresh');
        });
        // categoryId.change(function () {
        //     categoryId.selectpicker('refresh');
        // });
        // tourId.change(function () {
        //     tourId.selectpicker('refresh');
        // });

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
    saveProjectForm.click(function () {

        $("#to_country_name").val($("#to_country_id option:selected").selectpicker().text());
        $("#from_city_name").val($("#from_city_id option:selected").selectpicker().text());

        $("#to_city_name").val($("#to_city_id option:selected").selectpicker().text());
        // $("#hotel_name").val($("#hotel_id option:selected").selectpicker().text());

        // $("#category_name").val($("#category_id option:selected").selectpicker().text());
        // $("#tour_name").val($("#tour_id option:selected").selectpicker().text());

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
