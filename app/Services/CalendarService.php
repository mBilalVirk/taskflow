<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;

class CalendarService
{
    private Calendar $service;

    public function __construct(string $credentialsPath)
    {
        $client = new Client();
        $client->setAuthConfig($credentialsPath);
        $client->addScope(Calendar::CALENDAR);

        $this->service = new Calendar($client);
    }

    /**
     * Sync task due date to calendar
     */
    public function syncTask($task): void
    {
        if (!$task->due_date) {
            return;
        }

        $event = new \Google\Service\Calendar\Event([
            'summary' => $task->title,
            'description' => $task->description,
            'start' => ['dateTime' => $task->due_date->toAtomString()],
            'end' => ['dateTime' => $task->due_date->addHours(1)->toAtomString()],
        ]);

        $this->service->events->insert('primary', $event);
    }

    /**
     * Get calendar events
     */
    public function getEvents($startDate, $endDate): array
    {
        $results = $this->service->events->listEvents('primary', [
            'timeMin' => $startDate->toAtomString(),
            'timeMax' => $endDate->toAtomString(),
            'singleEvents' => true,
        ]);

        return $results->getItems();
    }
}