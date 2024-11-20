<div role="status" id="toaster" x-data="toasterHub(@js($toasts), @js($config))" class="fixed z-50 w-full pointer-events-none bottom-0">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.isVisible"
             x-init="$nextTick(() => toast.show($el))"
             x-transition:enter="transition-all duration-300"
             x-transition:enter-start="translate-y-full opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition-all duration-300"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-full opacity-0"
             class="relative transform w-full pointer-events-auto"
             :class="toast.select({ error: 'text-white', info: 'text-white', success: 'text-white', warning: 'text-white' })"
        >
            <div class="w-full"
                :class="toast.select({ error: 'bg-red-500', info: 'bg-indigo-600', success: 'bg-green-600', warning: 'bg-yellow-500' })">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 relative">
                    <div class="flex items-start justify-between space-x-2">
                        <div class="flex items-start space-x-2">
                            <template x-if="toast.type === 'success'">
                                <x-heroicon-c-check-circle class="w-5 h-5 text-green-100"/>
                            </template>
                            <template x-if="toast.type === 'error'">
                                <x-heroicon-c-x-circle class="w-5 h-5 text-red-100"/>
                            </template>
                            <template x-if="toast.type === 'info'">
                                <x-heroicon-c-exclamation-circle class="w-5 h-5 text-violet-100"/>
                            </template>
                            <template x-if="toast.type === 'warning'">
                                <x-heroicon-c-exclamation-triangle class="w-5 h-5 text-yellow-100"/>
                            </template>
                            <div class="flex-1">
                                <span x-text="toast.message"></span>
                            </div>
                        </div>
                        @if($closeable)
                        <button @click="toast.dispose()" aria-label="@lang('close')" class="p-1 hover:bg-white/20 rounded-full transition-colors shrink-0">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
