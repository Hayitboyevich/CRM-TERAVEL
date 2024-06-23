<?php

namespace App\Listeners;

use App\Events\TaskCommentMentionEvent;
use App\Models\User;
use App\Notifications\TaskCommentMention;
use Illuminate\Support\Facades\Notification;

class TaskCommentMentionListener
{

    /**
     * Handle the event.
     *
     * @param TaskCommentMentionEvent $event
     * @return void
     */

    public function handle(TaskCommentMentionEvent $event)
    {
        if (isset($event->mentionuser)) {

            $mention_user_id = $event->mentionuser;
            $mentionUser = User::whereIn('id', ($mention_user_id))->get();
            Notification::send($mentionUser, new TaskCommentMention($event->task, $event->comment));

        }

    }

}
