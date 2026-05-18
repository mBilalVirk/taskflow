<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class TaskDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $task_id;
    public $team_id;
    public $project_id;
    public $task_title;

    public function __construct($task_id, $team_id, $project_id, $task_title)
    {
        $this->task_id = $task_id;
        $this->team_id = $team_id;
        $this->project_id = $project_id;
        $this->task_title = $task_title;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("team.{$this->team_id}.project.{$this->project_id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'TaskDeleted';
    }

    public function broadcastWith(): array
    {
        return [
            'task_id' => $this->task_id,
            'message' => "Task deleted: {$this->task_title}",
        ];
    }
}