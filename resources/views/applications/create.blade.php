@extends('layouts.app')
@push('styles')
    <style>
        .debit-row {
            color: red !important;
        }

        .credit-row {
            color: green !important;
        }
    </style>

@endpush
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <a href="{{ route('debit.index')}}">
                    <x-cards.widget :title="__('app.clientPrice')"
                                    :value="currency_format($order?->total / $exchange_rate, company()->currency_id)"
                                    icon="layer-group">
                    </x-cards.widget>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <a href="{{ route('debit.index') }}">
                    <x-cards.widget :title="__('app.clientDebits')"
                                    :value="currency_format(($order?->total - $order?->total_paid) / $exchange_rate, company()->currency_id)"
                                    icon="layer-group">
                    </x-cards.widget>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <a href="{{ route('partner-debits.index')}}">
                    <x-cards.widget :title="__('app.partnerPrice')"
                                    :value="currency_format($order?->net_price / $exchange_rate, company()->currency_id)"
                                    icon="layer-group">
                    </x-cards.widget>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <a href="{{ route('partner-debits.index') }}">
                    <x-cards.widget :title="__('app.debtFromPartners')"
                                    :value="currency_format(($order?->net_price - $order?->net_price_paid) / $exchange_rate, company()->currency_id)"
                                    icon="layer-group">
                    </x-cards.widget>
                </a>
            </div>

        </div>

        @include($view)
    </div>

@endsection
@push('script')
    <script>
        $(document).ready(function () {

            $('body').on('click', '.add-partner', function () {
                const url = '{{ route('partner-settings.create') }}';
                $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
                $.ajaxModal(MODAL_LG, url);
            });

            $('body').on('click', '.add-lead-source', function () {
                const url = '{{ route('lead-source-settings.create') }}';
                $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
                $.ajaxModal(MODAL_LG, url);
            });
            $('body').on('click', '.add-lead-agent', function () {
                const url = '{{ route('lead-agent-settings.create') }}';
                $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
                $.ajaxModal(MODAL_LG, url);
            });

            $('body').on('click', '.add-order-type', function () {
                const url = '{{ route('order-type-settings.create') }}';
                $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
                $.ajaxModal(MODAL_LG, url);
            });


            $('body').on('click', '#deleteBtn', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: "@lang('messages.sweetAlertTitle')",
                    text: "@lang('messages.recoverRecord')",
                    icon: 'warning',
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: "@lang('messages.confirmDelete')",
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
                        var url = "{{ route('orders.deleteItems', ':id') }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            blockUI: true,
                            data: {
                                '_token': token,
                                '_method': 'DELETE'
                            },
                            success: function (response) {
                                if (response.status === "success") {
                                    location.reload();
                                }
                            }
                        });
                    }
                });
            });
            $('body').on('click', '#deleteTravellerBtn', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: "@lang('messages.sweetAlertTitle')",
                    text: "@lang('messages.recoverRecord')",
                    icon: 'warning',
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: "@lang('messages.confirmDelete')",
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
                        var url = "{{ route('applications.removeTraveller', [$application->id, ':id']) }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            blockUI: true,
                            data: {
                                '_token': token,
                                '_method': 'DELETE'
                            },
                            success: function (response) {
                                if (response.status === "success") {
                                    location.reload();
                                }
                            }
                        });
                    }
                });
            });
            $('body').on('click', '#deleteUserBtn', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: "@lang('messages.sweetAlertTitle')",
                    text: "@lang('messages.recoverRecord')",
                    icon: 'warning',
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: "@lang('messages.confirmDelete')",
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
                        var url = "{{ route('applications.removeUser', [$application->id, ':id']) }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            blockUI: true,
                            data: {
                                '_token': token,
                                '_method': 'DELETE'
                            },
                            success: function (response) {
                                if (response.status === "success") {
                                    location.reload();
                                }
                            }
                        });
                    }
                });
            });
            init(RIGHT_MODAL);
        });
    </script>
@endpush
