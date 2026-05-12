@extends('layouts.app')
@section('title', 'Projects')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Projects</h1>
                <a href="{{ route('projects.create', $team) }}"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    + New Project
                </a>
            </div>

            @if ($projects->isEmpty())
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <p class="text-gray-600 mb-4">No projects yet</p>
                    <a href="{{ route('projects.create', $team) }}" class="text-blue-500 hover:text-blue-700">
                        Create your first project →
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($projects as $project)
                        <div class="bg-white rounded-lg shadow hover:shadow-lg transition cursor-pointer"
                            onclick="window.location.href='{{ route('projects.show', [$team, $project]) }}'">
                            <div class="h-2" style="background-color: {{ $project->color }}"></div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $project->name }}</h3>
                                <p class="text-gray-600 text-sm mb-4">{{ $project->description }}</p>
                                <div class="flex justify-between items-center text-sm text-gray-500">
                                    <span>{{ $project->tasks()->count() }} tasks</span>
                                    <span>by {{ $project->creator->name }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{ $projects->links() }}
            @endif
        </div>
    </div>
@endsection
