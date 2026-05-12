<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Team;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{
    /**
     * Determine if user can view the team
     */
    public function view(User $user, Team $team): bool
    {
        return $user->hasTeamAccess($team);
    }

    /**
     * Determine if user can create teams
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine if user can update the team
     */
    public function update(User $user, Team $team): bool
    {
        return $user->isTeamAdmin($team);
    }

    /**
     * Determine if user can manage team members
     */
    public function manageMembers(User $user, Team $team): bool
    {
        return $user->isTeamAdmin($team);
    }

    /**
     * Determine if user can delete the team
     */
    public function delete(User $user, Team $team): bool
    {
        return $user->id === $team->user_id;
    }
}