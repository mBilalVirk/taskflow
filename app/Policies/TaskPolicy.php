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
    public function manageStatus(User $user, Task $task): bool
{
    // Make sure relationships are loaded
    if (!$task->relationLoaded('project')) {
        $task->load('project.team');
    }

    $team = $task->project->team;

    // User is in the team (via team_members pivot)
    // if ($team && $user->teams()->where('teams.id', $team->id)->exists()) {
    //     return true;
    // }

    // User is assigned to the task
    if ($user->id === $task->assigned_to) {
        return true;
    }

    // User created the task
    if ($user->id === $task->created_by) {
        return true;
    }

    return false;
}
}