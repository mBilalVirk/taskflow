<div id="toast-container" class="fixed top-6 right-6 z-[9999] flex flex-col gap-4 pointer-events-none">
</div>

<script>
    window.showToast = (message, type = 'success', duration = 5000) => {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');

        toast.className = `
            pointer-events-auto flex items-start gap-4 px-4 py-4 rounded-xl 
            backdrop-blur-md border shadow-2xl min-w-[320px] max-w-[400px] 
            translate-x-full opacity-0 transition-all duration-500 ease-[cubic-bezier(0.23,1,0.32,1)]
        `;

        const config = {
            success: {
                bg: 'bg-white/80 border-emerald-100',
                icon: 'text-emerald-500',
                faIcon: 'fa-solid fa-circle-check'
            },
            error: {
                bg: 'bg-white/80 border-rose-100',
                icon: 'text-rose-500',
                faIcon: 'fa-solid fa-circle-xmark'
            },
            warning: {
                bg: 'bg-white/80 border-amber-100',
                icon: 'text-amber-500',
                faIcon: 'fa-solid fa-triangle-exclamation'
            },
            info: {
                bg: 'bg-white/80 border-indigo-100',
                icon: 'text-indigo-500',
                faIcon: 'fa-solid fa-circle-info'
            }
        };

        const theme = config[type] || config.info;

        toast.innerHTML = `
            <div class="${theme.icon} text-xl mt-0.5">
                <i class="${theme.faIcon}"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-slate-900 leading-tight">
                    ${type.charAt(0).toUpperCase() + type.slice(1)}
                </p>
                <p class="text-xs text-slate-500 mt-1 font-medium leading-relaxed">
                    ${message}
                </p>
            </div>
            <button onclick="this.closest('.pointer-events-auto').remove()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        `;

        toast.classList.add(...theme.bg.split(' '));
        container.appendChild(toast);

        // Slide In
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
        });

        // Slide Out & Remove
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-2', 'scale-95');
            setTimeout(() => toast.remove(), 500);
        }, duration);
    };
</script>
