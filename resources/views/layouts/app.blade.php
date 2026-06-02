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

    <script>
        (function() {
                // Prevent flash of unstyled content

                document.documentElement.classList.add('no-transition');

                function applyTheme() {
                    let theme = localStorage.getItem('theme');


                    @auth
                    const serverPrefs = @json(auth()->user()->preferences ?? []);
                    const serverTheme = serverPrefs.theme ?? null;
                    if (!theme && serverTheme) {
                        theme = serverTheme;
                        localStorage.setItem('theme', theme);
                    }
                @endauth

                // Default to dark if nothing is set
                if (!theme) {
                    theme = 'light';
                    localStorage.setItem('theme', theme);
                }

                if (theme === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    document.documentElement.classList.toggle('dark', prefersDark);
                } else {
                    document.documentElement.classList.toggle('dark', theme === 'dark');
                }
            }

            // Run immediately
            applyTheme();

            // Also run after DOM is fully loaded (safety net)
            window.addEventListener('DOMContentLoaded', () => {
                applyTheme();
                setTimeout(() => {
                    document.documentElement.classList.remove('no-transition');
                }, 50);
            });
        })();
    </script>

    <script>
        console.log('LOCALSTORAGE THEME:', localStorage.getItem('theme'));
        @auth
        console.log('SERVER PREFS:', @json(auth()->user()->preferences ?? null));
        @endauth
        window.addEventListener('theme-changed', (event) => {
            const theme = event.detail.theme;
            localStorage.setItem('theme', theme);

            if (theme === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.classList.toggle('dark', prefersDark);
            } else {
                document.documentElement.classList.toggle('dark', theme === 'dark');
            }
        });
    </script>
    <style>
        /* Smooth color transitions */
        * {
            @apply transition-colors duration-200;
        }

        /* Prevent transition on initial load */
        html.no-transition * {
            @apply transition-none;
        }

        html.no-transition {
            @apply transition-none;
        }
    </style>
</head>

<body class="bg-white text-gray-900 dark:bg-dark-bg dark:text-white" data-theme="light"
    data-success="{{ session('success') ?? '' }}" data-error="{{ session('error') ?? '' }}"
    data-warning="{{ session('warning') ?? '' }}" data-info="{{ session('info') ?? '' }}">
    <div class="flex h-screen">

        <!-- Sidebar -->
        @include('components.nav.aside')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            @include('components.nav.header')

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
