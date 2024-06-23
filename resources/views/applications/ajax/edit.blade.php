@php
    use Illuminate\Support\Facades\Log;
@endphp
<div class="row">
    <div class="col-sm-12">
        <x-form id="save-application-data-form" method="PUT">
            <div class="add-application bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    {{__('app.fullInformation')}}
                </h4>
                <div class="row p-20 pb-0">
                    <div class="col-lg-4 col-md-6">
                        <x-forms.label class="my-3" fieldId="type_id"
                                       :fieldLabel="__('app.orderType')">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="type_id" id="type_id"
                                    data-live-search="true">
                                <option value="">--</option>
                                @foreach ($tourTypes as $type)
                                    <option
                                        @if($type->id == $application->type_id)
                                            selected
                                        @endif
                                        value="{{ $type->id }}">{{ mb_ucwords($type->name) }}</option>
                                @endforeach
                            </select>

                            <x-slot name="append">
                                <button type="button"
                                        class="btn btn-outline-secondary border-grey add-order-type"
                                        data-toggle="tooltip"
                                        data-original-title="{{ __('app.add').'  '.__('app.new').' '.__('modules.tickets.agents') }}">@lang('app.add')</button>
                            </x-slot>
                        </x-forms.input-group>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <x-forms.label class="my-3" fieldId="partner_id"
                                       :fieldLabel="__('app.tourOperator')">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="partner_id" id="partner_id"
                                    data-live-search="true">
                                <option value="">--</option>
                                @foreach ($partners as $partner)
                                    <option
                                        @if($partner->id == $application?->partner_id)
                                            selected
                                        @endif
                                        value="{{ $partner->id }}">{{ mb_ucwords($partner->name) }}</option>
                                @endforeach
                            </select>

                            <x-slot name="append">
                                <button type="button"
                                        class="btn btn-outline-secondary border-grey add-partner"
                                        data-toggle="tooltip"
                                        data-original-title="{{ __('app.add').'  '.__('app.new').' '.__('modules.tickets.agents') }}">@lang('app.add')</button>
                            </x-slot>
                        </x-forms.input-group>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <x-forms.label class="my-3" fieldId="source_id"
                                       :fieldLabel="__('modules.lead.leadSource')">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="source_id" id="source_id"
                                    data-live-search="true">
                                <option value="">--</option>
                                @foreach ($sources as $source)
                                    <option
                                        @if($source->id == $application->source_id)
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

                    <div class="col-lg-4 col-md-6">
                        <x-forms.label class="my-3" fieldId="agent_id"
                                       :fieldLabel="__('modules.tickets.chooseAgents')">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="agent_id" id="agent_id"
                                    data-live-search="true">
                                <option value="">--</option>
                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('demosuperadmin'))
                                @foreach ($leadAgents as $agent)
                                    <x-user-option :user="$agent->user"
                                                   :selected="($agent->id == $application->agent_id) ? true : false"
                                                   :userID="$agent->id"/>
                                @endforeach
                                @else
                                    <x-user-option :user="auth()->user()"
                                                   :selected="true"
                                                   :userID="auth()->user()->id"/>
                                @endif
                            </select>

                            <x-slot name="append">
                                <button type="button"
                                        class="btn btn-outline-secondary border-grey add-lead-agent"
                                        data-toggle="tooltip"
                                        data-original-title="{{ __('app.add').'  '.__('app.new').' '.__('modules.tickets.agents') }}">@lang('app.add')</button>
                            </x-slot>
                        </x-forms.input-group>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="form-group" id="orderNumbersContainer">
                            <!-- Order Numbers will be dynamically added here -->
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" id="addOrderNumberBtn">@lang('app.addReservationNumber')</button>
                    </div>

                </div>
                <x-form-actions>
                    <x-forms.button-primary id="save-application-form" class="mr-3"
                                            icon="check">@lang('app.save')
                    </x-forms.button-primary>

                    <x-forms.button-cancel id="cancel-save-application"
                                           class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>
            </div>

        </x-form>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="orderNumberModal" tabindex="-1" role="dialog" aria-labelledby="orderNumberModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderNumberModalLabel">Add Order Number</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="orderNumberForm">
                    <div class="form-group">
                        <label for="orderNumber">Order Number:</label>
                        <input type="text" name="order_number" id="orderNumber" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="partner">Partner:</label>
                        <select name="partner_id" id="partner" class="form-control" required>
                            <option value="">Select Partner</option>
                            @foreach($partners as $partner)
                                <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="validationErrors" class="text-danger"></div> <!-- Container for validation errors -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveOrderNumberBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-sm-12">
        <div class="bg-white rounded">
            <div class="row p-20">
                <h4 class="mb-0 pl-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    {{__('app.client')}}</h4>

                @if(!$application->client)
                    <x-forms.link-primary :link="route('applications.searchClient', $application->id)"
                                          class="ml-auto mr-4 float-left mb-2 mb-lg-0 mb-md-0"
                                          icon="plus">
                        @lang('app.add')
                    </x-forms.link-primary>
                @endif

            </div>
            <div class="row p-20 pb-0">
                <table class="table">
                    <thead>
                    <tr class="border-bottom">
                        <th class="font-weight-bold">#</th>
                        <th class="font-weight-bold">ФИО</th>
                        <th class="font-weight-bold">Контакты</th>
                        <th class="font-weight-bold">День рождения</th>
                        <th class="font-weight-bold">Личный кабинет</th>
                        <th class="font-weight-bold"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($application?->client_id)
                        <tr>
                            <td>1</td>
                            <td>
                                <strong>{{ $application?->client?->firstname .' ' . $application?->client?->lastname . ' '. $application?->client?->fathername  }}</strong>
                            </td>

                            <td>{{ $application?->client?->mobile }} <br>
                                {{ $application?->client?->email }}
                            </td>
                            <td>{{ $application?->client?->birthday?->format('d.m.Y') ?? "-" }}</td>
                            <td>Не создан</td>
                            <td>
                                <a href="{{route('applications.clients.edit', [$application->id, $application?->client_id])}}"
                                   class="btn btn-outline-warning height-35"><i class=" fas fa-pencil-alt"></i>
                                </a>
                                <button id="deleteUserBtn" data-id="{{$application?->client_id}}"
                                        class="btn btn-outline-danger height-35"><i class="fas fa-trash"></i>
                                </button>
                            </td>

                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>
