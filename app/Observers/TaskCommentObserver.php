<?php

namespace App\Observers;

use App\Events\TaskCommentEvent;
use App\Events\TaskCommentMentionEvent;
use App\Models\MentionUser;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;

class TaskCommentObserver
{

    public function saving(TaskComment $comment)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $comment->last_updated_by = user()->id;
        }
    }

    public function creating(TaskComment $comment)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $comment->added_by = user()->id;
        }
    }

    public function created(TaskComment $comment)
    {
        if (isRunningInConsoleOrSeeding()) {
            return true;
        }

        $task = $comment->task;

        if (request()->mention_user_id != null && request()->mention_user_id != '') {

            $comment->mentionUser()->sync(request()->mention_user_id);
            $task_users = json_decode($task->taskUsers->pluck('user_id'));
            $mention_ids = json_decode($comment->mentionComment->pluck('user_id'));

            $mention_userId = array_intersect($mention_ids, $task_users);

            if ($mention_userId != null && $mention_userId != '') {

                event(new TaskCommentMentionEvent($task, $comment, $mention_userId));

            }

            $unmention_ids = array_diff($task_users, $mention_ids);

            if ($unmention_ids != null && $unmention_ids != '') {

                $task_users_comment = User::whereIn('id', $unmention_ids)->get();

                event(new TaskCommentEvent($task, $comment, $task_users_comment, 'null'));

            }

        } else {

            event(new TaskCommentEvent($task, $comment, $task->users, 'null'));
        }

        if ($task->project_id != null) {

            if ($task->project->client_id != null && $task->project->allow_client_notification == 'enable') {

                event(new TaskCommentEvent($task, $comment, $task->project->client, 'client'));
            }

        }
    }

    public function updating(TaskComment $comment)
    {
        $mentioned_user = MentionUser::where('task_comment_id', $comment->id)->pluck('user_id');
        $request_mention_ids = request()->mention_user_id;
        $newMention = [];
        $comment->mentionUser()->sync(request()->mention_user_id);

        if ($request_mention_ids != null) {

            foreach ($request_mention_ids as $value) {

                if (($mentioned_user) != null) {
                    if (!in_array($value, json_decode($mentioned_user))) {

                        $newMention[] = $value;
                    }
                } else {

                    $newMention[] = $value;
                }
            }

            if (!empty($newMention)) {

                event(new TaskCommentMentionEvent($comment->task, $comment, $newMention));

            }

        }

    }

}
