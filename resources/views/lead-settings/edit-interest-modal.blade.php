<x-form id="editLeadInterest" method="PUT" class="ajax-form">
    <div class="modal-header">
        <h5 class="modal-title" id="modelHeading">@lang('modules.lead.editLeadInterest')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">×</span></button>
    </div>
    <div class="modal-body">
        <div class="portlet-body">
            <div class="form-body">
                <div class="row">
                    <div class="col-lg-12">
                        <x-forms.text fieldId="type" :fieldLabel="__('modules.lead.leadInterest')"
                                      fieldName="type" :fieldValue="interest->type" fieldRequired="true">
                        </x-forms.text>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.close')</x-forms.button-cancel>
        <x-forms.button-primary id="save-interest" icon="check">@lang('app.save')</x-forms.button-primary>
    </div>
</x-form>

<script>
    // save channel
    $('#save-interest').click(function () {
        $.easyAjax({
            url: "{{route('lead-interest-settings.update', $interest->id)}}",
            container: '#editLeadInterest',
            type: "POST",
            blockUI: true,
            disableButton: true,
            buttonSelector: "#save-interest",
            data: $('#editLeadInterest').serialize(),
            success: function (response) {
                if (response.status == "success") {
                    window.location.reload();
                }
            }
        })
    });
</script>
