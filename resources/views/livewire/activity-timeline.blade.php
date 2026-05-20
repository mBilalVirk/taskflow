<div class="p-6 bg-white rounded-xl border border-slate-100 shadow-sm">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
            <i class="fas fa-history text-slate-400 text-sm"></i>
            Activity Timeline
        </h3>
    </div>

    <!-- Filters -->
    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 mb-8">
        <!-- Search -->
        <div class="relative sm:col-span-8">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <i class="fas fa-search text-slate-400 text-sm"></i>
            </div>
            <input type="text" wire:model.live="searchTerm" placeholder="Search activities..."
                class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all placeholder:text-slate-400 text-slate-700">
        </div>

        <!-- Filter by Action -->
        <div class="relative sm:col-span-4">
            <select wire:model="filter"
                class="w-full appearance-none px-4 py-2 pr-10 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all text-slate-700 font-medium">
                <option value="all">All Actions</option>
                <option value="created">Created</option>
                <option value="updated">Updated</option>
                <option value="deleted">Deleted</option>
                <option value="commented">Commented</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>

    <!-- Activities List -->
    <div class="relative pl-6 border-l-2 border-slate-100 ml-4 space-y-6">
        @forelse($this->activities as $activity)
            @php
                // Contextual mapping for crisp action markers
                $badgeStyle = match ($activity->action) {
                    'created' => [
                        'bg' => 'bg-emerald-50',
                        'text' => 'text-emerald-700',
                        'border' => 'border-emerald-200/60',
                        'icon' => 'fa-plus',
                    ],
                    'updated' => [
                        'bg' => 'bg-amber-50',
                        'text' => 'text-amber-700',
                        'border' => 'border-amber-200/60',
                        'icon' => 'fa-pen',
                    ],
                    'deleted' => [
                        'bg' => 'bg-rose-50',
                        'text' => 'text-rose-700',
                        'border' => 'border-rose-200/60',
                        'icon' => 'fa-trash',
                    ],
                    'commented' => [
                        'bg' => 'bg-blue-50',
                        'text' => 'text-blue-700',
                        'border' => 'border-blue-200/60',
                        'icon' => 'fa-comment-alt',
                    ],
                    default => [
                        'bg' => 'bg-slate-50',
                        'text' => 'text-slate-700',
                        'border' => 'border-slate-200/60',
                        'icon' => 'fa-info-circle',
                    ],
                };
            @endphp

            <div class="relative group">
                <!-- Timeline Intersect Dot/Icon -->
                <div
                    class="absolute -left-[35px] top-0.5 flex items-center justify-center w-6 h-6 rounded-full bg-white border-2 border-slate-100 group-hover:border-indigo-500 transition-colors duration-200">
                    <i
                        class="fas {{ $badgeStyle['icon'] }} text-[10px] text-slate-400 group-hover:text-indigo-600 transition-colors"></i>
                </div>

                <div class="flex gap-4">
                    <!-- User Avatar -->
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($activity->user->name) }}&background=EEF2F6&color=475569&bold=true"
                        class="w-9 h-9 rounded-xl flex-shrink-0 border border-slate-100 shadow-sm"
                        alt="{{ $activity->user->name }}">

                    <!-- Content Block -->
                    <div
                        class="flex-1 min-w-0 bg-slate-50/40 hover:bg-slate-50/80 border border-transparent hover:border-slate-100 p-3.5 rounded-xl transition-all duration-200">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5">
                            <div>
                                <span class="font-semibold text-sm text-slate-900">{{ $activity->user->name }}</span>
                                <span
                                    class="text-xs text-slate-400 ml-1.5">{{ $activity->created_at->diffForHumans() }}</span>
                            </div>

                            <!-- Status Badge -->
                            <span
                                class="inline-flex items-center self-start sm:self-center text-[11px] font-semibold px-2 py-0.5 rounded-md border {{ $badgeStyle['bg'] }} {{ $badgeStyle['text'] }} {{ $badgeStyle['border'] }}">
                                {{ ucfirst($activity->action) }}
                            </span>
                        </div>

                        <p class="text-slate-600 text-sm mt-1.5 leading-relaxed break-words">
                            {{ $activity->description }}
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <!-- Beautiful Empty State -->
            <div class="text-center py-16 -ml-6">
                <div
                    class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 text-slate-400 mb-3.5 shadow-inner">
                    <i class="fas fa-folder-open text-xl"></i>
                </div>
                <h4 class="text-sm font-semibold text-slate-800">No activities recorded</h4>
                <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Try broadening your search term or selection
                    parameters.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($this->activities->hasPages())
        <div class="mt-8 pt-4 border-t border-slate-100">
            {{ $this->activities->links() }}
        </div>
    @endif
</div>
