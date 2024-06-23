<link rel="stylesheet" href="{{ asset('vendor/css/bootstrap-colorpicker.css') }}"/>

<x-form id="editDeadline" method="PUT" class="ajax-form">
    <div class="modal-header">
        <h5 class="modal-title" id="modelHeading">@lang('modules.lead.editLeadStatus')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">×</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="portlet-body">
            <div class="form-body">
                <div class="row">
                    <div class="col-sm-4 col-md-12 col-lg-6">
                        <x-forms.text fieldId="note" fieldLabel="Примечание"
                                      fieldName="note" fieldRequired="true"
                                      fieldPlaceholder="Напишите что-нибудь, чтобы изменить срок">
                        </x-forms.text>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class=" modal-footer">
        <x-forms.button-cancel data-dismiss="modal"
                               class="border-0 mr-3">@lang('app.close')</x-forms.button-cancel>
        <x-forms.button-primary id="save-status" icon="check">@lang('app.save')</x-forms.button-primary>
    </div>
</x-form>

<script src="{{ asset('vendor/jquery/bootstrap-colorpicker.js') }}"></script>

<script>


    // save status
    $('#save-status').click(function () {
        $.easyAjax({
            url: "{{route('deadline.update', $leadId)}}",
            container: '#editDeadline',
            type: "POST",
            blockUI: true,
            disableButton: true,
            buttonSelector: "#save-status",
            data: $('#editDeadline').serialize(),
            success: function (response) {
                if (response.status == "success") {
                    window.location.reload();
                }
            }
        })
    });

</script>
