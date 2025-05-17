<?php

namespace App\Broadcasting;

use App\Models\Project;
use App\Models\User;

class MentionChannel
{
    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user, $projectId)
    {
        $project = Project::findOrFail($projectId);
        return $project->users()->where('users.id', $user->id)->exists();
    }
}