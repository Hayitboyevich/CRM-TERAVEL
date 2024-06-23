@php
    $addClientCategoryPermission = user()->permission('manage_client_category');
    $addClientSubCategoryPermission = user()->permission('manage_client_subcategory');
@endphp

<div class="row">
    <div class="col-sm-12">
        <x-form id="save-data-form" method="PUT">
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
                                      fieldValue="{{ $client->firstname }}"
                                      fieldId="name" fieldPlaceholder="" fieldRequired="true"
                        />
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <x-forms.text :fieldLabel="__('modules.lead.clientLastname')" fieldName="lastname"
                                      fieldValue="{{ $client->lastname }}"
                                      fieldId="name" fieldPlaceholder="" fieldRequired="true"
                        />
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <x-forms.text :fieldLabel="__('modules.lead.clientFathername')" fieldName="fathername"
                                      fieldValue="{{ $client->fathername }}"
                                      fieldId="name" fieldPlaceholder="" fieldRequired="true"
                        />
                    </div>
                    <div class="col-md-4">
                        <x-forms.select fieldId="gender" :fieldLabel="__('modules.employees.gender')"
                                        fieldName="gender">
                            <option @if($client->gender == "male")
                                        selected
                                    @endif value="male">@lang('app.male')</option>
                            <option @if($client->gender == "female")
                                        selected
                                    @endif value="female">@lang('app.female')</option>
                            <option @if($client->gender == "others")
                                        selected
                                    @endif value="others">@lang('app.others')</option>
                        </x-forms.select>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group my-3">
                            <x-forms.label fieldId="birthday" :fieldLabel="__('modules.employees.dateOfBirth')">
                            </x-forms.label>
                            <div class="input-group">

                                <input type="text" id="birthday" name="birthday"
                                       value="{{ date('d-m-Y', strtotime($client->birthday)) ?? now(company()->timezone)->translatedFormat(company()->date_format) }}"
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
                                   value="{{ $client->mobile }}"
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
                </div>


                <x-form-actions>
                    <x-forms.button-primary id="save-form" class="mr-3" icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel :link="route('clients.index')" class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>
            </div>
        </x-form>

    </div>
</div>


<script>
    $(document).ready(function () {
        const dp1 = datepicker('#birthday', {
            position: 'bl',
            ...datepickerConfig
        });
        $('.custom-date-picker').each(function (ind, el) {
            datepicker(el, {
                position: 'bl',
                ...datepickerConfig
            });
        });


        $('#save-form').click(function () {
            const url = "{{ route('clients.update', $client->id) }}";

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
