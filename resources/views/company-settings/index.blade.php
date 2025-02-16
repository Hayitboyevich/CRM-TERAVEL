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

            <div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-4 ">
                @method('PUT')
                <div class="row">
                    <div class="col-lg-6">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2"
                                      :fieldLabel="__('modules.accountSettings.companyName')"
                                      fieldPlaceholder="e.g. Acme Corporation" fieldRequired="true"
                                      fieldName="company_name"
                                      :popover="__('messages.companyNameTooltip')"
                                      fieldId="company_name" :fieldValue="company()->company_name"/>
                    </div>
                    <div class="col-lg-6">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2"
                                      :fieldLabel="__('modules.accountSettings.companyEmail')"
                                      :fieldPlaceholder="__('placeholders.email')" fieldRequired="true"
                                      fieldName="company_email"
                                      fieldId="company_email" :fieldValue="company()->company_email"/>

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2"
                                      :fieldLabel="__('modules.accountSettings.companyPhone')"
                                      fieldPlaceholder="e.g. +19876543" fieldRequired="true" fieldName="company_phone"
                                      fieldId="company_phone" :fieldValue="company()->company_phone"/>
                    </div>
                    <div class="col-lg-6">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2"
                                      :fieldLabel="__('modules.accountSettings.companyWebsite')"
                                      fieldPlaceholder="e.g. https://www.spacex.com/" fieldRequired="false"
                                      fieldName="website"
                                      fieldId="website" :fieldValue="company()->website"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2"
                                      :fieldLabel="__('modules.accountSettings.companyCounterId')"
                                      fieldName="counter_id"
                                      fieldId="counter_id" :fieldValue="company()?->counter_id"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2"
                                      :fieldLabel="__('modules.accountSettings.goalIdLeadCreated')"
                                      fieldName="lead_created_goal_id"
                                      fieldId="lead_created_goal_id" :fieldValue="company()->metricaGoals->where('name', 'lead_created')->first()?->goal_id"/>
                    </div>
                    <div class="col-lg-6">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2"
                                      :fieldLabel="__('modules.accountSettings.goalIdOrderCreated')"
                                      fieldName="order_created_goal_id"
                                      fieldId="order_created_goal_id" :fieldValue="company()->metricaGoals->where('name', 'order_created')->first()?->goal_id"/>
                    </div>
                </div>

            </div>

            <x-slot name="action">
                <!-- Buttons Start -->
                <div class="w-100 border-top-grey">
                    <x-setting-form-actions>
                        <x-forms.button-primary id="save-form" class="mr-3" icon="check">@lang('app.save')
                        </x-forms.button-primary>
                        </x-settingsform-actions>
                </div>
                <!-- Buttons End -->
            </x-slot>

        </x-setting-card>

    </div>
    <!-- SETTINGS END -->
@endsection

@push('scripts')
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCCl9wZfCCqZ9BtkD_ItVG8dAWT9BTMVB0&callback=initMap&libraries=places&v=weekly"
        async>
    </script>
    <script>
        $('#save-form').click(function () {
            var url = "{{ route('company-settings.update', company()->id) }}";

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
