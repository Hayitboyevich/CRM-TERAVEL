<style>
    .file-drop-area {
        position: relative;
        top: 15px;
        display: flex;
        align-items: center;
        width: 450px;
        max-width: 100%;
        padding: 25px;
        border: 1px black dashed;
        border-radius: 3px;
        transition: 0.2s;

    }

    .choose-file-button {
        flex-shrink: 0;
        background-color: rgba(255, 255, 255, 0.04);
        border: 1px solid black;
        border-radius: 3px;
        padding: 8px 15px;
        margin-right: 10px;
        font-size: 12px;
        text-transform: uppercase;
    }

    .file-message {
        font-size: small;
        font-weight: 300;
        line-height: 1.4;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .file-input {
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        cursor: pointer;
        opacity: 0;

    }

    .mt-100 {
        margin-top: 100px;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <x-form id="scan-passport-data-form">
            <div class="scan-passport bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    Общая информация
                    <x-forms.button-primary id="save-form" class="mr-3 float-right" icon="arrow-right">Submit
                    </x-forms.button-primary>
                </h4>
                <div class="row p-20 pb-0">
                    {{--                    <input type="hidden" name="client_id" value="{{$client_id}}">--}}
                    <div class="col-md-4">
                        <x-forms.file fieldLabel="Passport Selfie" fieldName="selfie" fieldId="passport_selfie"/>
                    </div>
                    <div class="col-md-4">
                        <x-forms.file fieldLabel="Passport Back" fieldName="back" fieldId="passport_back"/>
                    </div>
                    <div class="col-md-4">
                        <x-forms.file fieldLabel="Passport Front" fieldName="front" fieldId="passport_front"/>
                    </div>
                    <div class="col-md-4">
                        <x-forms.file fieldLabel="Passport Address Page" fieldName="address_page"
                                      fieldId="passport_address_page"/>
                    </div>
                </div>
            </div>
        </x-form>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('#save-form').click(function () {
            const url = "{{ route('scanner.store') }}";
            let data = $('#scan-passport-data-form').serialize();
            saveForm(data, url, "#save-form");
        });

        function saveForm(data, url, buttonSelector) {
            $.easyAjax({
                url: url,
                container: '#scan-passport-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: buttonSelector,
                file: true,
                data: data,
                success: function (response) {
                    console.log(response);

                }

            });
        }
    });

</script>
