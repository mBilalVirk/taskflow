@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
    <div class="py-10 px-4 sm:px-6 max-w-4xl mx-auto min-h-screen">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Notifications</h1>
                <p class="text-sm text-gray-500 mt-1">Manage and track your application alerts and updates.</p>
            </div>
        </div>

        <!-- Filter Tabs Component -->
        <div class="mb-8 border-b border-gray-200">
            <nav class="-mb-px flex gap-6" aria-label="Tabs">
                <a href="{{ route('notifications.index', ['type' => 'all']) }}"
                    class="pb-4 px-1 text-sm font-semibold border-b-2 transition-all {{ $type === 'all' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    All
                </a>
                <a href="{{ route('notifications.index', ['type' => 'unread']) }}"
                    class="pb-4 px-1 text-sm font-semibold border-b-2 transition-all relative {{ $type === 'unread' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Unread
                </a>
                <a href="{{ route('notifications.index', ['type' => 'read']) }}"
                    class="pb-4 px-1 text-sm font-semibold border-b-2 transition-all {{ $type === 'read' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Read
                </a>
            </nav>
        </div>

        <!-- Notifications Stack Container -->
        <div class="space-y-3.5">
            @forelse($notifications as $notification)
                @php
                    $isRead = (bool) $notification->read_at;

                    // Dynamic tailwind coloring wrapper assignments
                    $colorMap = [
                        'blue' => ['bg' => 'bg-blue-50 text-blue-600', 'dot' => 'bg-blue-500'],
                        'green' => ['bg' => 'bg-emerald-50 text-emerald-600', 'dot' => 'bg-emerald-500'],
                        'yellow' => ['bg' => 'bg-amber-50 text-amber-600', 'dot' => 'bg-amber-500'],
                        'red' => ['bg' => 'bg-rose-50 text-rose-600', 'dot' => 'bg-rose-500'],
                    ];
                    $theme = $colorMap[$notification->color] ?? $colorMap['blue'];
                @endphp

                <div
                    class="group relative bg-white border border-gray-100/80 rounded-2xl p-4 sm:p-5 shadow-sm transition-all duration-200 hover:shadow-md hover:border-gray-200/60 {{ !$isRead ? 'ring-1 ring-indigo-500/10 bg-gradient-to-r from-indigo-50/10 via-transparent to-transparent' : 'opacity-85' }}">
                    <div class="flex gap-4 items-start">

                        <!-- Dynamic Status & Type Icon -->
                        <div class="flex-shrink-0 relative">
                            <div
                                class="w-11 h-11 rounded-xl flex items-center justify-center transition-transform group-hover:scale-105 {{ $theme['bg'] }}">
                                <i class="fas {{ $notification->icon ?? 'fa-bell' }} text-xl"></i>
                            </div>
                            @if (!$isRead)
                                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $theme['dot'] }}"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 {{ $theme['dot'] }}"></span>
                                </span>
                            @endif
                        </div>

                        <!-- Main Core Text Frame Component -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-1">
                                <h3
                                    class="font-bold text-gray-900 text-base group-hover:text-indigo-600 transition-colors truncate">
                                    <span>{{ $notification->data['title'] ?? 'Notification' }}</span>

                                </h3>
                                <span class="text-xs font-medium text-gray-400 whitespace-nowrap">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1 leading-relaxed max-w-2xl">
                                {{ $notification->data['message'] ?? 'No message available.' }}
                            </p>

                            @if ($isRead)
                                <div class="mt-2.5">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200/40">
                                        <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Read
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Interactive Micro Action Panel Component -->
                        <div
                            class="flex items-center gap-1 sm:opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity duration-150 self-center pl-2">
                            @if (!$isRead)
                                <button onclick="markAsRead({{ $notification->id }})"
                                    class="p-2 rounded-xl text-gray-400 hover:text-indigo-600 hover:bg-indigo-50/80 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500/40"
                                    title="Mark as read">
                                    <i class="fas fa-check text-sm"></i>
                                </button>
                            @endif

                            <form method="POST" action="{{ route('notifications.destroy', $notification) }}"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-2 rounded-xl text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition-all focus:outline-none focus:ring-2 focus:ring-rose-500/40"
                                    title="Delete notification"
                                    onclick="return confirm('Are you sure you want to delete this notification?')">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Sophisticated Empty Space Layout Indicator -->
                <div
                    class="bg-gray-50/50 border border-dashed border-gray-200 rounded-2xl p-12 text-center max-w-md mx-auto mt-6">
                    <div
                        class="w-14 h-14 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center mx-auto text-gray-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900">No notifications found</h3>
                    <p class="text-xs text-gray-500 mt-1 max-w-xs mx-auto">There are no messages in this filter tab view
                        loop category right now.</p>
                </div>
            @endforelse
        </div>

        <!-- Rendered Pagination Elements -->
        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
    </div>

    <script>
        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/read`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                })
                .then(() => {
                    location.reload();
                });
        }
    </script>
@endsection
