@extends('layouts.app')
@section('title', $project->name . ' - Kanban Board')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-dark-bg">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b mb-10 dark:bg-dark-card">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

                    <!-- Title and Task Count -->
                    <div>
                        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight dark:text-gray-400">
                            {{ $project->name }}
                        </h1>
                        <p class="text-gray-500 max-w-2xl mt-1 leading-relaxed">
                            {{ $project->description }}
                        </p>
                        <p class="text-gray-500 max-w-2xl mt-1 leading-relaxed">
                            {{ $project->tasks()->count() }} tasks
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <!-- Edit Button (Secondary Style) -->
                        <a href="{{ route('projects.edit', [$team, $project]) }}"
                            class="flex-1 md:flex-none text-center px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                            Edit
                        </a>

                        <a href="{{ route('analytics.project', [$team, $project]) }}"
                            class="flex-1 md:flex-none text-center px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                            Analytics
                        </a>

                        <!-- New Task Button (Primary Style) -->
                        <button onclick="openCreateTaskModal('todo')"
                            class="flex-1 md:flex-none text-center px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all shadow-sm">
                            <span class="mr-1">+</span> New Task
                        </button>

                    </div>

                </div>
            </div>
        </div>

        <!-- Kanban Board -->
        <div class="grid grid-cols-3 gap-6 p-6">
            <!-- To Do Column -->
            <div class="bg-gray-100 rounded-lg p-4 dark:bg-dark-card" data-status="todo">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-400">To Do</h2>
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
                                    <img src="{{ $task->assignee && $task->assignee->avatar
                                        ? asset('storage/' . $task->assignee->avatar)
                                        : 'https://ui-avatars.com/api/?background=random&name=' . urlencode($task->assignee->name ?? 'User') }}"
                                        alt="{{ $task->assignee->name ?? 'User' }}"
                                        class="w-7 h-7 rounded-full border-2 border-white shadow-sm"
                                        title="{{ $task->assignee->name ?? 'User' }}">
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
            <div class="bg-gray-100 rounded-lg p-4 dark:bg-dark-card" data-status="in_progress">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-400">In Progress</h2>
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
                                    <img src="{{ $task->assignee && $task->assignee->avatar
                                        ? asset('storage/' . $task->assignee->avatar)
                                        : 'https://ui-avatars.com/api/?background=random&name=' . urlencode($task->assignee->name ?? 'User') }}"
                                        alt="{{ $task->assignee->name ?? 'User' }}"
                                        class="w-7 h-7 rounded-full border-2 border-white shadow-sm"
                                        title="{{ $task->assignee->name ?? 'User' }}">
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

                <button onclick="openCreateTaskModal('in_progress')"
                    class="w-full mt-3 text-gray-600 hover:text-gray-900 font-bold py-2 text-sm">
                    + Add Task
                </button>
            </div>

            <!-- Done Column -->
            <div class="bg-gray-100 rounded-lg p-4 dark:bg-dark-card" data-status="done">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-400">Done</h2>
                    <span class="bg-green-300 text-green-700 px-3 py-1 rounded text-sm font-semibold">
                        {{ isset($tasks['done']) ? $tasks['done']->count() : 0 }}
                    </span>
                </div>

                <div class="space-y-3 min-h-96" id="done-column" data-status="done">
                    @forelse(isset($tasks['done']) ? $tasks['done'] : [] as $task)
                        <div class="group bg-white rounded-lg shadow p-4 cursor-move hover:shadow-lg transition task-card relative"
                            draggable="true" data-id="{{ $task->id }}" onclick="openTaskModal('{{ $task->id }}')">

                            <div class="flex justify-between items-start mb-2">
                                <h3
                                    class="task-title font-bold text-gray-900 text-sm {{ $task->status === 'done' ? 'line-through opacity-50' : '' }}">
                                    {{ $task->title }}</h3>
                                <!-- Edit Button Added -->
                                <button onclick="openEditModal({{ $task->id }})"
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
                                    <img src="{{ $task->assignee && $task->assignee->avatar
                                        ? asset('storage/' . $task->assignee->avatar)
                                        : 'https://ui-avatars.com/api/?background=random&name=' . urlencode($task->assignee->name ?? 'User') }}"
                                        alt="{{ $task->assignee->name ?? 'User' }}"
                                        class="w-7 h-7 rounded-full border-2 border-white shadow-sm"
                                        title="{{ $task->assignee->name ?? 'User' }}">
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


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            console.log('✅ Laravel Echo initialized with Reverb');

            const teamId = '{{ $team->id }}';
            const projectId = '{{ $project->id }}';
            const channelName = `team.${teamId}.project.${projectId}`;

            console.log('🔗 Subscribing to:', channelName);

            const channel = window.Echo.private(channelName);

            channel.subscribed(() => {
                console.log('✅ Successfully subscribed to channel');
            });

            // Real-time Listeners
            channel
                .listen('TaskCreated', (event) => {
                    console.log('✅ TaskCreated received:', event);
                    window.showToast(event.message || 'New task created!', 'success');
                    setTimeout(() => location.reload(), 800);
                })
                .listen('.TaskCreated', (event) => {
                    console.log('✅ .TaskCreated received:', event);
                    window.showToast(event.message || 'New task created!', 'success');
                    setTimeout(() => location.reload(), 800);
                })

                .listen('TaskUpdated', (event) => {
                    console.log('✅ TaskUpdated received:', event);
                    window.showToast(event.message || 'Task updated!', 'info');
                    updateTaskInBoard(event.task);
                })
                .listen('.TaskUpdated', (event) => {
                    console.log('✅ .TaskUpdated received:', event);
                    window.showToast(event.message || 'Task updated!', 'info');
                    updateTaskInBoard(event.task);
                })

                .listen('TaskStatusChanged', (event) => {
                    console.log('✅ TaskStatusChanged received:', event);
                    window.showToast(event.message || 'Task moved!', 'info');
                    moveTaskBetweenColumns(event.task, event.old_status, event.new_status);
                })
                .listen('.TaskStatusChanged', (event) => {
                    console.log('✅ .TaskStatusChanged received:', event);
                    window.showToast(event.message || 'Task moved!', 'info');
                    moveTaskBetweenColumns(event.task, event.old_status, event.new_status);
                })

                .listen('TaskDeleted', (event) => {
                    console.log('✅ TaskDeleted received:', event);
                    window.showToast(event.message || 'Task deleted!', 'warning');
                    removeTaskFromBoard(event.task_id);
                })
                .listen('.TaskDeleted', (event) => {
                    console.log('✅ .TaskDeleted received:', event);
                    window.showToast(event.message || 'Task deleted!', 'warning');
                    removeTaskFromBoard(event.task_id);
                })

                .error((error) => {
                    console.error('❌ Echo Error:', error);
                });

            // Helper Functions
            function updateTaskInBoard(task) {
                const taskCard = document.querySelector(`[data-id="${task.id}"]`);
                if (taskCard) {
                    const title = taskCard.querySelector('h3');
                    const desc = taskCard.querySelector('p');
                    if (title) title.textContent = task.title;
                    if (desc) desc.textContent = task.description || '';
                }
            }

            function moveTaskBetweenColumns(task, oldStatus, newStatus) {
                const taskCard = document.querySelector(`[data-id="${task.id}"]`);
                if (!taskCard) {
                    console.warn('Task card not found for moving');
                    return;
                }

                const oldColumn = document.getElementById(`${oldStatus}-column`);
                const newColumn = document.getElementById(`${newStatus}-column`);

                if (!newColumn) {
                    console.warn(`New column "${newStatus}-column" not found`);
                    return;
                }

                // Move the card with smooth animation
                taskCard.style.transition = 'all 0.3s ease';
                taskCard.style.opacity = '0.6';

                setTimeout(() => {
                    // Move to new column
                    if (oldColumn) {
                        oldColumn.removeChild(taskCard);
                    }
                    newColumn.appendChild(taskCard);

                    // Reset style
                    taskCard.style.opacity = '1';
                    taskCard.style.transform = '';

                    // Update counts and empty states
                    updateColumnCountsAndEmptyStates();
                }, 150);
            }

            // ==================== Helper Function ====================
            function updateColumnCountsAndEmptyStates() {
                const statuses = ['todo', 'in_progress', 'done'];

                statuses.forEach(status => {
                    const column = document.getElementById(`${status}-column`);
                    if (!column) return;

                    const countBadge = document.querySelector(`[data-status="${status}"] span`);
                    const taskCount = column.children.length;

                    // Update count badge
                    if (countBadge) {
                        countBadge.textContent = taskCount;
                    }

                    // Handle "No tasks yet" message
                    let emptyMsg = column.querySelector('.text-center.py-8');

                    if (taskCount === 0) {
                        if (!emptyMsg) {
                            const emptyHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-2xl mb-2"></i>
                        <p>No tasks yet</p>
                    </div>
                `;
                            column.insertAdjacentHTML('beforeend', emptyHTML);
                        }
                    } else {
                        if (emptyMsg) emptyMsg.remove();
                    }
                });
            }

            function removeTaskFromBoard(taskId) {

                const taskCard = document.querySelector(`[data-id="${taskId}"]`);

                if (taskCard) {
                    taskCard.style.transition = 'all 0.3s ease';
                    taskCard.style.opacity = '0';
                    taskCard.style.transform = 'scale(0.95)';
                    setTimeout(() => taskCard.remove(), 300);
                }
            }


        });
    </script>
