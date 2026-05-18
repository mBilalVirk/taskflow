<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;
    public $team_id;
    public $project_id;
    public $old_status;
    public $new_status;

    public function __construct(Task $task, $old_status, $new_status)
    {
        $this->task = $task->load('creator', 'assignee', 'project');
        $this->team_id = $task->project->team_id;
        $this->project_id = $task->project_id;
        $this->old_status = $old_status;
        $this->new_status = $new_status;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("team.{$this->team_id}.project.{$this->project_id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'TaskStatusChanged';
    }

    public function broadcastWith(): array
    {
        return [
            'task' => $this->task,
            'old_status' => $this->old_status,
            'new_status' => $this->new_status,
            'message' => "{$this->task->title} moved from {$this->old_status} to {$this->new_status}",
        ];
    }
}