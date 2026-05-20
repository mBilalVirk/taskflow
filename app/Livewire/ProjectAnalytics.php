<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Team;
use Livewire\Component;

class ProjectAnalytics extends Component
{
    public Team $team;
    public Project $project;

    public function mount(Team $team, Project $project)
    {
        $this->team = $team;
        $this->project = $project;
    }

    #[\Livewire\Attributes\Computed]
    public function analytics()
    {
        $tasks = $this->project->tasks;

        return [
            'total_tasks' => $tasks->count(),
            'by_status' => [
                'todo' => $tasks->where('status', 'todo')->count(),
                'in_progress' => $tasks->where('status', 'in_progress')->count(),
                'done' => $tasks->where('status', 'done')->count(),
            ],
            'by_priority' => [
                'urgent' => $tasks->where('priority', 'urgent')->count(),
                'high' => $tasks->where('priority', 'high')->count(),
                'medium' => $tasks->where('priority', 'medium')->count(),
                'low' => $tasks->where('priority', 'low')->count(),
            ],
            'completion_rate' => $tasks->count() > 0 ? round(($tasks->where('status', 'done')->count() / $tasks->count()) * 100, 2) : 0,
            'overdue_tasks' => $tasks->where('due_date', '<', now())->where('status', '!=', 'done')->count(),
            'due_soon' => $tasks
                ->where('due_date', '<=', now()->addDays(3))
                ->where('due_date', '>', now())
                ->where('status', '!=', 'done')
                ->count(),
        ];
    }

    public function exportCsv()
{
    $tasks = $this->project->tasks()->with('assignee')->get();

    $csv = "Task,Status,Priority,Assigned To,Due Date,Created Date\n";

    foreach ($tasks as $task) {
        $assigneeName = $task->assignee !== null ? $task->assignee->name : 'Unassigned';
        $dueDate = $task->due_date !== null ? $task->due_date->format('Y-m-d') : 'N/A';

        // Escaping double quotes inside titles just in case
        $title = str_replace('"', '""', $task->title);

        $csv .= "\"{$title}\",\"{$task->status}\",\"{$task->priority}\",";
        $csv .= "\"{$assigneeName}\",";
        $csv .= "\"{$dueDate}\",";
        $csv .= "\"{$task->created_at->format('Y-m-d')}\"\n";
    }

    $filename = $this->project->name . '_tasks.csv';

    // Use Livewire-compatible streamDownload helper
    return response()->streamDownload(function () use ($csv) {
        echo $csv;
    }, $filename, [
        'Content-Type' => 'text/csv',
    ]);
}

    public function render()
    {
        // \Log::info('Project Analytics Data: ' . json_encode($this->analytics()));
        return view('livewire.project-analytics', [
            'analytics' => $this->analytics(),
        ]);
    }
}
