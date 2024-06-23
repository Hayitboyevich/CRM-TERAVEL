<div class="modal-header">
    <h5 class="modal-title">@lang('modules.customFields.addField')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <x-form id="editForm" method="PUT" class="ajax-form">
            <div class="row">

                <div class="col-lg-6">
                    <x-forms.select fieldId="module" :fieldLabel="__('app.module')" fieldName="social_network_id" search="true">
                        <option></option>
                        @foreach ($socials as $social)
                            <option value="{{ $social->id }}" @if($workflow->social->id == $social->id) selected @endif>{{ mb_ucfirst($social->name) }}</option>
                        @endforeach
                    </x-forms.select>
                </div>
                <div class="col-lg-6">
                    <x-forms.select fieldId="type" :fieldLabel="__('modules.customFields.fieldType')" fieldName="social_event_id" search="true">
                        <option></option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}" @if($workflow->socialEvent->id == $event->id) selected @endif>{{ $event->event }}</option>
                        @endforeach
                    </x-forms.select>
                </div>

                <div class="col-lg-6">
                    <x-forms.select fieldId="type" :fieldLabel="__('modules.customFields.fieldType')" fieldName="condition_id" search="true">
                        <option></option>
                        @foreach ($conditions as $condition)
                            <option value="{{ $condition->id }}" @if($workflow->condition->id == $condition->id) selected @endif>{{ $condition->name }}</option>
                        @endforeach
                    </x-forms.select>
                </div>
                <div class="col-lg-6">
                    <x-forms.select fieldId="type" :fieldLabel="__('modules.customFields.fieldType')" fieldName="verify_id" search="true">
                        <option></option>
                        @foreach ($verifies as $verify)
                            <option value="{{ $verify->id }}" @if($workflow->verify->id == $verify->id) selected @endif>{{ $verify->name }}</option>
                        @endforeach
                    </x-forms.select>
                </div>

                <div class="col-lg-12">
                    <x-forms.text :fieldLabel="__('modules.customFields.label')" fieldName="text" :fieldValue="$workflow->text" fieldId="label"  fieldRequired="true"  />
                </div>
            </div>
        </x-form>
    </div>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="update-workflow" icon="check">@lang('app.save')</x-forms.button-primary>
</div>

<script>

    $(".select-picker").selectpicker();

    var $insertBefore = $('#insertBefore');
    var $i = 1;

    $('#update-workflow').click(function () {
        $.easyAjax({
            url: "{{route('webhook.update', $workflow->id)}}",
            container: '#editForm',
            type: "POST",
            data: $('#editForm').serialize(),
            file: true,
            blockUI: true,
            buttonSelector: "#update-workflow",
            success: function (response) {
                if (response.status === 'success') {
                    window.location.reload();
                }
            }
        })
        return false;
    })

</script>


