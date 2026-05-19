<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Events\TaskCreated; // You will create this below
use App\Notifications\TaskAssigned; // You will create this below

use App\Events\TaskUpdated;
use App\Events\TaskDeleted;
use App\Events\TaskStatusChanged;
class TaskController extends Controller
{
    use AuthorizesRequests;
    /**
     * Get tasks for a project (grouped by status)
     */
    public function index(Team $team, Project $project)
    {
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

        // Otherwise return view
        return view('projects.kanban', compact('team', 'project', 'tasks'));
    }
    /**
     * Show create task form
     */
    public function createForm(Team $team, Project $project)
    {
        $this->authorize('view', $project);
        $team_members = $team->members()->get();
        return view('tasks.create', compact('team', 'project', 'team_members'));
    }
    /**
     * Create new task
     */
    public function store(Request $request, Team $team, Project $project)
    {
        // 1. Policy check (ensure you added the AuthorizesRequests trait to the class)
        $this->authorize('view', $project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after:today',
            'status' => 'required|in:todo,in_progress,done',
        ]);

        // 2. Create the task
        $task = $project->tasks()->create([
            'created_by' => auth()->id(),
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'assigned_to' => $validated['assigned_to'],
            'due_date' => $validated['due_date'],
        ]);

        // 3. 🔥 LIVE UPDATE: Broadcast to the project channel
        // This tells Reverb to inform everyone else looking at the board
        $task->load(['creator', 'assignee', 'project']);

        \Log::info('=== Broadcasting TaskCreated ===', [
            'task_id' => $task->id,
            'channel' => "team.{$task->project->team_id}.project.{$task->project_id}",
        ]);
        broadcast(new TaskCreated($task))->toOthers();
        \Log::info('Broadcast call completed');
        // 4. 🔔 NOTIFICATION: If a user is assigned, notify them instantly
        if ($task->assigned_to && $task->assigned_to !== auth()->id()) {
            $task->assignee->notify(new TaskAssigned($task));
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'task' => $task->load('assignee', 'creator'),
                'message' => 'Task created successfully!',
            ]);
        }

        // return redirect("/team/{$team->id}/projects/{$project->id}")->with('success', 'Task created successfully!');
    }

    /**
     * Show task details
     */
    public function show(Team $team, Project $project, Task $task)
    {
        $this->authorize('view', $task);

        $task->load('comments.user', 'attachments', 'assignee', 'creator');

        if (request()->expectsJson()) {
            return response()->json($task);
        }

        return view('tasks.show', compact('team', 'project', 'task'));
    }

    /**
     * Show edit form
     */
    public function editForm(Team $team, Project $project, Task $task)
    {
        $this->authorize('view', $task);

        $team_members = $team->members()->get();

        return view('tasks.edit', compact('team', 'project', 'task', 'team_members'));
    }

    /**
     * Update task
     */
    public function update(Request $request, Team $team, Project $project, Task $task)
    {
        $this->authorize('view', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'status' => 'required|in:todo,in_progress,done',
        ]);

        // Store old values
        $old_status = $task->status;
        $old_assigned = $task->assigned_to;
        $task->update($validated);
        $task->load('assignee', 'creator');

        // Broadcast event
        broadcast(new TaskUpdated($task))->toOthers();

        // If status changed
        if ($old_status !== $task->status) {
            broadcast(new TaskStatusChanged($task, $old_status, $task->status))->toOthers();
        }

        // If assigned to different person
        if ($old_assigned !== $task->assigned_to && $task->assigned_to) {
            $task->assignee->notify(new \App\Notifications\TaskAssignedNotification($task));
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'task' => $task->load('assignee'),
                'message' => 'Task updated!',
            ]);
        }

        return back()->with('success', 'Task updated successfully!');
    }

    /**
     * Update task status (for drag & drop)
     */
    public function updateStatus(Request $request, Team $team, Project $project, Task $task)
    {
        if (!auth()->user()->can('manageStatus', $task)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'You don\'t have permission to move this task.',
                    'error' => 'unauthorized',
                ],
                403,
            );
        }

        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,done',
            'order' => 'nullable|integer',
        ]);

        $old_status = $task->status;

        $task->update([
            'status' => $validated['status'],
            'order_in_status' => $validated['order'] ?? 0,
        ]);

        $task->load('assignee', 'creator');

        // BROADCAST - Triggers live update for all users
        broadcast(new TaskStatusChanged($task, $old_status, $task->status))->toOthers();

        

        return response()->json([
            'success' => true,
            'task' => $task,
        ]);
    }

    /**
     * Delete task
     */
    public function destroy(Team $team, Project $project, Task $task)
    {
        $this->authorize('view', $task);

        // Delete comments and attachments
        $task->comments()->delete();
        $task->attachments()->delete();
        $task_id = $task->id;
        $task_title = $task->title;
        // Delete task
        $task->delete();

        // BROADCAST - Delete event
        broadcast(new TaskDeleted($task_id, $team->id, $project->id, $task_title))->toOthers();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()
            ->route('projects.show', [$team, $project])
            ->with('success', 'Task deleted successfully!');
    }

    /**
     * Get task modal (AJAX)
     */
    public function getModal(Team $team, Project $project, Task $task)
    {
        $this->authorize('view', $task);

        $task->load('comments.user', 'attachments', 'assignee', 'creator');
        $team_members = $team->members()->get();

        return view('tasks.modals.details', compact('team', 'project', 'task', 'team_members'))->render();
    }
}
