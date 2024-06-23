<x-form id="createPartner" method="POST" class="form-horizontal">
    <div class="modal-header">
        <h5 class="modal-title">@lang('app.addNew') @lang('modules.module.partner')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        <div class="portlet-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="my-3">
                        <x-forms.text fieldLabel="Name" fieldId="name" fieldName="name" placeholder="Enter name">

                        </x-forms.text>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
        <x-forms.button-primary id="save-partner" icon="check">@lang('app.save')</x-forms.button-primary>
    </div>
</x-form>

<script>

    // save agent
    $('#save-partner').click(function () {

        $.easyAjax({
            url: "{{ route('partner-settings.store') }}",
            container: '#createPartner',
            type: "POST",
            blockUI: true,
            data: $('#createPartner').serialize(),
            disableButton: true,
            buttonSelector: "#save-partner",
            success: function (response) {
                if (response.status == "success") {
                    if ($('table#example').length) {
                        window.location.reload();
                    } else {
                        $('#partner_id').html(response.data);
                        $('#partner_id').selectpicker('refresh');
                        $(MODAL_LG).modal('hide');
                    }
                }
            }
        })
    });

</script>
