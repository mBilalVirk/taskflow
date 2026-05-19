<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - TaskFlow</title>
    @vite(['resources/css/app.css'])
    <!-- Tailwind CSS -->
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        .sidebar-link {
            @apply px-4 py-3 rounded-lg transition duration-200;
        }

        .sidebar-link.active {
            @apply bg-blue-500 text-white;
        }

        .sidebar-link:not(.active):hover {
            @apply bg-gray-100;
        }

        .card-hover {
            @apply transition duration-300 hover:shadow-lg;
        }

        .sidebar {
            transition: transform 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="bg-slate-50" data-theme="light" data-success="{{ session('success') ?? '' }}"
    data-error="{{ session('error') ?? '' }}" data-warning="{{ session('warning') ?? '' }}"
    data-info="{{ session('info') ?? '' }}">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside id="sidebar"
            class=" sidebar w-64 bg-white border-r border-gray-200 flex flex-col fixed md:static h-full z-50 md:translate-x-0 shadow-lg md:shadow-none">
            <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="block">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">TF</span>
                        </div>
                        <div>
                            <h1 class="font-bold text-lg text-gray-900">TaskFlow</h1>
                            <p class="text-xs text-gray-500">Project Management</p>
                        </div>
                    </div>
                </div>
            </a>
            <!-- Team Selector -->
            @auth
                <div class="p-4 border-b border-gray-200">
                    <p class="text-xs font-semibold text-gray-600 uppercase mb-3">Current Team</p>
                    @php
                        $currentTeam = auth()->user()->currentTeam();
                    @endphp
                    @if ($currentTeam)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100"
                            onclick="document.getElementById('team-dropdown').classList.toggle('hidden')">
                            <div
                                class="w-10 h-10 rounded-lg overflow-hidden flex items-center justify-center bg-gradient-to-br from-blue-400 to-blue-600">
                                @if ($currentTeam->logo)
                                    <img src="{{ asset('storage/' . $currentTeam->logo) }}"
                                        alt="{{ $currentTeam->name }} Logo" class="w-full h-full object-cover">
                                @else
                                    <span class="text-white font-bold text-lg">
                                        {{ strtoupper(substr($currentTeam->name, 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm text-gray-900 truncate">{{ $currentTeam->name }}</p>
                                <p class="text-xs text-gray-500">{{ $currentTeam->members()->count() }} members</p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>

                        <!-- Team Dropdown -->
                        <div id="team-dropdown" class="hidden mt-2 space-y-1 max-h-48 overflow-y-auto">
                            @foreach (auth()->user()->teams as $team)
                                <form action="{{ route('team.switch', $team->id) }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center justify-between p-2.5 mb-1 rounded-lg text-sm transition-all duration-200 
           {{ $currentTeam->id === $team->id
               ? 'bg-indigo-50 text-indigo-700 font-bold border border-indigo-100'
               : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">

                                        <span class="truncate">{{ $team->name }}</span>

                                        @if ($currentTeam->id === $team->id)
                                            <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                            @endforeach
                            <hr class="my-2">
                            <a href="{{ route('team.create-form') }}"
                                class="block p-2 rounded text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50">
                                <i class="fas fa-plus mr-2"></i> New Team
                            </a>
                        </div>
                    @endif
                </div>
            @endauth

            <!-- Navigation Menu -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto bg-white">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase px-4 mb-4 tracking-[0.1em]">Menu</h3>

                <a href="{{ route('team.dashboard', auth()->user()->currentTeam()) }}"
                    class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
        {{ request()->routeIs('team.dashboard')
            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200'
            : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                    <i
                        class="fas fa-home w-5 mr-3 text-center transition-colors 
            {{ request()->routeIs('team.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}">
                    </i>
                    Dashboard
                </a>

                <a href="{{ route('projects.index', auth()->user()->currentTeam()) }}"
                    class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
        {{ request()->routeIs('projects.*')
            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200'
            : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                    <i
                        class="fas fa-folder w-5 mr-3 text-center transition-colors 
            {{ request()->routeIs('projects.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}">
                    </i>
                    Projects
                </a>

                <a href="{{ route('team.members', auth()->user()->currentTeam()) }}"
                    class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
        {{ request()->routeIs('team.members')
            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200'
            : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                    <i
                        class="fas fa-users w-5 mr-3 text-center transition-colors 
            {{ request()->routeIs('team.members') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}">
                    </i>
                    Team
                </a>
                @if (auth()->user()->isTeamAdmin($team))
                    <a href="{{ route('team.settings', auth()->user()->currentTeam()) }}"
                        class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
                        {{ request()->routeIs('team.settings')
                            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200'
                            : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                        <i
                            class="fas fa-cog w-5 mr-3 text-center transition-colors 
                                    {{ request()->routeIs('team.settings') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}">
                        </i>
                        Settings
                    </a>
                @endif
            </nav>

            <!-- User Profile -->
            @auth
                <div class="p-4 border-t border-gray-200">
                    <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer"
                        onclick="document.getElementById('user-menu').classList.toggle('hidden')">
                        <div
                            class="w-10 h-10 overflow-hidden bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center shadow-sm">
                            @if (auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                    alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-white font-bold text-lg uppercase ">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <i class="fas fa-ellipsis-v text-gray-400 text-xs"></i>
                    </div>

                    <!-- User Dropdown -->
                    <div id="user-menu" class="hidden mt-2 space-y-1">
                        <a href="#" class="block p-2 rounded text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                        <a href="#" class="block p-2 rounded text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-key mr-2"></i> Change Password
                        </a>
                        <hr class="my-2">
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="w-full text-left p-2 rounded text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button id="mobile-menu-button" class="md:hidden text-gray-600 hover:text-gray-900 mr-4">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-2xl font-bold text-gray-900">@yield('header', 'Dashboard')</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <div
                        class="hidden md:flex items-center bg-white border border-gray-300 hover:border-gray-400 focus-within:border-blue-500 rounded-2xl px-5 py-3 w-72 transition-all">
                        <i class="fas fa-search text-gray-400"></i>
                        <input type="text" placeholder="Search..."
                            class="bg-transparent ml-3 w-full focus:outline-none text-gray-700 placeholder:text-gray-400 text-sm">
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
                    <!-- User Menu Mobile -->
                    <div class="md:hidden">
                        @auth
                            <button
                                class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </button>
                        @endauth
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>
    <!-- Mobile Overlay -->
    <div id="mobile-overlay" class="hidden fixed inset-0 bg-black/50 z-40 md:hidden"></div>
    {{-- <script>
        const sidebar = document.getElementById('sidebar');
        const menuButton = document.getElementById('mobile-menu-button');
        const overlay = document.getElementById('mobile-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('hidden');
        }

        // Hamburger Button Click
        menuButton.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking overlay
        overlay.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking a link (optional)
        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    sidebar.classList.remove('open');
                    overlay.classList.add('hidden');
                }
            });
        });

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && window.innerWidth < 768) {
                sidebar.classList.remove('open');
                overlay.classList.add('hidden');
            }
        });
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[onclick]')) {
                document.getElementById('team-dropdown')?.classList.add('hidden');
                document.getElementById('user-menu')?.classList.add('hidden');
            }
        });
    </script> --}}
    @include('components.toast')
    @vite(['resources/js/utils/sidetoggle.js'])
    @vite(['resources/js/app.js'])
</body>

</html>
