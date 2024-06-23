@extends('layouts.app')

@section('content')

    <div class="w-100 d-flex ">

        @include('sections.setting-sidebar')

        <x-setting-card>
            <x-slot name="header">
                <div class="s-b-n-header" id="tabs">
                    <h2 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                        @lang($pageTitle)</h2>
                </div>
            </x-slot>

            @if (user()->permission('manage_tax') == 'all')
                <x-slot name="buttons">
                    <div class="row">

                        <div class="col-md-12 mb-2">
                            <x-forms.button-primary icon="plus" id="add-webhook" class="type-btn mb-2 actionBtn">
                                @lang('modules.customFields.addField')
                            </x-forms.button-primary>
                        </div>

                    </div>
                </x-slot>
            @endif

            <div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-0">
                <div class="table-responsive">
                    <x-table class="table-bordered table-hover">
                        <x-slot name="thead">
                            <th>#</th>
                            <th>@lang('app.social_type')</th>
                            <th>@lang('app.event')</th>
                            <th>@lang('modules.customFields.fieldType')</th>
                            <th>@lang('app.validation')</th>
                            <th>@lang('modules.customFields.label')</th>
                            <th class="text-right pr-20">@lang('app.action')</th>
                        </x-slot>

                        @forelse($workflows as $key => $workflow)
                            <tr id="webhook-{{ $workflow->id }}">
                                <td>{{ $workflow->id}}</td>
                                <td>{{ mb_ucwords($workflow->social->name) }}</td>
                                <td>{{ mb_ucwords($workflow->socialEvent->event) }}</td>
                                <td>{{ $workflow->verify->name }}</td>
                                <td>{{ mb_ucwords($workflow->condition->name) }}</td>
                                <td>{{ mb_ucwords($workflow->text) }}</td>
                                <td class="text-right pr-20">
                                    <div class="task_view">
                                        <a class="task_view_more d-flex align-items-center justify-content-center edit-webhook"
                                           href="javascript:;" data-webhook-id="{{ $workflow->id }}">
                                            <i class="fa fa-edit icons mr-2"></i> @lang('app.edit')
                                        </a>
                                    </div>
                                    <div class="task_view">
                                        <a class="task_view_more d-flex align-items-center justify-content-center delete-webhook"
                                           href="javascript:;" data-webhook-id="{{ $workflow->id }}">
                                            <i class="fa fa-edit icons mr-2"></i> @lang('app.delete')
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <x-cards.no-record-found-list colspan="7"/>
                        @endforelse
                    </x-table>
                </div>
            </div>

        </x-setting-card>

    </div>
    <!-- SETTINGS END -->
@endsection

@push('scripts')

    <script>
        $('#add-webhook').click(function () {
            const url = "{{ route('webhook.create') }}?via=webhook";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        // edit new webhook
        $('.edit-webhook').click(function () {
            let id = $(this).data('webhook-id');

            let url = "{{ route('webhook.edit', ':id') }}?via=webhook-setting";
            url = url.replace(':id', id);

            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        $('body').on('click', '.delete-webhook', function () {
            const id = $(this).data('webhook-id');
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
                    let url = "{{ route('webhook.destroy', ':id') }}";
                    url = url.replace(':id', id);

                    const token = "{{ csrf_token() }}";

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
                                $('#webhook-' + id).fadeOut(100);
                            }
                        }
                    });
                }
            });
        });
    </script>

@endpush