<div class="row mt-4">
    <div class="col-sm-12">
        <div class="bg-white rounded">
            <div class="row p-20">
                <h4 class="mb-0 pl-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    {{__('app.tourist')}}</h4>
                <button id="quick-reservation-button"
                        class="ml-auto mr-2 float-left btn btn-outline-primary quick-reservation"
                        data-client="{{ json_encode($clientData) }}"
                        data-passengers="{{ json_encode($passengersData) }}">
                    Quick Reservation
                </button>

                <x-forms.link-primary :link="route('applications.searchTraveller', $application->id)"
                                      class="mr-4 float-left openModal mb-lg-0 mb-md-0"
                                      icon="plus">
                    @lang('app.add')
                </x-forms.link-primary>

            </div>
            <div class="row p-20 pb-0">
                <table class="table">
                    <thead>
                    <tr class="border-bottom">
                        <th class="font-weight-bold">#</th>
                        <th class="font-weight-bold">ФИО туриста</th>
                        <th class="font-weight-bold">Паспорт</th>
                        <th class="font-weight-bold">Загранпаспорт</th>
                        <th class="font-weight-bold">День Рождения</th>
                        <th class="font-weight-bold"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($application?->travelers as $traveler)
                        <tr>
                            <td>{{$traveler->id}}</td>
                            <td>
                                {{ $traveler?->firstname .' ' . $traveler?->lastname . ' '. $traveler?->fathername  }}
                            </td>
                            <td>{{ $traveler?->localPassport?->passport_serial_number }} <br>
                                {{$traveler?->localPassport && $traveler?->localPassport?->given_by ? 'выдан' : ''}} {{ $traveler?->localPassport?->given_by }}
                                <br> {{ $traveler?->localPassport?->given_date }}
                            </td>
                            <td>{{ $traveler?->foreignPassport?->passport_serial_number }} <br>
                                {{$traveler?->localPassport?->expire_date }} <br>
                                {{ $traveler?->foreignPassport?->given_by}}
                            </td>
                            <td>{{ $traveler?->birthday }}</td>
                            <td>
                                <a href="{{  route('applications.clients.edit', [$application->id, $traveler->id]) }}"
                                   class="btn btn-outline-warning height-35"><i class=" fas fa-pencil-alt"></i>
                                </a>
                                <button id="deleteTravellerBtn" data-id="{{$traveler->id}}"
                                        class="btn btn-outline-danger height-35"><i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>

