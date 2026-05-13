<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view the project.
     */
    public function view(User $user, Project $project): bool
    {
        return $user->hasTeamAccess($project->team) || $user->id === $project->created_by;
    }

    /**
     * Determine whether the user can update the project.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->hasTeamAccess($project->team) && $user->id === $project->created_by;
    }

    /**
     * Determine whether the user can delete the project.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->hasTeamAccess($project->team) && $user->id === $project->created_by;
    }

    /**
     * Anyone who can access the team can create projects inside it.
     */
    public function create(User $user): bool
    {
        return true; 
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function restore(User $user, Project $project): bool
    {
        return false;
    }

    public function forceDelete(User $user, Project $project): bool
    {
        return false;
    }
}