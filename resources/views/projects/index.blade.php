@extends('layouts.app')
@section('title', 'Projects')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b mb-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

                    <!-- Title and Breadcrumb-lite -->
                    <div>
                        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                            Projects
                        </h1>
                        <p class="text-gray-500 max-w-2xl mt-1 leading-relaxed">
                            Manage and track all projects for <strong>{{ $team->name }}</strong>.
                        </p>
                    </div>

                    <!-- Action Button -->
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <a href="{{ route('projects.create', $team) }}"
                            class="flex-1 md:flex-none text-center px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                            <span class="mr-1">+</span> New Project
                        </a>
                    </div>


                </div>
            </div>
        </div>
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">


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
