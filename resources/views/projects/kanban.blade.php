@extends('layouts.app')
@section('title', $project->name . ' - Kanban Board')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">{{ $project->name }}</h1>
                <p class="text-gray-600 mt-2">{{ $project->tasks()->count() }} tasks</p>
            </div>
            <button onclick="openCreateTaskModal('todo')"
                class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">
                + New Task
            </button>
        </div>

        <!-- Kanban Board -->
        <div class="grid grid-cols-3 gap-6">
            <!-- To Do Column -->
            <div class="bg-gray-100 rounded-lg p-4" data-status="todo">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900">To Do</h2>
                    <span class="bg-gray-300 text-gray-700 px-3 py-1 rounded text-sm font-semibold">
                        {{ isset($tasks['todo']) ? $tasks['todo']->count() : 0 }}
                    </span>
                </div>

                <div class="space-y-3 min-h-96" id="todo-column" data-status="todo">
                    @forelse(isset($tasks['todo']) ? $tasks['todo'] : [] as $task)
                        <div class="group bg-white rounded-lg shadow p-4 cursor-move hover:shadow-lg transition task-card relative"
                            draggable="true" data-id="{{ $task->id }}" onclick="openTaskModal('{{ $task->id }}')">

                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-gray-900 text-sm">{{ $task->title }}</h3>
                                <!-- Edit Button Added -->
                                <button onclick="openEditModal('{{ $task->id }}')"
                                    class="opacity-0 group-hover:opacity-100 text-slate-400 hover:text-indigo-600 transition-all p-1">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                            </div>

                            <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ $task->description }}</p>

                            <div class="flex items-center justify-between">
                                <div class="flex flex-col gap-2">
                                    <span
                                        class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider
                                            @if ($task->priority === 'urgent') bg-red-100 text-red-700
                                            @elseif($task->priority === 'high') bg-orange-100 text-orange-700
                                            @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-700
                                            @else bg-blue-100 text-blue-700 @endif">
                                        {{ $task->priority }}
                                    </span>

                                    @if ($task->due_date)
                                        <p class="text-[10px] text-gray-400 font-medium">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ $task->due_date->format('M d') }}
                                        </p>
                                    @endif
                                </div>

                                @if ($task->assignee)
                                    <img src="{{ $task->assignee->avatar ?? 'https://ui-avatars.com/api/?background=random&name=' . urlencode($task->assignee->name) }}"
                                        alt="{{ $task->assignee->name }}"
                                        class="w-7 h-7 rounded-full border-2 border-white shadow-sm"
                                        title="{{ $task->assignee->name }}">
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-2xl mb-2"></i>
                            <p>No tasks yet</p>
                        </div>
                    @endforelse
                </div>

                <button onclick="openCreateTaskModal('todo')"
                    class="w-full mt-3 text-gray-600 hover:text-gray-900 font-bold py-2 text-sm">
                    + Add Task
                </button>
            </div>

            <!-- In Progress Column -->
            <div class="bg-gray-100 rounded-lg p-4" data-status="in_progress">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900">In Progress</h2>
                    <span class="bg-blue-300 text-blue-700 px-3 py-1 rounded text-sm font-semibold">
                        {{ isset($tasks['in_progress']) ? $tasks['in_progress']->count() : 0 }}
                    </span>
                </div>

                <div class="space-y-3 min-h-96" id="in_progress-column" data-status="in_progress">
                    @forelse(isset($tasks['in_progress']) ? $tasks['in_progress'] : [] as $task)
                        <div class="group bg-white rounded-lg shadow p-4 cursor-move hover:shadow-lg transition task-card relative"
                            draggable="true" data-id="{{ $task->id }}" onclick="openTaskModal('{{ $task->id }}')">

                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-gray-900 text-sm">{{ $task->title }}</h3>
                                <!-- Edit Button Added -->
                                <button onclick="openEditModal('{{ $task->id }}')"
                                    class="opacity-0 group-hover:opacity-100 text-slate-400 hover:text-indigo-600 transition-all p-1">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                            </div>

                            <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ $task->description }}</p>

                            <div class="flex items-center justify-between">
                                <span
                                    class="text-xs px-2 py-1 rounded font-semibold
                                    @if ($task->priority === 'urgent') bg-red-100 text-red-700
                                    @elseif($task->priority === 'high') bg-orange-100 text-orange-700
                                    @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-700
                                    @else bg-blue-100 text-blue-700 @endif">
                                    {{ ucfirst($task->priority) }}
                                </span>
                                @if ($task->assignee)
                                    <img src="{{ $task->assignee->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($task->assignee->name) }}"
                                        alt="{{ $task->assignee->name }}" class="w-6 h-6 rounded-full"
                                        title="{{ $task->assignee->name }}">
                                @endif
                            </div>

                            @if ($task->due_date)
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $task->due_date->format('M d') }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-2xl mb-2"></i>
                            <p>No tasks yet</p>
                        </div>
                    @endforelse
                </div>

                <button onclick="openCreateTaskModal('in_progress')"
                    class="w-full mt-3 text-gray-600 hover:text-gray-900 font-bold py-2 text-sm">
                    + Add Task
                </button>
            </div>

            <!-- Done Column -->
            <div class="bg-gray-100 rounded-lg p-4" data-status="done">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Done</h2>
                    <span class="bg-green-300 text-green-700 px-3 py-1 rounded text-sm font-semibold">
                        {{ isset($tasks['done']) ? $tasks['done']->count() : 0 }}
                    </span>
                </div>

                <div class="space-y-3 min-h-96" id="done-column" data-status="done">
                    @forelse(isset($tasks['done']) ? $tasks['done'] : [] as $task)
                        <div class="group bg-white rounded-lg shadow p-4 cursor-move hover:shadow-lg transition task-card relative"
                            draggable="true" data-id="{{ $task->id }}" onclick="openTaskModal('{{ $task->id }}')">

                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-gray-900 text-sm line-through">{{ $task->title }}</h3>
                                <!-- Edit Button Added -->
                                <button onclick="openEditModal({{ $task->id }})"
                                    class="opacity-0 group-hover:opacity-100 text-slate-400 hover:text-indigo-600 transition-all p-1">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                            </div>

                            <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ $task->description }}</p>

                            <div class="flex items-center justify-between">
                                <span
                                    class="text-xs px-2 py-1 rounded font-semibold
                                    @if ($task->priority === 'urgent') bg-red-100 text-red-700
                                    @elseif($task->priority === 'high') bg-orange-100 text-orange-700
                                    @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-700
                                    @else bg-blue-100 text-blue-700 @endif">
                                    {{ ucfirst($task->priority) }}
                                </span>
                                @if ($task->assignee)
                                    <img src="{{ $task->assignee->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($task->assignee->name) }}"
                                        alt="{{ $task->assignee->name }}" class="w-6 h-6 rounded-full"
                                        title="{{ $task->assignee->name }}">
                                @endif
                            </div>

                            @if ($task->due_date)
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $task->due_date->format('M d') }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-2xl mb-2"></i>
                            <p>No tasks yet</p>
                        </div>
                    @endforelse
                </div>

                <button onclick="openCreateTaskModal('done')"
                    class="w-full mt-3 text-gray-600 hover:text-gray-900 font-bold py-2 text-sm">
                    + Add Task
                </button>
            </div>

        </div>
    </div>

    <!-- Task Modal -->
    <div id="taskModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-96 overflow-y-auto" id="modalContent">
            <!-- Modal content will be loaded here -->
        </div>
    </div>

    <!-- Create Task Modal -->
    <div id="createTaskModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <h2 class="text-2xl font-bold mb-4">Create Task</h2>
            <form id="createTaskForm" method="POST"
                action="{{ route('tasks.store', ['team' => $team->id, 'project' => $project->id]) }}">
                @csrf
                <input type="hidden" id="taskStatus" name="status" value="todo">

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Task Title</label>
                    <input type="text" name="title" placeholder="Enter task title" required
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Description</label>
                    <textarea name="description" placeholder="Task description (optional)" rows="3"
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Priority</label>
                    <select name="priority" class="w-full border rounded px-3 py-2">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Assign To</label>
                    <select name="assigned_to" class="w-full border rounded px-3 py-2">
                        <option value="">Unassigned</option>
                        @foreach ($team->members()->get() as $member)
                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold mb-2">Due Date</label>
                    <input type="date" name="due_date"
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Create Task
                    </button>
                    <button type="button" onclick="closeCreateTaskModal()"
                        class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div id="editTaskModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <h2 class="text-2xl font-bold mb-4">Edit Task</h2>

            <form id="editTaskForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_task_id" name="task_id">

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Task Title</label>
                    <input type="text" id="edit_title" name="title" required
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Description</label>
                    <textarea id="edit_description" name="description" rows="3"
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Priority</label>
                    <select id="edit_priority" name="priority" class="w-full border rounded px-3 py-2">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Assign To</label>
                    <select id="edit_assigned_to" name="assigned_to" class="w-full border rounded px-3 py-2">
                        <option value="">Unassigned</option>
                        @foreach ($team->members()->get() as $member)
                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold mb-2">Due Date</label>
                    <input type="date" id="edit_due_date" name="due_date"
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Update Task
                    </button>
                    <button type="button" onclick="closeEditTaskModal()"
                        class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                </div>
            </form>



        </div>

        <!-- === PASS DATA TO JAVASCRIPT === -->
        <script>
            window.kanbanConfig = {
                teamId: "{{ $team->id }}",
                projectId: "{{ $project->id }}",
                csrfToken: "{{ csrf_token() }}",
                baseUrl: "{{ url('/') }}",
                routes: {
                    createTask: "{{ route('tasks.store', [$team, $project]) }}",
                    updateStatusBase: "/team/{{ $team->id }}/projects/{{ $project->id }}/tasks",
                    taskModalBase: "/team/{{ $team->id }}/projects/{{ $project->id }}/tasks"
                }
            };
        </script>

    @endsection
