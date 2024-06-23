<?php

namespace App\Listeners;

use App\Events\TaskNoteMentionEvent;
use App\Models\User;
use App\Notifications\TaskNoteMention;
use Illuminate\Support\Facades\Notification;

class TaskNoteMentionListener
{

    /**
     * Handle the event.
     *
     * @param TaskNoteMentionEvent $event
     * @return void
     */

    public function handle(TaskNoteMentionEvent $event)
    {
        if (isset($event->mentionuser)) {

            $mention_user_id = $event->mentionuser;
            $mentionUser = User::whereIn('id', ($mention_user_id))->get();
            Notification::send($mentionUser, new TaskNoteMention($event->task, $event));


        }

    }

}
