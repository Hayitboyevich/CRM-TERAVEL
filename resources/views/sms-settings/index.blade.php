@extends('layouts.app')

@section('content')

    <!-- SETTINGS START -->
    <div class="w-100 d-flex ">

        @include('sections.setting-sidebar')

        <x-setting-card>
            <x-slot name="header">
                <div class="s-b-n-header" id="tabs">
                    <h2 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                        @lang($pageTitle)</h2>
                </div>
            </x-slot>
            @method('PUT')
            <div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-4 ">
                <div class="row">
                    <div class="col-lg-4">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2"
                                      fieldLabel="Sms url"
                                      fieldName="settings[sms_url]"
                                      field-value="{{ $settings['url'] }}"
                                      fieldId="sms_url"/>
                    </div>
                    <div class="col-lg-4">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2"
                                      fieldLabel="Sms Username"
                                      fieldName="settings[sms_username]"
                                      field-value="{{ $settings['username'] }}"
                                      fieldId="sms_username"/>
                    </div>

                    <div class="col-lg-4">
                        <x-forms.password class="mr-0 mr-lg-2 mr-md-2"
                                          fieldLabel="Sms Password"
                                          fieldPlaceholder="Enter new password to change"
                                          fieldName="settings[sms_password]"
                        fieldId="sms_password"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2"
                                      :fieldLabel="__('messages.smsTemplateBeforeFlight')"
                                      fieldPlaceholder="e.g. Maroqli parvoz tilaymiz" fieldRequired="true"
                                      fieldName="template[before_flight]"
                                      field-value="{{ $template['before_flight']['content'] }}"
                                      :popover="__('messages.companyNameTooltip')"
                                      fieldId="content"/>

                        <x-forms.toggle-switch class="mr-0 mr-lg-2 mr-md-2"
                                               fieldLabel="Status"
                                               fieldName="template[before_flight_status]"
                                               checked="{{ $template['before_flight']['status'] }}"
                                               fieldId="before_flight_status"/>
                    </div>
                    <div class="col-lg-6">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2"
                                      :fieldLabel="__('messages.smsTemplateAfterFlight')"
                                      fieldPlaceholder="e.g. Xush kelibsiz" fieldRequired="true"
                                      fieldName="template[after_land]"
                                        field-value="{{ $template['after_land']['content'] }}"
                                      :popover="__('messages.companyNameTooltip')"
                                      fieldId="content" />

                        <x-forms.toggle-switch class="mr-0 mr-lg-2 mr-md-2"
                                               fieldLabel="Status"
                                               fieldName="template[after_land_status]"
                                               checked="{{ $template['after_land']['status'] }}"
                                               fieldId="after_land_status"/>
                    </div>
                    <div class="col-lg-6">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2"
                                      :fieldLabel="__('messages.smsTemplateForBirthday')"
                                      fieldPlaceholder="e.g. Tabriklaymiz!" fieldRequired="true"
                                      fieldName="template[before_birthday]"
                                      field-value="{{ $template['before_birthday']['content'] }}"
                                      fieldId="content"/>

                        <x-forms.toggle-switch class="mr-0 mr-lg-2 mr-md-2"
                                               fieldLabel="Status"
                                               fieldName="template[before_birthday_status]"
                                               checked="{{ $template['before_birthday']['status'] }}"
                                               fieldId="before_birthday_status"/>
                    </div>
                </div>
            </div>


            <x-slot name="action">
                <!-- Buttons Start -->
                <div class="w-100 border-top-grey">
                    <x-setting-form-actions>
                        <x-forms.button-primary id="save-form" class="mr-3" icon="check">@lang('app.save')
                        </x-forms.button-primary>
                    </x-setting-form-actions>
                </div>
                <!-- Buttons End -->
            </x-slot>

        </x-setting-card>

    </div>
    <!-- SETTINGS END -->
@endsection

@push('scripts')

    <script>
        $('#save-form').click(function () {
            var url = "{{ route('sms-settings.update', company()->id) }}";

            $.easyAjax({
                url: url,
                container: '#editSettings',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-form",
                data: $('#editSettings').serialize(),
            })
        });
    </script>
@endpush
