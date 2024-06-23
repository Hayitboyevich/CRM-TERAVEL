<?php

namespace App\Observers;

use App\Events\DiscussionEvent;
use App\Events\DiscussionMentionEvent;
use App\Models\DiscussionFile;
use App\Models\DiscussionReply;
use App\Events\DiscussionReplyEvent;
use App\Models\User;
use Carbon\Carbon;

class DiscussionReplyObserver
{

    public function creating(DiscussionReply $model)
    {
        if (company()) {
            $model->company_id = company()->id;
        }
    }

    public function created(DiscussionReply $discussionReply)
    {
        if (isset(request()->discussion_type) && request()->discussion_type == 'discussion_reply') {
            $discussion = $discussionReply->discussion;

            $project = $discussion->project;

                $mention_ids = explode(',', request()->mention_user_id);

                $project_users = json_decode($project->projectMembers->pluck('id'));
                $mention_userId = array_intersect($mention_ids, $project_users);

            if ($mention_userId != null && $mention_userId != '') {

                $discussionReply->mentionUser()->sync($mention_ids);
                event(new DiscussionMentionEvent($discussion, $mention_userId));

            } else {

                $unmention_ids = array_diff($project_users, $mention_ids);

                if ($unmention_ids != null && $unmention_ids != '') {

                    $project_member = User::whereIn('id', $unmention_ids)->get();
                    event(new DiscussionEvent($discussion, $project_member));

                } else {
                    if (!isRunningInConsoleOrSeeding()) {
                        $discussion->last_reply_at = now()->toDateTimeString();
                        $discussion->last_reply_by_id = user()->id;
                        $discussion->save();

                        event(new DiscussionReplyEvent($discussionReply, $discussion->user));
                    }
                }
            }
        }

    }

    public function deleted(DiscussionReply $discussionReply)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $discussion = $discussionReply->discussion;
            $discussion->best_answer_id = null;
            $discussion->save();
        }
    }

}
