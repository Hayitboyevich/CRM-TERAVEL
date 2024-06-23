<?php

namespace App\Listeners;

use App\Events\CallbackNotificationEvent;
use App\Notifications\CallbackNotification;
use Notification;

class CallbackNotificationListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CallbackNotificationEvent $event): void
    {
        $user = $event?->lead?->leadAgent?->user;
        if ($user) {
            $users = collect([$user]);
            Notification::send($users, new CallbackNotification($event->lead, $user));
            $event->lead->notified = 1;
            $event->lead->save();
        }
    }
}
