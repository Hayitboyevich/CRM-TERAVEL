<head>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        <?php $counterId = company()->counter_id; ?>
        (function (m, e, t, r, i, k, a) {
            m[i] = m[i] || function () {
                (m[i].a = m[i].a || []).push(arguments)
            };
            m[i].l = 1 * new Date();
            for (var j = 0; j < document.scripts.length; j++) {
                if (document.scripts[j].src === r) {
                    return;
                }
            }
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a);
        })(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(<?php echo $counterId; ?>, "init", {
            // clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true,
            // webvisor: true,
            ecommerce: "dataLayer"
        });
    </script>
    <noscript>
        <div><img src="https://mc.yandex.ru/watch/<?php echo $counterId; ?>" style="position:absolute; left:-9999px;"
                  alt=""/></div>
    </noscript>
    <!-- /Yandex.Metrika counter -->
</head>
<!-- ROW START -->
<div class="row">
    <!--  USER CARDS START -->
    <div class="col-xl-12 col-lg-12 col-md-12 mb-4 mb-xl-0 mb-lg-4 mb-md-0">

        <x-cards.data :title="__('modules.client.profileInfo')">
            <div class="row">

                <div class="col">
                    <x-slot name="action">
                        <div class="dropdown">
                            <button class="btn f-14 px-0 py-0 text-dark-grey dropdown-toggle" type="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-ellipsis-h"></i>
                            </button>

                            <div class="dropdown-menu dropdown-menu-right border-grey rounded b-shadow-4 p-0"
                                 aria-labelledby="dropdownMenuLink" tabindex="0">
                                <a class="dropdown-item openRightModal"
                                   href="{{ route('leads.edit', $lead->id) }}">@lang('app.edit')</a>
                                @if (
                                    $deleteLeadPermission == 'all'
                                    || ($deleteLeadPermission == 'added' && user()->id == $lead->added_by)
                                    || ($deleteLeadPermission == 'owned' && !is_null($lead->agent_id) && user()->id == $lead->leadAgent->user->id)
                                    || ($deleteLeadPermission == 'both' && ((!is_null($lead->agent_id) && user()->id == $lead->leadAgent->user->id)
                                            || user()->id == $lead->added_by))
                                )
                                    <a class="dropdown-item delete-table-row" href="javascript:"
                                       data-id="{{ $lead->id }}">
                                        @lang('app.delete')
                                    </a>
                                @endif
                                @if ($lead->client_id == null || $lead->client_id == '')
                                    <a class="dropdown-item" href="{{route('clients.create') . '?lead=' . $lead->id }}">
                                        @lang('modules.lead.changeToClient')
                                    </a>
                                @endif
                            </div>
                        </div>
                    </x-slot>
                    <x-cards.data-row :label="__('modules.lead.clientName')" :value="$lead->client_name ?? '--'"/>
                    <x-cards.data-row :label="__('modules.lead.mobile')" :value="$lead->mobile ?? '--'"/>
                    <div class="col-12 px-0 pb-3 d-flex">
                        <p class="mb-0 text-lightest f-14 w-30 d-inline-block text-capitalize">
                            @lang('modules.lead.leadAgent')</p>
                        <p class="mb-0 text-dark-grey f-14">
                            @if (!is_null($lead->leadAgent))
                                <x-employee :user="$lead->leadAgent->user"/>
                            @else
                                --
                            @endif
                        </p>
                    </div>
                    <x-cards.data-row :label="__('modules.lead.source')"
                                      :value="$lead->leadSource ? mb_ucwords($lead->leadSource->type) : '--'"/>
                    @if ($lead->leadStatus)
                        <div class="col-12 px-0 pb-3 d-flex">
                            <p class="mb-0 text-lightest f-14 w-30 d-inline-block text-capitalize">@lang('app.status')</p>
                            <p class="mb-0 text-dark-grey f-14">
                                <x-status :value="ucfirst($lead->leadStatus->type)"
                                          :style="'color:'.$lead->leadStatus->label_color"/>
                            </p>

                        </div>
                    @endif
                    <x-cards.data-row label="Стоимость заказа"
                                      :value="($lead->value) ? currency_format($lead->value, $lead->currency_id) : '--'"/>
                    <x-cards.data-row :label="__('app.note')"
                                      :value="strip_tags($lead->note)"/>
                </div>
                <div class="col">
                    <!-- Scroll Note -->
                    <div class="scroll-note">
                        <div class="page-content page-container" id="page-content">
                            <div class="row container d-flex justify-content-center">
                                <div class="col">
                                    <div class="card card-bordered">
                                        <div class="ps-container ps-theme-default ps-active-y" id="chat-content"
                                             style="overflow-y: scroll !important; height:200px !important;">
                                            @foreach($histories as $history)
                                                <div class="media media-chat">
                                                    <img class="avatar"
                                                         src="https://img.icons8.com/color/36/000000/administrator-male.png"
                                                         alt="...">
                                                    <div class="media-body">
                                                        {{$history->info}}
                                                        <p class="meta">
                                                            <time
                                                                datetime="{{date('Y', strtotime($history->created_at))}}">{{date('d.m.Y H:m', strtotime($history->created_at))}}</time>
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforeach


                                            <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;">
                                                <div class="ps-scrollbar-x" tabindex="0"
                                                     style="left: 0px; width: 0px;"></div>
                                            </div>
                                            <div class="ps-scrollbar-y-rail"
                                                 style="top: 0px; height: 0px; right: 2px;">
                                                <div class="ps-scrollbar-y" tabindex="0"
                                                     style="top: 0px; height: 2px;"></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @if($lead->client_id)
                @if(!empty($application))
                    <a class="btn btn-primary m-2"
                       href="{{route('applications.indexLeadApplication', $lead->client_id)}}">@lang('app.viewOrders')</a>
                @endif
                <button class="btn btn-primary m-2 order-create" id="order-create" data-lead-id="{{$lead->id}}"
                        data-client-id="{{$lead->client_id}}"
                >@lang('app.order')</button>
            @else
                <a class="btn btn-primary m-2 order-create"
                   href="{{route('applications.create')}}">@lang('app.order')</a>
            @endif


            @if($interests)
                <h5>Интересы клиентов</h5>
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{__('app.country')}}</th>
                        <th>{{__('app.adults')}}</th>
                        <th>{{__('app.children')}}</th>
                        <th>{{__('app.baby')}}</th>
                        <th>{{__('app.desired_date_from')}}</th>
                        <th>{{__('app.desired_date_to')}}</th>
                        <th>{{__('app.count_days_from')}}</th>
                        <th>{{__('app.count_days_to')}}</th>
                        <th>{{__('app.accommodation_type')}}</th>
                        <th>{{__('app.mealPlan')}}</th>
                        <th>{{__('app.price')}}</th>
                        <th>{{__('app.currency')}}</th>

                    </tr>
                    </thead>
                    <tbody>
                    {{--                @if($integration)--}}
                    {{--                    <tr>--}}
                    {{--                        <td>{{ $integration->from_city_name }}</td>--}}
                    {{--                        <td>{{ $integration->to_city_name }} {{ $integration->to_country_name ? "($integration->to_country_name )" : "" }}</td>--}}
                    {{--                        <td>{{ $integration->category_name }}</td>--}}
                    {{--                        <td>{{ $integration->hotel_name }}</td>--}}
                    {{--                        <td>{{ $integration->nights_count_from }} - {{ $integration->nights_count_to }}</td>--}}
                    {{--                        <td>{{ $integration->adults_count }}</td>--}}

                    {{--                        <td>{{ $integration->children_count }}</td>--}}
                    {{--                        <td>--}}
                    {{--                            @if($lead->order_id)--}}
                    {{--                                --}}{{--                                <x-forms.link-secondary :link="route('payments.partner.create', $lead->order_id)"--}}
                    {{--                                --}}{{--                                                        class="mr-3 float-left openRightModal"--}}
                    {{--                                --}}{{--                                                        icon="plus">--}}
                    {{--                                --}}{{--                                </x-forms.link-secondary>--}}
                    {{--                                <x-forms.link-primary :link="route('payments.custom.create', $lead->order_id)"--}}
                    {{--                                                      class="mr-3 float-left openRightModal"--}}
                    {{--                                                      icon="plus">--}}
                    {{--                                </x-forms.link-primary>--}}
                    {{--                                --}}{{--                                <a href="{{ route('custom.invoice.show', $lead->order_id) }}"--}}
                    {{--                                --}}{{--                                   class="btn-primary rounded f-14 p-2 mr-3 float-left"><i--}}
                    {{--                                --}}{{--                                            class="bi bi-eye"></i></a>--}}
                    {{--                            @else--}}
                    {{--                                --}}{{--                                <a href="{{ route('orders.custom-create', $lead->id) }}"--}}
                    {{--                                --}}{{--                                   class="btn-primary rounded f-14 p-2 mr-3 float-left">--}}
                    {{--                                --}}{{--                                    @lang('app.order')--}}
                    {{--                                --}}{{--                                </a>--}}
                    {{--                            @endif--}}
                    {{--                            <a href="{{route('integrations.edit', $integration->id)}}"--}}
                    {{--                               class="btn btn-outline-warning height-35"><i class=" fas fa-pencil-alt"></i></a>--}}
                    {{--                        </td>--}}
                    {{--                        <td>--}}
                    {{--                            <div class="col">--}}
                    {{--                                <a href=' {{ 'http://online.kompastour.uz/search_tour?TOWNFROMINC=' . $integration->fromCity?->kompastour_id .--}}
                    {{--                    '&STATEINC=' . $integration->state?->kompastour_id . '&TOURINC=' . $integration->tour_id .--}}
                    {{--                    '&PROGRAMGROUPINC=' . $integration->program_type_id . '&CHECKIN_BEG=' . date('Ymd', strtotime($integration->checkin_begin)) .--}}
                    {{--                    '&NIGHTS_FROM=' . $integration->nights_count_from . '&CHECKIN_END=' . date('Ymd', strtotime($integration->checkin_end)) .--}}
                    {{--                    '&NIGHTS_TILL='. $integration->nights_count_to .'&CHILD='.$integration->children_count.'&ADULT='.$integration->adults_count.'&CURRENCY=2&TOWNS=' . $integration->to_city_id . '&STARS=' . $integration->category_id .--}}
                    {{--                    '&HOTELS=' . $integration->hotel_id }} $link . ' class="" target="_blank">KOMPASTOUR</a>--}}
                    {{--                                <a href=' {{ 'https://tours.easybooking.uz/search_tour?TOWNFROMINC=' . $integration->fromCity?->easybooking_id  .--}}
                    {{--                    '&STATEINC=' . $integration->state?->easybooking_id . '&TOURINC=' . $integration->tour_id .--}}
                    {{--                    '&PROGRAMGROUPINC=' . $integration->program_type_id . '&CHECKIN_BEG=' . date('Ymd', strtotime($integration->checkin_begin)) .--}}
                    {{--                    '&NIGHTS_FROM=' . $integration->nights_count_from . '&CHECKIN_END=' . date('Ymd', strtotime($integration->checkin_end)) .--}}
                    {{--                    '&NIGHTS_TILL='. $integration->nights_count_to .'&CHILD='.$integration->children_count.'&ADULT='.$integration->adults_count.'&CURRENCY=2&TOWNS=' . $integration->to_city_id . '&STARS=' . $integration->category_id .--}}
                    {{--                    '&HOTELS=' . $integration->hotel_id }} $link . ' class="" target="_blank">EASYBOOKING</a>--}}
                    {{--                                <a href=' {{ 'http://online.uz-prestige.com/search_tour?TOWNFROMINC=' . $integration->fromCity?->prestige_id  .--}}
                    {{--                    '&STATEINC=' . $integration->state?->prestige_id . '&TOURINC=' . $integration->tour_id .--}}
                    {{--                    '&PROGRAMGROUPINC=' . $integration->program_type_id . '&CHECKIN_BEG=' . date('Ymd', strtotime($integration->checkin_begin)) .--}}
                    {{--                    '&NIGHTS_FROM=' . $integration->nights_count_from . '&CHECKIN_END=' . date('Ymd', strtotime($integration->checkin_end)) .--}}
                    {{--                    '&NIGHTS_TILL='. $integration->nights_count_to .'&CHILD='.$integration->children_count.'&ADULT='.$integration->adults_count.'&CURRENCY=2&TOWNS=' . $integration->to_city_id . '&STARS=' . $integration->category_id .--}}
                    {{--                    '&HOTELS=' . $integration->hotel_id }} $link . ' class="" target="_blank">PRESTIGE</a>--}}
                    {{--                            </div>--}}
                    {{--                        </td>--}}

                    {{--                    </tr>--}}
                    {{--                @else--}}
                    {{--                    <tr>--}}
                    {{--                        <td colspan="4">No data found</td>--}}
                    {{--                    </tr>--}}
                    {{--                @endif--}}

                    <!-- Add more rows as needed -->
                    <tr>
                        <td>{{$interests?->country?->name}}</td>
                        <td>{{$interests->adults}}</td>
                        <td>{{$interests->children}}</td>
                        <td>{{$interests->baby}}</td>
                        <td>{{$interests->desired_date_from}}</td>
                        <td>{{$interests->desired_date_to}}</td>
                        <td>{{$interests->count_days_from}}</td>
                        <td>{{$interests->count_days_to}}</td>
                        <td>{{$interests->accommodation_type}}</td>
                        <td>{{$interests->meal_plan}}</td>
                        <td>{{$interests->price}}</td>
                        <td>{{$interests->currency?->currency_code}}</td>
                    </tr>
                    </tbody>
                </table>
            @endif

            <table class="table">
                <thead>
                <tr>
                    <th>Номер заказа</th>
                    <th>Имя</th>
                    <th>Цена</th>
                    <th>Дата</th>
                    <th>Статус заказа</th>
                </tr>
                </thead>
                <tbody>

                <h5>История заказов</h5>
                @if($lead?->order)
                    {{--                    @forelse($lead?->client?->orders as $order)--}}
                    <tr>
                        <td>{{ $lead?->order->order_number }}</td>
                        <td>{{ $lead?->order->name }}</td>
                        <td>{{ currency_format($lead?->order->total, $lead?->order->currency_id) }}</td>
                        <td>{{ date('d, M, Y',strtotime($lead?->order->order_date)) }}</td>
                        <td>{{ $lead?->order->status }}</td>
                        <td><a href="{{route('orders.show', $lead?->order->id)}}"><i class="bi bi-eye"></i></a></td>
                        <td><a href="{{route('custom.orders.edit', $lead?->order->id)}}"><i
                                    class=" fas fa-pencil-alt"></i></a>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="4">No data found</td>
                    </tr>
                    {{--                    @endforelse--}}
                @endif

                </tbody>
            </table>
            <table class="table">
                <thead>
                <tr>
                    <th>Номер заказа</th>
                    <th>Цена</th>
                    <th>Дата</th>
                    <th>Статус заказа</th>
                    <th></th>

                </tr>
                </thead>
                <tbody>
                <h5>История платежей</h5>
                @if($lead?->order?->payments)
                    @forelse($lead?->order?->payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>{{ currency_format($payment->amount, $payment->currency_id) }}</td>
                            <td>{{$payment->paid_date}}</td>
                            <td>{{$payment->status}}</td>
                            <td><a href="{{route('custom.invoice.show', $payment->id)}}">Квитанция</a></td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No data found</td>
                        </tr>
                    @endforelse
                @endif

                </tbody>
            </table>

        </x-cards.data>
    </div>
    <!--  USER CARDS END -->
</div>
<!-- ROW END -->
<script>
    $(document).ready(function () {

        $('#order-create').click(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{route('applications.addViaLead', $lead->id)}}",
                method: "GET",
                success: function (response) {
                    if (response.status == "success") {

                        @if($counterId != null)
                        let params = {
                            'user_id': {{ $lead->id }},
                            'application_id': response.application.id  // Make sure application.id is not undefined
                        };
                        ym({{ company()->counter_id }}, 'reachGoal', 'order_created', params);
                        @endif

                        if (response && response.redirectUrl) {
                            window.location.href = response.redirectUrl;
                        }
                    }
                }
            });
        });

    });

</script>
