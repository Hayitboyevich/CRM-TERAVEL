<div class="row">
    <div class="col-sm-12">
        <x-form id="save-payment-data-form">
            <input hidden id="order_id" name="order_id" value="{{$order->id}}"/>
            <input hidden id="customer_id" name="customer_id" value="{{$order?->client?->id}}"/>

            <div class="add-client bg-white rounded">

                <div class="row">
                    <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">@lang('modules.payments.paymentDetails')</h4>
                    <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">Для заказа
                        №{{$order->id}} {{$order->name}} </h4>
                </div>
                <div class="row p-20">
                    <div class="col-md-3">
                        <input type="hidden" id="currency_id" name="currency_id" value="{{company()->currency_id}}">
                        <x-forms.select fieldId="currency" :fieldLabel="__('app.currency')" fieldName="currency"
                                        search="true">
                            @if (isset($invoice))
                                <option value="{{ $invoice->currency->id }}">
                                    {{ $invoice->currency->currency_code . ' (' . $invoice->currency->currency_symbol . ')' }}
                                </option>
                            @else
                                @foreach ($currencies as $currency)
                                    <option @if ($currency->id == company()->currency_id) selected
                                            @endif value="{{ $currency->id }}"
                                            data-currency-code="{{ $currency->currency_code }}">
                                        {{ $currency->currency_code . ' (' . $currency->currency_symbol . ')' }}
                                    </option>
                                @endforeach
                            @endif
                        </x-forms.select>
                    </div>
                    <div class="col-md-3">
                        <x-forms.text fieldId="exchange_rate" :fieldLabel="__('modules.currencySettings.exchangeRate')"
                                      fieldName="exchange_rate" fieldRequired="true" :fieldValue="$exchangeRate"
                                      fieldReadOnly="true"
                                      :fieldHelp="( company()->currency->currency_code.' '.__('app.to').' '.$currencyCode )"/>
                    </div>
                    <div class="col-md-3">
                        <x-forms.datepicker fieldId="paid_on" :fieldLabel="__('modules.payments.paidOn')"
                                            fieldName="paid_on" :fieldPlaceholder="__('placeholders.date')"
                                            :fieldValue="now()->timezone(company()->timezone)->format(company()->date_format)"/>
                    </div>

                    <div class="col-md-3">
                        <x-forms.number fieldId="amount" :fieldLabel="__('modules.invoices.amount')" fieldName="amount"
                                        :fieldPlaceholder="__('placeholders.price')"
                                        fieldRequired="true"/>
                    </div>
                </div>

                <div class="row pl-20 pr-20">
                    <div class="col-md-3">
                        <x-forms.text fieldId="client_name" :fieldLabel="__('app.client')"
                                      fieldName="client_name" fieldReadOnly="true"
                                      :fieldValue="$order?->client?->name"
                                      :fieldPlaceholder="__('app.client')"
                                      :fieldRequired="false">
                        </x-forms.text>
                    </div>
                    <div class="col-md-3">
                        <x-forms.text fieldId="total"
                                      :fieldLabel="__('app.amount') .' заказа '"
                                      fieldName="total" fieldReadOnly="true"
                                      :fieldValue="currency_format($order?->total, company()->currency_id)"
                                      :fieldPlaceholder="__('app.client')"
                                      :fieldRequired="false">
                        </x-forms.text>
                    </div>
                    <div class="col-md-3">
                        <x-forms.select fieldId="bank_account_id" :fieldLabel="__('app.menu.bankaccount')"
                                        fieldName="bank_account_id"
                                        search="true">
                            <option value="">--</option>
                            {{--                            @if($viewBankAccountPermission != 'none')--}}
                            @foreach ($bankDetails as $bankDetail)
                                <option value="{{ $bankDetail->id }}">
                                    {{ mb_ucwords($bankDetail->account_name) }}
                                </option>
                            @endforeach
                            {{--                            @endif--}}
                        </x-forms.select>
                    </div>
                    <div class="col-md-3">
                        <x-forms.text fieldId="total_remind" fieldLabel="Остаток"
                                      fieldName="total_remind" fieldReadOnly="true"
                                      :fieldValue="currency_format($order?->total - $order?->total_paid, company()->currency_id)"
                                      :fieldPlaceholder="__('app.client')"
                                      :fieldRequired="false">
                        </x-forms.text>
                    </div>

                    <div class="col-lg-12">
                        <x-forms.file allowedFileExtensions="txt pdf doc xls xlsx docx rtf png jpg jpeg svg"
                                      class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('app.receipt')" fieldName="bill"
                                      fieldId="bill" :popover="__('messages.fileFormat.multipleImageFile')"/>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group my-3">
                            <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('app.remark')"
                                              fieldName="remarks" fieldId="remarks"
                                              :fieldPlaceholder="__('placeholders.payments.remark')"/>
                        </div>
                    </div>

                </div>

                <x-form-actions>
                    <x-forms.button-primary id="save-payment-form" class="mr-3" icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel :link="route('payments.index')" class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>

            </div>
        </x-form>

    </div>
</div>

<script>
    $(document).ready(function () {
        const remindPrice = {{$order->total - $order->total_paid ?? 0 }};

        $('#amount').on("input", function () {
            let remind = (remindPrice - ($('#amount').val() * $('#exchange_rate').val()));

            $('#total_remind').val(remind.toFixed(2));
        });


        datepicker('#paid_on', {
            position: 'bl',
            ...datepickerConfig
        });


        $('#save-payment-form').click(function () {
            const url = "{{ route('payments.custom.store') }}";

            $.easyAjax({
                url: url,
                container: '#save-payment-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-payment-form",
                file: true,
                data: $('#save-payment-data-form').serialize(),
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.href = response.redirectUrl;
                    }
                }
            });
        });

        $('#currency').change(function () {
            var curId = $(this).val();
            $('#currency_id').val(curId);

            var companyCurrencyName = "{{$companyCurrency->currency_code}}";
            var currentCurrencyName = $('#currency option:selected').attr('data-currency-code');
            var companyCurrency = '{{ $companyCurrency->id }}';

            if (curId == companyCurrency) {
                $('#exchange_rate').prop('readonly', true);
            } else {
                $('#exchange_rate').prop('readonly', false);
            }

            var token = "{{ csrf_token() }}";

            $.easyAjax({
                url: "{{ route('payments.account_list') }}",
                type: "GET",
                blockUI: true,
                data: {'curId': curId, _token: token},
                success: function (response) {
                    if (response.status == 'success') {
                        $('#bank_account_id').html(response.data);
                        $('#bank_account_id').selectpicker('refresh');
                        $('#exchange_rate').val(response.exchangeRate);
                        $('#exchange_rateHelp').html('( ' + companyCurrencyName + ' @lang('app.to') ' + currentCurrencyName + ' )');
                    }
                }
            });
        });

        init(RIGHT_MODAL);
    });
</script>
