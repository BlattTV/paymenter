@php $announcement = theme('announcement_banner');
$announcementEnabled = theme('announcement_banner_enabled');
$announcementLink = theme('announcement_banner_link', '');
$announcementNewTab = theme('announcement_banner_new_tab', false);
$hasBanner = !empty($announcement) && $announcementEnabled; @endphp
@php $showDiscord = theme('show-discord-icon-navbar');
$discordUrl = theme('social-link-discord'); @endphp
@php $showBrandText = theme('show-brand-text', true); @endphp
@php $isWideLayout = theme('dashboard-layout', 'default') === 'wide' && (
    request()->routeIs('dashboard') ||
    request()->routeIs('services*') ||
    request()->routeIs('invoices*') ||
    request()->routeIs('tickets*') ||
    request()->routeIs('affiliates*') ||
    request()->routeIs('affiliate*') ||
    request()->routeIs('account*') ||
    request()->routeIs('credits') ||
    request()->routeIs('client*')
); @endphp
@if($hasBanner)
    <div x-data="{ modalOpen: false }" class="w-full fixed top-0" style="z-index: 30;">
        @if(!empty($announcementLink))
            <a href="{{ $announcementLink }}" @if($announcementNewTab) target="_blank" rel="noopener noreferrer" @endif class="hidden md:block w-full bg-primary font-medium text-sm text-white text-center py-2 hover:bg-primary/90 transition-colors">
                <div class="whitespace-nowrap overflow-hidden text-ellipsis px-4">{!! $announcement !!}</div>
            </a>
            <button @click="modalOpen = true" type="button" class="md:hidden w-full bg-primary font-medium text-sm text-white text-center py-2">
                <div class="whitespace-nowrap overflow-hidden text-ellipsis px-4">{!! $announcement !!}</div>
            </button>
        @else
            <div class="hidden md:block w-full bg-primary font-medium text-sm text-white text-center py-2">
                <div class="whitespace-nowrap overflow-hidden text-ellipsis px-4">{!! $announcement !!}</div>
            </div>
            <button @click="modalOpen = true" type="button" class="md:hidden w-full bg-primary font-medium text-sm text-white text-center py-2">
                <div class="whitespace-nowrap overflow-hidden text-ellipsis px-4">{!! $announcement !!}</div>
            </button>
        @endif
        
        <div x-show="modalOpen" 
             x-cloak
             @click.away="modalOpen = false"
             @keydown.escape.window="modalOpen = false"
             class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div @click.stop
                 class="bg-background rounded-[var(--card-radius)] shadow-[var(--card-shadow)] border border-neutral max-w-lg w-full max-h-[80vh] overflow-y-auto"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-base">{{ __('dashboard.announcement') }}</h3>
                        <button @click="modalOpen = false" type="button" class="size-8 flex items-center justify-center rounded-[var(--button-radius)] hover:bg-background-secondary transition-colors">
                            <x-ri-close-line class="size-5 text-base/70" />
                        </button>
                    </div>
                    <div class="prose dark:prose-invert prose-sm max-w-none text-base">
                        {!! $announcement !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@php
$link1Text = theme('navbar_link_1_text', '');
$link1Url = theme('navbar_link_1_url', '');
$link2Text = theme('navbar_link_2_text', '');
$link2Url = theme('navbar_link_2_url', '');
$link3Text = theme('navbar_link_3_text', '');
$link3Url = theme('navbar_link_3_url', '');
$statusIndicatorLink = theme('navbar_status_indicator_link', '1');
@endphp
<div class="sticky z-50 @if($hasBanner) top-9 @else top-0 @endif">
<div class="px-4 w-full bg-background py-2 sm:py-0">
    <div class="xl:px-4 flex justify-end align-center gap-6 {{ $isWideLayout ? '' : 'container' }} mx-auto" style="{{ $isWideLayout ? '' : 'max-width: var(--container-max-width)' }}">
        <div class="relative hidden sm:block" x-data="{ open: false, adjustWidth: 0 }" x-init="$watch('open', value => {
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
            @php
$locale = app()->getLocale();
$flagMap = [
    'en' => 'GB',
    'fr' => 'FR',
    'de' => 'DE',
    'es' => 'ES',
    'nl' => 'NL',
    'it' => 'IT',
    'pt' => 'PT',
    'pl' => 'PL',
    'ru' => 'RU',
    'tr' => 'TR',
    'ar' => 'SA',
    'zh' => 'CN',
    'ja' => 'JP',
    'ko' => 'KR',
    'sr' => 'SR',
    'ua' => 'UA',
];
$countryCode = $flagMap[$locale] ?? strtoupper($locale);
            @endphp

            <button
                class="flex h-9 cursor-pointer items-center gap-2 rounded-[var(--button-radius)] px-2 text-sm font-medium text-muted hover:bg-background-secondary transition-colors"
                x-on:click="open = !open"
                title="{{ __('general.language_and_currency') }}" aria-label="{{ __('general.language_and_currency') }}">
                <img src="https://flagsapi.com/{{ $countryCode }}/flat/64.png" alt="{{ $locale }}" class="size-5 rounded object-cover" />
                <span>{{ session('currency', config('settings.default_currency')) }}</span>
                <x-ri-arrow-down-s-line class="size-4 text-base/50" />
            </button>

            <div x-ref="dropdown"
                class="absolute right-0 mt-2 w-56 py-2 bg-background rounded-[var(--card-radius)] shadow-[var(--card-shadow)] z-50 border border-neutral"
                x-bind:style="{ left: `-${adjustWidth}px` }"
                x-show="open"
                x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                x-on:click.outside="open = false" x-cloak>
                <div class="mb-2">
                    <livewire:components.language-switch />
                </div>
                <livewire:components.currency-switch />
            </div>
        </div>
        @if(!empty($link1Text))
            <a href="{{ $link1Url }}" class="text-sm font-medium text-muted hover:text-primary transition-colors flex items-center gap-3">
                @if($statusIndicatorLink === '1')
                    <p class="sf-indicator">
                        <span class="status-dot status-green"></span>
                    </p>
                @endif
                {{ $link1Text }}
            </a>
        @endif
        @if(!empty($link2Text))
            <a href="{{ $link2Url }}" class="text-sm font-medium text-muted hover:text-primary transition-colors flex items-center gap-3">
                @if($statusIndicatorLink === '2')
                    <p class="sf-indicator">
                        <span class="status-dot status-green"></span>
                    </p>
                @endif
                {{ $link2Text }}
            </a>
        @endif
        @if(!empty($link3Text))
            <a href="{{ $link3Url }}" class="text-sm font-medium text-muted hover:text-primary transition-colors flex items-center gap-3">
                @if($statusIndicatorLink === '3')
                    <p class="sf-indicator">
                        <span class="status-dot status-green"></span>
                    </p>
                @endif
                {{ $link3Text }}
            </a>
        @endif
    </div>
</div>
<nav class="w-full border-b border-neutral bg-background-secondary sticky @if($hasBanner) top-20 @else top-9 @endif z-40">
    <div x-data="{ slideOverOpen: false }"
        x-init="$watch('slideOverOpen', value => { document.documentElement.style.overflow = value ? 'hidden' : '' })"
        class="mx-auto flex h-16 items-center justify-between lg:justify-start px-4" style="{{ $isWideLayout ? '' : 'max-width: var(--container-max-width)' }}">

        <div class="flex items-center gap-2 flex-shrink-0">
            @php $disableHomePage = theme('disable-home-page'); @endphp
            @if($disableHomePage)
                <div class="flex items-center gap-2 group/logo">
                    <div class="flex size-10 items-center justify-center rounded-[var(--card-radius)]">
                        <x-logo class="size-10" />
                    </div>
                    @if($showBrandText)
                        <span class="text-xl font-bold tracking-tight text-base">{{ config('app.name') }}</span>
                    @endif
                </div>
            @else
                <a href="{{ route('home') }}" class="flex items-center gap-2 group/logo" wire:navigate>
                    <div class="flex size-10 items-center justify-center rounded-[var(--card-radius)]">
                        <x-logo class="size-10" />
                    </div>
                    @if($showBrandText)
                        <span class="text-xl font-bold tracking-tight text-base">{{ config('app.name') }}</span>
                    @endif
                </a>
            @endif
        </div>

        <div class="hidden md:flex items-center gap-8 flex-1 justify-center">
                @php $disableHome = theme('disable-home-page');
$homeUrl = rtrim(url('/'), '/'); @endphp
                @foreach (\App\Classes\Navigation::getLinks() as $nav)
                    @php $navUrl = isset($nav['url']) ? rtrim($nav['url'], '/') : null; @endphp
                    @if ($disableHome && $navUrl === $homeUrl)
                        @continue
                    @endif

                    @php
    $isShop = ($nav['name'] ?? null) === __('navigation.shop') || str_contains(($nav['icon'] ?? ''), 'shopping-bag');
                    @endphp

                    @if ($isShop && isset($nav['children']) && count($nav['children']) > 0)
                        @foreach ($nav['children'] as $child)
                            @php $childUrl = isset($child['url']) ? rtrim($child['url'], '/') : null; @endphp
                            @if ($disableHome && $childUrl === $homeUrl)
                                @continue
                            @endif
                            <a href="{{ $child['url'] }}"
                                class="text-sm font-medium text-base hover:text-primary transition-colors"
                                @if(isset($child['spa']) ? $child['spa'] : (isset($nav['spa']) ? $nav['spa'] : true)) wire:navigate @endif>
                                {{ $child['name'] }}
                            </a>
                        @endforeach
                    @elseif (isset($nav['children']) && count($nav['children']) > 0)
                        <div class="relative">
                            <x-dropdown :showArrow="false"
                                buttonClass="flex h-16 items-center gap-1 text-sm font-medium text-base hover:text-primary transition-colors outline-none focus:outline-none">
                                <x-slot:trigger>
                                    <span class="flex cursor-pointer items-center gap-1">
                                        {{ $nav['name'] }}
                                        <x-ri-arrow-down-s-line class="size-4 text-base/50" />
                                    </span>
                                </x-slot:trigger>
                                <x-slot:content>
                                    <div class="w-56 p-2">
                                        @foreach ($nav['children'] as $child)
                                            @php $childUrl = isset($child['url']) ? rtrim($child['url'], '/') : null; @endphp
                                            @if ($disableHome && $childUrl === $homeUrl)
                                                @continue
                                            @endif
                                            <a href="{{ $child['url'] }}"
                                                class="flex items-center gap-3 rounded-[var(--button-radius)] px-3 py-2 text-sm text-muted hover:bg-background-secondary hover:text-primary transition-colors"
                                                @if(isset($child['spa']) ? $nav['spa'] : true) wire:navigate @endif>
                                                {{ $child['name'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                </x-slot:content>
                            </x-dropdown>
                        </div>
                    @else
                        <a href="{{ $nav['url'] }}"
                            class="text-sm font-medium text-base hover:text-primary transition-colors"
                            @if(isset($nav['spa']) ? $nav['spa'] : true) wire:navigate @endif>
                            {{ $nav['name'] }}
                        </a>
                    @endif
                @endforeach
                @php $extraNavbarLinks = theme('extra_navbar_links', []); @endphp
                @if(is_array($extraNavbarLinks))
                    @foreach($extraNavbarLinks as $extraLink)
                        @if(!empty($extraLink['text']) && !empty($extraLink['url']))
                            <a href="{{ $extraLink['url'] }}"
                                class="text-sm font-medium text-base hover:text-primary transition-colors"
                                @if(!empty($extraLink['new_tab'])) target="_blank" rel="noopener noreferrer" @endif>
                                {{ $extraLink['text'] }}
                            </a>
                        @endif
                    @endforeach
                @endif
            </div>

        <div class="flex items-center flex-shrink-0">
            <div class="hidden md:flex items-center gap-1 cursor-pointer ml-3">
                @if($showDiscord && !empty($discordUrl))
                    <a href="{{ $discordUrl }}" target="_blank" rel="noopener noreferrer"
                        class="flex size-9 items-center justify-center rounded-[var(--button-radius)] text-muted hover:bg-background-secondary transition-colors"
                        title="{{ __('dashboard.join_discord') }}" aria-label="{{ __('dashboard.join_discord') }}">
                        <x-ri-discord-fill class="size-5" />
                    </a>
                @endif
            </div>

            <div class="hidden md:block">
                <livewire:components.cart />
            </div>

            @if(auth()->check())
                <div class="hidden lg:flex items-center gap-1 cursor-pointer ml-3">
                    <livewire:components.notifications />
                </div>
                <div class="hidden lg:flex">
                    <x-dropdown :showArrow="false" buttonClass="p-0" panelClass="absolute right-0 mt-2 w-64 bg-background rounded-[var(--card-radius)] shadow-[var(--card-shadow)] z-10 border border-neutral overflow-hidden">
                        <x-slot:trigger>
                            <div class="flex items-center gap-2.5 rounded-[var(--button-radius)] px-2 py-1.5 hover:bg-background-secondary transition-colors cursor-pointer group" aria-label="{{ __('general.user_menu') }}">
                                <div class="size-9 rounded-[var(--card-radius)] border border-neutral/50 bg-background-secondary flex items-center justify-center">
                                    <x-ri-user-fill class="size-4.5 text-base" />
                                </div>
                                <div class="flex flex-col items-start min-w-0">
                                    <span class="text-sm font-semibold text-base truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                                    <span class="text-xs text-base/60 truncate max-w-[120px]">{{ auth()->user()->email }}</span>
                                </div>
                                <x-ri-arrow-down-s-line class="size-4 text-base/50 flex-shrink-0" />
                            </div>
                        </x-slot:trigger>
                        <x-slot:content>
                            <div class="px-4 py-3 bg-background-secondary border-b border-neutral/50">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-[var(--card-radius)] border border-neutral/50 bg-background flex items-center justify-center flex-shrink-0">
                                        <x-ri-user-fill class="size-5 text-base" />
                                    </div>
                                    <div class="flex flex-col min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-base truncate">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-base/60 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="py-2">
                                @foreach (\App\Classes\Navigation::getAccountDropdownLinks() as $nav)
                                    <a href="{{ $nav['url'] }}" 
                                       class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-base hover:bg-background-secondary hover:text-primary transition-colors group"
                                       @if(isset($nav['spa']) ? $nav['spa'] : true) wire:navigate @endif>
                                        <div class="flex items-center justify-center size-5 flex-shrink-0">
                                            @if(str_contains($nav['url'], 'dashboard'))
                                                <x-ri-dashboard-fill class="size-4 text-base/70 group-hover:text-primary transition-colors" />
                                            @elseif(str_contains($nav['url'], 'tickets'))
                                                <x-ri-ticket-fill class="size-4 text-base/70 group-hover:text-primary transition-colors" />
                                            @elseif(str_contains($nav['url'], 'account'))
                                                <x-ri-user-settings-fill class="size-4 text-base/70 group-hover:text-primary transition-colors" />
                                            @elseif(str_contains($nav['url'], 'admin'))
                                                <x-ri-shield-user-fill class="size-4 text-base/70 group-hover:text-primary transition-colors" />
                                            @endif
                                        </div>
                                        <span>{{ $nav['name'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                            <div class="border-t border-neutral/50 pt-2 pb-2">
                                <livewire:auth.logout />
                            </div>
                        </x-slot:content>
                    </x-dropdown>
                </div>
            @else
                <div class="hidden lg:flex items-center gap-3 ml-2">
                    <a href="{{ route('login') }}" wire:navigate class="rounded-[var(--button-radius)] text-sm font-medium px-4 py-2 border border-neutral bg-background-secondary text-base hover:border-primary/50 hover:text-primary transition-colors">
                        {{ __('navigation.login') }}
                    </a>
                    @if(!config('settings.registration_disabled', false))
                        <a href="{{ route('register') }}" wire:navigate class="rounded-[var(--button-radius)] text-sm font-medium bg-primary px-4 py-2 text-white hover:bg-primary/90 transition-colors">
                            {{ __('navigation.register') }}
                        </a>
                    @endif
                </div>
            @endif

            <button
                @click="slideOverOpen = !slideOverOpen"
                class="relative flex size-9 lg:hidden items-center justify-center rounded-[var(--button-radius)] text-muted hover:bg-background-secondary transition-colors"
                aria-label="{{ __('general.toggle_menu') }}">

                <span
                    x-show="!slideOverOpen"
                    x-transition:enter="transition duration-300"
                    x-transition:enter-start="opacity-0 -rotate-90 scale-75"
                    x-transition:enter-end="opacity-100 rotate-0 scale-100"
                    x-transition:leave="transition duration-150"
                    x-transition:leave-start="opacity-100 rotate-0 scale-100"
                    x-transition:leave-end="opacity-0 rotate-90 scale-75"
                    class="absolute inset-0 flex items-center justify-center"
                    aria-hidden="true">
                    <x-ri-menu-fill class="size-5" />
                </span>

                <span
                    x-show="slideOverOpen"
                    x-transition:enter="transition duration-300"
                    x-transition:enter-start="opacity-0 rotate-90 scale-75"
                    x-transition:enter-end="opacity-100 rotate-0 scale-100"
                    x-transition:leave="transition duration-150"
                    x-transition:leave-start="opacity-100 rotate-0 scale-100"
                    x-transition:leave-end="opacity-0 -rotate-90 scale-75"
                    class="absolute inset-0 flex items-center justify-center"
                    aria-hidden="true">
                    <x-ri-close-fill class="size-5" />
                </span>

            </button>

            <div
                x-show="slideOverOpen"
                @keydown.window.escape="slideOverOpen=false"
                x-cloak
                class="fixed inset-0 z-[99]"
                aria-modal="true"
                tabindex="-1">
                
                <div
                    x-show="slideOverOpen"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @click="slideOverOpen = false"
                    class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

                <div
                    x-show="slideOverOpen"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    class="absolute right-0 top-0 h-full w-[85%] max-w-sm bg-background shadow-2xl flex flex-col">
                    
                    <div class="flex items-center justify-between p-4 border-b border-neutral">
                        <a href="{{ route('home') }}" class="flex items-center gap-2" wire:navigate @click="slideOverOpen = false">
                            <x-logo class="size-8" />
                            <span class="font-bold text-base">{{ config('app.name') }}</span>
                        </a>
                        <button @click="slideOverOpen = false" class="size-10 flex items-center justify-center rounded-[var(--button-radius)] hover:bg-background-secondary transition-colors">
                            <x-ri-close-line class="size-5 text-base/70" />
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto">
                        <div class="p-4">
                            <x-navigation.sidebar-links />
                        </div>
                    </div>

                    <div class="mx-4 mb-3 px-4 py-3 flex items-center {{ theme('force_theme_mode', 'none') === 'none' ? 'justify-between' : 'justify-start' }} bg-background-secondary rounded-[var(--card-radius)] border border-neutral">
                        <div class="flex items-center gap-2">
                            <livewire:components.cart />
                            @if(auth()->check())
                                <livewire:components.notifications />
                            @endif
                        </div>
                        @if(theme('force_theme_mode', 'none') === 'none')
                            <div>
                                <x-theme-toggle class="size-10 rounded-[var(--button-radius)] border border-neutral bg-background" />
                            </div>
                        @endif
                    </div>

                    <div class="border-t border-neutral p-4 bg-background-secondary">
                        @if(auth()->check())
                            <div class="flex items-center justify-between gap-3">
                                <a href="/dashboard" wire:navigate @click="slideOverOpen = false"
                                   class="size-10 rounded-[var(--card-radius)] border border-neutral bg-background flex items-center justify-center flex-shrink-0 text-base/70 hover:text-primary hover:border-primary/50 transition-colors">
                                    <x-ri-user-fill class="size-5" />
                                </a>
                                <div class="flex items-center gap-2 flex-shrink-0 ml-auto">
                                    @if(auth()->user()->hasPermission('admin.view'))
                                        <a href="/admin" @click="slideOverOpen = false"
                                           class="w-10 h-10 rounded-[var(--button-radius)] border border-neutral bg-background flex items-center justify-center text-base/70 hover:text-primary hover:border-primary/50 transition-colors flex-shrink-0">
                                            <x-ri-shield-user-fill class="size-5" />
                                        </a>
                                    @endif
                                    <div class="flex-shrink-0 [&_button]:w-10 [&_button]:h-10 [&_button]:!px-0 [&_button]:!py-0 [&_button]:rounded-[var(--button-radius)] [&_button]:border [&_button]:border-neutral [&_button]:bg-background [&_button]:justify-center [&_button]:hover:border-red-500/40 [&_button]:hover:bg-red-500/10 [&_button_div]:!m-0 [&_button_span]:hidden">
                                        <livewire:auth.logout />
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col gap-2">
                                <a href="{{ route('login') }}" wire:navigate @click="slideOverOpen = false"
                                   class="flex items-center justify-center gap-2 w-full px-4 py-3 text-sm font-semibold text-white bg-primary rounded-[var(--button-radius)] hover:bg-primary/90 transition-colors">
                                    <x-ri-login-box-line class="size-4" />
                                    {{ __('navigation.login') }}
                                </a>
                                @if(!config('settings.registration_disabled', false))
                                    <a href="{{ route('register') }}" wire:navigate @click="slideOverOpen = false"
                                       class="flex items-center justify-center gap-2 w-full px-4 py-3 text-sm font-semibold text-base bg-background border border-neutral rounded-[var(--button-radius)] hover:border-primary/50 hover:text-primary transition-colors">
                                        <x-ri-user-add-line class="size-4" />
                                        {{ __('navigation.register') }}
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
@if(theme('dashboard-layout', 'default') === 'header' && (
    request()->routeIs('dashboard') ||
    request()->routeIs('services*') ||
    request()->routeIs('invoices*') ||
    request()->routeIs('tickets*') ||
    request()->routeIs('affiliates*') ||
    request()->routeIs('affiliate*') ||
    request()->routeIs('account*') ||
    request()->routeIs('credits') ||
    request()->routeIs('client*')
)))
<div class="bg-background-secondary border-b border-neutral hidden md:block overflow-visible">
    <div class="mx-auto px-4 overflow-visible" style="max-width: var(--container-max-width)">
        <div class="flex items-center justify-between h-12 overflow-visible">
            <div class="flex items-center gap-1 overflow-visible">
                @foreach (\App\Classes\Navigation::getDashboardLinks() as $nav)
                    @if (!empty($nav['children']))
                        <x-dropdown :showArrow="false">
                            <x-slot:trigger>
                                <div class="flex items-center gap-1.5 px-3 py-2 rounded-[var(--button-radius)] text-sm font-medium whitespace-nowrap transition-colors {{ $nav['active'] ? 'bg-primary/10 text-primary' : 'text-base/70 hover:bg-background hover:text-base' }}">
                                    @isset($nav['icon'])
                                        <x-dynamic-component :component="$nav['icon']" class="size-4" />
                                    @endisset
                                    <span>{{ $nav['name'] }}</span>
                                    <x-ri-arrow-down-s-line class="size-3.5" />
                                </div>
                            </x-slot:trigger>
                            <x-slot:content>
                                <div class="py-1">
                                    @foreach ($nav['children'] as $child)
                                        @if ($child['condition'] ?? true)
                                            <x-navigation.link :href="$child['url']" :spa="$child['spa'] ?? true"
                                                class="flex items-center gap-2 px-3 py-2 text-sm {{ $child['active'] ? 'text-primary bg-primary/5' : 'text-base/70 hover:bg-background-secondary hover:text-base' }} transition-colors">
                                                @isset($child['icon'])
                                                    <x-dynamic-component :component="$child['icon']" class="size-4" />
                                                @endisset
                                                <span>{{ $child['name'] }}</span>
                                            </x-navigation.link>
                                        @endif
                                    @endforeach
                                </div>
                            </x-slot:content>
                        </x-dropdown>
                    @else
                        <a href="{{ $nav['url'] }}" wire:navigate
                            class="flex items-center gap-1.5 px-3 py-2 rounded-[var(--button-radius)] text-sm font-medium whitespace-nowrap transition-colors {{ $nav['active'] ? 'bg-primary/10 text-primary' : 'text-base/70 hover:bg-background hover:text-base' }}">
                            @isset($nav['icon'])
                                <x-dynamic-component :component="$nav['icon']" class="size-4" />
                            @endisset
                            <span>{{ $nav['name'] }}</span>
                        </a>
                    @endif
                @endforeach
                @php $extraSidebarLinksNavbar = theme('extra_sidebar_links', []); @endphp
                @if(is_array($extraSidebarLinksNavbar) && count($extraSidebarLinksNavbar) > 0)
                    @foreach($extraSidebarLinksNavbar as $extraLink)
                        @if(!empty($extraLink['label']) && !empty($extraLink['url']))
                            <a href="{{ $extraLink['url'] }}"
                                class="flex items-center gap-1.5 px-3 py-2 rounded-[var(--button-radius)] text-sm font-medium whitespace-nowrap transition-colors text-base/70 hover:bg-background hover:text-base"
                                @if(!empty($extraLink['new_tab'])) target="_blank" rel="noopener noreferrer" @endif>
                                @php
                                    $__sbIconRaw = trim((string) ($extraLink['icon'] ?? ''));
                                    $__sbIconBlade = theme_sidebar_link_icon_blade($__sbIconRaw);
                                @endphp
                                @if ($__sbIconBlade === null)
                                    <i class="{{ $__sbIconRaw }} text-sm"></i>
                                @else
                                    <x-dynamic-component :component="$__sbIconBlade" class="size-4 shrink-0" />
                                @endif
                                <span>{{ $extraLink['label'] }}</span>
                                @if(!empty($extraLink['new_tab']))
                                    <i class="ri-external-link-line text-xs"></i>
                                @endif
                            </a>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endif
</div>