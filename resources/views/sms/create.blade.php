@extends('layouts.app')

@php
    $addPermission = user()->permission('add_smsmailing');
@endphp

@section('content')

    <div class="content-wrapper">

        <div class="row">
            <div class="col-sm-12">
                <x-form id="save-sms-data-form">
                    <div class="add-sms bg-white rounded">
                        <div class="row p-20 pb-0">
                            <div class="col-lg-4 col-md-6">
                                <x-forms.select
                                        fieldId="user_id[]"
                                        fieldLabel="Пользователь"
                                        fieldName="user_id[]"
                                        multiple="true"
                                        search="true">
                                    <option value="0">Все</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}">{{ ($client->name . ' (' . $client->mobile.')') }}</option>
                                    @endforeach
                                </x-forms.select>

                            </div>

                            <div class="col-md-4">
                                <div class="form-group my-3">
                                    <x-forms.label fieldId="delivery_date"
                                                   fieldLabel="Дата отправки">
                                    </x-forms.label>
                                    <div class="input-group">
                                        <input type="text" id="delivery_date" name="delivery_date"
                                               class=" position-relative text-dark font-weight-normal form-control height-35 f-14 rounded p-0 text-left"
                                               placeholder="@lang('placeholders.date')"
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="bootstrap-timepicker timepicker">
                                    <x-forms.text :fieldLabel="__('modules.events.startOnTime')"
                                                  :fieldPlaceholder="__('placeholders.hours')"
                                                  fieldName="delivery_time"
                                                  fieldId="delivery_time"
                                                  fieldRequired="true"/>
                                </div>
                            </div>
                        </div>
                        <div class="row p-20 pb-0">
                            <div class="col-md-12">
                                <div class="form-group my-3">
                                    <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" fieldLabel="Сообщение ${clientName}"
                                                      fieldName="message" fieldId="message"
                                                      fieldPlaceholder="Сообщение">
                                    </x-forms.textarea>
                                </div>
                            </div>
                        </div>
                        <x-form-actions>
                            <x-forms.button-primary id="save-sms-form" class="mr-3" icon="check">@lang('app.save')
                            </x-forms.button-primary>

                            <x-forms.button-cancel :link="route('sms.index')" class="border-0">@lang('app.cancel')
                            </x-forms.button-cancel>
                        </x-form-actions>
                    </div>


                </x-form>

            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="{{ asset('vendor/jquery/dropzone.min.js') }}"></script>

    <script>

        $(document).ready(function () {
            $('#save-sms-form').click(function () {
                    const url = "{{ route('sms.store') }}";
                    let data = $('#save-sms-data-form').serialize();
                    saveSms(data, url, "#save-sms-form");

                }
            );
            $('#delivery_time').timepicker({
                @if (company()->time_format == 'H:i')
                showMeridian: false,
                @endif
            });

            function saveSms(data, url, buttonSelector) {
                $.easyAjax({
                    url: url,
                    container: '#save-sms-data-form',
                    type: "POST",
                    file: true,
                    disableButton: true,
                    blockUI: true,
                    buttonSelector: buttonSelector,
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
                    }
                });

            }

            const dp1 = datepicker('#delivery_date', {
                position: 'bl',
                ...datepickerConfig
            });
        });

    </script>
@endpush

