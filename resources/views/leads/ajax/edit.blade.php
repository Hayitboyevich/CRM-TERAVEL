@php
    $viewLeadAgentPermission = user()->permission('view_lead_agents');
    $viewLeadCategoryPermission = user()->permission('view_lead_category');
    $addLeadAgentPermission = user()->permission('add_lead_agent');
    $addLeadCategoryPermission = user()->permission('add_lead_category');
//    $addProductPermission = user()->permission('add_product');
    $addLeadNotePermission = user()->permission('add_lead_note');
@endphp

{{--<link rel="stylesheet" href="{{ asset('vendor/css/dropzone.min.css') }}">--}}

<div class="row">
    <div class="col-sm-12">
        <x-form id="save-lead-data-form" method="put">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey"></h4>
                <div class="row p-20">

                    <div class="col-lg-4 col-md-6">
                        <x-forms.text :fieldLabel="__('modules.lead.clientName')" fieldName="client_name"
                                      fieldId="client_name" fieldPlaceholder="" fieldRequired="true"
                                      :fieldValue="$lead->client_name"/>
                    </div>
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
                                   value="{{ $lead->mobile }}"
                                   placeholder="@lang('placeholders.mobile')" name="mobile" id="mobile">
                        </x-forms.input-group>
                    </div>

                    @if ($viewLeadAgentPermission != 'none')
                        <div class="col-lg-4 col-md-6">
                            <x-forms.label class="my-3" fieldId="agent_id"
                                           :fieldLabel="__('modules.tickets.chooseAgents')">
                            </x-forms.label>
                            <x-forms.input-group>
                                <select class="form-control select-picker" name="agent_id" id="agent_id"
                                        data-live-search="true">
                                    <option value="">--</option>
                                    @foreach ($leadAgents as $emp)
                                        <x-user-option :user="$emp->user" :selected="$emp->id == $lead->agent_id"
                                                       :userID="$emp->id"/>
                                    @endforeach
                                </select>

                                @if ($addLeadAgentPermission == 'all' || $addLeadAgentPermission == 'added')
                                    <x-slot name="append">
                                        <button type="button"
                                                class="btn btn-outline-secondary border-grey add-lead-agent"
                                                data-toggle="tooltip"
                                                data-original-title="{{ __('app.add').'  '.__('app.new').' '.__('modules.tickets.agents') }}">@lang('app.add')</button>
                                    </x-slot>
                                @endif
                            </x-forms.input-group>
                        </div>
                    @endif

                    <div class="col-lg-4 col-md-6">
                        <x-forms.label class="my-3" fieldId="source_id" :fieldLabel="__('modules.lead.leadSource')">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="source_id" id="source_id"
                                    data-live-search="true">
                                <option value="">--</option>
                                @foreach ($sources as $source)
                                    <option
                                            @if($source->id == $lead->source_id)
                                                selected
                                            @endif
                                            value="{{ $source->id }}">{{ mb_ucwords($source->type) }}</option>
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

                    <div class="col-md-6 col-lg-4">
                        <x-forms.label class="mt-3" fieldId="status" :fieldLabel="__('app.status')">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="status" id="status"
                                    data-live-search="true"
                                    data-size="8">
                                @forelse($status as $sts)
                                    <option @if ($lead->status_id == $sts->id) selected
                                            @endif value="{{ $sts->id }}">
                                        {{ ucfirst($sts->type) }}</option>
                                @empty
                                    <option value="">--</option>
                                @endforelse
                            </select>
                        </x-forms.input-group>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group my-3">
                            <x-forms.label fieldId="departure_time" fieldLabel="Дата отправления">
                            </x-forms.label>
                            <div class="input-group">
                                <input type="text" id="departure_time" name="departure_time"
                                       value="{{$lead->departure_time ? date('d-m-Y', strtotime($lead->departure_time)) : ''}}"
                                       class=" position-relative text-dark font-weight-normal form-control height-35 f-14 rounded p-0 text-left"
                                       placeholder="@lang('placeholders.date')"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group my-3">
                            <x-forms.label fieldId="landing_time" fieldLabel="Дата возвращения">
                            </x-forms.label>
                            <div class="input-group">
                                <input type="text" id="landing_time" name="landing_time"
                                       value="{{  $lead->landing_time ? date('d-m-Y', strtotime($lead->landing_time)) : ''}}"
                                       class=" position-relative text-dark font-weight-normal form-control height-35 f-14 rounded p-0 text-left"
                                       placeholder="@lang('placeholders.date')"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group my-3">
                            <x-forms.label fieldId="callback_date"

                                           fieldLabel="Дата обратного звонка">
                            </x-forms.label>
                            <div class="input-group">
                                <input type="text" id="callback_date" name="callback_date"
                                       value="{{$lead->callback_at?->format(company()->date_format)}}"
                                       class=" position-relative text-dark font-weight-normal form-control height-35 f-14 rounded p-0 text-left"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group my-3">
                            <div class="bootstrap-timepicker timepicker">
                                <x-forms.text fieldLabel="Время обратного звонка"
                                              fieldName="callback_time"
                                              :fieldValue="$lead->callback_at?->format(company()->time_format)"
                                              fieldId="callback_time"
                                              />
                            </div>
                        </div>
                    </div>

                    @if ($addLeadNotePermission == 'all' || $addLeadNotePermission == 'added' || $addLeadNotePermission == 'both')
                        <div class="col-md-12">
                            <div class="form-group my-3">
                                <x-forms.label fieldId="note" :fieldLabel="__('app.note')">
                                </x-forms.label>
                                <div id="note"></div>
                                <textarea name="note" id="note-text" class="d-none"></textarea>
                            </div>
                        </div>
                    @endif


                </div>
                <x-form-actions>
                    <x-forms.button-primary id="save-lead-form" class="mr-3" icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel :link="route('leadboards.index')" class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>

            </div>
        </x-form>

    </div>
