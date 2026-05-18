<?php

namespace App\Events;

use App\Models\TaskComment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;
    public $team_id;
    public $project_id;
    public $task_id;

    public function __construct(TaskComment $comment)
    {
        $this->comment = $comment->load('user');
        $this->task_id = $comment->task_id;
        $this->team_id = $comment->task->project->team_id;
        $this->project_id = $comment->task->project_id;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("team.{$this->team_id}.project.{$this->project_id}.task.{$this->task_id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'CommentAdded';
    }

    public function broadcastWith(): array
    {
        return [
            'comment' => $this->comment,
            'message' => "{$this->comment->user->name} added a comment",
        ];
    }
}