<?php

namespace App\Livewire;

use App\Models\Team;
use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

class ActivityTimeline extends Component
{
    use WithPagination;

    public Team $team;
    public ?Project $project = null;
    public string $filter = 'all';
    public string $searchTerm = '';

    public function mount(Team $team, ?Project $project = null)
    {
        $this->team = $team;
        $this->project = $project;
    }

    #[Computed]
    public function activities()
    {
        $query = $this->team->activityLogs();

        if ($this->filter !== 'all') {
            $query->where('action', $this->filter);
        }

        if ($this->searchTerm) {
            $query->whereRaw('LOWER(description) LIKE ?', ['%' . strtolower($this->searchTerm) . '%']);

            $query->whereHas('user', function ($q) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($this->searchTerm) . '%']);
            });
        }

        return $query->with('user:id,name,avatar')->latest()->paginate(20);
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.activity-timeline');
    }
}
