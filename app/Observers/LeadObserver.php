<?php

namespace App\Observers;

use App\Events\LeadEvent;
use App\Models\Lead;
use App\Models\LeadLog;
use App\Models\LeadStatus;
use App\Models\User;
use App\Notifications\LeadAgentAssigned;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Modules\ActivityHistory\Entities\ActivityHistory;

class LeadObserver
{

    public function saving(Lead $lead)
    {
        if (!isRunningInConsoleOrSeeding()) {
//            $userID = (!is_null(user())) ? user()->id : null;
//            $lead->last_updated_by = $userID;
        }
    }

    public function creating(Lead $lead)
    {
        $lead->hash = md5(microtime());

        if (!isRunningInConsoleOrSeeding()) {
//            $userID = (!is_null(user())) ? user()->id : null;
//            $lead->added_by = $userID;
        }
        if (company()) {
            $lead->company_id = company()->id;
        }
    }

    public function updating(Lead $lead)
    {
        $diff = Carbon::now()->diffInSeconds($lead->updated_at);
        $deadline = $lead->leadStatus->time * 60;
        $late = abs($diff - $deadline);
        $leadLog = LeadLog::query()->where(['lead_id' => $lead->id])->first();
        $late_in_sec = $leadLog?->late_in_sec ?? 0;

        if ($late > 0 && $late > $late_in_sec) {
            LeadLog::query()->updateOrCreate([
                'lead_id' => $lead->id,
            ], [
                'lead_id' => $lead->id,
                'late_in_sec' => $late
            ]);
        }
        if ($lead->isDirty('landing_time') && $lead->landing_time) {
//            SmsMailing::query()->create([
//                'user_id' => json_encode([$lead->client_id]),
//                'message' => (SmsSetting::query()->where('name', '=', 'after_land')->first()?->body ?? "Welcome to paradise! Enjoy traveling with us. Bon voyage! 🌴🌞"),
//                'delivery_date' => date('Y-m-d', strtotime($lead->landing_time))
//            ]);
        }

    }

    public function updated(Lead $lead)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if ($lead->isDirty('agent_id')) {
                ActivityHistory::query()->create([
                    'module_id' => $lead->id,
                    'info' => $lead->client?->name . ' lead прикреплен к агенту ' . $lead->leadAgent?->user?->name . '',
                    'module_name' => 'leads',
                ]);
                event(new LeadEvent($lead, $lead->leadAgent, 'LeadAgentAssigned'));
            }
            if ($lead->isDirty('status_id')) {
                ActivityHistory::query()->create([
                    'module_id' => $lead->id,
                    'info' => $lead->client?->name . ' lead ' . $lead->leadStatus->type . ' statusga o`zgartirildi ',
                    'module_name' => 'leads',
                ]);
                event(new LeadEvent($lead, $lead->leadAgent, 'LeadAgentAssigned'));
            }
            if ($lead->isDirty('order_id')) {
                ActivityHistory::query()->create([
                    'module_id' => $lead->id,
                    'info' => $lead->client->name . ' ' . ActivityHistory::MESSAGE_CREATED_ORDER,
                    'module_name' => 'leads',
                ]);
            }

            if ($lead->getOriginal('callback_at')== null && $lead->isDirty('callback_at')){
                $lead->status_id = LeadStatus::query()->where('company_id', company()->id)->where('type', LeadStatus::NEED_TO_CONTACT_STATUS)->first()->id;
                $lead->save();
            }


        }
    }

    public function created(Lead $lead)
    {
        $user_name = Auth::user() ? Auth::user()->name : "TELEGRAM BOT";

//        // Save lead note
        if ($lead->note) {
            ActivityHistory::query()->create([
                'module_id' => $lead->id,
                'info' => $user_name . ' ' . ActivityHistory::MESSAGE_CREATED_NOTE,
                'module_name' => 'leads',
            ]);

            $lead->note()->create([
                'lead_id' => $lead->id,
                'title' => 'Note',
                'details' => $lead->note
            ]);


        }
        if (!isRunningInConsoleOrSeeding()) {
            ActivityHistory::query()->create([
                'module_id' => $lead->id,
                'info' => $user_name . ' ' . ActivityHistory::MESSAGE_CREATED_LEAD,
                'module_name' => 'leads',
            ]);

            if ($lead->agent_id != '') {
                event(new LeadEvent($lead, $lead->leadAgent, 'LeadAgentAssigned'));
            } else {
                Notification::send(User::allAdmins($lead->company->id), new LeadAgentAssigned($lead));
            }
        }
    }

    public function deleting(Lead $lead)
    {
        $notifyData = ['App\Notifications\LeadAgentAssigned'];
//        Notification::deleteNotification($notifyData, $lead->id);

    }

    public function deleted(Lead $lead)
    {
//        UniversalSearch::where('searchable_id', $lead->id)->where('module_type', 'lead')->delete();
    }

}
