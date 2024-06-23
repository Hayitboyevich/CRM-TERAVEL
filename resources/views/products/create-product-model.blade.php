<x-form id="createProduct" method="POST" class="form-horizontal">
    <div class="modal-header">
        <h5 class="modal-title">@lang('app.addNew') @lang('modules.module.product')</h5>
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
        <x-forms.button-primary id="save-product" icon="check">@lang('app.save')</x-forms.button-primary>
    </div>
</x-form>

<script>

    $('#save-product').click(function () {
        $.easyAjax({
            url: "{{ route('product-settings.store') }}",
            container: '#createProduct',
            type: "POST",
            blockUI: true,
            data: $('#createProduct').serialize(),
            disableButton: true,
            buttonSelector: "#save-product",
            success: function (response) {
                if (response.status == "success") {
                    // Update the dropdown options with the new list
                    $('#type_id').html(response.data);
                    // Refresh the select picker to show new values
                    $('#type_id').selectpicker('refresh');
                    // Close the modal after updating the dropdown
                    $(MODAL_LG).modal('hide');
                }
            }
        });
    });


</script>
