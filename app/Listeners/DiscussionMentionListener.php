<?php

namespace App\Listeners;

use App\Events\DiscussionMentionEvent;
use App\Models\User;
use App\Notifications\NewDiscussionMention;
use Illuminate\Support\Facades\Notification;

class DiscussionMentionListener
{

    /**
     * Handle the event.
     *
     * @param DiscussionMentionEvent $event
     * @return void
     */

    public function handle(DiscussionMentionEvent $event)
    {

        $mention_user_id = $event->mentionuser;
        $mentionUser = User::whereIn('id', ($mention_user_id))->get();

        Notification::send($mentionUser, new NewDiscussionMention($event->discussion));
    }

}
