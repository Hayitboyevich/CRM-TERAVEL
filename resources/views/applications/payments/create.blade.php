<head>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        <?php $counterId = company()->counter_id; ?>
        (function(m,e,t,r,i,k,a){
            m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();
            for (var j = 0; j < document.scripts.length; j++) {
                if (document.scripts[j].src === r) { return; }
            }
            k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a);
        })(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");


        @if($counterId != null)
            ym(<?php echo $counterId; ?>, "init", {
                clickmap: true,
                trackLinks: true,
                accurateTrackBounce: true,
                webvisor: true,
                ecommerce: "dataLayer"
            });
        @endif

    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/<?php echo $counterId; ?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->

</head>
<div class="row">
    <div class="col-sm-12">
        <x-form id="save-payments-data-form" method="POST">
            <div class="bg-white rounded">
                <div class="row p-20 pb-0">
                    <input hidden name="paid_for" id="paid_for" value="{{$type}}">
                    <div class="col-md-2">
                        <x-forms.datepicker fieldId="payment_date" fieldLabel="Дата платежа:"
                                            :fieldValue="now()->format(company()->date_format)"
                                            fieldRequired="true"
                                            fieldName="payment_date" :fieldPlaceholder="__('placeholders.date')"/>
                    </div>
                    <div class="col-md-2 bootstrap-timepicker timepicker">
                        <x-forms.text fieldLabel="Время оплаты"
                                      fieldName="payment_time"
                                      fieldId="payment_time"
                                      fieldRequired="true"/>
                    </div>
                    <div class="col-md-2">
                        <x-forms.select fieldId="type" fieldLabel="Type"
                                        fieldName="type">

                            <option @if($type == "partner") selected @endif value="debit">Расход</option>
                            <option @if($type == "client") selected @endif value="credit">Приход</option>
                        </x-forms.select>
                    </div>
                    <div class="col-md-2">
                        <x-forms.select fieldId="payment_type" fieldLabel="Тип платежа"
                                        fieldName="payment_type">
                            <option value="Безналичный">Безналичный</option>
                            <option value="Наличный">Наличный</option>
                            <option value="По карте">По карте</option>
                            <option value="Сертификат">Сертификат</option>
                            <option value="Терминал">Терминал</option>
                        </x-forms.select>
                    </div>
                    <div class="col-md-4">
                        <x-forms.select fieldId="bank_account_id" :fieldLabel="__('app.menu.bankaccount')"
                                        fieldName="bank_account_id"
                                        search="true">
                            <option value="">--</option>
                            @foreach ($bankDetails as $bankDetail)
                                <option value="{{ $bankDetail->id }}" data-currency-id="{{ $bankDetail->currency_id }}">
                                    {{ mb_ucwords($bankDetail->account_name) }}
                                </option>
                            @endforeach
                        </x-forms.select>
                    </div>
                </div>

                <div class="row p-20 pb-0 pt-0">
                    <div class="col-md-3">
                        <x-forms.text fieldId="amount"
                                      :fieldLabel="__('app.amount') .' заказа '"
                                      fieldName="amount"
                                      :fieldPlaceholder="__('app.amount')"
                                      :fieldRequired="true">
                        </x-forms.text>
                    </div>
                    <div class="col-md-3">
                        <x-forms.select fieldId="currency_id" :fieldLabel="__('app.currency')" fieldName="currency_id"
                                        search="true" :fieldReadOnly="true">
                            @foreach ($currencies as $currency)
                                <option @if ($currency->id == company()->currency_id) selected
                                        @endif value="{{ $currency->id }}"
                                        data-currency-code="{{ $currency->currency_code }}">
                                    {{ $currency->currency_code . ' (' . $currency->currency_symbol . ')' }}
                                </option>
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-md-2">
                        <x-forms.text fieldId="exchange_rate" :fieldLabel="__('modules.currencySettings.exchangeRate')"
                                      fieldName="exchange_rate" fieldRequired="true"
                                      :fieldValue="company()->currency->exchange_rate"
                                      fieldReadOnly="true"
                        >
                        </x-forms.text>
                    </div>
                    <div class="col-md-3">
                        <x-forms.select fieldId="partner_id" :fieldLabel="__('app.partner')" fieldName="partner_id"
                                        search="true">
                            <option value="">--</option>
                            @foreach ($partners as $partner)
                                <option value="{{$partner->id}}">
                                    {{$partner->name}}
                                </option>
                            @endforeach
                        </x-forms.select>
                    </div>
                </div>
                <x-form-actions>
                    <x-forms.button-primary id="create-payment" class="mr-3"
                                            icon="check">@lang('app.save')
                    </x-forms.button-primary>

                    <x-forms.button-cancel :link="route('applications.edit', $application->id)"
                                           class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>
            </div>
        </x-form>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#bank_account_id').change(function () {
            var selectedCurrencyId = $(this).find('option:selected').data('currency-id');

            $('#currency_id').val(selectedCurrencyId).trigger('change');
        });

        $('#payment_date').each(function (ind, el) {
            datepicker(el, {
                position: 'bl',
                ...datepickerConfig
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
        $('#payment_time').timepicker({
            @if (company()->time_format == 'H:i')
            showMeridian: false,
            @endif
        });

        $('#create-payment').click(function () {
            const url = "{{ route('applications.payments.store', $application->id) }}";
            let data = $('#save-payments-data-form').serialize();



            @if($counterId != null)
                let params = {
                    'amount': $('#amount').val(),
                    'currency': $('#currency_id option:selected').data('currency-code'),
                }
                ym(<?php echo company()->counter_id ?>, 'reachGoal', 'payment', params);
            @endif
            createPayment(data, url, "#create-payment");
        });

        function createPayment(data, url, buttonSelector) {
            $.easyAjax({
                url: url,
                container: '#save-payments-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: buttonSelector,
                file: true,
                data: data,
                success: function (response) {
                    if (response.status === 'success') {
                        if ($(MODAL_XL).hasClass('show')) {
                            $(MODAL_XL).modal('hide');
                            window.location.reload();
                        } else if (typeof response.redirectUrl !== 'undefined') {
                            window.location.href = response.redirectUrl;
                        }

                    }
                }
            });
        }

        init(RIGHT_MODAL);
    });


</script>
