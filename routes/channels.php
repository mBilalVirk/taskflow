<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

// Temporary test channel
Broadcast::channel('test', function () {
    return true;
});

// Your project channel (safe version)
Broadcast::channel('project.{projectId}', function (User $user, $projectId) {
    $project = Project::find($projectId);
    
    if (!$project) {
        return false;
    }

    // Adjust this based on your logic
    return $user->currentTeam?->id === $project->team_id;
});