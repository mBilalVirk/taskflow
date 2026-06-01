<div class="relative">
    <!-- Quick Toggle Button -->
    <button wire:click="toggleTheme"
        class="p-2 rounded-lg borde hover:border-gray-600 text-gray-400 hover:text-gray-300 transition"
        title="Toggle theme">
        <i class="fas {{ $currentTheme === 'dark' ? 'fa-sun' : 'fa-moon' }}"></i>
    </button>

    <!-- Advanced Dropdown (Optional) -->
    {{-- 
    <div class="relative">
        <button 
            wire:click="$toggle('isDropdownOpen')"
            class="p-2 rounded-lg border border-gray-700 hover:border-gray-600"
        >
            <i class="fas fa-palette"></i>
        </button>

        @if ($isDropdownOpen)
            <div class="absolute right-0 mt-2 w-48 bg-dark-card border border-gray-700 rounded-lg shadow-lg z-50">
                <button 
                    wire:click="setTheme('light')"
                    class="w-full text-left px-4 py-3 hover:bg-gray-700 transition flex items-center gap-3 {{ $currentTheme === 'light' ? 'bg-gray-700' : '' }}"
                >
                    <i class="fas fa-sun text-yellow-400"></i>
                    <span>Light</span>
                    @if ($currentTheme === 'light')
                        <i class="fas fa-check ml-auto text-cyan-400"></i>
                    @endif
                </button>

                <button 
                    wire:click="setTheme('dark')"
                    class="w-full text-left px-4 py-3 hover:bg-gray-700 transition flex items-center gap-3 {{ $currentTheme === 'dark' ? 'bg-gray-700' : '' }}"
                >
                    <i class="fas fa-moon text-purple-400"></i>
                    <span>Dark</span>
                    @if ($currentTheme === 'dark')
                        <i class="fas fa-check ml-auto text-cyan-400"></i>
                    @endif
                </button>

                <div class="border-t border-gray-700"></div>

                <button 
                    wire:click="setTheme('system')"
                    class="w-full text-left px-4 py-3 hover:bg-gray-700 transition flex items-center gap-3 {{ $currentTheme === 'system' ? 'bg-gray-700' : '' }}"
                >
                    <i class="fas fa-desktop text-gray-400"></i>
                    <span>System</span>
                    @if ($currentTheme === 'system')
                        <i class="fas fa-check ml-auto text-cyan-400"></i>
                    @endif
                </button>
            </div>
        @endif
    </div>
    --}}
</div>
