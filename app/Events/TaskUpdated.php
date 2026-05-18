<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;
    public $team_id;
    public $project_id;

    public function __construct(Task $task)
    {
        $this->task = $task->load('creator', 'assignee', 'project');
        $this->team_id = $task->project->team_id;
        $this->project_id = $task->project_id;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("team.{$this->team_id}.project.{$this->project_id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'TaskUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'task' => $this->task,
            'message' => "Task updated: {$this->task->title}",
        ];
    }
}