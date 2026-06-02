<aside id="sidebar"
    class=" sidebar w-64 bg-white border-r border-gray-200 flex flex-col fixed md:static h-full z-50 md:translate-x-0 shadow-lg md:shadow-none dark:bg-dark-bg dark:border-gray-700 ">
    <!-- Logo -->
    <a href="{{ route('dashboard') }}" class="block">
        <div class="p-6 border-b border-gray-200 dark:border-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-lg dark:text-white">TF</span>
                </div>
                <div>
                    <h1 class="font-bold text-lg text-gray-900 dark:text-white">TaskFlow</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Project Management</p>
                </div>
            </div>
        </div>
    </a>
    <!-- Team Selector -->
    @auth
        <div class="p-4 border-b border-gray-200 dark:border-0">
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
                            <img src="{{ asset('storage/' . $currentTeam->logo) }}" alt="{{ $currentTeam->name }} Logo"
                                class="w-full h-full object-cover">
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
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto bg-white dark:bg-dark-card">
        <h3 class="text-[10px] font-bold text-slate-400 uppercase px-4 mb-4 tracking-[0.1em]">Menu</h3>

        <a href="{{ route('analytics.team', auth()->user()->currentTeam()) }}"
            class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
                                {{ request()->routeIs('analytics.team')
                                    ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200'
                                    : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
            <i
                class="fas fa-home w-5 mr-3 text-center transition-colors 
            {{ request()->routeIs('analytics.team') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}">
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
        <div class="p-4 border-t border-gray-200 dark:border-0">
            <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer dark:bg-gray-800 dark:hover:bg-dark-hover"
                onclick="document.getElementById('user-menu').classList.toggle('hidden')">
                <div
                    class="w-10 h-10 overflow-hidden bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center shadow-sm">
                    @if (auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <span class="text-white font-bold text-lg uppercase ">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>
                <i class="fas fa-ellipsis-v text-gray-400 text-xs"></i>
            </div>

            <!-- User Dropdown -->
            <div id="user-menu" class="hidden mt-2 space-y-1">
                <a href="{{ route('profile.show') }}" class="block p-2 rounded text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>
                <a href="{{ route('profile.password') }}"
                    class="block p-2 rounded text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-key mr-2"></i> Change Password
                </a>
                <hr class="my-2">
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="w-full text-left p-2 rounded text-sm text-red-600 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    @endauth
</aside>
