<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Task;
use App\Models\Team;

class TaskPolicy
{
    /**
     * Can user view task
     */
    public function view(User $user, Task $task): bool
    {
        // Check if user is in the project's team
        return $user->hasTeamAccess($task->project->team);
    }

    /**
     * Can user create task
     */
    public function create(User $user, Team $team): bool
    {
        return $user->hasTeamAccess($team);
    }

    /**
     * Can user update task
     */
    public function update(User $user, Task $task): bool
    {
        return $user->hasTeamAccess($task->project->team);
    }

    /**
     * Can user delete task
     */
    public function delete(User $user, Task $task): bool
    {
        $team = $task->project->team;
        
        // Only admins or task creator can delete
        return $user->isTeamAdmin($team) || $user->id === $task->created_by;
    }

    /**
     * Can user manage comments
     */
    public function manageComments(User $user, Task $task): bool
    {
        return $user->hasTeamAccess($task->project->team);
    }
}