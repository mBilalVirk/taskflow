@extends('layouts.app')

@section('header', auth()->user()->currentTeam()->name . ' - Dashboard')

@section('content')
    <div class="p-6 space-y-6">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-8 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                    <p class="text-blue-100">Here's what's happening with your team today.</p>
                </div>
                <div class="text-5xl opacity-20">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
        </div>

        @php
            $team = auth()->user()->currentTeam();
            $memberCount = $team->members()->count();
            $projectCount = $team->projects()->count();
            $taskCount = $team->projects()->withCount('tasks')->get()->sum('tasks_count');
            $completedTasks = $team
                ->projects()
                ->with('tasks')
                ->get()
                ->flatMap(function ($project) {
                    return $project->tasks;
                })
                ->where('status', 'done')
                ->count();
        @endphp

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Tasks Card -->
            <div class="bg-white rounded-lg shadow p-6 card-hover border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Tasks</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $taskCount }}</p>
                        <p class="text-xs text-gray-500 mt-1">Across all projects</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-tasks text-blue-500 text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Completed Tasks Card -->
            <div class="bg-white rounded-lg shadow p-6 card-hover border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Completed</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $completedTasks }}</p>
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
            <div class="bg-white rounded-lg shadow p-6 card-hover border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Active Projects</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $projectCount }}</p>
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
            <div class="bg-white rounded-lg shadow p-6 card-hover border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Team Members</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $memberCount }}</p>
                        <p class="text-xs text-orange-600 mt-1">{{ $team->members_limit - $memberCount }} slots available
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-orange-500 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('projects.create', $team) }}"
                        class="flex items-center p-3 rounded-lg hover:bg-blue-50 transition">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-folder-plus text-blue-500"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-gray-900">Create Project</p>
                            <p class="text-xs text-gray-500">Start a new project</p>
                        </div>
                    </a>

                    <a href="{{ route('team.invite-form', $team) }}"
                        class="flex items-center p-3 rounded-lg hover:bg-green-50 transition">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user-plus text-green-500"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-gray-900">Invite Member</p>
                            <p class="text-xs text-gray-500">Add someone to the team</p>
                        </div>
                    </a>

                    <a href="{{ route('team.settings', $team) }}"
                        class="flex items-center p-3 rounded-lg hover:bg-purple-50 transition">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-cog text-purple-500"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-gray-900">Team Settings</p>
                            <p class="text-xs text-gray-500">Manage team configuration</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-orange-50 transition">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-chart-bar text-orange-500"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-gray-900">View Analytics</p>
                            <p class="text-xs text-gray-500">Check team performance</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Recent Activity</h3>
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
                        <div class="flex items-start space-x-4 p-3 rounded-lg hover:bg-gray-50 transition">
                            <div class="w-2 h-2 mt-2 rounded-full" style="background-color: {{ $task->project->color }}">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900">{{ $task->title }}</p>
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

        <!-- Projects Overview -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Your Projects</h3>
                <a href="{{ route('projects.index', $team) }}"
                    class="text-blue-500 hover:text-blue-700 text-sm font-semibold">
                    View All →
                </a>
            </div>

            @php
                $projects = $team->projects()->take(6)->get();
            @endphp

            @if ($projects->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-folder-open text-5xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 mb-4">No projects yet</p>
                    <a href="{{ route('projects.create', $team) }}"
                        class="inline-block bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                        Create First Project
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($projects as $project)
                        <a href="{{ route('projects.show', [$team, $project]) }}"
                            class="border rounded-lg p-4 hover:shadow-lg transition">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $project->color }}"></div>
                                <h4 class="font-semibold text-gray-900 text-sm">{{ $project->name }}</h4>
                            </div>
                            <p class="text-gray-600 text-xs mb-3 line-clamp-2">{{ $project->description }}</p>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>{{ $project->tasks()->count() }} tasks</span>
                                <span>
                                    @php
                                        $completed = $project->tasks()->where('status', 'done')->count();
                                        $total = $project->tasks()->count();
                                        $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;
                                    @endphp
                                    {{ $percentage }}% done
                                </span>
                            </div>
                            <div class="mt-3 w-full bg-gray-200 rounded-full h-1.5">
                                <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Team Members Overview -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Team Members</h3>
                <a href="{{ route('team.members', $team) }}"
                    class="text-blue-500 hover:text-blue-700 text-sm font-semibold">
                    Manage Team →
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $members = $team->members()->take(8)->get();
                @endphp

                @forelse($members as $member)
                    <div class="border rounded-lg p-4 text-center hover:shadow-lg transition">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg mx-auto mb-3">
                            {{ substr($member->name, 0, 1) }}
                        </div>
                        <h4 class="font-semibold text-gray-900 text-sm">{{ $member->name }}</h4>
                        <p class="text-xs text-gray-500 mb-3">{{ $member->email }}</p>
                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">
                            {{ ucfirst($member->pivot->role) }}
                        </span>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8">
                        <p class="text-gray-500 mb-4">No team members yet</p>
                        <a href="{{ route('team.invite-form', $team) }}"
                            class="text-blue-500 hover:text-blue-700 text-sm font-semibold">
                            Invite your first member →
                        </a>
                    </div>
                @endforelse
            </div>

            @if ($team->canAddMembers())
                <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200 text-center">
                    <p class="text-sm text-gray-700 mb-3">
                        You can add <strong>{{ $team->members_limit - $team->members()->count() }}</strong> more member(s)
                        to your plan
                    </p>
                    <a href="{{ route('team.invite-form', $team) }}"
                        class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm font-semibold">
                        Invite Member
                    </a>
                </div>
            @else
                <div class="mt-4 p-4 bg-orange-50 rounded-lg border border-orange-200 text-center">
                    <p class="text-sm text-gray-700 mb-3">
                        You've reached the member limit for your plan
                    </p>
                    <a href="#"
                        class="inline-block bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 text-sm font-semibold">
                        Upgrade Plan
                    </a>
                </div>
            @endif
        </div>

        <!-- Subscription Info -->
        @php
            $subscription = $team->subscription;
        @endphp
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Current Plan</h3>
                    <p class="text-gray-600 mt-1">
                        <span class="text-2xl font-bold">{{ ucfirst($subscription->plan) }}</span>
                        <span class="text-gray-500 ml-2">
                            @if ($subscription->status === 'active')
                                • Active until
                                {{ $subscription->current_period_end ? $subscription->current_period_end->format('M d, Y') : '' }}
                            @else
                                • {{ ucfirst($subscription->status) }}
                            @endif
                        </span>
                    </p>
                </div>
                <a href="#" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 font-semibold">
                    Manage Billing
                </a>
            </div>
        </div>
    </div>
@endsection
