@php
    $addProductPermission = user()->permission('edit_order');
@endphp

        <!-- CREATE INVOICE START -->
<div class="bg-white rounded b-shadow-4 create-inv">
    <!-- HEADING START -->
    <div class="px-lg-4 px-md-4 px-3 py-3">
        <h4 class="mb-0 f-21 font-weight-normal text-capitalize">@lang('modules.orders.createOrder')</h4>
    </div>
    <!-- HEADING END -->
    <hr class="m-0 border-top-grey">
    <!-- FORM START -->
    <x-form class="c-inv-form" id="saveInvoiceForm">
        @method('PUT')
        <input type="hidden" name="type" value="send">
        {{--        <input type="hidden" name="reminder" id="reminder">--}}
        <input type="hidden" name="total_paid" id="total_paid">

        <input type="hidden" name="uzs_rate" id="uzs_rate"
               value="{{ $currencies->where('currency_code', 'UZS')->first()?->exchange_rate }}">
        <input type="hidden" name="euro_rate" id="euro_rate"
               value="{{ $currencies->where('currency_code', 'EUR')->first()?->exchange_rate }}">
        <input type="hidden" name="usd_rate" id="usd_rate"
               value="{{ $currencies->where('currency_code', 'USD')->first()?->exchange_rate }}">

        <!-- CLIENT, PROJECT, GST, BILLING, SHIPPING ADDRESS START -->
        <div class="row px-lg-4 px-md-4 px-3 pt-3">
            <!-- INVOICE NUMBER START -->
            <div class="col-md-2 mb-4">
                <div class="form-group mb-lg-0 mb-md-0 mb-4">
                    <label class="f-14 text-dark-grey mb-12 text-capitalize"
                           for="usr">@lang('modules.orders.orderNumber')</label>
                    <div class="input-group">
                        <input type="text" name="order_number" id="order_id"
                               class="form-control height-35 f-15 readonly-background" readonly
                               value="{{ $order->order_number }}">
                    </div>
                </div>
            </div>
            <!-- INVOICE NUMBER END -->
            <!-- DATE START -->
            <div class="col-md-3">
                <div class="form-group my-3">
                    <x-forms.label fieldId="created_at" :fieldLabel="__('app.createdAt')">
                    </x-forms.label>
                    <div class="input-group">
                        <input type="text" id="created_at" name="created_at"
                               value="{{now()->format('d-m-Y')}}"
                               class=" position-relative text-dark font-weight-normal form-control height-35 f-14 rounded p-0 text-left"
                               placeholder="@lang('placeholders.date')"
                        >
                    </div>
                </div>
            </div>
            <!-- DATE END -->

            <!-- CLIENT START -->
            <div class="col-md-3">
                <x-forms.text :fieldLabel="__('modules.lead.clientName')" fieldName="client_name"
                              fieldId="client_name" fieldPlaceholder="" fieldRequired="true"
                              :fieldValue="$order?->client?->name ?? ''"
                              :fieldDisabled="true"
                              class="mb-lg-0 mb-md-0 mb-4 my-0"
                />
            </div>
            <!-- CLIENT END -->
            <!-- Order Status -->
            <div class="col-md-4 mt-3">
                <x-forms.label fieldId="status" :fieldLabel="__('app.status')" :fieldRequired="true"
                               class="mt-0"></x-forms.label>

                <select class="form-control select-picker" name="status" disabled id="status">
                    <option selected value="pending"
                            data-content="<i class='fa fa-circle mr-2 text-yellow'></i> @lang('app.pending') ">@lang('app.pending')</option>

                    <option value="on-hold"
                            data-content="<i class='fa fa-circle mr-2 text-info'></i> @lang('app.on-hold') ">@lang('app.on-hold')</option>

                    <option value="failed"
                            data-content="<i class='fa fa-circle mr-2 text-muted'></i> @lang('app.failed') ">@lang('app.failed')</option>

                    <option value="processing"
                            data-content="<i class='fa fa-circle mr-2 text-blue'></i> @lang('app.processing') ">@lang('app.processing')</option>

                    <option value="completed"
                            data-content="<i class='fa fa-circle mr-2 text-dark-green'></i> @lang('app.completed') ">@lang('app.completed')</option>

                    <option value="canceled"
                            data-content="<i class='fa fa-circle mr-2 text-red'></i> @lang('app.canceled') ">@lang('app.canceled')</option>

                </select>
            </div>
            <div class="col-md-2 mt-1">
                <x-forms.select fieldId="partner_id" :fieldLabel="__('app.partner')" fieldName="partner_id"
                                search="true">
                    <option value="">--</option>
                    @foreach($partners as $partner)
                        <option
                                @if($order->partner?->id == $partner->id)
                                    selected
                                @endif
                                value="{{$partner->id}}">{{$partner->name}}
                        </option>
                    @endforeach


                </x-forms.select>
            </div>

            <div class="col-md-2 mt-1">
                <x-forms.text
                        fieldLabel="Курс валюты" fieldName="partner_rate"
                        fieldId="partner_rate" fieldPlaceholder="" fieldRequired="true"
                        fieldValue=""
                        :fieldDisabled="true"
                        class="mb-lg-0 mb-md-0 mb-4 my-0"
                />
            </div>
            <div class="col-md-3">
                <x-forms.label class="my-3" fieldId="hotel_id"
                               :fieldLabel="__('modules.integrations.hotel')">
                </x-forms.label>

                <x-forms.input-group>
                    <select class="form-control select-picker" name="hotel_id" id="hotel_id"
                            data-live-search="true">
                        {{--                        <option--}}
                        {{--                                value="{{$integration->hotel_id ?? ""}}">{{$integration->hotel_id ?? ""}}</option>--}}
                    </select>
                </x-forms.input-group>
            </div>
            <div class="col-md-3">
                <x-forms.label class="my-3" fieldId="category_id"
                               :fieldLabel="__('modules.integrations.category')">
                </x-forms.label>

                <x-forms.input-group>
                    <select class="form-control select-picker" name="category_id" id="category_id"
                            data-live-search="true">
                        <option
                                value="{{$integration->category_id ?? ""}}">{{$integration->category_name ?? ""}}</option>
                    </select>
                </x-forms.input-group>
            </div>
            <div class="col-md-3">
                <x-forms.label class="my-3" fieldId="category_id"
                               :fieldLabel="__('modules.integrations.category')">
                </x-forms.label>

                <x-forms.input-group>
                    <select class="form-control select-picker" name="category_id" id="category_id"
                            data-live-search="true">
                        <option
                                value="{{$integration->category_id ?? ""}}">{{$integration->category_name ?? ""}}</option>
                    </select>
                </x-forms.input-group>
            </div>
            <input type="hidden" id="calculate_tax" value="after_discount">
        </div>


        <div id="sortable">
            <div class="d-flex px-4 py-3 c-inv-desc item-row">
                <div class="c-inv-desc-table w-100 d-lg-flex d-md-flex d-block">
                    <table width="100%">
                        <tbody>
                        <tr class="text-dark-grey font-weight-bold f-14">
                            <td width="{{ $invoiceSetting->hsn_sac_code_show ? '40%' : '50%' }}"
                                class="border-0 inv-desc-mbl btlr">Название тура
                            </td>
                            <td width="10%" class="border-0" align="right">Взрослые</td>
                            <td width="10%" class="border-0" align="right">Дети</td>
                            <td width="10%" class="border-0" align="right">Ночи</td>
                            <td width="13%" class="border-0" align="right">Гостиница</td>

                        </tr>
                        <tr>
                            <td class="border-bottom-0 btrr-mbl btlr">
                                <input type="text" class="form-control f-14 border-0 w-100 item_name"
                                       value="{{$order?->name}}"
                                       name="item_name" placeholder="@lang("modules.expenses.itemName")">
                            </td>
                            <td class="border-bottom-0 d-block d-lg-none d-md-none">' +
                                <textarea class="f-14 border-0 w-100 mobile-description" name="item_summary[]"
                                          placeholder="@lang("placeholders.invoices.description")">

                                </textarea>
                            </td>
                            <td class="border-bottom-0">
                                <input type="text" min="1"
                                       value="{{$integration?->adults_count ?? 0}}"
                                       class="form-control f-14 border-0 w-100 text-right hsn_sac_code"
                                       name="adults_count">
                            </td>
                            <td class="border-bottom-0">
                                <input type="number" min="1"
                                       value="{{$integration?->children_count ?? 0}}"
                                       class="form-control f-14 border-0 w-100 text-right quantity"
                                       name="children_count">
                            </td>
                            <td class="border-bottom-0">
                                <input type="number" min="1" class="mb-2 f-14 border-0 w-100 text-right cost_per_item"
                                       placeholder="0"
                                       value="{{$integration?->nights_count_from ?? 0}}"
                                       name="nights_count_from">

                                <input type="number" min="1" class="f-14 border-0 w-100 text-right cost_per_item"
                                       placeholder="0"
                                       value="{{$integration?->nights_count_from ?? 0}}"

                                       name="nights_count_to">
                            </td>
                            <td class="border-bottom-0">
                                <div class="select-others height-45 rounded border-0">
                                    <input type="text" class="form-control f-14 border-0 w-100 item_name"
                                           value="{{$order?->hotel_name ?? ""}}"
                                           name="hotel_name">

                                    <x-forms.checkbox fieldLabel="Visa"
                                                      fieldName="visa"
                                                      fieldId="visa"
                                                      :checked="$order->visa"
                                                      class="mt-1"/>
                                    <x-forms.checkbox fieldLabel="Авиабилет"
                                                      fieldName="air_ticket"
                                                      fieldId="air_ticket"
                                                      :checked="$order->air_ticket"
                                                      class="mt-1"/>

                                    <x-forms.checkbox fieldLabel="Страхование"
                                                      fieldName="insurance"
                                                      fieldId="insurance"
                                                      :checked="$order->insurance"
                                                      class="mt-1"/>

                                    <x-forms.checkbox fieldLabel="Отель"
                                                      fieldName="hotel"
                                                      fieldId="hotel"
                                                      :checked="$order->hotel"
                                                      class="mt-1"/>
                                    <x-forms.checkbox fieldLabel="трансфер"
                                                      fieldName="transfer"
                                                      fieldId="transfer"
                                                      :checked="$order->transfer"

                                                      class="mt-1"/>
                                </div>

                            </td>
                            {{--                            <td rowspan="2" align="right" valign="top" class="bg-amt-grey btrr-bbrr">--}}
                            {{--                                <span class="amount-html">0.00</span>--}}
                            {{--                                <input type="hidden" class="amount" name="amount[]" value="0">--}}
                            {{--                            </td>--}}
                        </tr>
                        <tr class="d-none d-md-table-row d-lg-table-row">
                            <td colspan="{{ $invoiceSetting->hsn_sac_code_show ? 5 : 4 }}" class="dash-border-top bblr">
                                <textarea class="f-14 border-0 w-100 desktop-description" name="note"
                                          placeholder="@lang("placeholders.invoices.description")"></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <hr class="m-0 border-top-grey ">

        <!-- TOTAL, DISCOUNT START -->
        <div class="d-flex px-lg-4 px-md-4 px-3 pb-3 c-inv-total">
            <table width="100%" class="text-right f-14 text-capitalize">
                <tbody>

                <br>
                <tr>
                    <td width="50%" class="border-0 d-lg-table d-md-table d-none"></td>
                    <td width="50%" class="p-1 border-0 c-inv-total-right mt-3">
                        <table width="100%">
                            <tbody>

                            <tr class="bg-amt-grey f-16 f-w-500">

                                <td colspan="2">@lang('modules.invoices.total')</td>
                                <td width="38%"><input class="form-control f-14 border-0 w-100 text-right hsn_sac_code"
                                                       name="total"
                                                       id="total"
                                                       value="{{$order->total}}"></td>
                                <td width="12%">
                                    <select id="currency_id" name="currency_id" disabled>
                                        @foreach ($currencies as $currency)
                                            <option @if ($currency->id == company()->currency_id) selected
                                                    @endif value="{{ $currency->id }}"
                                                    data-currency-code="{{ $currency->currency_code }}">
                                                {{ $currency->currency_code . ' (' . $currency->currency_symbol . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="50%" class="border-0 d-lg-table d-md-table d-none"></td>
                    <td width="50%" class="p-1 border-0 c-inv-total-right mt-3">
                        <table width="100%">
                            <tbody>
                            {{-- TOTAL PAID SECTION --}}
                            <tr class="bg-amt-grey f-16 f-w-100">
                                <td width="5%">euro</td>
                                <td width="20%"><input
                                            class="form-control f-14 border-0 w-100 text-right hsn_sac_code paid_input"
                                            name="paid_euro"
                                            id="paid_euro"
                                            value="0"></td>
                                <td width="5%">$</td>
                                <td width="20%"><input
                                            class="form-control f-14 border-0 w-100 text-right hsn_sac_code paid_input"
                                            name="paid_usd"
                                            id="paid_usd"
                                            value="0"></td>
                                <td width="5%">uzs</td>
                                <td width="50%"><input
                                            class="form-control f-14 border-0 w-100 text-right hsn_sac_code paid_input"
                                            name="paid_uzs"
                                            id="paid_uzs"
                                            value="0"></td>
                            </tr>
                            <tr class="bg-amt-grey f-16 f-w-100">
                                {{-- TOTAL REMIND SECTION --}}
                                <td colspan="5">Остаток</td>
                                <td width="30%"><input
                                            class="form-control f-14 border-0 w-100 text-right hsn_sac_code"
                                            name="reminder"
                                            id="reminder"
                                            readonly
                                            value="0"></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="50%" class="border-0 d-lg-table d-md-table d-none"></td>
                    <td width="50%" class="p-1 border-0 c-inv-total-right mt-3">
                        <table width="100%">
                            <tbody>
                            <tr class="bg-amt-grey f-16 f-w-100">
                                <td colspan="2">Netto цена</td>
                                <td width="50%"><input
                                            class="form-control f-14 border-0 w-100 text-right hsn_sac_code"
                                            name="net_price"
                                            id="net_price"
                                            value="{{$order->net_price ?? 0}}"></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td width="50%" class="border-0 d-lg-table d-md-table d-none"></td>
                    <td width="50%" class="p-1 border-0 c-inv-total-right mt-3">
                        <table width="100%">
                            <tbody>
                            <tr class="bg-amt-grey f-16 f-w-100">
                                <td colspan="2">Услуга фирмы</td>
                                <td width="50%"><input
                                            class="form-control f-14 border-0 w-100 text-right hsn_sac_code"
                                            name="service_fee"
                                            id="service_fee"
                                            value="0"></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
        <!-- TOTAL, DISCOUNT END -->

        <!-- NOTE AND TERMS AND CONDITIONS START -->
        {{--        <div class="d-flex flex-wrap px-lg-4 px-md-4 px-3 py-3">--}}
        {{--            <div class="col-md-6 col-sm-12 c-inv-note-terms p-0 mb-lg-0 mb-md-0 mb-3">--}}
        {{--                <label class="f-14 text-dark-grey mb-12 text-capitalize w-100"--}}
        {{--                       for="usr">@lang('app.clientNote')</label>--}}
        {{--                <textarea class="form-control" name="note" id="note" rows="4"></textarea>--}}
        {{--            </div>--}}

        {{--        </div>--}}
        <!-- NOTE AND TERMS AND CONDITIONS END -->
        <!-- CANCEL SAVE SEND START -->
        <x-form-actions class="c-inv-btns d-block d-lg-flex d-md-flex">
            <x-forms.button-primary id="createOrder">@lang('app.submit')</x-forms.button-primary>

            <x-forms.button-cancel :link="route('leadboards.index')" class="ml-2 border-0 ">@lang('app.cancel')
            </x-forms.button-cancel>

        </x-form-actions>
        <!-- CANCEL SAVE SEND END -->

    </x-form>
    <!-- FORM END -->
</div>
<!-- CREATE INVOICE END -->
<script>
    $(document).ready(function () {
        $('.paid_input').on('input', function () {
            const paidUzs = parseFloat(($('#paid_uzs').val() ?? 0) * $('#uzs_rate').val());
            const paidUsd = parseFloat(($('#paid_usd').val() ?? 0));
            const paidEur = parseFloat(($('#paid_euro').val() ?? 0) * $('#euro_rate').val());

            const total_paid = (paidEur + paidUzs + paidUsd);
            //set total paid & reminder
            $('#total_paid').val(total_paid);
            console.log(total_paid)
            console.log(paidUsd)
            console.log(paidUzs)
            console.log($('#paid_usd').val())

            $('#reminder').val('$' + parseFloat($('#total').val() - total_paid).toFixed(2));
        });

        $('.toggle-product-category').click(function () {
            $('.product-category-filter').toggleClass('d-none');
        });

        $('#product_category_id').on('change', function () {
            var categoryId = $(this).val();
            var url = "{{route('invoices.product_category', ':id')}}",
                url = (categoryId) ? url.replace(':id', categoryId) : url.replace(':id', null);

            $.easyAjax({
                url: url,
                type: "GET",
                container: '#saveInvoiceForm',
                blockUI: true,
                success: function (response) {
                    if (response.status == 'success') {
                        var options = [];
                        var rData = [];
                        rData = response.data;
                        $.each(rData, function (index, value) {
                            var selectData = '';
                            selectData = '<option value="' + value.id + '">' + value.name +
                                '</option>';
                            options.push(selectData);
                        });
                        $('#add-products').html(
                            '<option value="" class="form-control" >{{ __('app.select') . ' ' . __('app.product') }}</option>' +
                            options);
                        $('#add-products').selectpicker('refresh');
                    }
                }
            });
        });
        const hsn_status = {{ $invoiceSetting->hsn_sac_code_show }};
        $('#client_list_id').change(function () {
            var id = $(this).val();
            changeClient(id);
        });
        $('#partner_id').change(function () {
            const rate = $('#partner_rate');
            $.ajax({
                url: "{{ route('integrations.getCurrency') }}", // Replace with your API endpoint
                data: {
                    "from": "USD",
                    "to": "UZS",
                    'name': $(this).find("option:selected").text()
                },
                method: 'GET',
                success: function (response) {
                    rate.val(response)
                    console.log(response)
                },
                error: function () {
                    console.error('API request failed');
                }
            });

        });

        function changeClient(id) {

            if (id == '') {
                id = 0;
            }
            console.log(id);
            var token = "{{ csrf_token() }}";


            var url = "{{ route('clients.ajax_details', ':id') }}";
            url = url.replace(':id', id);

            $.easyAjax({
                url: url,
                container: '#saveInvoiceForm',
                type: "POST",
                blockUI: true,
                data: {
                    _token: token
                },
                success: function (response) {
                    if (response.status == 'success') {
                        if (response.data !== null) {
                            $('#client_billing_address').html(nl2br(response.data.client_details
                                .address));
                            $('#add-shipping-field').addClass('d-none');
                            $('#client_shipping_address').removeClass('d-none');

                            if (response.data.client_details.shipping_address === null) {
                                var addShippingLink =
                                    '<a href="javascript:;" class="text-capitalize" id="show-shipping-field"><i class="f-12 mr-2 fa fa-plus"></i>@lang("app.addShippingAddress")</a>';
                                $('#client_shipping_address').html(addShippingLink);
                            } else {
                                $('#client_shipping_address').html(nl2br(response.data
                                    .client_details
                                    .shipping_address));
                            }

                        } else {
                            $('#client_billing_address').html(
                                '<span class="text-lightest">@lang("messages.selectCustomerForBillingAddress")</span>'
                            );
                        }
                    } else {
                        var addShippingLink =
                            '<a href="javascript:;" class="text-capitalize" id="show-shipping-field"><i class="f-12 mr-2 fa fa-plus"></i>@lang("app.addShippingAddress")</a>';
                        $('#client_shipping_address').html(addShippingLink);
                    }
                }
            });

        }


        $('body').on('click', '#show-shipping-field', function () {
            $('#add-shipping-field, #client_shipping_address').toggleClass('d-none');
        });

        const resetAddProductButton = () => {
            $("#add-products").val('').selectpicker("refresh");
        };

        $('#add-products').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
            e.stopImmediatePropagation()
            var id = $(this).val();
            if (previousValue != id && id != '') {
                addProduct(id);
                resetAddProductButton();
            }
        });

        function ucWord(str) {
            str = str.toLowerCase().replace(/\b[a-z]/g, function (letter) {
                return letter.toUpperCase();
            });
            return str;
        }

        function addProduct(id) {

            $.easyAjax({
                url: "{{ route('orders.add_item') }}",
                type: "GET",
                data: {
                    id: id
                },
                blockUI: true,
                success: function (response) {
                    if ($('input[name="item_name[]"]').val() == '') {
                        $("#sortable .item-row").remove();
                    }
                    $(response.view).hide().appendTo("#sortable").fadeIn(500);
                    $('.selectpicker').selectpicker('refresh');
                    calculateTotal();
                    $('.dropify').dropify();
                    $('#alertMessage').hide().fadeOut(500);
                    var noOfRows = $(document).find('#sortable .item-row').length;
                    var i = $(document).find('.item_name').length - 1;
                    var itemRow = $(document).find('#sortable .item-row:nth-child(' + noOfRows +
                        ') select.type');
                    itemRow.attr('id', 'multiselect' + i);
                    itemRow.attr('name', 'taxes[' + i + '][]');
                    $(document).find('#multiselect' + i).selectpicker();

                    $(document).find('#dropify' + i).dropify({
                        messages: dropifyMessages
                    });
                }
            });
        }


        $('#saveInvoiceForm').on('click', '.remove-item', function () {
            $(this).closest('.item-row').fadeOut(300, function () {
                $(this).remove();
                $('select.customSequence').each(function (index) {
                    $(this).attr('name', 'taxes[' + index + '][]');
                    $(this).attr('id', 'multiselect' + index + '');
                });

                if ($(document).find('#sortable .item-row').length == 0) {
                    $('#alertMessage').show().fadeIn(500);
                }

                calculateTotal();
            });
        });

        $('#createOrder').click(function () {

            if (KTUtil.isMobileDevice()) {
                $('.desktop-description').remove();
            } else {
                $('.mobile-description').remove();
            }

            calculateTotal();

            var discount = $('#discount_amount').html();
            var total = $('.sub-total-field').val();

            if (parseFloat(discount) > parseFloat(total)) {
                Swal.fire({
                    icon: 'error',
                    text: "{{ __('messages.discountExceed') }}",

                    customClass: {
                        confirmButton: 'btn btn-primary',
                    },
                    showClass: {
                        popup: 'swal2-noanimation',
                        backdrop: 'swal2-noanimation'
                    },
                    buttonsStyling: false
                });
                return false;
            }

            $.easyAjax({
                url: "{{ route('custom.orders.update', $order->id) }}",
                container: '#saveInvoiceForm',
                type: "POST",
                blockUI: true,
                redirect: true,
                file: true,
                data: $('#saveInvoiceForm').serialize()
            })
        });

        $('#saveInvoiceForm').on('click', '.remove-item', function () {
            $(this).closest('.item-row').fadeOut(300, function () {
                $(this).remove();
                $('select.customSequence').each(function (index) {
                    $(this).attr('name', 'taxes[' + index + '][]');
                    $(this).attr('id', 'multiselect' + index + '');
                });
                calculateTotal();
            });
        });


        $('#saveInvoiceForm').on('keyup', '.quantity,.cost_per_item,.item_name, .discount_value', function () {
            var quantity = $(this).closest('.item-row').find('.quantity').val();
            var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();
            var amount = (quantity * perItemCost);

            $(this).closest('.item-row').find('.amount').val(decimalupto2(amount));
            $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount));

            calculateTotal();
        });

        $('#saveInvoiceForm').on('change', '.type, #discount_type, #calculate_tax', function () {
            var quantity = $(this).closest('.item-row').find('.quantity').val();
            var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();
            var amount = (quantity * perItemCost);

            $(this).closest('.item-row').find('.amount').val(decimalupto2(amount));
            $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount));

            calculateTotal();
        });

        $('#saveInvoiceForm').on('input', '.quantity', function () {
            var quantity = $(this).closest('.item-row').find('.quantity').val();
            var perItemCost = $(this).closest('.item-row').find('.cost_per_item').val();
            var amount = (quantity * perItemCost);

            $(this).closest('.item-row').find('.amount').val(decimalupto2(amount));
            $(this).closest('.item-row').find('.amount-html').html(decimalupto2(amount));

            calculateTotal();
        });

        calculateTotal();

        const dp1 = datepicker('#created_at', {
            position: 'bl',
            ...datepickerConfig
        });

        init(RIGHT_MODAL);
    });

</script>
