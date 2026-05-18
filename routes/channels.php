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
Broadcast::channel('team.{teamId}.project.{projectId}', function ($user, $teamId, $projectId) {

    $project = \App\Models\Project::where('id', $projectId)
        ->where('team_id', $teamId)
        ->first();

    if (!$project) {
        return false;
    }

    return (string) $project->team_id === (string) $teamId;
});

Broadcast::channel('team.{teamId}.project.{projectId}.task.{taskId}', function ($user, $teamId, $projectId, $taskId) {
    $project = Project::where('id', $projectId)
        ->where('team_id', $teamId)
        ->first();

    if (!$project) {
        return false;
    }

    // Check if user belongs to the team
    if (!$user->belongsToTeam($teamId)) {   // or use your own method
        return false;
    }

    // Optional: Check if task belongs to the project
    $taskExists = \App\Models\Task::where('id', $taskId)
        ->where('project_id', $projectId)
        ->exists();

    return $taskExists;
});