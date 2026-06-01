<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - TaskFlow</title>

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
    </style>
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col">
            <!-- Logo -->
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
                            @foreach (auth()->user()->teams()->get() as $team)
                                <a href="{{ route('team.switch', $team) }}"
                                    class="block p-2 rounded text-sm text-gray-700 hover:bg-blue-50 {{ $currentTeam->id === $team->id ? 'bg-blue-100 text-blue-700 font-semibold' : '' }}">
                                    {{ $team->name }}
                                </a>
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
            </nav>

            <!-- User Profile -->
            @auth
                <div class="p-4 border-t border-gray-200">
                    <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer"
                        onclick="document.getElementById('user-menu').classList.toggle('hidden')">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
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
                    <button class="md:hidden text-gray-600 hover:text-gray-900 mr-4">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-2xl font-bold text-gray-900">@yield('header', 'Dashboard')</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <div class="hidden md:flex items-center bg-gray-100 rounded-lg px-4 py-2 w-64">
                        <i class="fas fa-search text-gray-400"></i>
                        <input type="text" placeholder="Search..."
                            class="bg-transparent ml-2 w-full focus:outline-none text-sm">
                    </div>

                    <!-- Notifications -->
                    <button class="relative text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

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

    <script>
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[onclick]')) {
                document.getElementById('team-dropdown')?.classList.add('hidden');
                document.getElementById('user-menu')?.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
