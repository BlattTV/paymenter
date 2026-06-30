<div class="md:hidden fixed bottom-0 left-0 right-0 bg-background-secondary border-t border-neutral z-40 px-2 py-2 safe-area-bottom">
    <div class="flex justify-around items-center">
        @php
            $mobileLinks = collect(\App\Classes\Navigation::getDashboardLinks())
                ->filter(fn($nav) => isset($nav['url']) || !empty($nav['children']))
                ->take(5);
        @endphp
        @foreach ($mobileLinks as $nav)
            @php
                $linkUrl = $nav['url'] ?? ($nav['children'][0]['url'] ?? '#');
            @endphp
            <a href="{{ $linkUrl }}" wire:navigate
                class="flex flex-col items-center gap-1 px-3 py-1.5 rounded-[var(--button-radius)] {{ $nav['active'] ?? false ? 'text-primary' : 'text-base/60' }} transition-colors">
                @isset($nav['icon'])
                    <x-dynamic-component :component="$nav['icon']" class="size-5" />
                @endisset
                <span class="text-[10px] font-medium">{{ Str::limit($nav['name'], 8) }}</span>
            </a>
        @endforeach
    </div>
</div>
