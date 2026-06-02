<!-- Quick Actions & Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6 dark:bg-dark-card">
        <h3 class="text-lg font-bold text-gray-900 mb-4 dark:text-gray-400">Quick Actions</h3>
        <div class="space-y-3">
            <a href="{{ route('projects.create', $team) }}"
                class="flex items-center p-3 rounded-lg hover:bg-blue-50 transition dark:hover:bg-dark-hover">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-folder-plus text-blue-500"></i>
                </div>
                <div>
                    <p class="font-semibold text-sm text-gray-900 dark:text-gray-400">Create Project</p>
                    <p class="text-xs text-gray-500">Start a new project</p>
                </div>
            </a>

            <a href="{{ route('team.invite-form', $team) }}"
                class="flex items-center p-3 rounded-lg hover:bg-green-50 transition dark:hover:bg-dark-hover">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-user-plus text-green-500"></i>
                </div>
                <div>
                    <p class="font-semibold text-sm text-gray-900 dark:text-gray-400">Invite Member</p>
                    <p class="text-xs text-gray-500">Add someone to the team</p>
                </div>
            </a>

            <a href="{{ route('team.settings', $team) }}"
                class="flex items-center p-3 rounded-lg hover:bg-purple-50 transition dark:hover:bg-dark-hover">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-cog text-purple-500"></i>
                </div>
                <div>
                    <p class="font-semibold text-sm text-gray-900 dark:text-gray-400">Team Settings</p>
                    <p class="text-xs text-gray-500">Manage team configuration</p>
                </div>
            </a>

            <a href="#"
                class="flex items-center p-3 rounded-lg hover:bg-orange-50 transition dark:hover:bg-dark-hover">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-chart-bar text-orange-500"></i>
                </div>
                <div>
                    <p class="font-semibold text-sm text-gray-900 dark:text-gray-400">View Analytics</p>
                    <p class="text-xs text-gray-500">Check team performance</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6 dark:bg-dark-card">
        <h3 class="text-lg font-bold text-gray-900 mb-4 dark:text-gray-400">Recent Activity</h3>
        <div class="space-y-4">
            @php
                // Get recent tasks
                $recentTasks = $team
                    ->projects()
                    ->with('tasks')
                    ->get()
                    ->flatMap(function ($project) {
                        return $project->tasks->map(function ($task) use ($project) {
                            $task->project = $project;
                            return $task;
                        });
                    })
                    ->sortByDesc('created_at')
                    ->take(5);
            @endphp

            @forelse($recentTasks as $task)
                <div
                    class="flex items-start space-x-4 p-3 rounded-lg hover:bg-gray-50 transition dark:hover:bg-dark-hover">
                    <div class="w-2 h-2 mt-2 rounded-full" style="background-color: {{ $task->project->color }}">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-400 ">
                            {{ $task->title }}</p>
                        <p class="text-xs text-gray-500">
                            in <span class="font-semibold">{{ $task->project->name }}</span>
                            • {{ $task->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <span class="px-2 py-1 rounded text-xs font-semibold"
                        style="background-color: {{ $task->project->color }}20; color: {{ $task->project->color }}">
                        {{ ucfirst($task->status) }}
                    </span>
                </div>
            @empty
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No recent activity</p>
                    <a href="{{ route('projects.create', $team) }}"
                        class="text-blue-500 hover:text-blue-700 text-sm mt-2 inline-block">
                        Create your first project →
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
