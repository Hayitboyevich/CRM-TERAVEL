<?php

namespace App\Observers;

use App\Events\DiscussionEvent;
use App\Events\DiscussionMentionEvent;
use App\Models\Discussion;
use App\Models\User;

class DiscussionObserver
{

    public function saving(Discussion $discussion)
    {
        if (!isRunningInConsoleOrSeeding() && user()) {
            $discussion->last_updated_by = user()->id;
        }
    }

    public function creating(Discussion $discussion)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if (user()) {
                $discussion->last_updated_by = user()->id;
                $discussion->added_by = user()->id;
            }
        }

        if (company()) {
            $discussion->company_id = company()->id;
        }
    }

    public function created(Discussion $discussion)
    {

        $project = $discussion->project;

            $mention_ids = explode(',', request()->mention_user_id);

            $project_users = json_decode($project->projectMembers->pluck('id'));

            $mention_userId = array_intersect($mention_ids, $project_users);

        if ($mention_userId != null && $mention_userId != '') {

            $discussion->mentionUser()->sync($mention_ids);

            event(new DiscussionMentionEvent($discussion, $mention_userId));

        } else {

            $unmention_ids = array_diff($project_users, $mention_ids);

            if ($unmention_ids != null && $unmention_ids != '') {

                $project_member = User::whereIn('id', $unmention_ids)->get();
                event(new DiscussionEvent($discussion, $project_member));

            } else {
                if (!isRunningInConsoleOrSeeding()) {
                    event(new DiscussionEvent($discussion, null));
                }
            }
        }

    }

    public function deleting(Discussion $discussion)
    {
        $notifyData = ['App\Notifications\NewDiscussion', 'App\Notifications\NewDiscussionReply'];
        \App\Models\Notification::deleteNotification($notifyData, $discussion->id);

    }

}
