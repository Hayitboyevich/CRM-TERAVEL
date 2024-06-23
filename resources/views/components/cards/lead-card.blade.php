@php
    use Carbon\Carbon;$moveClass = '';
@endphp
@if ($draggable == 'false')
    @php
        $moveClass = 'move-disable';
    @endphp
@endif

<div class="card rounded bg-white border-grey b-shadow-4 m-1 mb-2 {{ $moveClass }} task-card"
     data-task-id="{{ $lead->id }}"
     id="drag-task-{{ $lead->id }}">
    <div class="card-body p-2">
        <div class="d-flex justify-content-between mb-2">
            <a href="{{ route('leads.show', [$lead->id]) }}"
               class="f-12 f-w-500 text-dark mb-0 text-wrap">{{ ucfirst($lead->client_name) }}
                @if (!is_null($lead->client_id))
                    <i class="fa fa-check-circle text-success" data-toggle="tooltip"
                       data-original-title="@lang('modules.lead.convertedClient')"></i>
                @endif
                <br>
                <span>{{$lead->mobile}}</span> - <span>{{$lead->integration?->to_city_name}}</span> -
                <span>{{$lead?->leadSource?->type}}</span>
                <br>
                <span> {!! $lead->note !!}</span>
                <br>

                <span> {{ date('d.m H:i',strtotime($lead->created_at)) }}</span>
{{--                <span> - </span>--}}
{{--                <span style="color:orange;"> {{ date('d.m H:i',strtotime($lead->updated_at)) }}</span><span> - </span>--}}
{{--                <span style="color:red;"> {{ date('d.m H:i', strtotime($lead->updated_at)+($lead?->leadStatus->time ?? 0)*60) }}</span>--}}

            </a>

            {{--            @if (!is_null($lead->value))--}}
            {{--            {{ currency_format($lead->order?->payments?->each(function ($payment) use (&$totalSum) {$totalSum += $payment->amount * $payment->currency;--}}
            {{--}), $lead->currency_id) }}--}}
            <div class="d-flex">
                    <span
                            class="ml-2 f-11 text-lightest">{{ currency_format($lead->value, $lead->currency_id) }}</span>
            </div>
            {{--            @endif--}}

        </div>

        @if ($lead->company_name)
            <div class="d-flex mb-3 align-items-center">
                <i class="fa fa-building f-11 text-lightest"></i><span
                        class="ml-2 f-11 text-lightest">{{ ucfirst($lead->company_name) }}</span>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center">
            @if (!is_null($lead->agent_id))
                <div class="d-flex flex-wrap">
                    <div class="avatar-img mr-1 rounded-circle">
                        <a href="{{ route('employees.show', $lead->leadAgent->user_id) }}"
                           alt="{{ mb_ucwords($lead->leadAgent->user->name) }}" data-toggle="tooltip"
                           data-original-title="{{ __('app.leadAgent') .' : '. mb_ucwords($lead->leadAgent->user->name) }}"
                           data-placement="right"><img src="{{ $lead->leadAgent->user->image_url }}"></a>
                    </div>
                    @if((($lead->leadStatus?->type == 'Частично оплачен' && ($lead->order?->total ?? 1) != 0 && $lead->order?->total_paid / ($lead->order?->total ?? 1) < 0.4) || $lead->leadStatus?->type == 'Несортированный' || $lead->leadStatus?->type == 'В процессе' || $lead->leadStatus?->type == 'Оплаченный') && ((strtotime(now()) - strtotime($lead->updated_at)) > ($lead->leadStatus?->time ?? 0) * 60))
                    <div class="deadline-img mr-1 rounded-circle edit-deadline">
                            <a data-lead-id="{{$lead->id}}" href="javascript:"
                               alt="{{ mb_ucwords($lead->leadAgent->user->name) }}"
                               data-original-title="{{ __('app.leadAgent') .' : '. mb_ucwords($lead->leadAgent->user->name) }}"
                               data-placement="right"><img
                                        src="https://pro-plintys.ru/images/004/696/433/4696433/50x50no_crop/wall-clock-2.svg">
                            </a>
                        </div>
                    @endif
                </div>
            @endif
            @if ($lead->next_follow_up_date != null && $lead->next_follow_up_date != '')
                <div class="d-flex text-lightest">
                    <span class="f-12 ml-1"><i class="f-11 bi bi-calendar"></i> {{ Carbon::parse($lead->next_follow_up_date)->translatedFormat(company()->date_format) }}</span>
                </div>
            @endif

        </div>
    </div>
</div><!-- div end -->
