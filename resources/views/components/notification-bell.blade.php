<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<div class="relative inline-block text-left" x-data="{ open: false }">
    <!-- Bell Icon -->
    <button @click="open = !open"
        class="relative p-2.5 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100/80 rounded-xl transition-all duration-200 active:scale-95 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
        </svg>

        <!-- Unread badge -->
        <span id="notificationBadge"
            class="absolute top-1.5 right-1.5 bg-gradient-to-tr from-rose-500 to-red-500 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center border-2 border-white shadow-sm scale-100 transition-all"
            style="display: none;">
            0
        </span>
    </button>

    <!-- Dropdown Panel -->
    <div @click.away="open = false" x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
        class="absolute right-0 mt-3 w-92 max-w-sm sm:w-96 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden divide-y divide-gray-50 origin-top-right"
        style="display: none;">

        <!-- Header -->
        <div class="px-5 py-4 flex justify-between items-center bg-gray-50/50 backdrop-blur-sm">
            <div>
                <h3 class="font-semibold text-gray-900 text-base">Notifications</h3>
                <p class="text-xs text-gray-500 font-medium mt-0.5">Stay updated with your latest alerts</p>
            </div>
            <button @click="markAllRead()"
                class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100/80 px-3 py-1.5 rounded-lg transition-colors focus:outline-none">
                Mark all as read
            </button>
        </div>

        <!-- Notifications List -->
        <div id="notificationsList" class="max-h-[26rem] overflow-y-auto divide-y divide-gray-100/80 scrollbar-thin">
            <!-- Loaded via JavaScript -->
        </div>

        <!-- Footer Footer Link -->
        <div class="p-3 bg-gray-50/30 text-center">
            <a href="{{ route('notifications.index') }}"
                class="inline-block w-full py-2 text-sm font-semibold text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-xl transition-all">
                View All Notifications
            </a>
        </div>
    </div>
</div>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    // Load notifications
    function loadNotifications() {
        fetch('{{ route('notifications.recent') }}?limit=5')
            .then(res => res.json())
            .then(data => {
                renderNotifications(data);
                updateBadge();
            });
    }

    function renderNotifications(notifications) {
        if (!notifications) {
            console.error('Failed to load notifications');
            return;
        }
        const list = document.getElementById('notificationsList');

        if (notifications.length === 0) {
            list.innerHTML = `
                <div class="p-8 text-center flex flex-col items-center justify-center">
                    <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.143 17.082a24.248 24.248 0 0 0 3.844.148m-3.844-.148a23.856 23.856 0 0 1-5.455-1.31 8.961 8.961 0 0 0 2.3-5.541V8.25a5.25 5.25 0 0 1 10.5 0v1.98a8.96 8.96 0 0 0 2.3 5.541 23.841 23.841 0 0 1-5.455 1.31m-3.844.148m3.844 0a24.14 24.14 0 0 1-3.844.148m3.844-.148a24.256 24.256 0 0 1 3.844.148M9.143 17.082c.485.205.986.37 1.498.496m4.556-4.962A18.93 18.93 0 0 0 16.5 12V8.25m0 0a21.22 21.22 0 0 0-3.071-1.028" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-900">All caught up!</p>
                    <p class="text-xs text-gray-400 mt-0.5">No new notifications at the moment.</p>
                </div>`;
            return;
        }

        list.innerHTML = notifications.map(notif => {
            const isRead = !!notif.read_at;
            return `
                <div class="p-4 flex gap-3 items-start transition-all duration-200 cursor-pointer relative group ${isRead ? 'opacity-75 hover:bg-gray-50/50' : 'bg-indigo-50/20 hover:bg-indigo-50/40'}" 
                     onclick="goToNotification('${notif.action_url}')">
                    
                    <!-- Modern Status Dot Indication -->
                    <div class="mt-1.5 flex-shrink-0">
                        ${isRead 
                            ? `<span class="block w-2.5 h-2.5 rounded-full border border-gray-300 bg-transparent"></span>`
                            : `<span class="block w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-500/20 animate-pulse"></span>`
                        }
                    </div>

                    <!-- Content Body -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <h4 class="font-semibold text-sm text-gray-900 truncate group-hover:text-indigo-600 transition-colors">${notif.data.title}</h4>
                            <span class="text-[11px] font-medium text-gray-400 whitespace-nowrap mt-0.5">${formatTime(notif.created_at)}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-0.5 line-clamp-2 leading-relaxed">${notif.data.message}</p>
                    </div>
                    
                    <!-- Quick Actions -->
                    ${!isRead ? `
                    <div class="flex-shrink-0 self-center pl-1">
                        <button 
                            onclick="markAsRead(${notif.id}, event)"
                            class="p-1.5 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all opacity-0 group-hover:opacity-100 focus:opacity-100 focus:outline-none"
                            title="Mark as read"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </button>
                    </div>
                    ` : ''}
                </div>
            `;
        }).join('');
    }

    function updateBadge() {
        fetch('{{ route('notifications.count') }}')
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById('notificationBadge');
                if (data.count > 0) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            });
    }

    function markAsRead(notificationId, event) {
        event.stopPropagation();
        fetch(`/notifications/${notificationId}/read`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            })
            .then(() => loadNotifications());
    }

    function markAllRead() {
        fetch('{{ route('notifications.mark-all-read') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            })
            .then(() => loadNotifications());
    }

    function goToNotification(url) {
        if (url) window.location.href = url;
    }

    // Elegant relative time formatter helper function
    function formatTime(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);

        if (diffInSeconds < 60) return 'Just now';

        const diffInMinutes = Math.floor(diffInSeconds / 60);
        if (diffInMinutes < 60) return `${diffInMinutes}m ago`;

        const diffInHours = Math.floor(diffInMinutes / 60);
        if (diffInHours < 24) return `${diffInHours}h ago`;

        return date.toLocaleDateString(undefined, {
            month: 'short',
            day: 'numeric'
        });
    }

    // Initial load
    document.addEventListener('DOMContentLoaded', loadNotifications);

    // Refresh sequence
    setInterval(loadNotifications, 10000);

    // Live Event Bindings
    if (window.Echo) {
        window.Echo.private(`user.{{ auth()->id() }}`)
            .listen('task.assigned', (event) => {
                loadNotifications();
                if (typeof showNotificationToast === 'function') {
                    showNotificationToast(event.message);
                }
            });
    }
</script>
