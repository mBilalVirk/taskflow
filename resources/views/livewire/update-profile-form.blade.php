<div class="max-w-2xl bg-white border border-slate-100 rounded-xl shadow-sm p-6 relative">

    <!-- Success Banner Notification -->
    <div x-data="{ show: false, message: '' }"
        x-on:profile-updated.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 4000)"
        x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="absolute top-4 right-6 left-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-2 text-sm font-medium z-10"
        style="display: none;">
        <i class="fas fa-check-circle text-emerald-500 text-base"></i>
        <span x-text="message"></span>
    </div>

    <div class="mb-6">
        <h3 class="text-base font-bold text-slate-900">Profile Information</h3>
        <p class="text-xs text-slate-400 mt-1">Update your account's profile details and email address.</p>
    </div>

    <form wire:submit="updateProfile" class="space-y-6">

        <!-- Avatar Section -->
        <div class="flex items-center gap-5">
            <div class="relative group">
                @if ($avatar)
                    <img src="{{ $avatar->temporaryUrl() }}"
                        class="w-20 h-20 rounded-2xl object-cover border-2 border-indigo-500 shadow-md">
                @else
                    <img src="{{ asset('storage/' . auth()->user()->avatar) ?? 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=EEF2F6&color=475569&bold=true' }}"
                        class="w-20 h-20 rounded-2xl object-cover border border-slate-200 shadow-sm">
                @endif

                <!-- Uploading state screen mask overlay -->
                <div wire:loading wire:target="avatar"
                    class="absolute inset-0 bg-white/80 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-spinner fa-spin text-indigo-600 text-sm"></i>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5 cursor-pointer">
                    <span
                        class="px-3 py-2 bg-slate-50 border border-slate-200 text-slate-700 text-xs font-medium rounded-xl hover:bg-slate-100 transition duration-200 inline-block">
                        <i class="fas fa-camera mr-1.5 text-slate-400"></i> Change Photo
                    </span>
                    <input type="file" wire:model="avatar" class="hidden" accept="image/*">
                </label>
                <p class="text-[11px] text-slate-400">JPG, PNG or GIF. Max size 2MB.</p>
                @error('avatar')
                    <span class="text-xs text-rose-600 font-medium block mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <hr class="border-slate-100">

        <!-- Name Input -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2">Full Name</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-user text-sm"></i>
                </div>
                <input type="text" wire:model="name"
                    class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all text-slate-800 placeholder:text-slate-400">
            </div>
            @error('name')
                <span class="text-xs text-rose-600 font-medium block mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email Input -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-envelope text-sm"></i>
                </div>
                <input type="email" wire:model="email"
                    class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all text-slate-800 placeholder:text-slate-400">
            </div>
            @error('email')
                <span class="text-xs text-rose-600 font-medium block mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Phone Input -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2">Phone Number</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-phone text-sm"></i>
                </div>
                <input type="text" wire:model="phone"
                    class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all text-slate-800 placeholder:text-slate-400">
            </div>
            @error('phone')
                <span class="text-xs text-rose-600 font-medium block mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Country Input -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2">Country</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-globe text-sm"></i>
                </div>
                <input type="text" wire:model="country"
                    class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all text-slate-800 placeholder:text-slate-400">
            </div>
            @error('country')
                <span class="text-xs text-rose-600 font-medium block mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Action Button -->
        <div class="flex items-center justify-end pt-2">
            <button type="submit" wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 active:bg-indigo-800 shadow-md shadow-indigo-100 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading wire:target="updateProfile">
                    <i class="fas fa-spinner fa-spin mr-1"></i>
                </span>
                <span wire:loading.remove wire:target="updateProfile">
                    <i class="fas fa-save mr-1"></i>
                </span>
                Save Changes
            </button>
        </div>
    </form>
</div>
