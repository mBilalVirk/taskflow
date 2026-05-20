<?php

namespace App\Livewire;

use App\Models\Team;
use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\Attributes\Computed;

class TeamAnalyticsDashboard extends Component
{
    public Team $team;
    public string $period = '30'; // days
    // public array $overview = [];
    // public array $memberActivity = [];
    // public array $completionTrend = [];
    // public array $recentActivity = [];
public function loadAnalytics()
{
    $this->overview = [
        'total' => $this->totalTasks(),
        'completed' => $this->completedTasks(),
        'in_progress' => $this->inProgressTasks(),
        'rate' => $this->completionRate(),
    ];
}
    public function mount(Team $team)
    {
        $this->team = $team;
       //$this->loadAnalytics();
    }

    #[Computed]
    public function totalTasks()
    {
        return $this->team->projects()
            ->with('tasks')
            ->get()
            ->pluck('tasks')
            ->flatten()
            ->count();
    }

    #[Computed]
    public function completedTasks()
    {
        return $this->team->projects()
            ->with('tasks')
            ->get()
            ->pluck('tasks')
            ->flatten()
            ->where('status', 'done')
            ->count();
    }

    #[Computed]
    public function inProgressTasks()
    {
        return $this->team->projects()
            ->with('tasks')
            ->get()
            ->pluck('tasks')
            ->flatten()
            ->where('status', 'in_progress')
            ->count();
    }

     #[Computed]
    public function todoTasks()
    {
        return $this->team->projects()
            ->with('tasks')
            ->get()
            ->pluck('tasks')
            ->flatten()
            ->where('status', 'todo')
            ->count();
    }

    #[Computed]
    public function completionRate()
    {
        $total = $this->totalTasks();
        return $total > 0 ? round(($this->completedTasks() / $total) * 100, 2) : 0;
    }

    #[Computed]
    public function memberActivityData()
    {
        return $this->team->activityLogs()
            ->where('created_at', '>=', now()->subDays((int)$this->period))
            ->groupBy('user_id')
            ->selectRaw('user_id, count(*) as activity_count')
            ->with('user:id,name')
            ->get()
            ->sortByDesc('activity_count');
    }

    #[Computed]
    public function recentActivityData()
    {
        return $this->team->activityLogs()
            ->where('created_at', '>=', now()->subDays((int)$this->period))
            ->with('user:id,name,avatar')
            ->latest()
            ->limit(10)
            ->get();
        
    }

    public function updatedPeriod()
    {
        // Automatically recompute when period changes
        $this->dispatch('periodChanged');
    }

    public function render()
    {
        // \Log::info('Recent Activity Data: ' . $this->recentActivityData());
        return view('livewire.team-analytics-dashboard', [
            'totalTasks' => $this->totalTasks(),
            'completedTasks' => $this->completedTasks(),
            'inProgressTasks' => $this->inProgressTasks(),
            'completionRate' => $this->completionRate(),
            'memberActivity' => $this->memberActivityData(),
            'recentActivity' => $this->recentActivityData(),
        ]);
    }
}