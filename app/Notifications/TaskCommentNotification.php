<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $task;
    public $comment;

    public function __construct(Task $task, TaskComment $comment)
    {
        $this->task = $task;
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Someone commented on a task!')
            ->line('Task: ' . $this->task->title)
            ->line('Comment: ' . substr($this->comment->content, 0, 100) . '...')
            ->action('View Task', url("/team/{$this->task->project->team_id}/projects/{$this->task->project_id}/tasks/{$this->task->id}"))
            ->line('Thank you for using TaskFlow!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'comment' => substr($this->comment->content, 0, 100),
            'commented_by' => $this->comment->user->name,
            'project_id' => $this->task->project_id,
            'message' => $this->comment->user->name . ' commented on: ' . $this->task->title,
            'url' => "/team/{$this->task->project->team_id}/projects/{$this->task->project_id}/tasks/{$this->task->id}",
        ];
    }
}