<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SlackService
{
    private string $webhookUrl;

    public function __construct(string $webhookUrl)
    {
        $this->webhookUrl = $webhookUrl;
    }

    /**
     * Send notification to Slack
     */
    public function notify(string $title, string $message, string $color = '#36a64f'): bool
    {
        $payload = [
            'attachments' => [
                [
                    'color' => $color,
                    'title' => $title,
                    'text' => $message,
                    'footer' => 'TaskFlow',
                    'ts' => time(),
                ]
            ]
        ];

        $response = Http::post($this->webhookUrl, $payload);
        return $response->successful();
    }

    /**
     * Task notification
     */
    public function notifyTaskCreated($task): void
    {
        $this->notify(
            '✅ New Task Created',
            "*{$task->title}* in project *{$task->project->name}*",
            '#3b82f6'
        );
    }

    /**
     * Task completion notification
     */
    public function notifyTaskCompleted($task): void
    {
        $this->notify(
            '🎉 Task Completed',
            "*{$task->title}* has been completed!",
            '#22c55e'
        );
    }
}