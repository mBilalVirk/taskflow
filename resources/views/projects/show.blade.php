@extends('layouts.app')

@section('title', $project->name)

@section('content')
    <div class="min-h-screen bg-gray-50">
        {{-- <!-- Breadcrumbs & Navigation -->
        <nav class="flex mb-4 text-sm text-gray-500" aria-label="Breadcrumb">
            <a href="{{ route('projects.index', $team) }}" class="hover:text-blue-600 transition">Projects</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 font-medium">{{ $project->name }}</span>
        </nav> --}}


        <!-- Header -->
        <div class="bg-white shadow-sm border-b mb-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

                    <!-- Project Info & Icon -->
                    <div class="flex items-center gap-4">
                        {{-- Project Color Icon --}}
                        <div class="w-12 h-12 rounded-xl shadow-inner flex-shrink-0"
                            style="background-color: {{ $project->color }}; border: 4px solid white; box-shadow: 0 0 0 1px rgba(0,0,0,0.1);">
                        </div>

                        <div>
                            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                                {{ $project->name }}
                            </h1>
                            <p class="text-gray-500 max-w-2xl mt-1 leading-relaxed">
                                {{ $project->description ?: 'No description provided.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if (auth()->user()->can('update', $project))
                        <div class="flex items-center gap-3 w-full md:w-auto">
                            <a href="{{ route('projects.edit', [$team, $project]) }}"
                                class="flex-1 md:flex-none text-center px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                                ➕ Edit Project
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- Task Board (Kanban Style) -->
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                    $statusConfig = [
                        'todo' => [
                            'label' => 'To Do',
                            'color' => 'bg-slate-100 text-slate-700',
                            'dot' => 'bg-slate-400',
                        ],
                        'in_progress' => [
                            'label' => 'In Progress',
                            'color' => 'bg-blue-50 text-blue-700',
                            'dot' => 'bg-blue-500',
                        ],
                        'done' => [
                            'label' => 'Completed',
                            'color' => 'bg-emerald-50 text-emerald-700',
                            'dot' => 'bg-emerald-500',
                        ],
                    ];
                @endphp

                @foreach ($statusConfig as $key => $config)
                    <div class="flex flex-col h-full">
                        <!-- Column Header -->
                        <div class="flex items-center justify-between mb-4 px-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $config['dot'] }}"></span>
                                <h3 class="font-bold text-gray-700 uppercase tracking-wider text-sm">
                                    {{ $config['label'] }}
                                </h3>
                                <span class="ml-2 px-2 py-0.5 text-xs font-bold rounded-full {{ $config['color'] }}">
                                    {{ $tasksByStatus->has($key) ? $tasksByStatus[$key]->count() : 0 }}
                                </span>
                            </div>
                        </div>

                        <!-- Column Content -->
                        <div
                            class="bg-gray-50/50 rounded-2xl p-4 flex-1 border border-dashed border-gray-200 min-h-[500px]">
                            <div class="space-y-4">
                                @if (isset($tasksByStatus[$key]) && $tasksByStatus[$key]->count() > 0)
                                    @foreach ($tasksByStatus[$key] as $task)
                                        <div
                                            class="group bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:border-blue-300 hover:shadow-md transition-all cursor-pointer">
                                            <h4
                                                class="text-gray-900 font-semibold group-hover:text-blue-600 transition-colors">
                                                {{ $task->title }}
                                            </h4>

                                            <div class="flex items-center justify-between mt-4">
                                                @if ($task->assignee)
                                                    <div class="flex items-center gap-2">
                                                        <div
                                                            class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-600 uppercase">
                                                            {{ substr($task->assignee->name, 0, 2) }}
                                                        </div>
                                                        <span
                                                            class="text-xs text-gray-500 font-medium">{{ $task->assignee->name }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Unassigned</span>
                                                @endif

                                                <span class="text-[10px] text-gray-400">#{{ $task->id }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex flex-col items-center justify-center py-12 text-center">
                                        <div
                                            class="w-12 h-12 rounded-full bg-white mb-3 flex items-center justify-center shadow-sm">
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-60H6" />
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-400">Empty</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Add Task Button -->
                            <button
                                class="w-full mt-4 py-2 flex items-center justify-center gap-2 text-sm font-medium text-gray-500 hover:text-blue-600 hover:bg-white rounded-lg transition-all group">
                                <span class="text-lg group-hover:scale-110 transition-transform">+</span>
                                Add Task
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
