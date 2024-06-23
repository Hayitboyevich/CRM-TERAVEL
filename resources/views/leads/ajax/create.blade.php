@php

    $trip = [
        'country_id' => [],'service_id' => [],'budget' => [],

];
@endphp

<link rel="stylesheet" href="{{ asset('vendor/css/dropzone.min.css') }}">


<div class="row">
    <div class="col-sm-12">
        <x-form id="save-lead-data-form">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('modules.lead.leadDetails')</h4>
                <div class="row p-20">
                    <div class="col-lg-4 col-md-6">
                        <x-forms.text :fieldLabel="__('modules.lead.clientName')" fieldName="client_name"
                                      fieldId="client_name" :fieldPlaceholder="__('placeholders.name')"
                                      fieldRequired="true"/>
                    </div>
                    <div class="col-md-4">
                        <x-forms.label class="my-3" fieldId="mobile"
                                       :fieldLabel="__('app.mobile')"></x-forms.label>
                        <x-forms.input-group style="margin-top:-4px">
                            <x-forms.select fieldId="country_phonecode" fieldName="country_phonecode"
                                            search="true">
                                @foreach ($countries as $item)
                                    <option data-tokens="{{ $item->name }}"
                                            {{$item->phonecode == '+998' ? "selected" : ""}}
                                            data-content="{{$item->flagSpanCountryCode()}}"
                                            value="{{ $item->phonecode }}">{{ $item->phonecode }}
                                    </option>
                                @endforeach
                            </x-forms.select>

                            <input type="tel" class="form-control height-35 f-14"
                                   placeholder="@lang('placeholders.mobile')" name="mobile" id="mobile">
                        </x-forms.input-group>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group my-3">
                            <x-forms.label fieldId="note" :fieldLabel="__('app.note')">
                            </x-forms.label>
                            <div id="note"></div>
                            <textarea name="note" id="note-text" class="d-none"></textarea>
                        </div>
                    </div>

                </div>
                <input type="hidden" name="status" value="{{ $columnId }}">
            </div>

            {{--            lead interest--}}
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    Lead Interests</h4>
                <div class="form-row p-20">
                    <div class="col-md-3">
                        <x-forms.select fieldId="country_id" fieldLabel="{{__('app.country')}}"
                                        fieldName="lead_interests[country_id]"
                                        search="true">
                            <option value="">--</option>
                            @foreach($countries as $country)
                                <option
                                    value="{{$country->id}}">{{$country->name}}
                                </option>
                            @endforeach
                        </x-forms.select>
                    </div>

                    <div class="col-md-3">
                        <x-forms.number fieldLabel="{{__('app.adults')}}" fieldName="lead_interests[adults]"
                                        fieldId="adults"
                        />
                    </div>
                    <div class="col-md-3">
                        <x-forms.number fieldLabel="{{__('app.children')}}" fieldName="lead_interests[children]"
                                        fieldId="children"
                        />
                    </div>
                    <div class="col-md-3">
                        <x-forms.number fieldLabel="{{__('app.baby')}}" fieldName="lead_interests[baby]"
                                        fieldId="baby"
                        />
                    </div>
                </div>

                <div class="form-row p-20">
                    <div class="col-md-6 mb-3">
                        <label for="desired_date_from">@lang('app.desired_date_from')</label>
                        <input type="date" class="form-control" id="desired_date_from" name="lead_interests[desired_date_from]"
                               value="{{ old('desired_date_from', $leadInterest->desired_date_from ?? '') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="desired_date_to">@lang('app.desired_date_to')</label>
                        <input type="date" class="form-control" id="desired_date_to" name="lead_interests[desired_date_to]"
                               value="{{ old('desired_date_to', $leadInterest->desired_date_to ?? '') }}">
                    </div>
                </div>

                <div class="form-row p-20">
                    <div class="col-md-4 mb-3">
                        <label for="count_days_from">@lang('app.count_days_from')</label>
                        <input type="number" class="form-control" id="count_days_from" name="lead_interests[count_days_from]"
                               value="{{ old('count_days_from', $leadInterest->count_days_from ?? '') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="count_days_to">@lang('app.count_days_to')</label>
                        <input type="number" class="form-control" id="count_days_to" name="lead_interests[count_days_to]"
                               value="{{ old('count_days_to', $leadInterest->count_days_to ?? '') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="meal_plan">@lang('app.mealPlan')</label>
                        <select class="form-control" id="meal_plan" name="lead_interests[meal_plan]">
                            <option value="">Select a Meal Plan</option>
                            <option
                                value="RO"{{ old('meal_plan', $leadInterest->meal_plan ?? '') == 'RO' ? ' selected' : '' }}>
                                Room Only
                            </option>
                            <option
                                value="BB"{{ old('meal_plan', $leadInterest->meal_plan ?? '') == 'BB' ? ' selected' : '' }}>
                                Bed & Breakfast
                            </option>
                            <option
                                value="HB"{{ old('meal_plan', $leadInterest->meal_plan ?? '') == 'HB' ? ' selected' : '' }}>
                                Half Board
                            </option>
                            <option
                                value="FB"{{ old('meal_plan', $leadInterest->meal_plan ?? '') == 'FB' ? ' selected' : '' }}>
                                Full Board
                            </option>
                            <option
                                value="AI"{{ old('meal_plan', $leadInterest->meal_plan ?? '') == 'AI' ? ' selected' : '' }}>
                                All Inclusive
                            </option>
                            <option
                                value="UAI"{{ old('meal_plan', $leadInterest->meal_plan ?? '') == 'UAI' ? 'selected' : '' }}>
                                Ultra All Inclusive
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-row p-20">

                    <div class="col-md-4 mb-3">
                        <label for="price">@lang('app.price')</label>
                        <input type="text" class="form-control" id="price" name="lead_interests[price]"
                               value="{{ old('price', $leadInterest->price ?? '') }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="service_id">{{__('app.currency')}}</label>
                        <select class="form-control" id="currency_id" name="lead_interests[currency_id]">
                            @foreach($currencies as $currency)
                                <option
                                    value="{{$currency->id}}">
                                    {{$currency->currency_code}}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="accommodation">{{__('app.accommodation_type')}}</label>
                        <div>
                            <!-- Assume options for accommodations -->
                            <label><input type="radio" name="lead_interests[accommodation_type]" value="1_star"> 1*</label>
                            <label><input type="radio" name="lead_interests[accommodation_type]" value="2_star"> 2*</label>
                            <label><input type="radio" name="lead_interests[accommodation_type]" value="3_star"> 3*</label>
                            <label><input type="radio" name="lead_interests[accommodation_type]" value="4_star"> 4*</label>
                            <label><input type="radio" name="lead_interests[accommodation_type]" value="5_star"> 5*</label>
                            <label><input type="radio" name="lead_interests[accommodation_type]" value="apartments"> Apts</label>
                            <label><input type="radio" name="lead_interests[accommodation_type]" value="villa"> Villa</label>
                        </div>
                    </div>
                </div>
            </div>

            {{--            // end lead interest--}}
            <x-form-actions>
                <x-forms.button-primary id="save-lead-form" class="mr-3" icon="check">@lang('app.save')
                </x-forms.button-primary>
                <x-forms.button-secondary class="mr-3" id="save-more-lead-form"
                                          icon="check-double">@lang('app.saveAddMore')
                </x-forms.button-secondary>
                <x-forms.button-cancel :link="route('tasks.index')" class="border-0">@lang('app.cancel')
                </x-forms.button-cancel>
            </x-form-actions>


        </x-form>

    </div>
</div>


<script src="{{ asset('vendor/jquery/dropzone.min.js') }}"></script>
<script>

    $(document).ready(function () {

        $('.custom-date-picker').each(function (ind, el) {
            datepicker(el, {
                position: 'bl',
                ...datepickerConfig
            });
        });


        quillImageLoad('#note');

        $('#save-more-lead-form').click(function () {

            document.getElementById('note-text').value = document.getElementById('note').children[0].innerHTML;

            const url = "{{ route('leads.store') }}";
            const data = $('#save-lead-data-form').serialize() + '&add_more=true';

            saveLead(data, url, "#save-more-lead-form");

        });
        $('body').on('click', '.add-lead-source', function () {

            const url = '{{ route('lead-source-settings.create') }}';
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        $('#save-lead-form').click(function () {
                document.getElementById('note-text').value = document.getElementById('note').children[0].innerHTML;

                const url = "{{ route('leads.store') }}";
                var data = $('#save-lead-data-form').serialize();
                saveLead(data, url, "#save-lead-form");

            }
        );

        function saveLead(data, url, buttonSelector) {
            $.easyAjax({
                url: url,
                container: '#save-lead-data-form',
                type: "POST",
                file: true,
                disableButton: true,
                blockUI: true,
                buttonSelector: buttonSelector,
                data: data,
                success: function (response) {
                    if (response.add_more == true) {

                        var right_modal_content = $.trim($(RIGHT_MODAL_CONTENT).html());

                        if (right_modal_content.length) {

                            $(RIGHT_MODAL_CONTENT).html(response.html.html);
                            $('#add_more').val(false);
                        } else {

                            $('.content-wrapper').html(response.html.html);
                            init('.content-wrapper');
                            $('#add_more').val(false);
                        }
                    } else {
                        window.location.href = response.redirectUrl;
                    }

                    if (typeof showTable !== 'undefined' && typeof showTable === 'function') {
                        showTable();
                    }
                }
            });

        }

        $('body').on('click', '.add-lead-agent', function () {
                var url = '{{ route('lead-agent-settings.create') }}';
                $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
                $.ajaxModal(MODAL_LG, url);
            }
        );

        $('body').on('click', '.add-lead-category', function () {
                var url = '{{ route('leadCategory.create') }}';
                $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
                $.ajaxModal(MODAL_LG, url);
            }
        );

        $('.toggle-other-details').click(function () {
                $(this).find('svg').toggleClass('fa-chevron-down fa-chevron-up');
                $('#other-details').toggleClass('d-none');
            }
        );

        init(RIGHT_MODAL);
    });
    $('.mobile').on('keyup', function () {
        var mobile = $(this).val();
        var phonecode = $('#mobile').val();

        var phoneRegex = /^\+?[1-9]\d{1,14}$/; // Regular expression for phone number validation

        if (phoneRegex.test(phoneInput)) {
            document.getElementById('phoneError').innerHTML = ''; // Clear any existing error sms
            // Perform any additional logic or submit the form here
        } else {
            document.getElementById('phoneError').innerHTML = 'Invalid mobile phone number';
        }
    });


    function checkboxChange(parentClass, id) {
        var checkedData = '';
        $('.' + parentClass).find("input[type= 'checkbox']:checked").each(function () {
            checkedData = (checkedData !== '') ? checkedData + ', ' + $(this).val() : $(this).val();
        });
        $('#' + id).val(checkedData);
    }

</script>