<div class="row mt-4">
    <div class="col-sm-12">
        <div class="bg-white rounded">
            <div class="row p-20">
                <h4 class="mb-0 pl-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    {{__('app.menu.services')}}</h4>
                <div class="btn-group ml-auto mr-4">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        @lang('app.add')
                    </button>
                    <div class="dropdown-menu dropdown-menu-left">
                        <a class="dropdown-item" href="{{route('applications.packages.create', $application->id)}}">Пакетный
                            тур</a>
                        <a class="dropdown-item"
                           href="{{route('applications.packages.findPackage', $application->id)}}">Пакет из
                            каталога</a>
                        <a class="dropdown-item"
                           href="{{route('applications.services.findService', $application->id)}}">Услугу из
                            каталога</a>
                        {{--                            <a class="dropdown-item" href="{{route('applications.services.create')}}">Индивидуальную--}}
                        {{--                                услугу</a>--}}
                    </div>

                </div>
            </div>
            <div class="row p-20 pb-0">
                <table class="table" id="orders-table">
                    <thead>
                    <tr class="border-bottom">
                        {{--                        <th class="font-weight-bold">#</th>--}}
                        <th class="font-weight-bold">Даты</th>
                        <th class="font-weight-bold">Описание</th>
                        <th class="font-weight-bold">Партнер</th>
{{--                        <th class="font-weight-bold">Статус</th>--}}
                        <th class="font-weight-bold">Курс и расчет</th>
{{--                        <th class="font-weight-bold">Цена(UZS)</th>--}}
                        <th class="font-weight-bold"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($application->order)
                        @forelse($application->order?->items as $product)
                            <tr>
                                {{--                                                                <td>{{$application->order?->id}}</td>--}}
                                <td>
                                    <strong>{{ date('d.m.Y', strtotime($product->date_from)) . (!empty($product->date_to) ? ' - ' . date('d.m.Y', strtotime($product->date_to)) : '') }}</strong>
                                    <br>
                                    @if(!empty($product->nights))
                                        <small>({{$product->nights}} ночей)</small>
                                    @endif
                                    @if(!empty($product->departure_time) && !empty($product->arrival_time))
                                        <small>({{$product->departure_time}} - {{$product->arrival_time}})</small>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{$product->item_name}}</strong>
                                    @if($product->region || $product->country)
                                        <br>
                                    @endif
                                    @if(!empty($product->product->name) && !in_array($product->product->name, ['visa', 'airticket', 'transfer', 'insurance']))
                                        {{$product->country?->name  . ', '. $product->region?->name . ', '. $product->hotel?->name. ', '
                                                                               . $product->beadType?->name . ', '. $product->mealType?->name}}
                                        <br>
                                    @else
                                        {{$product->country?->name  . ', '. $product->region?->name }}
                                        <br>
                                    @endif

                                    @if(!empty($product->item_summary))
                                        {{$product->item_summary}}
                                        <br>
                                    @endif
                                    Кол-во туристов: {{$product->adults_count ?? 0}}
                                    / {{$product->children_count ?? 0}} / {{$product->infants_count ?? 0}}
                                    <br>
                                    @if($product->schema_id && in_array($product->product->name, ['tourservice', 'tourpackage']))
                                        <a href="{{route('applications.schemas.create', [$application->id, $product->schema_id])}}"
                                           class="button button-blue ml-auto float-left height-35 mb-lg-0 mb-md-0"
                                        >
                                            Вибрат место
                                        </a>
                                    @endif

                                </td>

                                <td>{{$product?->partner?->name}}</td>

                                <td>{{currency_format(($product->unit_price), $product->currency_id)}}</td>

                                <td>
                                    @if($application->order)
                                        <a href="{{route('applications.packages.edit', [$application->id, $product->id])}}"
                                           class="btn btn-outline-warning height-35"><i class=" fas fa-pencil-alt"></i>
                                        </a>
                                        <button id="deleteBtn" data-id="{{$product->id}}"
                                                class="btn btn-outline-danger height-35"><i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </td>

                            </tr>

                        @empty
                        @endforelse
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="">
                                Общий: {{currency_format($application->order->total, company()->currency_id)}}</td>
                        </tr>
                    @endif

                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>
<div class="row mt-4">
    <div class="col-sm-6">
        <div class="bg-white rounded">
            <div class="row p-20">
                <h4 class="mb-0 pl-20 f-21 font-weight-bold">

                    {{__('app.clientPayments')}}</h4>
                <x-forms.link-primary :link="route('applications.payments.create', [$application->id, 'client'])"
                                      class="ml-auto mr-4 float-left mb-2 openRightModal mb-lg-0 mb-md-0"
                                      icon="plus">
                    @lang('app.add')
                </x-forms.link-primary>
            </div>
            <div class="row p-20 ml-2" id="payment_client">
                <div class="block-highlight margin-bottom">
                    <table border="0" width="100%">
                        <tbody>
                        <tr>
                            <td class="form-group">
                                <nobr><b>Dead-line по оплате:</b></nobr>
                            </td>
                            <td class="form-group" width="90%" style="vertical-align: bottom; padding-left: 20px;">

                                <a href="{{route('payment-deadline.create', ['client', $application->id])}}"
                                   class="openRightModal"
                                   style="color: #b94a48; border-bottom: 1px dashed #b94a48;"
                                >
                                    {{$clientDeadline?->deadline ? date('d.m.Y', strtotime($clientDeadline?->deadline)) : "установить дату"}}
                                </a>

                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <table class="table">
                    <thead>
                    <tr class="border-bottom">
                        <th class="font-weight-bold">Печать</th>
                        <th class="font-weight-bold">Касса</th>
                        <th class="font-weight-bold">Тип и дата</th>
                        <th class="font-weight-bold">Курс</th>
                        <th class="font-weight-bold">Сумма</th>
                        <th class="font-weight-bold"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($application?->order?->clientPayments)
                        @foreach($application?->order?->clientPayments as $payment)
                            <div class="text-success">
                                <tr class="{{$payment->type == 'debit' ? 'debit-row' : 'credit-row'}}">
                                    <td class="{{$payment->type == 'debit' ? 'debit-row' : 'credit-row'}}"><a
                                            class="btn btn-outline-primary"
                                            href="{{route('custom.invoice.show', $payment->id)}}"><i
                                                class="fa fa-print" aria-hidden="true"></i></a></td>
                                    <td class="{{$payment->type == 'debit' ? 'debit-row' : 'credit-row'}}">{{$payment->bankAccount?->account_name}}</td>
                                    <td class="{{$payment->type == 'debit' ? 'debit-row' : 'credit-row'}}">
                                        <div>{{$payment->type == "credit" ? "Приход" : "Расход"}}
                                            ({{$payment->payment_type}})
                                        </div>
                                        <div>8</div>
                                        {{$payment->paid_on?->format('d.m.Y H:i')}}
                                    </td>
                                    <td class="{{$payment->type == 'debit' ? 'debit-row' : 'credit-row'}}">{{$payment->exchange_rate}}</td>
                                    <td class="{{$payment->type == 'debit' ? 'debit-row' : 'credit-row'}}">{{currency_format($payment->amount, $payment->currency_id)}}</td>
                                </tr>
                            </div>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if((auth()->user() && auth()->user()->hasRole('finance')) || auth()->user()->hasRole('admin'))
    <div class="col-sm-6">
        <div class="bg-white rounded">
            <div class="row p-20">
                <h4 class="mb-0 pl-20 f-21 font-weight-bold">
                    {{__('app.partnerPayments')}}
                </h4>
                <x-forms.link-primary :link="route('applications.payments.create', [$application->id, 'partner'])"
                                      class="ml-auto mr-4 float-left mb-2 openRightModal mb-lg-0 mb-md-0"
                                      icon="plus">
                    @lang('app.add')
                </x-forms.link-primary>
            </div>
            <div class="row p-20 ml-2" id="payment_client">
                <div class="block-highlight margin-bottom">
                    <table border="0" width="100%">
                        <tbody>
                        <tr>
                            <td class="form-group">
                                <nobr><b>Dead-line по оплате:</b></nobr>
                            </td>
                            <td class="form-group" width="90%" style="vertical-align: bottom; padding-left: 20px;">
                                <a href="{{route('payment-deadline.create', ['partner', $application->id])}}"
                                   class="openRightModal"
                                   style="color: #b94a48; border-bottom: 1px dashed #b94a48;"
                                >
                                    {{$partnerDeadline?->deadline ? date('d.m.Y', strtotime($partnerDeadline?->deadline)) : "установить дату"}}
                                </a>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <table class="table">
                    <thead>
                    <tr class="border-bottom">
                        {{--                        <th class="font-weight-bold">Печать</th>--}}
                        <th class="font-weight-bold">Касса</th>
                        <th class="font-weight-bold">Тип и дата</th>
                        <th class="font-weight-bold">Курс</th>
                        <th class="font-weight-bold">Сумма</th>
                        <th class="font-weight-bold"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($application?->order?->partnerPayments)
                        @foreach($application?->order?->partnerPayments as $payment)
                            <tr>
                                {{--                                <td class="{{$payment->type == 'debit' ? 'debit-row' : 'credit-row'}}"><a--}}
                                {{--                                            class="btn btn-outline-primary"--}}
                                {{--                                            href="{{route('custom.invoice.show', $payment->id)}}"><i--}}
                                {{--                                                class="fa fa-print" aria-hidden="true"></i></a></td>--}}
                                <td class="{{$payment->type == 'debit' ? 'debit-row' : 'credit-row'}}">{{$payment->bankAccount?->account_name}}</td>
                                <td class="{{$payment->type == 'debit' ? 'debit-row' : 'credit-row'}}">
                                    <div>{{$payment->type == "credit" ? "Приход" : "Расход"}}
                                        ({{$payment->payment_type}})
                                    </div>
                                    <div>8</div>
                                    {{$payment->paid_on->format('d.m.Y H:i')}}
                                </td>
                                <td class="{{$payment->type == 'debit' ? 'debit-row' : 'credit-row'}}">{{$payment->exchange_rate}}</td>
                                <td class="{{$payment->type == 'debit' ? 'debit-row' : 'credit-row'}}">{{currency_format($payment->amount, $payment->currency_id)}}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    @endif

</div>

<script src="{{ asset('vendor/jquery/dropzone.min.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const button = document.getElementById("quick-reservation-button");

        button.addEventListener("click", function () {
            // if (!button.classList.contains("qq-installed")) {
            //     window.open('https://qui-quo.ru/tutorial/quick-reservation', '_blank');
            // }
        });
    });
    $(document).ready(function() {
        // Counter for dynamic order number inputs
        let orderNumberCount = 0;

        // Add order number button click handler
        $('#addOrderNumberBtn').click(function() {
            $('#orderNumberModal').modal('show');
        });

        // Save order number button click handler
        $('#saveOrderNumberBtn').click(function() {
            let orderNumber = $('#orderNumber').val();
            let partnerId = $('#partner').val();
            let applicationId = '{{ $application->id }}'; // Pass the application ID from the backend
            let partnerName = $('#partner option:selected').text(); // Get the selected partner's name

            // Send AJAX request to store order number
            $.ajax({
                url: '{{ route("order-number.store") }}',
                method: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    order_number: orderNumber,
                    partner_id: partnerId,
                    application_id: applicationId,
                    partner_name: partnerName,
                },
                success: function(response) {
                    // Append order number and partner to the container
                    $('#orderNumbersContainer').append(
                        '<div class="form-row row mb-2">' +
                        '   <div class="col">' +
                        '       <input type="text" class="form-control" name="order_numbers[' + orderNumberCount + ']" value="' + orderNumber + '">' +
                        '   </div>' +
                        '   <div class="col">' +
                        '       <input type="text" class="form-control" name="partners[' + orderNumberCount + ']" value="' + partnerName + '" readonly>' +
                        '   </div>' +
                        '   <div class="col-auto">' +
                        '       <button class="btn btn-sm btn-danger deleteOrderNumberBtn" data-id="' + response.orderNumber.id + '"><i class="fas fa-trash"></i></button>' +
                        '       <input type="hidden" class="order-number-id" name="order_number_id" value="' + response.orderNumber.id + '">' +
                        '       <input type="hidden" name="partners[' + orderNumberCount + ']" value="' + partnerId + '">' +
                        '   </div>' +
                        '</div>'
                    );

                    // Increment the counter
                    orderNumberCount++;
                    // Clear the input fields
                    $('#orderNumber').val('');
                    $('#partner').val('');
                    // Close the modal
                    $('#orderNumberModal').modal('hide');
                },
                error: function(xhr) {
                    // Handle error response, e.g., display error message
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var validationErrors = $('#validationErrors');
                        validationErrors.empty(); // Clear previous errors
                        $.each(errors, function(key, value) {
                            validationErrors.append('<p>' + value + '</p>');
                        });
                    }
                }
            });
        });

        // Event listener for order number input change
        $('#orderNumbersContainer').on('change', 'input[name^="order_numbers"]', function() {
            let orderNumber = $(this).val();
            let orderNumberId = $(this).closest('.row').find('.order-number-id').val();
            console.log(orderNumberId)
            // Send AJAX request to update order number in database
            $.ajax({
                url: '{{ route("order-number.update") }}',
                method: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    order_number_id: orderNumberId,
                    order_number: orderNumber,
                },
                success: function(response) {
                    // Handle success response if needed
                },
                error: function(xhr) {
                    // Handle error response, e.g., display error message
                    alert('Error: ' + xhr.responseText);
                }
            });
        });

    });



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

        $('#save-application-form').click(function () {
            const url = "{{ route('applications.update', $application->id) }}";

            $.easyAjax({
                url: url,
                container: '#save-application-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                file: true,
                buttonSelector: "#save-application-form",
                data: $('#save-application-data-form').serialize(),
                success: function (response) {
                    if (response.status === 'success') {
                        window.location.href = '{{route('applications.edit', $application->id)}}';
                    }
                }
            });
        });

        $(document).ready(function() {
            $('#cancel-save-application').click(function(e) {
                e.preventDefault(); // Prevent default button action

                // Confirm with the user
                if (confirm("@lang('Are you sure you want to cancel and delete this application?')")) {
                    // AJAX request to server to delete the application
                    $.ajax({
                        url: "{{ route('applications.destroy', $application->id ?? 0) }}", // Adjust the route as necessary
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: "DELETE"
                        },
                        success: function(response) {
                            // Redirect to the applications index route on success
                            window.location.href = "{{ route('applications.index') }}";
                        },
                        error: function(xhr, status, error) {
                            // Handle error scenario
                            alert("@lang('An error occurred while deleting the application.')");
                        }
                    });
                }
            });
        });

        $('body').on('click', '#deleteBtn', function () {
            var id = $(this).data('id');
            console.log(id);
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
        $('#orders-table').on('change', '.order-status', function () {
            var id = $(this).data('order-id');
            var status = $(this).val();

            changeOrderStatus(id, status);
        });

        function changeOrderStatus(orderID, status) {
            var url = "{{ route('order-item.change_status') }}";
            var token = "{{ csrf_token() }}";
            var id = orderID;
            var statusMessage = '';

            if (id != "" && status != "") {

                switch (status) {
                    case 'pending':
                        statusMessage = "@lang('messages.orderStatus.pending')";
                        break;
                    case 'on-hold':
                        statusMessage = "@lang('messages.orderStatus.onHold')";
                        break;
                    case 'failed':
                        statusMessage = "@lang('messages.orderStatus.failed')";
                        break;
                    case 'processing':
                        statusMessage = "@lang('messages.orderStatus.processing')";
                        break;
                    case 'completed':
                        statusMessage = "@lang('messages.orderStatus.completed')";
                        break;
                    case 'canceled':
                        statusMessage = "@lang('messages.orderStatus.canceled')";
                        break;
                    case 'refunded':
                        statusMessage = "@lang('messages.orderStatus.refunded')";
                        break;

                    default:
                        statusMessage = "@lang('messages.orderStatus.pending')";
                        break;
                }

                Swal.fire({
                    title: "@lang('messages.confirmation.orderStatusChange')",
                    text: statusMessage,
                    icon: 'warning',
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: "@lang('app.yes')",
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
                        $.easyAjax({
                            url: url,
                            type: "POST",
                            container: '.content-wrapper',
                            blockUI: true,
                            data: {
                                '_token': token,
                                orderId: id,
                                status: status,
                            },
                            success: function (data) {
                                // showTable();
                            }
                        });
                    } else {
                        showTable();
                    }
                });

            }
        }

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

    // Function to fetch and display order numbers
    function fetchAndDisplayOrderNumbers() {
        // Fetch order numbers using AJAX
        $.ajax({
            url: "{{ route('order-numbers.fetch', $application->id) }}", // Adjust the route according to your application
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                // Check if there are any order numbers
                if (response.orderNumbers.length > 0) {
                    // Clear existing content in the container
                    $('#orderNumbersContainer').empty();
                    // Iterate through each order number and append it to the container
                    response.orderNumbers.forEach(function(orderNumber) {
                        $('#orderNumbersContainer').append(
                            '<div class="form-row row mb-2">' + // Added Bootstrap class "form-row" for spacing
                            '<div class="col">' + // Use Bootstrap grid system for layout
                            '<input type="text" class="form-control" name="order_numbers[]" value="' + orderNumber.order_number + '" >' + // Added Bootstrap class "form-control" for styling
                            '</div>' +
                            '<div class="col">' + // Use Bootstrap grid system for layout
                            '<input type="hidden" class="order-number-id" name="order_number_id" value="' + orderNumber.id + '">' +
                            '<input type="text" class="form-control" name="partners[]" value="' + orderNumber.partner_name + '" readonly>' + // Added Bootstrap class "form-control" for styling
                            '</div>' +
                            '<div class="col-auto">' + // Use Bootstrap grid system for layout and added "col-auto" for automatic width
                            '<button class="btn btn-sm btn-danger deleteOrderNumberBtn" data-id="' + orderNumber.id + '"><i class="fas fa-trash"></i></button>' + // Added Bootstrap class "btn" and "btn-danger" for styling, and Font Awesome icon for trash
                            '</div>' +
                            '</div>'
                        );
                    });


                }
            },
            error: function(xhr) {
                // Handle error response
                console.error('Error fetching order numbers:', xhr.responseText);
            }
        });
    }

    // Call the function to fetch and display order numbers when the page is ready
    $(document).ready(function() {
        fetchAndDisplayOrderNumbers();
    });

    // Add order number button click handler
    $('#addOrderNumberBtn').click(function() {
        $('#orderNumberModal').modal('show');
    });

    $(document).on('click', '.deleteOrderNumberBtn', function(event) {
        // Prevent default action of the event (e.g., following a link)
        event.preventDefault();

        let $button = $(this);

        let orderNumberId = $button.data('id'); // Get the order_number_id from the data attribute

        // Send AJAX request to delete order number
        $.ajax({
            url: '{{ route("order-number.delete") }}',
            method: 'POST',
            data: {
                _token: '{{csrf_token()}}',
                order_number_id: orderNumberId,
            },
            success: function(response) {
                // Remove the corresponding order number container from the DOM

                $button.closest('div.form-row.row.mb-2').remove();
            },
            error: function(xhr) {
                // Handle error response, e.g., display error message
                alert('Error: ' + xhr.responseText);
            }
        });
    });



</script>
