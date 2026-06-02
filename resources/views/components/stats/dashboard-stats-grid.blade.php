<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Total Tasks Card -->
    <div class="bg-white rounded-lg shadow p-6 card-hover border-l-4 border-blue-500 dark:bg-dark-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Tasks</p>
                <p class="text-3xl font-bold text-gray-900 mt-2 dark:text-gray-400">{{ $taskCount }}</p>
                <p class="text-xs text-gray-500 mt-1">Across all projects</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-tasks text-blue-500 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Completed Tasks Card -->
    <div class="bg-white rounded-lg shadow p-6 card-hover border-l-4 border-green-500 dark:bg-dark-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium ">Completed</p>
                <p class="text-3xl font-bold text-gray-900 mt-2  dark:text-gray-400">{{ $completedTasks }}</p>
                <p class="text-xs text-green-600 mt-1">
                    {{ $taskCount > 0 ? round(($completedTasks / $taskCount) * 100) : 0 }}% completion rate
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-green-500 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Active Projects Card -->
    <div class="bg-white rounded-lg shadow p-6 card-hover border-l-4 border-purple-500 dark:bg-dark-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Active Projects</p>
                <p class="text-3xl font-bold text-gray-900 mt-2  dark:text-gray-400">{{ $projectCount }}</p>
                <a href="{{ route('projects.index', $team) }}"
                    class="text-xs text-purple-600 hover:text-purple-700 mt-1 inline-block">
                    View all →
                </a>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-folder text-purple-500 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Team Members Card -->
    <div class="bg-white rounded-lg shadow p-6 card-hover border-l-4 border-orange-500 dark:bg-dark-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Team Members</p>
                <p class="text-3xl font-bold text-gray-900 mt-2  dark:text-gray-400">{{ $memberCount }}</p>
                <p class="text-xs text-orange-600 mt-1">{{ $team->members_limit - $memberCount }} slots available
                </p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-orange-500 text-lg"></i>
            </div>
        </div>
    </div>
</div>
