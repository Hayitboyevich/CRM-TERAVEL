<?php

namespace App\Observers;

use App\Events\TaskNoteEvent;
use App\Events\TaskNoteMentionEvent;
use App\Models\MentionUser;
use App\Models\Task;
use App\Models\TaskNote;
use App\Models\User;

class TaskNoteObserver
{

    public function saving(TaskNote $note)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $note->last_updated_by = user()->id;
        }
    }

    public function creating(TaskNote $note)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $note->added_by = user()->id;
        }
    }

    public function created(TaskNote $note)
    {
        if (isRunningInConsoleOrSeeding()) {
            return true;
        }

        $task = $note->task;

        if ($task->project_id != null) {

            if (request()->mention_user_id != null && request()->mention_user_id != '') {

                $note->mentionUser()->sync(request()->mention_user_id);

                $task_users = json_decode($task->taskUsers->pluck('user_id'));

                $mention_ids = json_decode($note->mentionNote->pluck('user_id'));

                $mention_userId = array_intersect($mention_ids, $task_users);

                if ($mention_userId != null && $mention_userId != '') {
                    event(new TaskNoteMentionEvent($task, $note->created_at, $mention_userId));

                }

                $unmention_ids = array_diff($task_users, $mention_ids);

                if ($unmention_ids != null && $unmention_ids != '') {

                    $task_users_note = User::whereIn('id', $unmention_ids)->get();

                    if ($task->project->client_id != null && $task->project->allow_client_notification == 'enable') {

                        event(new TaskNoteEvent($task, $note->created_at, $task->project->client, 'client'));

                    }

                    event(new TaskNoteEvent($task, $note->created_at, $task_users_note));

                }

            } else {

                    event(new TaskNoteEvent($task, $note->created_at, $task->project->projectMembers));
            }

            if ($task->project->client_id != null && $task->project->allow_client_notification == 'enable') {

                event(new TaskNoteEvent($task, $note->created_at, $task->project->client, 'client'));

            }

        } else {
            event(new TaskNoteEvent($task, $note->created_at, $task->users));
        }
    }

    public function updating(TaskNote $note)
    {

        $mentioned_user = MentionUser::where('task_note_id', $note->id)->pluck('user_id');
        $request_mention_ids = request()->mention_user_id;
        $newMention = [];
        $note->mentionUser()->sync(request()->mention_user_id);

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

                event(new TaskNoteMentionEvent($note->task, $note, $newMention));

            }

        }

    }

}
