@props([
    'width' => null,
    'content' => null,
    'trigger' => null,
    'showArrow' => true,
    'buttonClass' => null,
    'panelClass' => null,
    'persistOnDesktop' => false,
])
<div class="relative" x-data="{ open: false, adjustWidth: 0 }" @if($persistOnDesktop) x-persist="open" @endif x-init="$watch('open', value => {
    if (value) {
        adjustWidth = 0;
        $nextTick(() => {
            let dropdown = $refs.dropdown;
            let rect = dropdown.getBoundingClientRect();
            let windowWidth = window.innerWidth;
            adjustWidth = rect.right > windowWidth ? rect.width - 40 : 0;
        });
    }
})">

    <button
        class="{{ $buttonClass ?? 'flex flex-row items-center px-2 py-1 text-sm font-semibold whitespace-nowrap text-base hover:text-base/80' }}"
        x-on:click="open = !open">
        {{ $trigger }}
        @if($showArrow)
        <x-ri-arrow-down-s-line x-bind:class="{ '-rotate-180' : open }"
            class="md:block hidden ml-1 size-4 text-base ease-out duration-300" />
        @endif
    </button>

    <div x-ref="dropdown"
        class="{{ $panelClass ?? 'absolute mt-2 ' . ($width ?? 'w-48') . ' px-2 py-1 bg-background-secondary rounded-[var(--card-radius)] shadow-[var(--card-shadow)] z-10 border border-neutral' }}"
        x-bind:style="{
            left: `-${adjustWidth}px`,
        }"
        x-show="open"
        x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
        @if($persistOnDesktop)
        @click.stop
        x-on:click.outside="open = false"
        @else
        x-on:click.outside="open = false"
        @endif
        x-cloak>
        @if($persistOnDesktop)
        <div @click.stop @click.self.stop>
            {{ $content }}
        </div>
        @else
        {{ $content }}
        @endif
    </div>
</div>
