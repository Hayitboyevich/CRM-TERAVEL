@extends('layouts.app')
@push('styles')
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
@endpush
@section('content')
    <div class="row">
        <div class="col p-20">
            <div class="col-8 layout-container">

            </div>
            <div class="row p-20 m-2">
                <button id="save-application-form"
                        class="btn-primary rounded f-14 p-2 mr-1 float-left mb-2 mb-lg-0 mb-md-0">
                    Сохранять
                </button>
                <a href="{{route('applications.edit', $application->id)}}"
                   class="btn-danger rounded f-14 p-2 float-left mb-2 mb-lg-0 mb-md-0">
                    Отмена
                </a>
            </div>
        </div>

    </div>

@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            let selectedItems = [];

            $('#save-application-form').click(function () {
                const url = "{{ route('applications.book', [$application->id, $schema->id]) }}";

                $.ajax({
                    url: url,
                    type: "POST",
                    container: '#save-schema-data-form',
                    disableButton: true,
                    blockUI: true,
                    file: true,
                    buttonSelector: "#save-application-form",
                    data: {
                        'seat_ids': selectedItems,
                        '_token': "{{csrf_token()}}"
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            window.location.href = '{{route('applications.edit', $application->id)}}';
                        }
                    }
                });
            });
            var row = '{!! $schema->row_amount !!}';
            var dimension = '{!! $schema->dimension !!}';
            var dimension = dimension.split('x');
            var dimension = parseInt(dimension[0]) + parseInt(dimension[1]) + 1;
            var seats = '{!! $schema->seats !!}';
            var seats = JSON.parse(seats);
            var seats_count = '{!! count($schema->seats) !!}';

            console.log(seats);

            var data = [];
            for (var i = 0; i < row; i++) {
                var array = [];
                for (var j = 0; j < dimension; j++) {
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
            $('body').on('click', '.layout-item', function () {
                let seat = $(this).find('button');
                let seatId = seat.attr('data-id');
                selectedItems.push(seatId);
                seat[0].style.backgroundColor = 'green';
                console.log(selectedItems);

                {{--const url = '{{ route('applications.book') }}';--}}
                {{--$(MODAL_LG + ' ' + MODAL_HEADING).html('...');--}}
                {{--$.ajaxModal(MODAL_LG, url);--}}
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
                $(v).val(c);

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
            t += '<div class="layout-body">';
            for (var row = 0; row < rows; row++) {
                t += '<div class="layout-row">';
                $.each(ar[row], function (k, v) {
                    v = v.toString();

                    t += '<div class="layout-item' + (v !== '' ? ' layout-active' : '') + (v != '' && $.inArray(v, $places_selected) > -1 ? ' layout-selected' : '') + (v != '' && $.inArray(v, $places_busy) > -1 ? ' layout-busy' : '') + '">';

                    t += '<button  data-id="' + (v !== '' ? v : '') + '" class="layout-input" name="cells[]" maxlength="10" value="" >' + (v != '' ? v : '') + '</button>';

                    t += '</div>';
                });
                t += '</div>';
            }
            t += '</div>';

            $('.layout-container').html(t);
        }

        function setNewLayout() {
            var rows = $('#rows').val();
            var columns = $('#layout').val();
            var columns = columns.split('x');
            var data = [];
            var index = 1;
            for (var i = 1; i <= rows; i++) {
                var array = [];

                for (var j = 1; j <= columns[0]; j++) {
                    array.push(index);
                    index++;
                }

                array.push('');

                for (var l = 1; l <= columns[1]; l++) {
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
@endpush