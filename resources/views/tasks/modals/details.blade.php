<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h2>
            <span
                class="inline-block mt-2 text-xs px-3 py-1 rounded font-semibold
                @if ($task->priority === 'urgent') bg-red-100 text-red-700
                @elseif($task->priority === 'high') bg-orange-100 text-orange-700
                @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-700
                @else bg-blue-100 text-blue-700 @endif">
                {{ ucfirst($task->priority) }}
            </span>
        </div>
        <button onclick="closeTaskModal()" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times text-2xl"></i>
        </button>
    </div>

    <!-- Status Badge -->
    <div class="mb-6">
        <span
            class="inline-block px-4 py-2 rounded-lg font-semibold
            @if ($task->status === 'todo') bg-gray-100 text-gray-700
            @elseif($task->status === 'in_progress') bg-blue-100 text-blue-700
            @else bg-green-100 text-green-700 @endif">
            {{ $task->status === 'todo' ? 'To Do' : ($task->status === 'in_progress' ? 'In Progress' : 'Done') }}
        </span>
    </div>

    <!-- Description -->
    @if ($task->description)
        <div class="mb-6">
            <h3 class="font-bold text-gray-900 mb-2">Description</h3>
            <p class="text-gray-700 whitespace-pre-wrap">{{ $task->description }}</p>
        </div>
    @endif

    <!-- Details Grid -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <p class="text-sm text-gray-600">Assigned To</p>
            @if ($task->assignee)
                <p class="font-semibold text-gray-900">{{ $task->assignee->name }}</p>
            @else
                <p class="text-gray-500">Unassigned</p>
            @endif
        </div>

        <div>
            <p class="text-sm text-gray-600">Due Date</p>
            @if ($task->due_date)
                <p class="font-semibold text-gray-900">{{ $task->due_date->format('M d, Y') }}</p>
            @else
                <p class="text-gray-500">No due date</p>
            @endif
        </div>

        <div>
            <p class="text-sm text-gray-600">Created By</p>
            <p class="font-semibold text-gray-900">{{ $task->creator->name }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-600">Created Date</p>
            <p class="font-semibold text-gray-900">{{ $task->created_at->format('M d, Y') }}</p>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="border-t pt-6">
        <h3 class="font-bold text-gray-900 mb-4">Comments ({{ $task->comments->count() }})</h3>

        <div class="space-y-4 mb-4 max-h-48 overflow-y-auto">
            @forelse($task->comments as $comment)
                <div class="bg-gray-50 p-3 rounded">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-sm text-gray-900">{{ $comment->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                        </div>
                        @if (auth()->id() === $comment->user_id || auth()->user()->isTeamAdmin($team))
                            <form method="POST"
                                action="{{ route('comments.destroy', [$team, $project, $task, $comment]) }}"
                                class="inline" onsubmit="return confirm('Delete comment?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Delete</button>
                            </form>
                        @endif
                    </div>
                    <p class="text-sm text-gray-700 mt-1">{{ $comment->content }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-sm">No comments yet</p>
            @endforelse
        </div>

        <!-- Add Comment Form -->
        <form method="POST" action="{{ route('comments.store', [$team, $project, $task]) }}" class="space-y-2">
            @csrf
            <textarea name="content" placeholder="Add a comment..." rows="2"
                class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"></textarea>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">
                Add Comment
            </button>
        </form>
    </div>

    <!-- Edit Button -->
    <div class="border-t mt-6 pt-4">
        <a href="{{ route('tasks.edit', [$team, $project, $task]) }}"
            class="block text-center bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 font-semibold">
            Edit Task
        </a>
    </div>
</div>
