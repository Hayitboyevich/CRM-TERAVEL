<style>
    .layout-body {
        width: auto;
        margin: auto;
        padding: 10px 0;
        border: 1px solid #888;
        border-radius: 10px
    }

    .layout-row {
        position: relative
    }

    .layout-item {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 30px;
        margin: 5px;
        border: 1px solid #fff
    }

    .layout-item.layout-active {
        border: 1px solid #000
    }

    .layout-item .layout-input {
        background-color: inherit;
        border: 0;
        width: 50px;
        margin: 5px
    }

    .layout-item.layout-active .layout-input:hover, .layout-item .layout-input:focus {
        background-color: #eee;
        outline: 0
    }

    .layout-item .layout-label {
        background-color: inherit;
        border: 0;
        width: 58px;
        cursor: pointer;
        padding-top: 7px;
        padding-bottom: 7px
    }

    .layout-item.layout-busy .layout-label {
        cursor: not-allowed;
        color: #ddd
    }

    .layout-item.layout-selected .layout-label {
        background-color: #080;
        color: #fff
    }

    .layout-row-add {
        background-color: #eee;
        color: #9c0000;
        border: 1px solid #9c0000;
        width: 100px;
        text-align: center;
        margin: 10px auto;
        cursor: pointer
    }

    .layout-item .layout-item-remove {
        visibility: hidden;
        position: absolute;
        top: -8px;
        right: -8px;
        width: 15px;
        height: 15px;
        background-color: #9c0000;
        color: #fff;
        text-align: center;
        cursor: pointer
    }

    .layout-item.layout-active:hover .layout-item-remove {
        visibility: visible
    }

    .layout-item .layout-item-add, .layout-item.layout-active:hover .layout-item-add {
        visibility: hidden;
        position: absolute;
        top: 5px;
        right: 5px;
        width: 50px;
        height: 20px;
        background-color: #080;
        color: #fff;
        text-align: center;
        cursor: pointer;
        padding-top: 3px;
        opacity: .8
    }

    .layout-item:hover .layout-item-add {
        visibility: visible
    }

    .layout-row .layout-row-remove {
        visibility: hidden;
        position: absolute;
        top: 10px;
        right: 20px;
        width: 20px;
        height: 20px;
        background-color: #9c0000;
        color: #fff;
        text-align: center;
        cursor: pointer;
        font-weight: bold;
        font-size: 13px;
        border-radius: 10px
    }

    .layout-row:hover .layout-row-remove {
        visibility: visible
    }
</style>
<div class="col-sm-12">
    <x-form id="save-schema-data-form">
        <div class="bg-white rounded">
            <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                @lang('modules.employees.accountDetails')</h4>
            <div class="section">
                <div class="p-20 row">
                    <div class="col-4 ">
                        <x-forms.text fieldName="name" fieldLabel="Name" fieldId="name" field-value="{{ $schema->name }}" fieldRequired="true">
                        </x-forms.text>
                        <x-forms.text fieldName="description" fieldLabel="Description" field-value="{{ $schema->description }}" fieldId="description">
                        </x-forms.text>
                        <x-forms.select fieldId="layout" fieldLabel="Мест в ряду"
                                        fieldName="dimension">
                            <option value="1x1" {{ $schema->dimension == "1x1" ? 'selected' : '' }}>1x1</option>
                            <option value="2x2" {{ $schema->dimension == "2x2" ? 'selected' : '' }}>2x2</option>
                            <option value="3x3" {{ $schema->dimension == "3x3" ? 'selected' : '' }}>3x3</option>
                            <option value="4x4" {{ $schema->dimension == "4x4" ? 'selected' : '' }}>4x4</option>
                            <option value="5x5" {{ $schema->dimension == "5x5" ? 'selected' : '' }}>5x5</option>
                            <option value="5x1" {{ $schema->dimension == "5x1" ? 'selected' : '' }}>5x1</option>
                        </x-forms.select>
                        <x-forms.select fieldId="rows" fieldLabel="Кол-во рядов"
                                        fieldName="rows">
                            @for($i = 1;$i<=50;$i++)
                                <option value="{{$i}}" {{ $schema->row_amount == $i ? 'selected' : '' }}>{{$i}}</option>
                            @endfor

                        </x-forms.select>
                        <x-forms.button-primary id="" class="layout-build">
                            Сформировать схему
                        </x-forms.button-primary>
                    </div>

                    <div class="col-8 layout-container">

                    </div>
                    <div class="clearfix">

                    </div>
                </div>
            </div>
            <x-form-actions>
                <x-forms.button-primary id="save-schema-form" class="mr-3" icon="check">@lang('app.save')
                </x-forms.button-primary>

                <x-forms.button-cancel :link="route('schema.index')" class="border-0">@lang('app.cancel')
                </x-forms.button-cancel>
            </x-form-actions>
        </div>
    </x-form>

</div>

