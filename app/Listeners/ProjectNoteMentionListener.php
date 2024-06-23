<?php

namespace App\Listeners;

use App\Events\ProjectNoteMentionEvent;
use App\Events\TaskCommentEvent;
use App\Models\User;
use App\Notifications\ProjectNoteMention;
use Illuminate\Support\Facades\Notification;

class ProjectNoteMentionListener
{

    /**
     * Handle the event.
     *
     * @param  ProjectNoteMentionEvent $event
     * @return void
     */

    public function handle(ProjectNoteMentionEvent $event)
    {
        if (isset($event->mentionuser)) {

            $mention_user_id = $event->mentionuser;
            $mentionUser = User::whereIn('id', ($mention_user_id))->get();
            Notification::send($mentionUser, new ProjectNoteMention($event->project, $event));

        }


    }

}
