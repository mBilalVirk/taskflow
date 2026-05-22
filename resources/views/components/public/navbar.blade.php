<nav class="fixed top-0 w-full z-50">
    <div class="glassmorphism px-6 py-4 border-b border-gray-800/50">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-3 hover:opacity-80 transition">
                <div
                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-400 to-purple-500 flex items-center justify-center">
                    <i class="fas fa-tasks text-white font-bold"></i>
                </div>
                <span class="text-xl font-bold gradient-text">TaskFlow</span>
            </a>

            <!-- Menu -->
            <div class="hidden lg:flex items-center gap-8">
                <a href="#features" class="text-gray-300 hover:text-white transition text-sm">Features</a>
                <a href="#solutions" class="text-gray-300 hover:text-white transition text-sm">Solutions</a>
                <a href="#pricing" class="text-gray-300 hover:text-white transition text-sm">Pricing</a>
                <a href="#about" class="text-gray-300 hover:text-white transition text-sm">About</a>
            </div>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}"
                    class="px-4 py-2 text-gray-300 hover:text-white transition text-sm font-medium">
                    Sign In
                </a>
                <a href="{{ route('register') }}"
                    class="px-6 py-2 rounded-lg bg-gradient-to-r from-cyan-400 to-purple-500 text-white font-semibold text-sm hover:shadow-lg hover:shadow-cyan-500/50 transition btn-glow">
                    Get Started Free
                </a>
            </div>
        </div>
    </div>
</nav>