<script>
    $(document).ready(function () {

        var row = '{!! $schema->row_amount !!}';
        var dimension = '{!! $schema->dimension !!}';
        var dimension = dimension.split('x');
        var dimension = parseInt(dimension[0]) + parseInt(dimension[1]) + 1;
        var seats = '{!! $schema->seats !!}';
        var seats = JSON.parse(seats);
        var seats_count = '{!! count($schema->seats) !!}';

        console.log(seats);

        var data = [];
        for(var i=0; i<row; i++){
            var array = [];
            for(var j=0; j<dimension; j++){
                array.push('');
            }
            data.push(array);
        }

        let oldRow = 1;
        let oldRowLastIndex = 0;

        seats.forEach((seat) => {
            let index = seat.index;

            if (oldRow < seat.row) {
                oldRowLastIndex = index;
            }

            index -= oldRowLastIndex;

            if (!data[seat.row - 1]) {
                data[seat.row - 1] = [];
            }

            data[seat.row - 1][index] = seat.cell;
            oldRow = seat.row;
        });


        console.log(data);

        setLayout(JSON.stringify(data));

        $('#save-schema-form').click(function() {
            var url = "{{ route('schema.update', $schema) }}";
            $.easyAjax({
                url: url,
                container: '#createUnitType',
                type: "PUT",
                data: $('#save-schema-data-form').serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        window.location.reload();
                    }
                }
            })
        });
    });
    let $places_selected = [];
    let $places_busy = [];

    $(document).on('change', '.layout-input', function () {
        let $this = $(this);

        if ($this.val() === "") {
            $this.parents('.layout-item').removeClass('layout-active');
        } else {
            $this.parents('.layout-item').addClass('layout-active');
        }

        $this.val($this.val().replace(/[^0-9a-zA-Zа-яА-Я\-\s\/_]+/, ''));
    });

    $(document).off('click', '.layout-row-add').on('click', '.layout-row-add', function () {
        var cloned = $('.layout-body').find('.layout-row:last').clone();
        cloned.insertAfter('.layout-row:last');
        var row_count = $('.layout-body').children('.layout-row').length;
        $('#rows').val(row_count);
        console.log(row_count);


        setLayoutNumbers();
    });

    $(document).on('click', '.layout-row-remove', function () {
        $(this).parents('.layout-row').remove();

        setLayoutNumbers();
    });

    $(document).on('click', '.layout-item-add', function () {
        $(this).parents('.layout-item').find('.layout-input').val("1").trigger('change');

        setLayoutNumbers();
    });

    $(document).on('click', '.layout-item-remove', function () {
        $(this).parents('.layout-item').find('.layout-input').val("").trigger('change');

        setLayoutNumbers();
    });

    $(document)
        .off('click', '.layout-item.layout-active .layout-label')
        .on('click', '.layout-item.layout-active .layout-label', function () {
            if (!$(this).parents('.layout-item').hasClass('layout-busy')) {
                $(this).parents('.layout-item').toggleClass('layout-selected');
            }
        });

    function setLayoutNumbers() {
        var $places = [];
        $('.layout-input[value!=""]').each(function (k, v) {
            $places[k] = $(v).val();
        });

        var count = 0;
        var c = 1;
        $('.layout-active .layout-input').each(function (k, v) {
            // console.log(k,v);
            // if ($places[count] === parseInt($places[count]) || $places[count] === '' || $places[count] === undefined) {
            $(v).val(c);
            // } else {
            //     $(v).val($places[count]);
            // }
            c += 1;
            count++;
        });
    }

    function setLayout(data, dataExt = '') {
        $('.layout-container').html('');
        var ar = JSON.parse(data);
        var rows = ar.length;

        var t = '', val = '';

        var touristsNames = '';
        if (dataExt !== '') {
            touristsNames = JSON.parse(dataExt);
        }

        var addElement = '';
        addElement = '<div class="layout-item-add">+</div>';

        var delElement = '';
        delElement = '<div class="layout-item-remove">x</div>';

        var delRow = '';
        delRow = '<div class="layout-row-remove" title="Удалить ряд">-</div>';

        t += '<div class="layout-body">';
        for (var row = 0; row < rows; row++) {
            t += '<div class="layout-row">';
            $.each(ar[row], function (k, v) {
                v = v.toString();

                t += '<div class="layout-item' + (v !== '' ? ' layout-active' : '') + (v != '' && $.inArray(v, $places_selected) > -1 ? ' layout-selected' : '') + (v != '' && $.inArray(v, $places_busy) > -1 ? ' layout-busy' : '') + '">';

                t += '<input type="text" class="layout-input" name="cells[]" maxlength="10" value="' + (v != '' ? v : '') + '" />' + delElement + addElement;

                t += '</div>';
            });
            t += delRow + '</div>';
        }
        t += '<div class="layout-row-add">+</div>';
        t += '</div>';

        $('.layout-container').html(t);
    }

    function setNewLayout() {
        var rows = $('#rows').val();
        var columns = $('#layout').val();
        var columns = columns.split('x');
        var data = [];
        var index = 1;
        for(var i=1; i<=rows; i++){
            var array = [];

            for(var j = 1; j<=columns[0]; j++){
                array.push(index);
                index++;
            }

            array.push('');

            for(var l = 1; l<=columns[1]; l++){
                array.push(index);
                index++;
            }
            data.push(array);
        }


        console.log(data);
        setLayout(JSON.stringify(data));
    }


    $('.layout-build').click(function () {

        var $this = $(this);
        if ($.trim($('.layout-container').html()) !== "") {

            Swal.fire({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.approvalWarning')",
                icon: 'warning',
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "@lang('app.approve')",
                cancelButtonText: "@lang('app.cancel')",
                customClass: {
                    confirmButton: 'btn btn-primary mr-3',
                    cancelButton: 'btn btn-secondary'
                },
                showClass: {
                    popup: 'swal2-noanimation',
                    backdrop: 'swal2-noanimation'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    setNewLayout();
                }
            });
        } else {
            setNewLayout();
        }
    });

</script>
