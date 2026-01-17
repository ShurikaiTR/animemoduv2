<div x-data="{ 
    toasts: [],
    addToast(type, message) {
        const id = Date.now();
        this.toasts.push({ id, type, message });
        setTimeout(() => {
            this.removeToast(id);
        }, 4000);
    },
    removeToast(id) {
        this.toasts = this.toasts.filter(t => t.id !== id);
    }
}" x-init="
    @if(session()->has('toast'))
        addToast('{{ session('toast.type') }}', '{{ session('toast.message') }}');
    @endif
" @toast-notify.window="addToast($event.detail.type, $event.detail.message)"
    class="fixed bottom-6 right-6 z-toast flex flex-col-reverse gap-3 w-full max-w-sm pointer-events-none">

    <template x-for="toast in toasts" :key="toast.id">
        <div class="pointer-events-auto flex items-center gap-4 p-4 rounded-2xl border bg-glass animate-toast-in shadow-2xl transition-all duration-300"
            :class="{
                'border-success/20 shadow-success-glow/20': toast.type === 'success',
                'border-danger/20 shadow-danger-glow/20': toast.type === 'danger',
                'border-warning/20 shadow-warning-glow/20': toast.type === 'warning',
                'border-primary/20 shadow-glow/20': toast.type === 'info'
            }">
            {{-- Icon --}}
            <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center" :class="{
                    'bg-success/20 text-success': toast.type === 'success',
                    'bg-danger/20 text-danger': toast.type === 'danger',
                    'bg-warning/20 text-warning': toast.type === 'warning',
                    'bg-primary/20 text-primary': toast.type === 'info'
                }">
                <template x-if="toast.type === 'success'">
                    <x-heroicon-o-check-circle class="w-6 h-6" />
                </template>
                <template x-if="toast.type === 'danger'">
                    <x-heroicon-o-x-circle class="w-6 h-6" />
                </template>
                <template x-if="toast.type === 'warning'">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6" />
                </template>
                <template x-if="toast.type === 'info'">
                    <x-heroicon-o-information-circle class="w-6 h-6" />
                </template>
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white/90 leading-snug" x-text="toast.message"></p>
            </div>

            {{-- Close Button --}}
            <button @click="removeToast(toast.id)" class="text-white/20 hover:text-white transition-colors">
                <x-heroicon-o-x-mark class="w-5 h-5" />
            </button>
        </div>
    </template>
</div>