@php
    $addClientCategoryPermission = user()->permission('manage_client_category');
    $addClientSubCategoryPermission = user()->permission('manage_client_subcategory');
@endphp

<div class="row">
    <div class="col-sm-12">
        <x-form id="save-data-form" method="POST">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('modules.employees.accountDetails')</h4>

                @if (isset($lead->id))
                    <input type="hidden" name="lead"
                           value="{{ $lead->id }}">
                @endif

                <div class="row p-20 pb-0">
                    <div class="col-lg-4 col-md-4">
                        <x-forms.text :fieldLabel="__('modules.lead.clientFirstname')" fieldName="firstname"
                                      fieldId="firstname" fieldPlaceholder="" fieldRequired="true"
                        />
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <x-forms.text :fieldLabel="__('modules.lead.clientLastname')" fieldName="lastname"
                                      fieldId="lastname" fieldPlaceholder="" fieldRequired="true"
                        />
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <x-forms.text :fieldLabel="__('modules.lead.clientFathername')" fieldName="fathername"
                                      fieldId="fathername" fieldPlaceholder="" fieldRequired="true"
                        />
                    </div>
                    <div class="col-md-4">
                        <x-forms.select fieldId="gender" :fieldLabel="__('modules.employees.gender')"
                                        fieldName="gender">
                            <option value="male">@lang('app.male')</option>
                            <option value="female">@lang('app.female')</option>
                        </x-forms.select>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group my-3">
                            <x-forms.label fieldId="birthday" :fieldLabel="__('modules.employees.dateOfBirth')">
                            </x-forms.label>
                            <div class="input-group">
                                <input type="text" id="birthday" name="birthday"
                                       class=" position-relative text-dark font-weight-normal form-control height-35 f-14 rounded p-0 text-left"
                                       placeholder="@lang('placeholders.date')">
                            </div>
                        </div>
                    </div>

                </div>
                <input hidden name="client_id" id="client_id"/>

                <div class="row p-20">

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
                            <input type="tel" class="form-control height-35 f-14"
                                   placeholder="@lang('placeholders.mobile')" name="mobile" id="mobile"
                                   pattern="[0-9]{9}" title="Please enter a 9-digit number">

                        </x-forms.input-group>
                    </div>

                </div>

            </div>
            <div class="add-client-foreign_passport bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    Загранпаспорт</h4>
                <div class="row p-20">
                    <div class="col-md-4">
                        <x-forms.file allowedFileExtensions="png jpg jpeg svg" fieldLabel="Изображение загранпаспорт"
                                      fieldName="foreign_passport_image"
                                      fieldId="foreign_passport_image"/>
                    </div>

                    <div class="col-lg-4 col-md-4">
                        <x-forms.text fieldName="foreign[last_name]" fieldId="foreign[last_name]"
                                      fieldLabel="Фамилия по загранпаспорту"

                        >
                        </x-forms.text>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <x-forms.text fieldName="foreign[first_name]" fieldId="foreign[first_name]"
                                      fieldLabel="Имя по загранпаспорту"

                        >
                        </x-forms.text>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <x-forms.text fieldName="foreign[passport_serial_number]"
                                      fieldId="foreign[passport_serial_number]"
                                      fieldLabel="Серия и номер"

                        >
                        </x-forms.text>

                    </div>
                    <div class="col-lg-4 col-md-4">

                        <x-forms.text fieldName="foreign[given_by]" fieldId="foreign[given_by]"
                                      fieldLabel="Орган, выдавший з/п"

                        >
                        </x-forms.text>
                    </div>
                    <div class="col-md-4">
                        <x-forms.text fieldName="foreign[given_date]" fieldId="foreign[given_date]"
                                      fieldLabel="Дата выдачи">
                        </x-forms.text>
                    </div>
                    <div class="col-md-4">
                        <x-forms.text fieldName="foreign[date_of_expiry]" fieldId="foreign[date_of_expiry]"
                                      fieldLabel="Срок действия">
                        </x-forms.text>
                    </div>

                    <div class="col-md-4">
                        <x-forms.select fieldId="foreign[residence_id]" fieldLabel="Гражданство"
                                        fieldName="foreign[residence_id]"
                                        search="true">
                            <option value="">--</option>
                            @foreach($countries as $country)
                                <option
                                    value="{{$country->id}}">{{$country->name}}
                                </option>
                            @endforeach
                        </x-forms.select>
                    </div>

                    <div class="col-md-4">
                        <x-forms.text fieldName="foreign[place_of_birth]" fieldId="foreign[place_of_birth]"
                                      fieldLabel="Место рождения:">
                        </x-forms.text>
                    </div>

                    <div class="col-md-4">
                        <x-forms.number fieldName="foreign[stir]" fieldId="foreign[stir]"
                                        fieldLabel="ЖШШИР">
                        </x-forms.number>
                    </div>

                </div>

            </div>

            <div class="add-client-passport bg-white rounded">
{{--                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">--}}
{{--                    Гражданский паспорт</h4>--}}
{{--                <div class="row p-20">--}}

{{--                    <div class="col-md-4">--}}
{{--                        <x-forms.file allowedFileExtensions="png jpg jpeg svg" fieldLabel="Изображение паспорта"--}}
{{--                                      fieldName="passport_image"--}}
{{--                                      fieldId="passport_image"/>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <x-forms.select fieldId="passport[residence_id]" fieldLabel="Гражданство"--}}
{{--                                        fieldName="passport[residence_id]"--}}
{{--                                        search="true">--}}
{{--                            <option value="">--</option>--}}
{{--                            @foreach($countries as $country)--}}
{{--                                <option--}}
{{--                                        value="{{$country->id}}">{{$country->name}}--}}
{{--                                </option>--}}
{{--                            @endforeach--}}
{{--                        </x-forms.select>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <x-forms.text fieldName="passport[place_of_birth]" fieldId="passport[place_of_birth]"--}}
{{--                                      fieldLabel="Место рождения:">--}}
{{--                        </x-forms.text>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <x-forms.text fieldName="passport[passport_serial_number]"--}}
{{--                                      fieldId="passport[passport_serial_number]"--}}
{{--                                      fieldLabel="Серия и номер">--}}
{{--                        </x-forms.text>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <x-forms.text fieldName="passport[given_by]" fieldId="passport[given_by]"--}}
{{--                                      fieldLabel="Кем выдан">--}}
{{--                        </x-forms.text>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <x-forms.text fieldName="passport[given_date]" fieldId="passport[given_date]"--}}
{{--                                      fieldLabel="Дата выдачи">--}}
{{--                        </x-forms.text>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <x-forms.text fieldName="passport[date_of_expiry]" fieldId="passport[date_of_expiry]"--}}
{{--                                      fieldLabel="Срок действия">--}}
{{--                        </x-forms.text>--}}
{{--                    </div>--}}
{{--                    <div class=" col-md-4">--}}
{{--                        <x-forms.number fieldName="passport[given_department]" fieldId="passport[given_department]"--}}
{{--                                        fieldLabel="Код подразделения">--}}
{{--                        </x-forms.number>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <x-forms.number fieldName="passport[stir]" fieldId="passport[stir]"--}}
{{--                                        fieldLabel="ЖШШИР">--}}
{{--                        </x-forms.number>--}}
{{--                    </div>--}}

{{--                    <div class="col-md-3">--}}
{{--                        <x-forms.select fieldId="passport[living_country_id]" fieldLabel="Страна проживания"--}}
{{--                                        fieldName="passport[living_country_id]"--}}
{{--                                        search="true">--}}
{{--                            <option value="">--</option>--}}
{{--                            @foreach($countries as $country)--}}
{{--                                <option--}}

{{--                                        value="{{$country->id}}">{{$country->name}}</option>--}}
{{--                            @endforeach--}}
{{--                        </x-forms.select>--}}
{{--                    </div>--}}
                    <x-form-actions>
                        <x-forms.button-primary id="save-form" class="mr-3" icon="check">@lang('app.save')
                        </x-forms.button-primary>
                        <x-forms.button-cancel :link="route('applications.edit', $application->id)"
                                               class="border-0">@lang('app.cancel')
                        </x-forms.button-cancel>
                    </x-form-actions>
                </div>
{{--            </div>--}}
        </x-form>

    </div>
</div>

<script>
    function findUser(mobile) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                container: '#save-data-form',
                url: '{{url('api')}}/find-user?mobile=' + mobile,
                method: 'GET',
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

    $(document).ready(function () {
        const dp1 = datepicker('#birthday', {
            position: 'bl',
            ...datepickerConfig
        });
        // const givenDate = datepicker('#passport[given_date]', {
        //     position: 'bl',
        //     ...datepickerConfig
        // });


        @if($company->can_pass_scan == 1)
        $('#foreign_passport_image').on('change', function () {
            let image = this.files[0];
            if (image) {
                let token = "{{ csrf_token() }}";

                const formData = new FormData();
                formData.append('passport_image', image);
                formData.append('_token', token);
                formData.append('type', "foreign");

                const url = "{{ route('scanner.store') }}";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    file: true,
                    processData: false, // Don't process the data
                    contentType: false, // Don't set content type
                    success: function (response) {
                        let data = response.data;
                        $('#foreign\\[last_name\\]').val(data['last_name']);
                        $('#foreign\\[first_name\\]').val(data['first_name']);
                        $('#foreign\\[passport_serial_number\\]').val(data['card_no']);
                        $('#foreign\\[date_of_expiry\\]').val(data['date_of_expiry']);
                        $('#foreign\\[given_by\\]').val(data['issuer']);
                        $('#foreign\\[place_of_birth\\]').val(data['issuer']);
                        $('#foreign\\[stir\\]').val(data['personal_number']);

                        $('#firstname').val(data['first_name']);
                        $('#lastname').val(data['last_name']);
                        $('#birthday').val(data['date_of_birth']);
                        $('#gender').val(data['gender']);

                        // Get current date and format it as MM-YYYY
                        const currentDate = new Date();
                        const month = ("0" + (currentDate.getMonth() + 1)).slice(-2);
                        const year = currentDate.getFullYear();
                        const formattedDate = `${month}-${year}`;

                        $.ajax({
                            url: "{{ route('increase_scan_number') }}",
                            type: 'POST',
                            data: {
                                _token: token,
                                status: true,
                                date: formattedDate
                            },
                            success: function (response) {
                                console.log('Scan number increased successfully');
                            },
                            error: function () {
                                console.error('An error occurred while increasing the scan number.');
                            }
                        });
                    },
                    error: function () {
                        // Handle error
                    }
                });
            }
        });

        $('#passport_image').on('change', function () {
            let image = this.files[0];
            if (image) {
                let token = "{{ csrf_token() }}";

                const formData = new FormData();
                formData.append('passport_image', image);
                formData.append('_token', token);
                formData.append('type', "passport");

                const url = "{{ route('scanner.store') }}";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    file: true,
                    processData: false, // Don't process the data
                    contentType: false, // Don't set content type
                    success: function (response) {

                        let data = response.data;

                        $('#passport\\[passport_serial_number\\]').val(data['card_no']);
                        $('#passport\\[date_of_expiry\\]').val(data['date_of_expiry']);
                        $('#passport\\[given_by\\]').val(data['issuer']);
                        $('#passport\\[place_of_birth\\]').val(data['issuer']);
                        $('#passport\\[stir\\]').val(data['personal_number']);

                        $('#firstname').val(data['first_name']);
                        $('#lastname').val(data['last_name']);
                        $('#birthday').val(data['date_of_birth']);
                        $('#gender').val(data['gender']);
                    },
                    error: function () {
                        // Handle error
                    }
                });
            }
        });

        @endif
        $('#mobile').on('input', async function (event) {
            console.log('ok');
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
        // Function to initialize datepicker on elements
        function initializeDatePickers() {
            // Initialize datepicker on all elements with the 'custom-date-picker' class
            $('.custom-date-picker').each(function () {
                datepicker(this, {
                    position: 'bl',
                    ...datepickerConfig
                });
            });

            // Initialize datepicker on specific elements by ID
            const ids = ['#foreign[given_date]', '#foreign[date_of_expiry]'];
            ids.forEach(id => {
                datepicker(id, {
                    position: 'bl',
                    ...datepickerConfig
                });
            });
        }

// Call the function to initialize all datepickers
        initializeDatePickers();

        $('#save-form').click(function () {
            const url = "{{ route('applications.client.store', $application->id) }}";

            $.easyAjax({
                url: url,
                container: '#save-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                file: true,
                buttonSelector: "#save-form",
                data: $('#save-data-form').serialize(),
                success: function (response) {
                    if (response.status === 'success') {
                        window.location.href = response.redirectUrl;
                    }
                }
            })
        });


        init(RIGHT_MODAL);
    });

</script>
