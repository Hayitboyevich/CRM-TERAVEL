<div class="row">
    <div class="col-sm-12">
        <x-form id="save-partner-data-form">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('modules.partner.partnerDetails')</h4>

                <div class="row p-20 pb-0">
                    <div class="col-lg-4 col-md-4">
                        <x-forms.text fieldLabel="Название группы" fieldName="name"
                                      fieldId="name" fieldPlaceholder="Введите имя социальной учетной записи"
                                      fieldRequired="true"
                        />
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <x-forms.text fieldLabel="Имя пользователя" fieldName="login"
                                      fieldId="login" fieldPlaceholder="Имя пользователя"
                        />
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <x-forms.text fieldLabel="Пароль" fieldName="password"
                                      fieldId="password" fieldPlaceholder="Пароль"
                        />
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <x-forms.text fieldLabel="Веб-сайт" fieldName="type"
                                      fieldId="type" fieldPlaceholder="Веб-сайт"
                        />
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <x-forms.text fieldLabel="Обменный курс" fieldName="exchange_rate"
                                      fieldId="exchange_rate" fieldPlaceholder="Обменный курс"
                        />
                    </div>

                </div>
                <div class="row p-20 pt-0">
                    <x-form-actions>
                        <x-forms.button-primary id="save-partner-form" class="mr-3" icon="check">@lang('app.save')
                        </x-forms.button-primary>

                        <x-forms.button-cancel :link="route('partners.index')" class="border-0">@lang('app.cancel')
                        </x-forms.button-cancel>
                    </x-form-actions>
                </div>
            </div>
        </x-form>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#create-lead').click(function () {
            const url = "{{ route('partners.create') }}";
            let data = $('#save-partner-data-form').serialize();
            createPartner(data, url, "#create-partner");
        });

        $('#save-partner-form').click(function () {

            const url = "{{ route('partners.store') }}";
            let data = $('#save-partner-data-form').serialize();
            savePartner(data, url, "#save-partner-form");

        });

        function savePartner(data, url, buttonSelector) {
            $.easyAjax({
                url: url,
                container: '#save-partner-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: buttonSelector,
                file: true,
                data: data,
                success: function (response) {
                    if (response.status === 'success') {
                        if ($(MODAL_XL).hasClass('show')) {
                            $(MODAL_XL).modal('hide');
                            window.location.reload();
                        } else if (typeof response.redirectUrl !== 'undefined') {
                            window.location.href = response.redirectUrl;
                        } else if (response.add_more === true) {

                            var right_modal_content = $.trim($(RIGHT_MODAL_CONTENT).html());
                            if (right_modal_content.length) {
                                $(RIGHT_MODAL_CONTENT).html(response.html.html);
                                $('#add_more').val(false);
                            } else {
                                $('#add_more').val(false);
                            }
                        }

                        if (typeof showTable !== 'undefined' && typeof showTable === 'function') {
                            showTable();
                        }
                    }
                }
            });
        }

        function createPartner(data, url, buttonSelector) {
            $.easyAjax({
                url: url,
                container: '#save-partner-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: buttonSelector,
                file: true,
                data: data,
                success: function (response) {
                    if (response.status === 'success') {
                        if ($(MODAL_XL).hasClass('show')) {
                            $(MODAL_XL).modal('hide');
                            window.location.reload();
                        } else if (typeof response.redirectUrl !== 'undefined') {
                            window.location.href = response.redirectUrl;
                        } else if (response.add_more === true) {

                            var right_modal_content = $.trim($(RIGHT_MODAL_CONTENT).html());
                            if (right_modal_content.length) {

                                $(RIGHT_MODAL_CONTENT).html(response.html.html);
                                $('#add_more').val(false);
                            } else {
                                $('#add_more').val(false);
                            }
                        }

                        if (typeof showTable !== 'undefined' && typeof showTable === 'function') {
                            showTable();
                        }
                    }
                }
            });
        }

        init(RIGHT_MODAL);
    });

</script>