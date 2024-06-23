@extends('layouts.app')

@section('content')

    <!-- SETTINGS START -->
    <div class="w-100 d-flex ">

        <x-setting-sidebar :activeMenu="$activeSettingMenu"/>

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
                    <div class="col-md-3">
                        <x-forms.text fieldLabel="Название интеграции"
                                      fieldName="name" fieldId="name"
                                      :fieldValue="$setting->name"
                        >

                        </x-forms.text>
                    </div>
                    <div class="col-md-3">
                        <x-forms.text fieldLabel="Ссылка для входа"
                                      fieldName="type" fieldId="type"
                                      :fieldValue="$setting->type"
                        >

                        </x-forms.text>
                    </div>
                    <div class="col-md-3">
                        <x-forms.text fieldLabel="Имя пользователя"
                                      fieldName="login" fieldId="login"
                                      :fieldValue="$setting->login"
                        >

                        </x-forms.text>
                    </div>
                    <div class="col-md-3">
                        <x-forms.text fieldLabel="Пароль"
                                      fieldName="password" fieldId="password"

                        >

                        </x-forms.text>
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
            $.easyAjax({
                url: "{{ route('integration-settings.update', $setting->id) }}",
                container: '#editSettings',
                type: "POST",
                data: $('#editSettings').serialize()
            })
        });

    </script>
@endpush
