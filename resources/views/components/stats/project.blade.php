<!-- Projects Overview -->
<div class="bg-white rounded-lg shadow p-6 dark:bg-dark-card">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-400">Your Projects</h3>
        <a href="{{ route('projects.index', $team) }}" class="text-blue-500 hover:text-blue-700 text-sm font-semibold">
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
                    class="border rounded-lg p-4 hover:shadow-lg transition dark:border-gray-400">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-3 h-3 rounded-full" style="background-color: {{ $project->color }}"></div>
                        <h4 class="font-semibold text-gray-900 text-sm dark:text-gray-400">{{ $project->name }}</h4>
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
