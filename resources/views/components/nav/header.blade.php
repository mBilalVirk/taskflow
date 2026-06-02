<header
    class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between dark:bg-dark-card dark:border-gray-700 ">
    <div class="flex items-center">
        <button id="mobile-menu-button" class="md:hidden text-gray-600 hover:text-gray-900 mr-4">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@yield('header', 'Dashboard')</h2>
    </div>
    <div class="flex items-center space-x-4">
        <div
            class="hidden md:flex items-center bg-white border border-gray-300 hover:border-gray-400 focus-within:border-blue-500 rounded-2xl px-5 py-3 w-72 transition-all dark:bg-dark-card dark:border-gray-700">
            <i class="fas fa-search text-gray-400 "></i>
            <input type="text" placeholder="Search..."
                class="bg-transparent ml-3 w-full focus:outline-none text-gray-700 placeholder:text-gray-400 text-sm dark:text-gray-300 dark:placeholder:text-gray-500">
        </div>

        <!-- Notifications -->
        <!-- Notifications -->
        {{-- <div class="relative">

                        <!-- Button -->
                        <button id="notificationBtn"
                            class="relative text-gray-600 hover:text-gray-900 p-2 rounded-xl hover:bg-gray-100 transition">

                            <i class="fas fa-bell text-xl"></i>

                            @if (auth()->user()->unreadNotifications()->count() > 0)
                                <span id="notificationDot"
                                    class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full"></span>
                            @endif
                        </button>

                        <!-- Dropdown -->
                        <div id="notificationDropdown"
                            class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden">

                            <!-- Header -->
                            <div class="px-4 py-3 border-b flex justify-between items-center bg-gray-50">
                                <h3 class="font-semibold text-gray-800">Notifications</h3>
                                <span class="text-xs text-indigo-600 font-bold">
                                    {{ auth()->user()->unreadNotifications()->count() }} new
                                </span>
                            </div>

                            <!-- Notifications List -->
                            <div class="max-h-80 overflow-y-auto">

                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <div class="px-4 py-3 border-b hover:bg-gray-50 cursor-pointer">

                                        <p class="text-sm text-gray-800">
                                            {{ $notification->data['message'] ?? 'New notification' }}
                                        </p>

                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                @empty
                                    <div class="px-4 py-10 text-center text-gray-400">
                                        No notifications
                                    </div>
                                @endforelse

                            </div>

                            <!-- Footer -->
                            <div class="px-4 py-2 text-center bg-gray-50">
                                <a href="#" class="text-xs text-indigo-600 font-semibold hover:underline">
                                    View All
                                </a>
                            </div>
                        </div>
                    </div> --}}
        @include('components.notification-bell')


        <!-- Theme Toggle -->
        @livewire('theme-toggle')
        <!-- User Menu Mobile -->
        <div class="md:hidden">

            <div
                class="w-10 h-10 overflow-hidden bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center shadow-sm">
                @if (auth()->user()->avatar)
                    <a href="{{ route('profile.show') }}">
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}"
                            class="w-full h-full object-cover">
                    </a>
                @else
                    <a href="{{ route('profile.show') }}">
                        <span class="text-white font-bold text-lg uppercase ">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>
