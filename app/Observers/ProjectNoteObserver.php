<?php

namespace App\Observers;

use App\Events\ProjectNoteEvent;
use App\Events\ProjectNoteMentionEvent;
use App\Models\MentionUser;
use App\Models\ProjectNote;
use App\Models\User;

// use function GuzzleHttp\json_decode;

class ProjectNoteObserver
{

    /**
     * @param ProjectNote $ProjectNote
     */
    public function saving(ProjectNote $ProjectNote)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $ProjectNote->last_updated_by = user()->id;
        }
    }

    public function creating(ProjectNote $ProjectNote)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $ProjectNote->added_by = user()->id;
        }
    }

    public function created(ProjectNote $projectNote)
    {
        $project = $projectNote->project;

        if (request()->mention_user_id != null && request()->mention_user_id != '') {

            $projectNote->mentionUser()->sync(request()->mention_user_id);

            $project_users = json_decode($project->projectMembers->pluck('id'));

            $mention_ids = json_decode($projectNote->mentionNote->pluck('user_id'));

            $mention_userId = array_intersect($mention_ids, $project_users);

            if ($mention_userId != null && $mention_userId != '') {

                event(new ProjectNoteMentionEvent($project, $projectNote->created_at, $mention_userId));

            }

            $unmention_ids = array_diff($project_users, $mention_ids);

            if ($unmention_ids != null && $unmention_ids != '') {

                $project_note_users = User::whereIn('id', $unmention_ids)->get();
                event(new ProjectNoteEvent($project, $projectNote->created_at, $project_note_users));

            }

        } else {

            event(new ProjectNoteEvent($project, $projectNote->created_at, $projectNote->project->projectMembers));

        }

    }

    public function updating(ProjectNote $projectNote)
    {

        $mentioned_user = MentionUser::where('project_note_id', $projectNote->id)->pluck('user_id');

        $request_mention_ids = explode(',', (request()->mention_user_id));
        $project = $projectNote->project;
        $newMention = [];
        $projectNote->mentionUser()->sync($request_mention_ids);

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
            
            if ($newMention != null) {

                event(new ProjectNoteMentionEvent($project, $projectNote->created_at, $newMention));

            }

        }
    }

}
