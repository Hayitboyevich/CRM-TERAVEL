<div class="row">
    <div class="col-sm-12">
        <x-form id="save-services-data-form" method="PUT">
            <div class="add-application bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    Общая информация</h4>
                <div class="row p-20 pb-0">
                    <div class="col-md-3">
                        <x-forms.text fieldId="name" :fieldLabel="'Наименование услуги:'"
                                      fieldValue="{{$service->name}}"
                                      fieldName="name"
                        />
                    </div>
                    <div class="col-md-3">
                        <x-forms.textarea fieldId="description" :fieldLabel="'Краткое описание услуги:'"
                                          fieldValue="{{$service->description}}"
                                          fieldName="description"
                        />
                    </div>
                    <div class="col-md-3">
                        <x-forms.label class="my-3" fieldId="type_id"
                                       fieldLabel="Тип услуги:">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="type_id" id="type_id"
                                    data-live-search="true">
                                <option value="">--</option>
                                @foreach ($products as $product)
                                    <option
                                            @if($product->id == $service->type_id)
                                                selected
                                            @endif
                                            value="{{ $product->id }}">{{ mb_ucwords($product->name) }}</option>
                                @endforeach
                            </select>
                        </x-forms.input-group>
                    </div>
                    <div class="col-md-3">
                        <x-forms.label class="my-3" fieldId="partner_id"
                                       fieldLabel="Партнер:">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="partner_id" id="partner_id"
                                    data-live-search="true">
                                <option value="">--</option>
                                @foreach ($partners as $partner)
                                    <option
                                            @if($partner->id == $service->partner_id) selected @endif
                                    value="{{ $partner->id }}">{{ mb_ucwords($partner->name) }}</option>
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
                    <div class="col-md-3">
                        <x-forms.select fieldId="country_id" fieldLabel="Страна" fieldName="country_id"
                                        search="true">
                            <option value="">--</option>
                            @foreach($countries as $country)

                                <option @if($country->id == $service->country_id)
                                            selected
                                        @endif value="{{$country->id}}">{{$country->name}}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-md-3">
                        <x-forms.select fieldId="region_id" fieldLabel="Город:" fieldName="region_id"
                                        search="true">
                            <option value="{{$application->region?->id ?? ''}}">{{$application->region?->name ?? '--'}}</option>
                            {{--                            @foreach($cities as $city)--}}

                            {{--                                <option @if($city->id == $service->region_id)--}}
                            {{--                                            selected--}}
                            {{--                                        @endif--}}
                            {{--                                        value="{{$city->id}}">{{$city->name}}</option>--}}
                            {{--                            @endforeach--}}
                        </x-forms.select>
                    </div>
                    <div class="col-md-3">
                        <x-forms.label class="my-3" fieldId="hotel_id"
                                       fieldLabel="Отель:">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="hotel_id" id="hotel_id"
                                    data-live-search="true">
                                <option value="">--</option>
                                @foreach ($hotels as $hotel)
                                    <option
                                            @if($hotel->id == $service->hotel_id) selected @endif
                                    value="{{ $hotel->id }}">{{ mb_ucwords($hotel->name) }}</option>
                                @endforeach
                            </select>

                            <x-slot name="append">
                                <button type="button"
                                        class="btn btn-outline-secondary border-grey add-hotel"
                                        data-toggle="tooltip"
                                        data-original-title="{{ __('app.add').'  '.__('app.new').' '.__('modules.tickets.agents') }}">@lang('app.add')</button>
                            </x-slot>
                        </x-forms.input-group>
                    </div>
                </div>
                <div class="row p-20 pb-0">

                    <div class="col-md-3">
                        <x-forms.select fieldId="room_type_id" fieldLabel="Тип номера:" fieldName="room_type_id"
                                        search="true">
                            <option value="">--</option>
                            @foreach($bedTypes as $type)
                                <option
                                        @if($type->id == $service->room_type_id) selected @endif
                                value="{{$type->id}}">{{$type->name}}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-md-3">
                        <x-forms.select fieldId="bad_type" fieldLabel="Тип размещения:" fieldName="bad_type"
                                        search="true">
                            <option value="">--</option>
                            @foreach($bedTypes as $type)
                                <option value="{{$type->id}}">{{$type->name}}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-md-3">
                        <x-forms.select fieldId="meal_id" fieldLabel="Питание:" fieldName="meal_id"
                                        search="true">
                            <option value="">--</option>
                            @foreach($mealTypes as $type)
                                <option
                                        @if($type->id == $service->meal_id) selected @endif
                                value="{{$type->id}}">{{$type->name}}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-md-3">
                        <x-forms.select fieldId="schema_id" fieldLabel="Схема мест:" fieldName="schema_id"
                                        search="true">
                            <option value="">--</option>
                            @foreach($schemas as $schema)
                                <option @if($schema->id == $service->schema_id)
                                            selected
                                        @endif value="{{$schema->id}}">{{$schema->name}}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                </div>
                
                <div class="row p-20">
                    <div class="col">
                        <div class="row">
                            <div class="col-md-2">
                                <x-forms.number fieldId="net_price" fieldLabel="Себестоимость:"
                                                fieldName="net_price"
                                                :fieldValue="$service->net_price"
                                                fieldRequired="true">
                                </x-forms.number>
                            </div>
                            <div class="col-md-2">
                                <x-forms.select fieldId="net_currency_id"
                                                fieldName="net_currency_id" fieldClass="form-control select-picker">
                                    <option value="">--</option>
                                    @foreach($currencies as $currency)
                                        <option
                                                @if($currency->id == ($service->net_currency_id ?? company()->currency_id)) selected
                                                @endif
                                                value="{{$currency->id}}">{{$currency->currency_symbol}}
                                        </option>

                                    @endforeach

                                </x-forms.select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.text fieldId="net_exchange_rate"
                                              :fieldLabel="__('modules.currencySettings.exchangeRate')"
                                              fieldName="net_exchange_rate" fieldRequired="true"
                                              :fieldValue="$service->net_exchange_rate ?? company()->currency->exchange_rate"
                                              fieldReadOnly="true"
                                              :fieldHelp="( company()->currency->currency_code.' '.__('app.to').' '.$currencyCode )"/>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <x-forms.number fieldId="price" fieldLabel="Стоимость клиенту:"
                                                fieldName="price"
                                                :fieldValue="$service->price"
                                                fieldRequired="true">
                                </x-forms.number>
                            </div>
                            <div class="col-md-2">
                                <x-forms.select fieldId="currency_id"
                                                fieldName="currency_id" fieldClass="form-control select-picker">
                                    <option value="">--</option>
                                    @foreach($currencies as $currency)
                                        <option
                                                @if($currency->id == ($service->currency_id ?? company()->currency_id)) selected
                                                @endif
                                                value="{{$currency->id}}">{{$currency->currency_symbol}}</option>

                                    @endforeach

                                </x-forms.select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.text fieldId="exchange_rate"
                                              :fieldLabel="__('modules.currencySettings.exchangeRate')"
                                              fieldName="exchange_rate" fieldRequired="true"
                                              :fieldValue="$service->exchange_rate ?? company()->currency->exchange_rate"
                                              fieldReadOnly="true"
                                              :fieldHelp="( company()->currency->currency_code.' '.__('app.to').' '.$currencyCode )"/>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="row p-20">
                    <div class="col-md-3">
                        <x-forms.datepicker fieldId="date_from" :fieldLabel="'Дата начала:'"
                                            :fieldValue="$service->date_from?->format('d.m.Y')"
                                            fieldName="date_from" :fieldPlaceholder="__('placeholders.date')"
                        />
                    </div>
                    <div class="col-md-3">
                        <x-forms.datepicker fieldId="date_to" :fieldLabel="'Дата окончания:'"
                                            :fieldValue="$service->date_to?->format('d.m.Y')"

                                            fieldName="date_to" :fieldPlaceholder="__('placeholders.date')"
                        />
                    </div>
                    <div class="col-md-2">
                        <x-forms.select fieldId="adults_count" fieldLabel="Количество взрослых:"
                                        fieldName="adults_count"
                                        search="true">
                            @for($i = 0; $i < 10; $i++)
                                <option
                                        @if($service->adults_count == $i)
                                            selected
                                        @endif

                                        value="{{$i}}">{{$i}}</option>

                            @endfor
                        </x-forms.select>
                    </div>
                    <div class="col-md-2">
                        <x-forms.select fieldId="children_count" fieldLabel="Количество младенцев:"
                                        fieldName="children_count"
                                        search="true">
                            @for($i = 0; $i < 10; $i++)
                                <option
                                        @if($service->children_count == $i)
                                            selected
                                        @endif

                                        value="{{$i}}">{{$i}}</option>
                            @endfor
                        </x-forms.select>
                    </div>
                    <div class="col-md-2">
                        <x-forms.select fieldId="infants_count" fieldLabel="Количество детей:"
                                        fieldName="infants_count"
                                        search="true">
                            @for($i = 0; $i < 10; $i++)
                                <option
                                        @if($service->infants_count == $i)
                                            selected
                                        @endif
                                        value="{{$i}}">{{$i}}</option>
                            @endfor
                        </x-forms.select>
                    </div>
                </div>

                <x-form-actions>
                    <x-forms.button-primary id="save-services-form" class="mr-3"
                                            icon="check">@lang('app.save')
                    </x-forms.button-primary>

                    <x-forms.button-cancel :link="route('services.index')"
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

        $(document).ready(function () {
            datepicker('#date_from', {
                position: 'bl',
                ...datepickerConfig
            });
            datepicker('#date_to', {
                position: 'bl',
                ...datepickerConfig
            });
        })

        $('#save-services-form').click(function () {
            const url = "{{ route('services.update', $service->id) }}";

            $.easyAjax({
                url: url,
                container: '#save-services-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                file: true,
                buttonSelector: "#save-service-form",
                data: $('#save-service-data-form').serialize(),
                success: function (response) {
                    if (response.status === 'success') {
                        window.location.href = '{{route('services.index')}}';
                    }
                }
            });
        });

        $.ajax({
            url: '{{route('get-regions')}}', // Replace with your API endpoint
            method: 'GET',
            data: {
                to_country_id: $('#country_id').val(),
            },
            success: function (response) {
                $('#region_id').val('');
                $('#region_id').empty();
                $('#region_id').append($('<option>', {
                    value: '',
                    text: '--',
                }));
                $.each(response, function (index, option) {
                    $('#region_id').append($('<option>', {
                        value: option.id,
                        text: option.name,
                        selected: ({{$service->region_id ?? 0}} === option.id)
                    }));
                });
                $('#region_id').selectpicker('refresh');
            },
            error: function () {
                console.error('API request failed');
            }
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
    });

</script>
