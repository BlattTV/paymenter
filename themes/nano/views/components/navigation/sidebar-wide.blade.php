@props(['bannerOffset' => false])
<aside class="fixed left-0 top-0 h-screen w-72 bg-background-secondary border-r border-neutral z-20 hidden md:flex flex-col" style="padding-top: {{ $bannerOffset ? 'calc(6rem + 2.5rem)' : '6rem' }}">
    <div class="flex-1 overflow-y-auto p-4">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-3 p-4 rounded-[var(--card-radius)] shadow-[var(--card-shadow)] gradient-paper border border-white dark:from-slate-800 dark:border-neutral-800 relative overflow-hidden group">
                <div class="flex items-center gap-3 relative z-10">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-[var(--card-radius)] size-10 border border-white/60 shadow-[var(--card-shadow)]" style="background-image: url('{{ auth()->user()->avatar }}');"></div>
                    <div class="flex flex-col min-w-0 flex-1">
                        <p class="text-white font-semibold truncate">{{ auth()->user()->name }}</p>
                        <p class="text-white/60 text-sm truncate opacity-80">{{ auth()->user()->email }}</p>
                    </div>
                    @if(theme('force_theme_mode', 'none') === 'none')
                    <button @click="darkMode = !darkMode" type="button" class="flex size-8 items-center cursor-pointer justify-center rounded-[var(--button-radius)] bg-white/20 hover:bg-white/30 transition-colors flex-shrink-0">
                        <template x-if="!darkMode">
                            <x-ri-sun-fill class="size-4 text-white" />
                        </template>
                        <template x-if="darkMode">
                            <x-ri-moon-fill class="size-4 text-white" />
                        </template>
                    </button>
                    @endif
                </div>
                <div class="relative z-10 mt-1">
                    <a href="{{ route('account') }}" wire:navigate class="block w-full bg-white dark:bg-background-secondary text-base dark:hover:bg-background-secondary hover:bg-white text-xs font-medium py-1.5 rounded-[var(--button-radius)] text-center">
                        Profile
                    </a>
                </div>
            </div>

            <div class="flex flex-col flex-1 gap-1">
                @foreach (\App\Classes\Navigation::getDashboardLinks() as $nav)
                    @if (!empty($nav['children']))
                    <div x-data="{ activeAccordion: {{ $nav['active'] ? 'true' : 'false' }} }" class="relative w-full overflow-hidden">
                        <button @click="activeAccordion = !activeAccordion"
                            class="sidebar-link flex items-center justify-between w-full px-3 py-2.5 rounded-[var(--button-radius)] text-base hover:bg-background transition-all duration-200 group {{ $nav['active'] ? 'active' : '' }}">
                            <div class="flex items-center gap-2.5">
                                @isset($nav['icon'])
                                    <x-dynamic-component :component="$nav['icon']"
                                        class="size-5 text-base/40 transition-colors" />
                                @endisset
                                <p class="text-sm font-medium text-base">{{ $nav['name'] }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="active-indicator opacity-0 size-1.5 bg-primary rounded-full"></div>
                                <x-ri-arrow-down-s-line x-bind:class="{ 'rotate-180': activeAccordion }"
                                    class="size-4 text-base/40 ease-out duration-300" />
                            </div>
                        </button>
                        <div x-show="activeAccordion" x-collapse x-cloak>
                            <div class="flex flex-col gap-0.5 mt-0.5 ml-5 pl-3">
                                @foreach ($nav['children'] as $child)
                                    @if ($child['condition'] ?? true)
                                    <x-navigation.link :href="$child['url']"
                                        :spa="$child['spa'] ?? true"
                                        class="sidebar-link flex items-center justify-between px-2.5 py-2 rounded-[var(--button-radius)] text-base hover:bg-background transition-all duration-200 group {{ $child['active'] ? 'active' : '' }}">
                                        <div class="flex items-center gap-2.5">
                                            @isset($child['icon'])
                                                <x-dynamic-component :component="$child['icon']"
                                                    class="size-4 text-base/40 transition-colors" />
                                            @endisset
                                            <span class="text-sm font-medium text-base">{{ $child['name'] }}</span>
                                        </div>
                                        <div class="active-indicator opacity-0 size-1.5 bg-primary rounded-full"></div>
                                    </x-navigation.link>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @else
                    <x-navigation.link :href="$nav['url']"
                        :spa="$nav['spa'] ?? true"
                        class="sidebar-link flex items-center justify-between px-3 py-2.5 rounded-[var(--button-radius)] text-base hover:bg-background transition-all duration-200 group {{ $nav['active'] ? 'active' : '' }}">
                        <div class="flex items-center gap-2.5">
                            @isset($nav['icon'])
                                <x-dynamic-component :component="$nav['icon']"
                                    class="size-5 text-base/40 transition-colors" />
                            @endisset
                            <p class="text-sm font-medium text-base">{{ $nav['name'] }}</p>
                        </div>
                        <div class="active-indicator opacity-0 size-1.5 bg-primary rounded-full"></div>
                    </x-navigation.link>
                    @endif
                @endforeach
                @php $extraSidebarLinksWide = theme('extra_sidebar_links', []); @endphp
                @if(is_array($extraSidebarLinksWide) && count($extraSidebarLinksWide) > 0)
                    @foreach($extraSidebarLinksWide as $extraLink)
                        @if(!empty($extraLink['label']) && !empty($extraLink['url']))
                            <a href="{{ $extraLink['url'] }}"
                                class="sidebar-link flex items-center justify-between px-3 py-2.5 rounded-[var(--button-radius)] text-base hover:bg-background transition-all duration-200 group"
                                @if(!empty($extraLink['new_tab'])) target="_blank" rel="noopener noreferrer" @endif>
                                <div class="flex items-center gap-2.5">
                                    @php
                                        $__sbIconRaw = trim((string) ($extraLink['icon'] ?? ''));
                                        $__sbIconBlade = theme_sidebar_link_icon_blade($__sbIconRaw);
                                    @endphp
                                    @if ($__sbIconBlade === null)
                                        <i class="{{ $__sbIconRaw }} text-base/40 transition-colors text-lg"></i>
                                    @else
                                        <x-dynamic-component :component="$__sbIconBlade" class="size-5 text-base/40 transition-colors" />
                                    @endif
                                    <p class="text-sm font-medium text-base">{{ $extraLink['label'] }}</p>
                                </div>
                                @if(!empty($extraLink['new_tab']))
                                    <i class="ri-external-link-line text-base/40 text-sm"></i>
                                @else
                                    <div class="active-indicator opacity-0 size-1.5 bg-primary rounded-full"></div>
                                @endif
                            </a>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    @if(theme('show-support-sidebar-widget', true))
        <div class="p-4 border-t border-neutral">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center size-9 bg-primary/10 rounded-[var(--button-radius)] flex-shrink-0">
                    <i class="fa-brands fa-discord text-primary"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-base truncate">{{ Lang::has('dashboard.need_some_help') && trim(__('dashboard.need_some_help')) !== '' ? __('dashboard.need_some_help') : 'Need some help?' }}</p>
                    <a href="{{ theme('social-link-discord', 'https://discord.gg/buzz') }}" target="_blank" class="text-xs text-primary hover:underline">
                        {{ Lang::has('dashboard.join_discord') && trim(__('dashboard.join_discord')) !== '' ? __('dashboard.join_discord') : 'Join Discord' }}
                    </a>
                </div>
            </div>
        </div>
    @endif
</aside>

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
