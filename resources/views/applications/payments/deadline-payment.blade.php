<div class="row">
    <div class="col-sm-12">
        <x-form id="save-deadline-data-form" method="PUT">
            <div class="bg-white rounded">
                <div class="row p-20 pb-0">
                    <input hidden name="type" id="type" value="{{$type}}">
                    <div class="col-md-2">
                        <x-forms.datepicker fieldId="deadline" fieldLabel="Дата платежа:"
                                            :fieldValue="$deadline?->deadline ?? now()->format(company()->date_format)"
                                            fieldRequired="true"
                                            fieldName="deadline"
                                            :fieldPlaceholder="__('placeholders.date')"/>
                    </div>
                    <div class="col-md-2">
                        <x-forms.number fieldLabel="Процент"
                                        fieldName="percent"
                                        :fieldValue="$deadline?->percent"
                                        fieldId="percent"
                                        fieldRequired="true"/>
                    </div>
                    <div class="col-md-2">
                        <x-forms.number fieldLabel="Сумма"
                                        fieldName="amount"
                                        :fieldValue="$deadline?->amount"
                                        fieldId="amount"
                                        fieldRequired="true"/>
                    </div>
                </div>
                <x-form-actions>
                    <x-forms.button-primary id="create-deadline" class="mr-3"
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
        $('#deadline').each(function (ind, el) {
            datepicker(el, {
                position: 'bl',
                ...datepickerConfig
            });
        });

        $('#create-deadline').click(function () {
            const url = "{{ route('payment-deadline.update', $application->id) }}";
            let data = $('#save-deadline-data-form').serialize();
            createPayment(data, url, "#create-payment");
        });

        function createPayment(data, url, buttonSelector) {
            $.easyAjax({
                url: url,
                container: '#save-deadline-data-form',
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