<div class="modal-header">
    <h5 class="modal-title">@lang('modules.customFields.addField')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <x-form id="createForm" method="POST" class="ajax-form">
            <div class="row">

                <div class="col-lg-6">
                    <x-forms.select fieldId="module" :fieldLabel="__('app.social_type')" fieldName="social_network_id" search="true">
                        <option></option>
                        @foreach ($socials as $social)
                            <option value="{{ $social->id }}">{{ mb_ucfirst($social->name) }}</option>
                        @endforeach
                    </x-forms.select>
                </div>
                <div class="col-lg-6">
                    <x-forms.select fieldId="type" :fieldLabel="__('app.event')" fieldName="social_event_id" search="true">
                            <option></option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}">{{ $event->event }}</option>
                            @endforeach
                    </x-forms.select>
                </div>

                <div class="col-lg-6">
                    <x-forms.select fieldId="type" :fieldLabel="__('modules.customFields.fieldType')" fieldName="condition_id" search="true">
                        <option></option>
                        @foreach ($conditions as $condition)
                            <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                        @endforeach
                    </x-forms.select>
                </div>
                <div class="col-lg-6">
                    <x-forms.select fieldId="type" :fieldLabel="__('app.validation')" fieldName="verify_id" search="true">
                        <option></option>
                        @foreach ($verifies as $verify)
                            <option value="{{ $verify->id }}">{{ $verify->name }}</option>
                        @endforeach
                    </x-forms.select>
                </div>

                <div class="col-lg-12">
                    <x-forms.text :fieldLabel="__('modules.customFields.label')" fieldName="text" fieldId="label"  fieldRequired="true"  />
                </div>
            </div>
        </x-form>
    </div>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="save-workflow" icon="check">@lang('app.save')</x-forms.button-primary>
</div>

<script>

    $(".select-picker").selectpicker();

    var $insertBefore = $('#insertBefore');
    var $i = 1;

    $('#save-workflow').click(function () {
        $.easyAjax({
            url: "{{route('webhook.store')}}",
            container: '#createForm',
            type: "POST",
            data: $('#createForm').serialize(),
            file: true,
            blockUI: true,
            buttonSelector: "#save-workflow",
            success: function (response) {
                if (response.status === 'success') {
                    window.location.reload();
                }
            }
        })
        return false;
    })

</script>

