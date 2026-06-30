@php 
    $disableHome = theme('disable-home-page');
    $homeUrl = rtrim(url('/'), '/');
    $hasSidebar = isset($sidebar) ? $sidebar : (
        request()->routeIs('dashboard') ||
        request()->routeIs('services*') ||
        request()->routeIs('invoices*') ||
        request()->routeIs('tickets*') ||
        request()->routeIs('affiliates*') ||
        request()->routeIs('affiliate*') ||
        request()->routeIs('account*') ||
        request()->routeIs('credits') ||
        request()->routeIs('client*')
    );
@endphp

@if ($hasSidebar)
<div class="flex flex-col flex-1 bg-background-secondary rounded-[var(--card-radius)] shadow-[var(--card-shadow)] border border-neutral p-2 gap-2">
    @foreach (\App\Classes\Navigation::getDashboardLinks() as $nav)
        @if (!empty($nav['children']))
            <x-dropdown :showArrow="false" buttonClass="w-full" panelClass="relative w-full mt-1 bg-background rounded-[var(--card-radius)] shadow-[var(--card-shadow)] border border-neutral" :persistOnDesktop="true">
                <x-slot:trigger>
                    <div class="sidebar-link flex items-center justify-between w-full px-3 py-2.5 rounded-[var(--button-radius)] text-base hover:bg-slate-50 transition-all duration-200 group {{ $nav['active'] ? 'active' : '' }}">
                        <div class="flex items-center gap-2.5">
                            @isset($nav['icon'])
                                <x-dynamic-component :component="$nav['icon']" class="size-5 text-base/40 transition-colors" />
                            @endisset
                            <p class="text-sm font-medium text-base">{{ $nav['name'] }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="active-indicator opacity-0 size-1.5 bg-primary rounded-full"></div>
                            <x-ri-arrow-down-s-line class="size-4 text-base/40" />
                        </div>
                    </div>
                </x-slot:trigger>
                <x-slot:content>
                    <div class="p-2">
                        @foreach ($nav['children'] as $child)
                            @if ($child['condition'] ?? true)
                                <x-navigation.link :href="$child['url']" :spa="$child['spa'] ?? true"
                                    class="flex items-center gap-2.5 px-3 py-2 rounded-[var(--button-radius)] text-sm {{ $child['active'] ? 'bg-primary/10 text-primary' : 'text-base hover:bg-background-secondary' }} transition-colors">
                                    @isset($child['icon'])
                                        <x-dynamic-component :component="$child['icon']" class="size-4" />
                                    @endisset
                                    {{ $child['name'] }}
                                </x-navigation.link>
                            @endif
                        @endforeach
                    </div>
                </x-slot:content>
            </x-dropdown>
        @else
            <x-navigation.link :href="$nav['url']" :spa="$nav['spa'] ?? true"
                class="sidebar-link flex items-center justify-between px-3 py-2.5 rounded-[var(--button-radius)] text-base hover:bg-background-secondary transition-all duration-200 group {{ $nav['active'] ? 'active' : '' }}">
                <div class="flex items-center gap-2.5">
                    @isset($nav['icon'])
                        <x-dynamic-component :component="$nav['icon']" class="size-5 text-base/40 transition-colors" />
                    @endisset
                    <p class="text-sm font-medium text-base">{{ $nav['name'] }}</p>
                </div>
                <div class="active-indicator opacity-0 size-1.5 bg-primary rounded-full"></div>
            </x-navigation.link>
        @endif
    @endforeach
    @php $extraSidebarLinks = theme('extra_sidebar_links', []); @endphp
    @if(is_array($extraSidebarLinks) && count($extraSidebarLinks) > 0)
        @foreach($extraSidebarLinks as $extraLink)
            @if(!empty($extraLink['label']) && !empty($extraLink['url']))
                <a href="{{ $extraLink['url'] }}"
                    class="sidebar-link flex items-center justify-between px-3 py-2.5 rounded-[var(--button-radius)] text-base hover:bg-slate-50 transition-all duration-200 group"
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
@else
<div class="flex flex-col flex-1 bg-background-secondary rounded-[var(--card-radius)] shadow-[var(--card-shadow)] border border-neutral p-2 gap-0.5">
    @foreach (\App\Classes\Navigation::getLinks() as $nav)
        @php $navUrl = isset($nav['url']) ? rtrim($nav['url'], '/') : null; @endphp
        @if ($disableHome && $navUrl === $homeUrl)
            @continue
        @endif
        @php
            $isShop = ($nav['name'] ?? null) === __('navigation.shop') || str_contains(($nav['icon'] ?? ''), 'shopping-bag');
        @endphp
        @if ($isShop && !empty($nav['children']))
            @foreach ($nav['children'] as $child)
                @php 
                    $childUrl = isset($child['url']) ? rtrim($child['url'], '/') : null;
                    $currentUrl = rtrim(request()->livewireUrl() ?? request()->url(), '/');
                    $isChildActive = $childUrl && $currentUrl === $childUrl;
                @endphp
                @if ($disableHome && $childUrl === $homeUrl)
                    @continue
                @endif
                <x-navigation.link :href="$child['url']" :spa="$child['spa'] ?? ($nav['spa'] ?? true)"
                    class="sidebar-link flex items-center justify-between px-3 py-2.5 rounded-[var(--button-radius)] text-base hover:bg-slate-50 transition-all duration-200 group {{ $isChildActive ? 'active' : '' }}">
                    <div class="flex items-center gap-2.5">
                        @isset($child['icon'])
                            <x-dynamic-component :component="$child['icon']" class="size-4 text-base/40 transition-colors" />
                        @endisset
                        <p class="text-sm font-medium text-base">{{ $child['name'] }}</p>
                    </div>
                    <div class="active-indicator opacity-0 size-1.5 bg-primary rounded-full"></div>
                </x-navigation.link>
            @endforeach
        @elseif (!empty($nav['children']))
            <x-dropdown :showArrow="false" buttonClass="w-full" panelClass="relative w-full mt-1 bg-background rounded-[var(--card-radius)] shadow-[var(--card-shadow)] border border-neutral">
                <x-slot:trigger>
                    <div class="sidebar-link flex items-center justify-between w-full px-3 py-2.5 rounded-[var(--button-radius)] text-base hover:bg-slate-50 transition-all duration-200 group">
                        <div class="flex items-center gap-2.5">
                            @isset($nav['icon'])
                                <x-dynamic-component :component="$nav['icon']" class="size-5 text-base/40 transition-colors" />
                            @endisset
                            <p class="text-sm font-medium text-base">{{ $nav['name'] }}</p>
                        </div>
                        <x-ri-arrow-down-s-line class="size-4 text-base/40" />
                    </div>
                </x-slot:trigger>
                <x-slot:content>
                    <div class="p-2">
                        @foreach ($nav['children'] as $child)
                            @php 
                                $childUrl = isset($child['url']) ? rtrim($child['url'], '/') : null;
                                $currentUrl = rtrim(request()->livewireUrl() ?? request()->url(), '/');
                                $isChildActive = $childUrl && $currentUrl === $childUrl;
                            @endphp
                            @if ($disableHome && $childUrl === $homeUrl)
                                @continue
                            @endif
                            <x-navigation.link :href="$child['url']" :spa="$child['spa'] ?? true"
                                class="flex items-center gap-2.5 px-3 py-2 rounded-[var(--button-radius)] text-sm {{ $isChildActive ? 'bg-primary/10 text-primary' : 'text-base hover:bg-slate-50' }} transition-colors">
                                @isset($child['icon'])
                                    <x-dynamic-component :component="$child['icon']" class="size-4" />
                                @endisset
                                {{ $child['name'] }}
                            </x-navigation.link>
                        @endforeach
                    </div>
                </x-slot:content>
            </x-dropdown>
        @else
            @php 
                $currentUrl = rtrim(request()->livewireUrl() ?? request()->url(), '/');
                $isNavActive = $navUrl && $currentUrl === $navUrl;
            @endphp
            <x-navigation.link :href="$nav['url']" :spa="$nav['spa'] ?? true"
                class="sidebar-link flex items-center justify-between px-3 py-2.5 rounded-[var(--button-radius)] text-base hover:bg-slate-50 transition-all duration-200 group {{ $isNavActive ? 'active' : '' }}">
                <div class="flex items-center gap-2.5">
                    @isset($nav['icon'])
                        <x-dynamic-component :component="$nav['icon']" class="size-5 text-base/40 transition-colors" />
                    @endisset
                    <p class="text-sm font-medium text-base">{{ $nav['name'] }}</p>
                </div>
                <div class="active-indicator opacity-0 size-1.5 bg-primary rounded-full"></div>
            </x-navigation.link>
        @endif
    @endforeach
    @php $extraSidebarLinksNonDash = theme('extra_sidebar_links', []); @endphp
    @if(is_array($extraSidebarLinksNonDash) && count($extraSidebarLinksNonDash) > 0)
        @foreach($extraSidebarLinksNonDash as $extraLink)
            @if(!empty($extraLink['label']) && !empty($extraLink['url']))
                <a href="{{ $extraLink['url'] }}"
                    class="sidebar-link flex items-center justify-between px-3 py-2.5 rounded-[var(--button-radius)] text-base hover:bg-slate-50 transition-all duration-200 group"
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
    <div class="flex flex-row items-center my-4 justify-between md:hidden">
        <x-dropdown>
            <x-slot:trigger>
                <div class="flex flex-col">
                    <span class="text-sm text-base font-semibold text-nowrap">{{ strtoupper(app()->getLocale()) }} <span class="text-base/50 font-semibold">|</span> {{ session('currency', config('settings.default_currency')) }}</span>
                </div>
            </x-slot:trigger>
            <x-slot:content>
                <strong class="block p-2 text-xs font-semibold uppercase text-base/50"> Language </strong>
                <livewire:components.language-switch />
                <livewire:components.currency-switch />
            </x-slot:content>
        </x-dropdown>
    </div>
</div>
@endif
