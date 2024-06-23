<div class="row">
    <div class="col-sm-12">
        <x-form id="save-package-data-form" method="PUT">
            <div class="add-application bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    Общая информация</h4>

                {{--                <input hidden name="lead_id" value="{{  $applicationId }}">--}}
                <div class="row p-20">
                    <div class="col-md-3">
                        <x-forms.text fieldId="name" :fieldLabel="'Название пакета:'"
                                      fieldName="name" :fieldValue=" $package->name "
                        />
                    </div>
                    <div class="col-md-3">
                        <x-forms.textarea fieldId="description" :fieldLabel="'Краткое описание услуги:'"
                                          fieldName="description" :fieldValue=" $package->description "
                        />
                    </div>
                    <div class="col-md-3">
                        <x-forms.number fieldId="quantity" :fieldLabel="'Количество:'"
                                        fieldName="quantity" :fieldValue="$package->quantity "
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

                                <option
                                        @if(in_array($service->id, $package->services->pluck('id')->toArray()))
                                            selected
                                        @endif
                                        value="{{ $service->id }}">{{ ($service->product?->name . ' (' . date('d M', strtotime($service->date_from)).' - '. date('d M', strtotime($service->date_to)).')') }}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                </div>


                <div class="row p-20">
                    <div class="col-md-3">
                        <x-forms.select
                                fieldId="type_id"
                                fieldLabel="Тип:"
                                fieldName="type_id"
                                search="true">
                            <option value="-">-</option>
                            @foreach ($products as $product)
                                <option
                                        @if($product->id == $package->type_id) selected @endif
                                value="{{ $product->id }}">{{ ($product->name) }}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-md-3">
                        <x-forms.select fieldId="country_id" fieldLabel="Страна" fieldName="country_id"
                                        search="true">
                            <option value="">--</option>
                            @foreach($countries as $country)
                                <option
                                        @if($package->country_id == $country->id) selected @endif
                                value="{{$country->id}}">{{$country->name}}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-md-3">
                        <x-forms.select fieldId="region_id" fieldLabel="Город:" fieldName="region_id"
                                        search="true">
                            <option value="">--</option>
                            @foreach($cities as $city)

                                <option
                                        @if($package->region_id == $city->id) selected @endif
                                value="{{$city->id}}">{{$city->name}}</option>
                            @endforeach
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
                                            @if($hotel->id == $package->hotel_id) selected @endif
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
                <div class="row p-20">
                    <div class="col-md-2">
                        <x-forms.select fieldId="room_type" fieldLabel="Тип номера:" fieldName="room_type"
                                        search="true">
                            <option value="">--</option>
                        </x-forms.select>
                    </div>
                    <div class="col-md-2">
                        <x-forms.select fieldId="room_type_id" fieldLabel="Тип размещения:" fieldName="room_type_id"
                                        search="true">
                            <option value="">--</option>
                            @foreach($bedTypes as $type)
                                <option
                                        @if($type->id == $package->room_type_id) selected @endif

                                value="{{$type->id}}">{{$type->name}}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-md-3">
                        <x-forms.select fieldId="meal_id" fieldLabel="Тип питания:" fieldName="meal_id"
                                        search="true">
                            <option value="">--</option>
                            @foreach($mealTypes as $type)
                                <option
                                        @if($type->id == $package->meal_id) selected @endif
                                value="{{$type->id}}">{{$type->name}}</option>
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
                                                :fieldValue="$package->net_price"

                                                fieldRequired="true">
                                </x-forms.number>
                            </div>
                            <div class="col-md-2">
                                <x-forms.select fieldId="net_currency_id"
                                                fieldName="net_currency_id" fieldClass="form-control select-picker">
                                    <option value="">--</option>
                                    @foreach($currencies as $currency)
                                        <option
                                                @if($currency->id == $package->currency_id) selected
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
                                              :fieldValue="$package->net_exchange_rate ?? company()->currency->exchange_rate"
                                              fieldReadOnly="true"
                                              :fieldHelp="( company()->currency->currency_code.' '.__('app.to').' '.$currencyCode )"/>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <x-forms.number fieldId="price" fieldLabel="Стоимость клиенту:"
                                                fieldName="price"
                                                :fieldValue="$package->price"
                                                fieldRequired="true">
                                </x-forms.number>
                            </div>
                            <div class="col-md-2">
                                <x-forms.select fieldId="currency_id"
                                                fieldName="currency_id"
                                                fieldClass="form-control select-picker">
                                    <option value="">--</option>
                                    @foreach($currencies as $currency)
                                        <option
                                                @if($currency->id == $package->currency_id) selected @endif
                                        value="{{$currency->id}}">{{$currency->currency_symbol}}</option>

                                    @endforeach

                                </x-forms.select>
                            </div>
                            <div class="col-md-3">
                                <x-forms.text fieldId="exchange_rate"
                                              :fieldLabel="__('modules.currencySettings.exchangeRate')"
                                              fieldName="exchange_rate" fieldRequired="true"
                                              :fieldValue="$package->exchange_rate ?? company()->currency->exchange_rate"
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
                                            fieldName="date_from"
                                            :fieldValue="$package->date_from"
                                            :fieldPlaceholder="__('placeholders.date')"
                                            :fieldValue="$package->date_from?->format('d.m.Y') ?? now()->addDays(14)->timezone(company()->timezone)->format(company()->date_format)"/>
                    </div>
                    <div class="col-md-2">
                        <x-forms.datepicker fieldId="date_to" fieldLabel="Дата тура до:"
                                            :fieldValue="$package->date_to"
                                            fieldName="date_to" :fieldPlaceholder="__('placeholders.date')"
                                            :fieldValue="$package->date_to?->format('d.m.Y') ?? now()->addDays(14)->timezone(company()->timezone)->format(company()->date_format)"/>
                    </div>

                    <div class="col-md-2">
                        <x-forms.select fieldId="adults_count" fieldLabel="Количество взрослых:"
                                        fieldName="adults_count"
                                        search="true">
                            <option value="0">0</option>
                            @for($i = 1; $i<=100; $i++)
                                <option @if($i==2 || $i==$package->adults_count) selected
                                        @endif value="{{$i}}">{{$i}}</option>
                            @endfor
                        </x-forms.select>
                    </div>
                    <div class="col-md-2">
                        <x-forms.select fieldId="infants_count" fieldLabel="Количество младенцев:"
                                        fieldName="infants_count"
                                        search="true">
                            <option value="0">0</option>
                            @for($i = 1; $i<=100; $i++)
                                <option
                                        @if($i==$package->infants_count) selected @endif
                                value="{{$i}}">{{$i}}</option>
                            @endfor
                        </x-forms.select>
                    </div>
                    <div class="col-md-2">
                        <x-forms.select fieldId="children_count" fieldLabel="Количество детей:"
                                        fieldName="children_count"
                                        search="true">
                            <option value="0">0</option>
                            @for($i = 1; $i<=100; $i++)
                                <option
                                        @if($i==$package->children_count) selected @endif
                                value="{{$i}}">{{$i}}</option>
                            @endfor
                        </x-forms.select>
                    </div>
                </div>

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

        $('#nett_currency_id').change(function () {
            let curId = $(this).val();
            $('#nett_currency_id').val(curId);

            let companyCurrency = '{{ company()->currency_id }}';

            if (curId === companyCurrency) {
                $('#nett_exchange_rate').prop('readonly', true);

            } else {
                $('#nett_exchange_rate').prop('readonly', false);

            }

            let token = "{{ csrf_token() }}";

            $.easyAjax({
                url: "{{ route('payments.account_list') }}",
                type: "GET",
                blockUI: true,
                data: {'curId': curId, _token: token},
                success: function (response) {
                    if (response.status === 'success') {
                        $('#nett_exchange_rate').val(response.exchangeRate);

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
            $('#country_id').change(function () {
                // $('#country_id').selectpicker('refresh');

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
            const url = "{{ route('packages.update', $package->id) }}";

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


    });

</script>
