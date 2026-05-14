<?php
namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $task;

    public function __construct(Task $task) {
        $this->task = $task;
    }

    public function via($notifiable): array {
        return ['database', 'broadcast']; // Saves to DB and sends via Reverb
    }

    public function toBroadcast($notifiable): BroadcastMessage {
        return new BroadcastMessage([
            'title' => 'New Task Assigned',
            'message' => "You have been assigned to: {$this->task->title}",
            'url' => url("/tasks/{$this->task->id}"),
        ]);
    }
    
    // Add toDatabase if you want it stored in your notifications table
    public function toArray($notifiable): array {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
        ];
    }
}