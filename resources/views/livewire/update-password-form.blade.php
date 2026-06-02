<div class="max-w-2xl bg-white border border-slate-100 rounded-xl shadow-sm p-6 relative mt-6 dark:bg-dark-card dark:border-gray-400"
    x-on:password-updated.window="window.showToast($event.detail.message, 'success')">



    <div class="mb-6">
        <h3 class="text-base font-bold text-slate-900 dark:text-gray-400">Update Password</h3>
        <p class="text-xs text-slate-400 mt-1">Ensure your account is using a long, random password to stay secure.</p>
    </div>

    <form wire:submit="updatePassword" class="space-y-5">

        <!-- Current Password -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2 dark:text-gray-400">Current
                Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-lock text-sm"></i>
                </div>
                <input type="password" wire:model="current_password"
                    class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all text-slate-800 placeholder:text-slate-400 dark:bg-dark-card dark:text-gray-300 dark:placeholder:text-gray-500 dark:border-gray-400">
            </div>
            @error('current_password')
                <span class="text-xs text-rose-600 font-medium block mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- New Password -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2 dark:text-gray-400">New
                Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-key text-sm"></i>
                </div>
                <input type="password" wire:model="password"
                    class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all text-slate-800 placeholder:text-slate-400 dark:bg-dark-card dark:text-gray-300 dark:placeholder:text-gray-500 dark:border-gray-400">
            </div>
            @error('password')
                <span class="text-xs text-rose-600 font-medium block mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirm New Password -->
        <div>
            <label
                class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2 dark:text-gray-400">Confirm
                New
                Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-shield-alt text-sm"></i>
                </div>
                <input type="password" wire:model="password_confirmation"
                    class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all text-slate-800 placeholder:text-slate-400 dark:bg-dark-card dark:text-gray-300 dark:placeholder:text-gray-500 dark:border-gray-400">
            </div>
        </div>

        <!-- Action Button -->
        <div class="flex items-center justify-end pt-2">
            <button type="submit" wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 active:bg-indigo-800 shadow-md shadow-indigo-100 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading wire:target="updatePassword">
                    <i class="fas fa-spinner fa-spin mr-1"></i>
                </span>
                <span wire:loading.remove wire:target="updatePassword">
                    <i class="fas fa-lock mr-1.5"></i>
                </span>
                Update Password
            </button>
        </div>
    </form>
</div>
