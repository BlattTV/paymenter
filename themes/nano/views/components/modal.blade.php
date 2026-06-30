@props([
    'title' => '',
    'closable' => true,
    'closeTrigger' => '',
    'open' => false,
    'width' => 'max-w-4xl'
])
<div x-data="{ open: {{ $open ? 'true' : 'false' }} }" @open-modal.window="open = $event.detail.open">
    <template x-teleport="body">
        <div class="fixed inset-0 z-30 flex items-center justify-center rounded-[var(--card-radius)] bg-black/50"
            x-show="open"
            @click.self="open = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="mx-4 modal-scrollbar w-full text-left bg-white dark:bg-[#111418] rounded-[var(--card-radius)] border border-[#f0f2f4] dark:border-gray-800 shadow-[var(--card-shadow)] {{ $width }} max-h-[80vh] overflow-y-auto flex flex-col"
                x-cloak
                x-transition:enter="transition transform cubic-bezier(0.34,1.56,0.64,1) duration-400"
                x-transition:enter-start="translate-y-24"
                x-transition:enter-end="translate-y-0"
                x-transition:leave="transition transform ease-in duration-200"
                x-transition:leave-start="translate-y-0"
                x-transition:leave-end="translate-y-24">
                <div class="flex justify-between items-center px-6 py-5 border-b border-[#f0f2f4] dark:border-gray-800">
                    <h2 class="text-xl font-bold text-[#111418] dark:text-white">{{ $title }}</h2>
                    @if ($closable && !$closeTrigger)
                        <button @click="open = false" class="text-[#617589] hover:text-[#111418] dark:hover:text-white transition-colors cursor-pointer">
                            <x-ri-close-fill class="size-6" />
                        </button>
                    @elseif ($closable && $closeTrigger)
                        {{ $closeTrigger }}
                    @endif
                </div>
                <div class="px-6 py-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </template>

</div>
