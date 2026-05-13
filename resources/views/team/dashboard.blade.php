@extends('layouts.app')
@section('title', $team->name . ' - Dashboard')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $team->name }}</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ $team->description }}</p>
                    </div>
                    @if (auth()->user()->isTeamAdmin($team))
                        <a href="{{ route('team.settings', $team) }}"
                            class="flex-1 md:flex-none text-center px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                            ⚙️ Settings
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Members Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-gray-600 text-sm font-medium">Team Members</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $members }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">👥</span>
                        </div>
                    </div>
                    <a href="{{ route('team.members', $team) }}"
                        class="mt-4 inline-block text-blue-500 hover:text-blue-700 text-sm font-medium">
                        Manage Members →
                    </a>
                </div>

                <!-- Projects Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-gray-600 text-sm font-medium">Projects</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $projects }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">📁</span>
                        </div>
                    </div>
                    <a href="{{ route('projects.index', $team) }}"
                        class="mt-4 inline-block text-blue-500 hover:text-blue-700 text-sm font-medium">
                        View Projects →
                    </a>
                </div>

                <!-- Tasks Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-gray-600 text-sm font-medium">Active Tasks</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $tasks }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">✓</span>
                        </div>
                    </div>
                    <a href="{{ route('projects.index', $team) }}"
                        class="mt-4 inline-block text-blue-500 hover:text-blue-700 text-sm font-medium">
                        View Tasks →
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('projects.create', $team) }}"
                        class="p-4 border rounded-lg hover:bg-blue-50 transition">
                        <p class="font-bold">📁 Create Project</p>
                        <p class="text-sm text-gray-600">Start a new project</p>
                    </a>
                    <a href="{{ route('team.invite-form', $team) }}"
                        class="p-4 border rounded-lg hover:bg-blue-50 transition">
                        <p class="font-bold">👥 Invite Member</p>
                        <p class="text-sm text-gray-600">Add team members</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
