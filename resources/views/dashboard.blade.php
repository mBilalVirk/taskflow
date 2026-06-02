@extends('layouts.app')

@section('header', auth()->user()->currentTeam()->name . ' - Dashboard')
@section('title', 'Dashboard') {{-- You can customize this title as needed --}}
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
            \Log::debug('Current Team: ' . ($team ? $team->id : 'No team found'));
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
        @include('components.stats.dashboard-stats-grid')

        <!-- Quick Actions & Recent Activity -->
        @include('components.stats.quick-recent-activity')

        <!-- Projects Overview -->
        @include('components.stats.project')

        <!-- Team Members Overview -->
        @include('components.stats.team')

        <!-- Subscription Info -->
        @include('components.subscription.subscription')
    </div>
@endsection
