<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class ProjectController extends Controller
{
    use AuthorizesRequests;
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * List all projects in team
     */
    public function index(Team $team)
    {
        $this->authorize('view', $team);

        $projects = $team->projects()->paginate(12);

        return view('projects.index', compact('team', 'projects'));
    }

    /**
     * Show single project
     */
    public function show(Team $team, Project $project)
    {
        $this->authorize('view', $team);
        $this->authorize('view', $project);

        $tasks = $project->tasks()->with('assignee', 'creator')->get()->groupBy('status');
        $statuses = ['todo', 'in_progress', 'done'];
        foreach ($statuses as $status) {
            if (!isset($tasks[$status])) {
                $tasks[$status] = collect();
            }
        }

        if (request()->expectsJSON) {
            return response()->json($tasks);
        }

        return view('projects.show', compact('team', 'project', 'tasks'));
    }

    /**
     * Show create project form
     */
    public function createForm(Team $team)
    {
        $this->authorize('view', $team);

        return view('projects.create', compact('team'));
    }

    /**
     * Create new project
     */
    public function store(Request $request, Team $team)
    {
        $this->authorize('view', $team);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|regex:/#[0-9A-F]{6}/i',
        ]);

        $project = $team->projects()->create([
            'created_by' => auth()->id(),
            'slug' => str($validated['name'])->slug(),
            'color' => $validated['color'] ?? '#3B82F6',
            ...$validated,
        ]);

        // LOG ACTIVITY
    \App\Models\ActivityLog::log(
        $team->id,
        auth()->id(),
        'created',
        'Project',
        $project->id,
        auth()->user()->name . " created project: {$project->name}"
    );

        // 🔥 Send notification to all team members except the creator
        $team->members()
        ->where('users.id', '!=', auth()->id())
        ->select('users.id')
        ->lazy()
        ->each(function ($user) use ($project) {
            $user->notify(new \App\Notifications\ProjectCreated($project));
        });
        

        return redirect("/team/{$team->id}/projects/{$project->id}")->with('success', 'Project created!');
    }

    /**
     * Show edit project form
     */
    public function editForm(Team $team, Project $project)
    {
        $this->authorize('view', $team);
        $this->authorize('update', $project);

        return view('projects.edit', compact('team', 'project'));
    }

    /**
     * Update project
     */
    public function update(Request $request, Team $team, Project $project)
    {
        $this->authorize('view', $team);
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|regex:/#[0-9A-F]{6}/i',
        ]);

        $project->update($validated);

        return back()->with('success', 'Project updated!');
    }

    /**
     * Delete project
     */
    public function destroy(Request $request, Team $team, Project $project)
    {
        $this->authorize('view', $team);
        $this->authorize('delete', $project);

        $project->delete();

        return redirect("/team/{$team->id}/projects")->with('success', 'Project deleted!');
    }
}
