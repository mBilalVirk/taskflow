<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b mb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

                <!-- Title and Description -->
                <div>
                    <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                        Team Analytics
                    </h1>
                    <p class="text-gray-500 max-w-2xl mt-1 leading-relaxed">
                        Monitor project metrics, progression trends, and team contributions for
                        <strong>{{ $team->name }}</strong>.
                    </p>
                </div>

                <!-- Action Area / Time Period Selector -->
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div
                        class="flex-1 md:flex-none flex items-center justify-between md:justify-start gap-3 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-lg shadow-sm">
                        <label for="period"
                            class="text-xs font-bold uppercase tracking-wider text-gray-400 select-none">Period:</label>
                        <div class="relative flex items-center">
                            <select id="period" wire:model.live="period"
                                class="bg-transparent text-sm font-semibold text-gray-700 focus:outline-none pr-6 cursor-pointer appearance-none">
                                <option value="7">Last 7 days</option>
                                <option value="30">Last 30 days</option>
                                <option value="90">Last 90 days</option>
                                <option value="365">Last year</option>
                            </select>

                            <!-- Subtle Loading Spinner inline with dropdown -->
                            <div wire:loading wire:target="period"
                                class="absolute right-0 animate-spin h-4 w-4 text-blue-500 border-2 border-blue-500 border-t-transparent rounded-full">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Main Content Container matching the project index footprint -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Overview Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Tasks Card -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 transition-all duration-200 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-gray-500 text-sm font-medium tracking-wide">Total Tasks</p>
                        <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ $totalTasks }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <i class="fas fa-tasks text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Completed Tasks Card -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 transition-all duration-200 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-gray-500 text-sm font-medium tracking-wide">Completed</p>
                        <p class="text-3xl font-bold text-emerald-600 tracking-tight">{{ $completedTasks }}</p>
                    </div>
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- In Progress Card -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 transition-all duration-200 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-gray-500 text-sm font-medium tracking-wide">In Progress</p>
                        <p class="text-3xl font-bold text-amber-500 tracking-tight">{{ $inProgressTasks }}</p>
                    </div>
                    <div class="p-3 bg-amber-50 text-amber-500 rounded-xl">
                        <i class="fas fa-spinner animate-spin-slow text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Completion Rate Card -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 transition-all duration-200 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-gray-500 text-sm font-medium tracking-wide">Completion Rate</p>
                        <p class="text-3xl font-bold text-violet-600 tracking-tight">{{ $completionRate }}%</p>
                    </div>
                    <div class="p-3 bg-violet-50 text-violet-600 rounded-xl">
                        <i class="fas fa-chart-pie text-xl"></i>
                    </div>
                </div>
                <!-- Dynamic Visual Progress bar -->
                <div class="w-full bg-gray-100 h-1.5 rounded-full mt-4 overflow-hidden">
                    <div class="bg-violet-600 h-1.5 rounded-full transition-all duration-500"
                        style="width: {{ min($completionRate, 100) }}%"></div>
                </div>
            </div>
        </div>

        <!-- Detailed Analytics Splitting Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Member Activity Split -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Team Member Activity</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Total contributions grouped by individual actions</p>
                    </div>
                    <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2.5 py-1 rounded-md">Ranked</span>
                </div>
                <div class="p-6 divide-y divide-gray-100 space-y-4 max-h-[480px] overflow-y-auto">
                    @forelse($memberActivity as $activity)
                        <div class="flex justify-between items-center pt-4 first:pt-0 group">
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($activity->user->name) }}&background=EBF4FF&color=1E40AF&bold=true"
                                        class="w-9 h-9 rounded-full border border-gray-100 object-cover"
                                        alt="{{ $activity->user->name }}">
                                    <span
                                        class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                                </div>
                                <span
                                    class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">{{ $activity->user->name }}</span>
                            </div>
                            <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-lg text-xs font-bold tracking-wide">
                                {{ number_format($activity->activity_count) }} actions
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="text-gray-300 text-4xl mb-2"><i class="far fa-folder-open"></i></div>
                            <p class="text-gray-400 text-sm">No activity recorded for this period</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activity Live Feed -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Recent Activity</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Live event stream of team action indicators</p>
                </div>
                <div class="p-6 space-y-5 max-h-[480px] overflow-y-auto relative">
                    @forelse($recentActivity as $activity)
                        <div class="flex gap-4 relative group">
                            <!-- Timeline trace lines -->
                            @if (!$loop->last)
                                <span class="absolute left-5 top-10 bottom-0 w-0.5 bg-gray-100 -mb-5"></span>
                            @endif

                            <img src="https://ui-avatars.com/api/?name={{ urlencode($activity->user->name) }}&background=F3F4F6&color=4B5563"
                                class="w-10 h-10 rounded-full border border-gray-150 relative z-10 bg-white"
                                alt="">

                            <div
                                class="flex-1 bg-gray-50 rounded-xl p-3.5 group-hover:bg-gray-100/70 transition-colors">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-1">
                                    <span class="font-bold text-sm text-gray-900">{{ $activity->user->name }}</span>
                                    <span class="text-gray-400 text-[11px] font-medium"><i
                                            class="far fa-clock mr-1"></i>{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-gray-600 text-sm leading-relaxed">{{ $activity->description }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="text-gray-300 text-4xl mb-2"><i class="feather icon-activity"></i></div>
                            <p class="text-gray-400 text-sm">No events streamed yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