</div>


<script src="{{ asset('vendor/jquery/dropzone.min.js') }}"></script>
<script>
    $(document).ready(function () {
        var add_lead_note_permission = "{{ $addLeadNotePermission }}";
        const dp1 = datepicker('#departure_time', {
            position: 'bl',
            ...datepickerConfig
        });
        $('body').on('click', '.add-lead-source', function () {

            const url = '{{ route('lead-source-settings.create') }}';
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        $('#callback_time').timepicker({
            @if (company()->time_format == 'H:i')
            showMeridian: false,
            defaultTime: false
            @endif
        });
        const dp3 = datepicker('#callback_date', {
            position: 'bl',
            ...datepickerConfig
        });

        const dp2 = datepicker('#landing_time', {
            position: 'bl',
            ...datepickerConfig
        });
        if (add_lead_note_permission == 'all' || add_lead_note_permission == 'added' || add_lead_note_permission == 'both') {
            quillImageLoad('#note');
        }
        let $sourceSelect = $('#country');
        let $options = $sourceSelect.find('option');
        $options.clone().appendTo('.country');
        $('.select-picker').selectpicker('refresh');

        $('.custom-date-picker').each(function (ind, el) {
            datepicker(el, {
                position: 'bl',
                ...datepickerConfig
            });
        });

        document.getElementById('note').children[0].innerHTML = `{!! $lead->note !!}`;

        $('#save-lead-form').click(function () {
            const url = "{{ route('leads.update', [$lead->id]) }}";
            if (add_lead_note_permission === 'all' || add_lead_note_permission === 'added' || add_lead_note_permission === 'both') {
                var note = document.getElementById('note').children[0].innerHTML;
                document.getElementById('note-text').value = note;
            }
            $.easyAjax({
                url: url,
                container: '#save-lead-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                file: true,
                buttonSelector: "#save-lead-form",
                data: $('#save-lead-data-form').serialize(),
                success: function (response) {
                    window.location.href = response.redirectUrl;
                }
            });
        });

        $('body').on('click', '.add-lead-agent', function () {
            const url = '{{ route('lead-agent-settings.create') }}';
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });


        $('#client_view_task').change(function () {
            $('#clientNotification').toggleClass('d-none');
        });

        $('#set_time_estimate').change(function () {
            $('#set-time-estimate-fields').toggleClass('d-none');
        });

        $('#repeat-task').change(function () {
            $('#repeat-fields').toggleClass('d-none');
        });

        $('#dependent-task').change(function () {
            $('#dependent-fields').toggleClass('d-none');
        });

        $('.toggle-other-details').click(function () {
            $(this).find('svg').toggleClass('fa-chevron-down fa-chevron-up');
            $('#other-details').toggleClass('d-none');
        });

        $('#createTaskLabel').click(function () {
            const url = "{{ route('task-label.create') }}";
            $(MODAL_XL + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_XL, url);
        });

        $('#add-project').click(function () {
            $(MODAL_XL).modal('show');
            const url = "{{ route('projects.create') }}";
            $.easyAjax({
                url: url,
                blockUI: true,
                container: MODAL_XL,
                success: function (response) {
                    if (response.status == "success") {
                        $(MODAL_XL + ' .modal-body').html(response.html);
                        $(MODAL_XL + ' .modal-title').html(response.title);
                        init(MODAL_XL);
                    }
                }
            });
        });

        $('#add-employee').click(function () {
            $(MODAL_XL).modal('show');
            const url = "{{ route('employees.create') }}";

            $.easyAjax({
                url: url,
                blockUI: true,
                container: MODAL_XL,
                success: function (response) {
                    if (response.status == "success") {
                        $(MODAL_XL + ' .modal-body').html(response.html);
                        $(MODAL_XL + ' .modal-title').html(response.title);
                        init(MODAL_XL);
                    }
                }
            });
        });

        <x-forms.custom-field-filejs/>

        init(RIGHT_MODAL);
    });


    function checkboxChange(parentClass, id) {
        let checkedData = '';
        $('.' + parentClass).find("input[type= 'checkbox']:checked").each(function () {
            checkedData = (checkedData !== '') ? checkedData + ', ' + $(this).val() : $(this).val();
        });
        $('#' + id).val(checkedData);
    }
</script>
