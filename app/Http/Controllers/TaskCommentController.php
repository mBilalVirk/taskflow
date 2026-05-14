<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Team;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class TaskCommentController extends Controller
{
   use AuthorizesRequests;
    /**
     * Get all comments for a task
     */
    public function index(Team $team, Project $project, Task $task)
    {
        $this->authorize('view', $task);

        $comments = $task->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($comments);
    }

    /**
     * Add comment to task
     */
    public function store(Request $request, Team $team, Project $project, Task $task)
    {
        $this->authorize('view', $task);

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $comment = $task->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        $comment->load('user');

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $comment,
            ]);
        }

        return back()->with('success', 'Comment added!');
    }

    /**
     * Delete comment
     */
    public function destroy(Team $team, Project $project, Task $task, TaskComment $comment)
    {
        $this->authorize('view', $task);

        // Only author or admin can delete
        if (auth()->id() !== $comment->user_id && !auth()->user()->isTeamAdmin($team)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Comment deleted!');
    }
}