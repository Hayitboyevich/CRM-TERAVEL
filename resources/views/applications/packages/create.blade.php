<div class="row">
    <div class="col-sm-12">
        <x-form id="save-package-data-form">
            <div class="add-application bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    Общая информация</h4>

                <div class="row p-20">
                    <div class="col-md-3">
                        <x-forms.text fieldId="name" :fieldLabel="'Название пакета:'"
                                      fieldName="name"
                        />
                    </div>
                    <div class="col-md-3">
                        <x-forms.textarea fieldId="description" :fieldLabel="'Краткое описание услуги:'"
                                          fieldName="description"
                        />
                    </div>
                    <div class="col-md-3">
                        <x-forms.number fieldId="quantity" :fieldLabel="'Количество:'"
                                        fieldName="quantity"
                        />
                    </div>
                    <div class="col-md-3">
                        <x-forms.select
                                fieldId="services[]"
                                fieldLabel="Услуги в пакете:"
                                fieldName="services[]"
                                multiple="true"
                                search="true">
                            <option value="0">Все</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}">{{ ($service->product?->name . ' (' . date('d M', strtotime($service->date_from)).' - '. date('d M', strtotime($service->date_to)).')') }}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                </div>
                <div class="row p-20">
                    <div class="col-md-4">
                        <x-forms.select
                                fieldId="type_id"
                                fieldLabel="Тип:"
                                fieldName="type_id"
                                search="true">
                            <option value="-">-</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ ($product->name) }}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                </div>

                <div class="row p-20">
                    <div class="col-md-3" id="from_city_select_box">
                        <x-forms.select fieldId="from_city_id" fieldLabel="Город вылета:" fieldName="from_city_id"
                                        search="true">
                            <option value="">--</option>
                            @foreach($cities as $city)
                                <option value="{{$city->id}}">{{$city->name}}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-md-3" id="country_select_box">
                        <x-forms.select fieldId="country_id" fieldLabel="Страна" fieldName="country_id"
                                        search="true">
                            <option value="">--</option>
                            @foreach($countries as $country)
                                <option value="{{$country->id}}">{{$country->name}}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-md-3" id="region_select_box">
                        <x-forms.select fieldId="region_id" fieldLabel="Город:" fieldName="region_id"
                                        search="true">
                            <option value="">--</option>
                            {{--                            @foreach($cities as $city)--}}
                            {{--                                <option value="{{$city->id}}">{{$city->name}}</option>--}}
                            {{--                            @endforeach--}}
                        </x-forms.select>
                    </div>
                    <div class="col-md-3">
                        <x-forms.text fieldId="hotel_name" fieldName="hotel_name"
                                      field-label="Отель:">
                        </x-forms.text>
                    </div>
                </div>
                <div class="row p-20">
                    <div class="col-md-2">
                        <x-forms.text fieldId="room_type" fieldLabel="Тип комната:"
                                      fieldName="room_type"/>
                    </div>
{{--                    <div class="col-md-2">--}}
{{--                        <x-forms.select fieldId="bed_type" fieldLabel="Тип размещения:" fieldName="bed_type"--}}
{{--                                        search="true">--}}
{{--                            <option value="">--</option>--}}
{{--                            @foreach($bedTypes as $type)--}}
{{--                                <option value="{{$type->id}}">{{$type->name}}</option>--}}
{{--                            @endforeach--}}
{{--                        </x-forms.select>--}}
{{--                    </div>--}}
                    <div class="col-md-3">
                        <x-forms.select fieldId="meal_id" fieldLabel="Тип питания:" fieldName="meal_id"
                                        search="true">
                            <option value="">--</option>
                            @foreach($mealTypes as $type)
                                <option value="{{$type->id}}">{{$type->name}}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-md-4">
                        <x-forms.label class="my-3" fieldId="partner_id"
                                       fieldLabel="Туроператор">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="partner_id" id="partner_id"
                                    data-live-search="true">
                                <option value="">--</option>
                                @foreach ($partners as $partner)
                                    <option
                                            value="{{ $partner->id }}">
                                        {{ mb_ucwords($partner->name) }}
                                    </option>
                                @endforeach
                            </select>

                            <x-slot name="append">
                                <button type="button"
                                        class="btn btn-outline-secondary border-grey add-partner"
                                        data-toggle="tooltip"
                                        data-original-title="{{ __('app.add').'  '.__('app.new').' '.__('modules.tickets.agents') }}">@lang('app.add')</button>
                            </x-slot>
                        </x-forms.input-group>
                    </div>
                </div>
                <div class="row p-20">
                    <div class="col">
                        <div class="row">
                            <div class="col-md-2">
                                <x-forms.number fieldId="net_price" fieldLabel="Себестоимость:"
                                                fieldName="net_price"
                                                fieldRequired="true">
                                </x-forms.number>
                            </div>
                            <div class="col-md-2">
                                <x-forms.select fieldId="net_currency_id"
                                                fieldName="net_currency_id" fieldClass="form-control select-picker">
                                    <option value="">--</option>
                                    @foreach($currencies as $currency)
                                        <option
                                                @if($currency->id == company()->currency_id) selected @endif
                                        value="{{$currency->id}}">{{$currency->currency_symbol}}
                                        </option>

                                    @endforeach

                                </x-forms.select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.text fieldId="net_exchange_rate"
                                              :fieldLabel="__('modules.currencySettings.exchangeRate')"
                                              fieldName="net_exchange_rate" fieldRequired="true"
                                              :fieldValue="company()->currency->exchange_rate"
                                              fieldReadOnly="true"
                                              :fieldHelp="( company()->currency->currency_code.' '.__('app.to').' '.$currencyCode )"/>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <x-forms.number fieldId="price" fieldLabel="Стоимость клиенту:"
                                                fieldName="price"
                                                fieldRequired="true">
                                </x-forms.number>
                            </div>
                            <div class="col-md-2">
                                <x-forms.select fieldId="currency_id"
                                                fieldName="currency_id" fieldClass="form-control select-picker">
                                    <option value="">--</option>
                                    @foreach($currencies as $currency)
                                        <option
                                                @if($currency->id == company()->currency_id) selected @endif
                                        value="{{$currency->id}}">{{$currency->currency_symbol}}</option>

                                    @endforeach

                                </x-forms.select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.text fieldId="exchange_rate"
                                              :fieldLabel="__('modules.currencySettings.exchangeRate')"
                                              fieldName="exchange_rate" fieldRequired="true"
                                              :fieldValue="company()->currency->exchange_rate"
                                              fieldReadOnly="true"
                                              :fieldHelp="( company()->currency->currency_code.' '.__('app.to').' '.$currencyCode )"/>
                            </div>

                        </div>
                    </div>

                </div>
                <br>
                <div class="row p-20">
                    <div class="col-md-2">
                        <x-forms.datepicker fieldId="date_from" fieldLabel="Дата тура с:"
                                            fieldName="date_from" :fieldPlaceholder="__('placeholders.date')"
                                            :fieldValue="now()->addDays(14)->timezone(company()->timezone)->format(company()->date_format)"/>
                    </div>
                    <div class="col-md-2">
                        <x-forms.datepicker fieldId="date_to" fieldLabel="Дата тура до:"
                                            fieldName="date_to" :fieldPlaceholder="__('placeholders.date')"
                                            :fieldValue="now()->addDays(14)->timezone(company()->timezone)->format(company()->date_format)"/>
                    </div>

                    <div class="col-md-2">
                        <x-forms.select fieldId="adults_count" fieldLabel="Количество взрослых:"
                                        fieldName="adults_count"
                                        search="true">
                            <option value="0">0</option>
                            @for($i = 1; $i<=100; $i++)
                                <option @if($i==2) selected @endif value="{{$i}}">{{$i}}</option>
                            @endfor
                        </x-forms.select>
                    </div>

                    <div class="col-md-2">
                        <x-forms.select fieldId="babies_count" fieldLabel="Количество младенцев:"
                                        fieldName="babies_count"
                                        search="true">
                            <option value="0">0</option>
                            @for($i = 1; $i<=100; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </x-forms.select>
                    </div>
                    <div class="col-md-2">
                        <x-forms.select fieldId="children_count" fieldLabel="Количество детей:"
                                        fieldName="children_count"
                                        search="true">
                            <option value="0">0</option>
                            @for($i = 1; $i<=100; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </x-forms.select>
                    </div>
                </div>
                <br>
                <x-form-actions>
                    <x-forms.button-primary id="save-package-form" class="mr-3"
                                            icon="check">@lang('app.save')
                    </x-forms.button-primary>

                    <x-forms.button-cancel :link="route('packages.index')"
                                           class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>
            </div>
        </x-form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('body').on('click', '.add-partner', function () {
            const url = '{{ route('partner-settings.create') }}';
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });
        $('body').on('click', '.add-hotel', function () {
            const url = '{{ route('hotel-settings.create') }}';
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });
        $('#country_id').change(function () {
            let to_country_id = $(this).val();
            let toCityId = $('#region_id');
            $.ajax({
                url: '{{route('get-regions')}}', // Replace with your API endpoint
                method: 'GET',
                data: {
                    to_country_id: to_country_id,
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
        });

        $('#from_city_id').select2({
            ajax: {
                url: '{{route('get-cities')}}',
                processResults: function (data) {
                    return {
                        results: data.map(function(city) {
                            return {id: city.id, text: city.name};
                        })
                    };
                }
            }
        });

        $('#net_currency_id').change(function () {
            let curId = $(this).val();
            $('#net_currency_id').val(curId);

            let companyCurrency = '{{ company()->currency_id }}';

            if (curId === companyCurrency) {
                $('#net_exchange_rate').prop('readonly', true);

            } else {
                $('#net_exchange_rate').prop('readonly', false);

            }

            let token = "{{ csrf_token() }}";

            $.easyAjax({
                url: "{{ route('payments.account_list') }}",
                type: "GET",
                blockUI: true,
                data: {'curId': curId, _token: token},
                success: function (response) {
                    if (response.status === 'success') {
                        $('#net_exchange_rate').val(response.exchangeRate);

                        {{--$('#exchange_rateHelp').html('( ' + companyCurrencyName + ' @lang('app.to') ' + currentCurrencyName + ' )');--}}
                    }
                }
            });
        });
        $('#currency_id').change(function () {
            let curId = $(this).val();
            $('#currency_id').val(curId);

            let companyCurrency = '{{ company()->currency_id }}';

            if (curId === companyCurrency) {
                $('#exchange_rate').prop('readonly', true);

            } else {
                $('#exchange_rate').prop('readonly', false);

            }

            let token = "{{ csrf_token() }}";

            $.easyAjax({
                url: "{{ route('payments.account_list') }}",
                type: "GET",
                blockUI: true,
                data: {'curId': curId, _token: token},
                success: function (response) {
                    if (response.status === 'success') {
                        $('#exchange_rate').val(response.exchangeRate);
                        {{--$('#exchange_rateHelp').html('( ' + companyCurrencyName + ' @lang('app.to') ' + currentCurrencyName + ' )');--}}
                    }
                }
            });
        });
        datepicker('#date_from', {
            position: 'bl',
            ...datepickerConfig
        });

        datepicker('#date_to', {
            position: 'bl',
            ...datepickerConfig
        });

        $('#save-package-form').click(function () {
            const url = "{{ route('packages.store') }}";

            $.easyAjax({
                url: url,
                container: '#save-package-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                file: true,
                buttonSelector: "#save-package-form",
                data: $('#save-package-data-form').serialize(),
                success: function (response) {
                    if (response.status === 'success') {
                        window.location.href = response.redirectUrl;
                    }
                }
            });
        });

        $('#country_id').selectpicker();
        $('#region_id').selectpicker();
        $('#from_city_id').selectpicker();

        // Add an event listener for the search input
        $('.bs-searchbox input[type="search"]').on('input', function () {
            var searchInput = $(this).siblings('.bs-searchbox').find('input[type="search"]');
            var selectId = $(this).attr('id');
            console.log('Search input:', selectId);
            // Get the value typed into the search input
            var searchValue = $(this).val();
            // Get the ID of the closest select element
            var closestSelect = $(this).closest('.bootstrap-select').siblings('select');
            console.log('Closest Select:', closestSelect);
            // Get the ID of the closest select element
            var selectId = closestSelect.attr('id');
            console.log('Select ID:', selectId);

            // Check if the select element has the ID 'country_id'
            if (selectId === 'country_id') {
                console.log('Yes, it is #country_id');
            }

            // Log the value to the console
            console.log('Search input value:', searchValue);
        });

        $('#from_city_select_box .bs-searchbox input[type="search"]').on('keyup', function (event) {
            if(event.key === 'Enter') {
                var searchValue = $(this).val();
                $.ajax({
                    url: '{{ route("store-city") }}',
                    method: 'POST',
                    data: { cityName: searchValue },
                    success: function (response) {
                        $('#from_city_id').append($('<option>', {
                            value: response.id,
                            text: response.name
                        }));
                        $('#from_city_id').selectpicker('refresh');
                        $('#from_city_id').selectpicker('val', response.id).trigger('change');
                        $('#from_city_id').selectpicker('toggle');
                    },
                    error: function (error) {
                        console.error('Error sending value:', error);
                    }
                });
                $('#confirmationModal').modal('hide');
            }
        });

        $('#region_select_box .bs-searchbox input[type="search"]').on('keyup', function (event) {
            if(event.key === 'Enter') {
                var searchValue = $(this).val();
                var countryId = $('#country_id').val();
                $.ajax({
                    url: '{{ route("store-region") }}',
                    method: 'POST',
                    data: { countryId: countryId, regionName: searchValue },
                    success: function (response) {
                        $('#region_id').append($('<option>', {
                            value: response.id,
                            text: response.name
                        }));
                        $('#region_id').selectpicker('refresh');
                        $('#region_id').selectpicker('val', response.id).trigger('change');
                        $('#region_id').selectpicker('toggle');
                    },
                    error: function (error) {
                        console.error('Error sending value:', error);
                    }
                });
                $('#confirmationModal').modal('hide');
            }
        });

        // Add an event listener for the 'keyup' event
        $('#country_select_box .bs-searchbox input[type="search"]').on('keyup', function (event) {
            // Check if the pressed key is 'Enter'
            if (event.key === 'Enter') {
                // Get the value typed into the search input
                var searchValue = $(this).val();
                console.log(searchValue);

                // Show a confirmation dialog
                // Show the custom confirmation modal

                // Add an event listener for the Confirm button in the modal
                $.ajax({
                    url: '{{ route("store-country") }}',
                    method: 'POST',
                    data: { countryName: searchValue },
                    success: function (response) {
                        // Add the new option to the select element
                        $('#country_id').append($('<option>', {
                            value: response.id,
                            text: response.name
                        }));
                        // Refresh the select element
                        $('#country_id').selectpicker('refresh');
                        // select the new option
                        $('#country_id').selectpicker('val', response.id).trigger('change');
                        // give the new option focus
                        $('#country_id').selectpicker('toggle');


                    },
                    error: function (error) {
                        console.error('Error sending value:', error);
                        // Handle the error if needed
                    }
                });
                // Close the modal
                $('#confirmationModal').modal('hide');

            }
        });
    })
</script>
