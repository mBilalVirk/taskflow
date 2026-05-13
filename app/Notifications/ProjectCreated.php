<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
class ProjectCreated extends Notification 
{
    use Queueable;

    public $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'project_created',
            'title' => 'New Project Created',
            'message' => "{$this->project->created_by_user->name} created a new project: {$this->project->name}",
            'project_id' => $this->project->id,
            'team_id' => $this->project->team_id,
            'url' => route('projects.show', [$this->project->team, $this->project]),
            'icon' => '📁'
        ];
    }
}