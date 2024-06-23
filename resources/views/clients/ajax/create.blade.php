@php
    $addPermission = user()->permission('add_clients');
@endphp

<link rel="stylesheet" href="{{ asset('vendor/css/dropzone.min.css') }}">

<div class="row">
    <div class="col-sm-12">
        <x-form id="save-client-data-form">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('modules.employees.accountDetails')</h4>
                @if (isset($lead?->id))
                    <input type="hidden" name="lead"
                           value="{{ $lead->id }}">
                @endif

                <div class="row p-20 pb-0">
                    <div class="col-lg-4 col-md-4">
                        <x-forms.text :fieldLabel="__('modules.lead.clientFirstname')" fieldName="firstname"
                                      fieldId="name" fieldPlaceholder="" fieldRequired="true"
                        />
                    </div>
                    {{--                    <div class="col-lg-4 col-md-4">--}}
                    {{--                        <x-forms.text :fieldLabel="__('modules.lead.clientLastname')" fieldName="lastname"--}}
                    {{--                                      fieldId="name" fieldPlaceholder=""--}}
                    {{--                        />--}}
                    {{--                    </div>--}}
                    {{--                    <div class="col-lg-4 col-md-4">--}}
                    {{--                        <x-forms.text :fieldLabel="__('modules.lead.clientFathername')" fieldName="fathername"--}}
                    {{--                                      fieldId="name" fieldPlaceholder=""--}}
                    {{--                        />--}}
                    {{--                    </div>--}}
                    <div class="col-lg-4 col-md-6">
                        <x-forms.label class="my-3" fieldId="source_id" :fieldLabel="__('modules.lead.leadSource')">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="source_id" id="source_id"
                                    data-live-search="true">
                                <option value="">--</option>
                                @foreach ($sources as $source)
                                    <option value="{{ $source->id }}">{{ mb_ucwords($source->type) }}</option>
                                @endforeach
                            </select>

                            <x-slot name="append">
                                <button type="button"
                                        class="btn btn-outline-secondary border-grey add-lead-source"
                                        data-toggle="tooltip"
                                        data-original-title="{{ __('app.add').' '.__('modules.lead.leadSource') }}">
                                    @lang('app.add')</button>
                            </x-slot>
                        </x-forms.input-group>
                    </div>
                    <div class="col-md-4">
                        <x-forms.select fieldId="gender" :fieldLabel="__('modules.employees.gender')"
                                        fieldName="gender">
                            <option value="male">@lang('app.male')</option>
                            <option value="female">@lang('app.female')</option>
                        </x-forms.select>
                    </div>

                </div>
                <input hidden name="client_id" id="client_id"/>
                <div class="row p-20 pt-0">
                    <div class="col-md-4">
                        <x-forms.label class="my-3" fieldId="mobile"
                                       :fieldLabel="__('app.mobile')"></x-forms.label>
                        <x-forms.input-group style="margin-top:-4px">
                            <x-forms.select fieldId="country_phonecode" fieldName="country_phonecode"
                                            search="true">
                                @foreach ($countries as $item)
                                    <option data-tokens="{{ $item->name }}"
                                            {{$item->phonecode == '+998' ? "selected" : ""}}
                                            data-content="{{$item->flagSpanCountryCode()}}"
                                            value="{{ $item->phonecode }}">{{ $item->phonecode }}
                                    </option>
                                @endforeach
                            </x-forms.select>

                            <input type="number" class="form-control height-35 f-14"
                                   placeholder="@lang('placeholders.mobile')" name="mobile" id="mobile">
                        </x-forms.input-group>
                    </div>
                    <a id="view_client" hidden type="button"
                       class="mt-5 height-35 btn btn-outline-info">Просмотр
                        клиента</a>

                    <button id="create-lead" hidden type="button"
                            class="mt-5 ml-2 height-35 btn btn-outline-success">Создать
                        новый лид
                    </button>
                    <x-form-actions>
                        <x-forms.button-primary id="save-client-form" class="mr-3" icon="check">@lang('app.save')
                        </x-forms.button-primary>

                        <x-forms.button-cancel :link="route('leadboards.index')" class="border-0">@lang('app.cancel')
                        </x-forms.button-cancel>
                    </x-form-actions>
                </div>
            </div>
        </x-form>

    </div>
</div>
<script>
    function makeAjaxRequest(name) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: 'https://api.genderize.io?name=' + name,
                method: 'GET',
                contentType: 'application/json',
                success: function (responseData) {
                    // Handle the response data
                    let gender = responseData['gender'] ?? 'male';
                    resolve(gender);
                },
                error: function (xhr, textStatus, error) {
                    // Handle any errors
                    console.error('Error:', error);
                    reject(error);
                }
            });
        });
    }

    function findUser(mobile) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: "{{ route('users.find') }}",
                method: 'GET',
                data: {
                    "mobile": mobile,
                    "_token": "{{csrf_token()}}"
                },
                contentType: 'application/json',
                success: function (responseData) {
                    // Handle the response data
                    resolve(responseData);
                },
                error: function (xhr, textStatus, error) {
                    // Handle any errors
                    console.error('Error:', error);
                    reject(error);
                }
            });
        });
    }


</script>
<script src="{{ asset('vendor/jquery/dropzone.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#name').on('input', async function (event) {
            const inputValue = $(this).val();
            const letters = /^[a-zA-Z]+$/;
            let gender;
            if (letters.test(inputValue)) {
                gender = await makeAjaxRequest(inputValue);
                $('#gender').val(gender);
                $('#gender').selectpicker('refresh');
            }
        });

        $('#create-lead').click(function () {
            $('#add_more').val(true);
            const url = "{{ route('leadboards.custom-create') }}";
            let data = $('#save-client-data-form').serialize();
            createLead(data, url, "#create-lead");
        });
        $('#mobile').on('input', async function (event) {
            const inputValue = $(this).val();
            const view_client_button = $('#view_client');
            const create_lead = $('#create-lead');
            const client_name = $('#name');
            const client_id = $('#client_id');

            if (inputValue.length > 8) {
                let user = await findUser(inputValue);
                console.log(user);
                const show_url = '{{url('/')}}/account/clients/' + user.id;
                const mbDiv = $('#mobile')
                mbDiv.addClass('is-invalid');
                console.log(mbDiv.parent()[0]);

                mbDiv.parent().append(`<div class="invalid-feedback">Клиент уже зарегистрирован с этим номером.</div>`);
                client_name.val(user.name);
                client_id.val(user.id);
                view_client_button.removeAttr('hidden');
                create_lead.removeAttr('hidden');
                view_client_button.attr('href', show_url);

                $('#create-lead').show();
            }
        })
        $('body').on('click', '.add-lead-source', function () {

            const url = '{{ route('lead-source-settings.create') }}';
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });
        $('#save-more-client-form').click(function () {
                $('#add_more').val(true);

                const url = "{{ route('clients.store') }}";
                let data = $('#save-client-data-form').serialize();

                // console.log(data);
                saveClient(data, url, "#save-more-client-form");

            }
        )


        $('#save-client-form').click(function () {

            const url = "{{ route('clients.store') }}";
            let data = $('#save-client-data-form').serialize();

            saveClient(data, url, "#save-client-form");

        });


        function saveClient(data, url, buttonSelector) {
            $.easyAjax({
                url: url,
                container: '#save-client-data-form',
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

        function createLead(data, url, buttonSelector) {
            $.easyAjax({
                url: url,
                container: '#save-client-data-form',
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

