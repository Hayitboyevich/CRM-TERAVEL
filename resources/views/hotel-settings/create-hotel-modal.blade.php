<x-form id="createHotel" method="POST" class="form-horizontal">
    <div class="modal-header">
        <h5 class="modal-title">@lang('app.addNew') @lang('modules.module.hotel')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="my-3">
                        <x-forms.text fieldLabel="Name" fieldId="name" fieldName="name" placeholder="Enter name">
                        </x-forms.text>
                    </div>
                </div>

                {{--                <div class="col-md-4">--}}
                {{--                    <x-forms.label class="my-3" fieldId="country_id"--}}
                {{--                                   fieldLabel="Тип заявки">--}}
                {{--                    </x-forms.label>--}}
                {{--                    <x-forms.input-group>--}}
                {{--                        <select class="form-control select-picker" name="country_id" id="country_id"--}}
                {{--                                data-live-search="true">--}}
                {{--                            <option value="">--</option>--}}
                {{--                            @foreach ($countries as $country)--}}
                {{--                                <option--}}
                {{--                                        value="{{ $country->id }}">{{ mb_ucwords($country->name) }}</option>--}}
                {{--                            @endforeach--}}
                {{--                        </select>--}}
                {{--                    </x-forms.input-group>--}}
                {{--                </div>--}}
                {{--                <div class="col-md-4">--}}
                {{--                    <x-forms.label class="my-3" fieldId="region_id"--}}
                {{--                                   fieldLabel="Тип заявки">--}}
                {{--                    </x-forms.label>--}}
                {{--                    <x-forms.input-group>--}}
                {{--                        <select class="form-control select-picker" name="region_id" id="region_id" required--}}
                {{--                                data-live-search="true">--}}
                {{--                            <option value="">--</option>--}}

                {{--                        </select>--}}
                {{--                    </x-forms.input-group>--}}
                {{--                </div>--}}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
        <x-forms.button-primary id="save-hotel" icon="check">@lang('app.save')</x-forms.button-primary>
    </div>
</x-form>

<script>
    $(document).ready(function () {
        $('#country_id').change(function () {
            let to_country_id = $(this).val();
            let toCityId = $('#region_id');
            $.ajax({
                url: '{{route('get-regions')}}', // Replace with your API endpoint
                method: 'GET',
                data: {
                    to_country_id: to_country_id,
                },
                success: function (response) {
                    toCityId.val('');
                    toCityId.empty();
                    toCityId.append($('<option>', {
                        value: '',
                        text: '--'
                    }));
                    $.each(response, function (index, option) {
                        toCityId.append($('<option>', {
                            value: option.id,
                            text: option.name
                        }));
                    });
                    toCityId.selectpicker('refresh');
                },
                error: function () {
                    console.error('API request failed');
                }
            });
            toCityId.selectpicker('refresh');
        });
        // save agent
        $('#save-hotel').click(function () {

            $.easyAjax({
                url: "{{ route('hotel-settings.store') }}",
                container: '#createHotel',
                type: "POST",
                blockUI: true,
                data: $('#createHotel').serialize(),
                disableButton: true,
                buttonSelector: "#save-hotel",
                success: function (response) {
                    if (response.status == "success") {
                        if ($('table#example').length) {
                            window.location.reload();
                        } else {
                            $('#hotel_id').html(response.data);
                            $('#hotel_id').selectpicker('refresh');
                            $(MODAL_LG).modal('hide');
                        }
                    }
                }
            })
        });
    });
</script>
