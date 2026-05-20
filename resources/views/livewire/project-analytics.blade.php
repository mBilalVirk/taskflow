<div class="">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold">{{ $project->name }} - Analytics</h1>
        <button wire:click="exportCsv" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
            <i class="fas fa-download mr-2"></i>Export CSV
        </button>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Tasks -->
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm font-semibold">Total Tasks</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $analytics['total_tasks'] }}</p>
        </div>

        <!-- Completion Rate -->
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm font-semibold">Completion Rate</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $analytics['completion_rate'] }}%</p>
        </div>

        <!-- Overdue Tasks -->
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm font-semibold">Overdue Tasks</p>
            <p class="text-3xl font-bold text-red-600 mt-2">{{ $analytics['overdue_tasks'] }}</p>
        </div>
    </div>

    <!-- Task Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- By Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4 text-gray-900">Tasks by Status</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-700">To Do</span>
                    <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-bold">
                        {{ $analytics['by_status']['todo'] }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-700">In Progress</span>
                    <span class="bg-blue-200 text-blue-700 px-3 py-1 rounded-full text-sm font-bold">
                        {{ $analytics['by_status']['in_progress'] }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-700">Done</span>
                    <span class="bg-green-200 text-green-700 px-3 py-1 rounded-full text-sm font-bold">
                        {{ $analytics['by_status']['done'] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- By Priority -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4 text-gray-900">Tasks by Priority</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-700">Urgent</span>
                    <span class="bg-red-200 text-red-700 px-3 py-1 rounded-full text-sm font-bold">
                        {{ $analytics['by_priority']['urgent'] }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-700">High</span>
                    <span class="bg-orange-200 text-orange-700 px-3 py-1 rounded-full text-sm font-bold">
                        {{ $analytics['by_priority']['high'] }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-700">Medium</span>
                    <span class="bg-yellow-200 text-yellow-700 px-3 py-1 rounded-full text-sm font-bold">
                        {{ $analytics['by_priority']['medium'] }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-700">Low</span>
                    <span class="bg-blue-200 text-blue-700 px-3 py-1 rounded-full text-sm font-bold">
                        {{ $analytics['by_priority']['low'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks by Assignee -->
    {{-- <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold mb-4 text-gray-900">Tasks by Assignee</h3>
        <div class="space-y-3">
            @forelse($analytics['tasks_by_assignee'] as $assignee)
                <div class="flex justify-between items-center pb-3 border-b">
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($assignee['name']) }}"
                            class="w-8 h-8 rounded-full" alt="{{ $assignee['name'] }}">
                        <span class="font-semibold text-gray-700">{{ $assignee['name'] }}</span>
                    </div>
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold">
                        {{ $assignee['count'] }} tasks
                    </span>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No tasks assigned</p>
            @endforelse
        </div>
    </div> --}}

</div>
