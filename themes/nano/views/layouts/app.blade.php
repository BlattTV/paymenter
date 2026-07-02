<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(in_array(app()->getLocale(), config('app.rtl_locales'))) dir="rtl" @endif>
    
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @php
        $fontFamily = theme('font_family', 'Geist Sans');
        $googleFontsMap = [
            'Inter' => 'Inter:wght@100..900',
            'Roboto' => 'Roboto:wght@100;300;400;500;700;900',
            'Open Sans' => 'Open+Sans:wght@300;400;500;600;700;800',
            'Lato' => 'Lato:wght@100;300;400;700;900',
            'Montserrat' => 'Montserrat:wght@100..900',
            'Poppins' => 'Poppins:wght@100;200;300;400;500;600;700;800;900',
            'Raleway' => 'Raleway:wght@100..900',
            'Nunito' => 'Nunito:wght@200..1000',
            'Source Sans Pro' => 'Source+Sans+3:wght@200..900',
            'Ubuntu' => 'Ubuntu:wght@300;400;500;700',
            'Playfair Display' => 'Playfair+Display:wght@400;500;600;700;800;900',
            'Merriweather' => 'Merriweather:wght@300;400;700;900',
            'Oswald' => 'Oswald:wght@200..700',
            'Lora' => 'Lora:wght@400;500;600;700',
            'PT Sans' => 'PT+Sans:wght@400;700',
            'Noto Sans' => 'Noto+Sans:wght@100..900',
            'Work Sans' => 'Work+Sans:wght@100..900',
            'DM Sans' => 'DM+Sans:wght@100..1000',
            'Manrope' => 'Manrope:wght@200..800',
            'Plus Jakarta Sans' => 'Plus+Jakarta+Sans:wght@200..800',
            'Space Grotesk' => 'Space+Grotesk:wght@300..700',
            'Outfit' => 'Outfit:wght@100..900',
            'Sora' => 'Sora:wght@100..800',
        ];
    @endphp
    @if($fontFamily === 'Geist Sans')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/geist@1.3.1/dist/fonts/geist-sans/style.min.css">
    @elseif($fontFamily !== 'System Default' && isset($googleFontsMap[$fontFamily]))
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family={{ $googleFontsMap[$fontFamily] }}&display=swap" rel="stylesheet">
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @isset($title)
            {{ $title }} - {{ config('app.name', 'Paymenter') }}
        @else
            {{ config('app.name', 'Paymenter') }}
        @endisset
    </title>
       @livewireStyles
    @vite(['themes/' . config('settings.theme') . '/js/app.js', 'themes/' . config('settings.theme') . '/css/app.css'], config('settings.theme'))
    @include('layouts.colors')

    @if (config('settings.favicon'))
        <link rel="icon" href="{{ Storage::url(config('settings.favicon')) }}" type="image/png">
    @endif
    @isset($title)
    <meta content="{{ isset($title) ? $title . ' - ' . config('app.name', 'Paymenter') : config('app.name', 'Paymenter') }}" property="og:title">
    <meta content="{{ isset($title) ? $title . ' - ' . config('app.name', 'Paymenter') : config('app.name', 'Paymenter') }}" name="title">
    @endisset
    @php
        $metaDescription = $description ?? theme('meta_description', 'Minecraft Server mieten bei Hoelni-Hosting – leistungsstarke Gameserver mit NVMe-SSDs, DDoS-Schutz, Sofort-Setup und eigenem Panel. Serverstandort Deutschland, ab 5 € im Monat.');
        $metaImage = $image ?? null;
        $metaSiteName = theme('meta_site_name', config('app.name', 'Paymenter'));
        $themeMetaImage = theme('meta_image', '');
        
        if (is_array($themeMetaImage)) {
            $themeMetaImage = $themeMetaImage[0] ?? '';
        }
        
        if (!$metaImage && $themeMetaImage) {
            if (str_starts_with($themeMetaImage, 'http') || str_starts_with($themeMetaImage, '/')) {
                $metaImage = $themeMetaImage;
            } else {
                $metaImage = asset('storage/' . $themeMetaImage);
            }
        }
    @endphp
    @if($metaDescription)
    <meta content="{{ $metaDescription }}" property="og:description">
    <meta content="{{ $metaDescription }}" name="description">
    @endif
    @if($metaImage)
    <meta content="{{ $metaImage }}" property="og:image">
    <meta content="{{ $metaImage }}" name="image">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="{{ $metaImage }}">
    @endif
    <meta property="og:site_name" content="{{ $metaSiteName }}">
    <meta property="og:type" content="website">
   
    <meta name="theme-color" content="{{ theme('primary') }}">

    <meta name="robots" content="{{ theme('meta_robots', 'index,follow') }}">

    {{-- SEO: canonical URL (strips query strings, avoids duplicate content) --}}
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- SEO: Organization + WebSite structured data --}}
    @php
        $seoOrganization = [
            '@type' => 'Organization',
            'name' => config('app.name'),
            'url' => config('app.url'),
        ];
        if (config('settings.logo')) {
            $seoOrganization['logo'] = \Storage::url(config('settings.logo'));
        }
        $seoGraph = [
            '@context' => 'https://schema.org',
            '@graph' => [
                $seoOrganization,
                [
                    '@type' => 'WebSite',
                    'name' => $metaSiteName ?? config('app.name'),
                    'url' => config('app.url'),
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($seoGraph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

    {{-- Page-specific SEO tags pushed by individual views --}}
    @stack('head')

    {{-- Analytics scripts are only loaded after the visitor consented (TTDSG/GDPR) --}}
    @php
        $analyticsConfigured = theme('google_analytics_id') || theme('facebook_pixel_id') || theme('microsoft_clarity_id');
    @endphp
    @if($analyticsConfigured)
    <script>
        (function () {
            var loaded = false;
            window.pmLoadAnalytics = function () {
                if (loaded) return;
                loaded = true;
                @if(theme('google_analytics_id'))
                var ga = document.createElement('script');
                ga.async = true;
                ga.src = 'https://www.googletagmanager.com/gtag/js?id={{ theme('google_analytics_id') }}';
                document.head.appendChild(ga);
                window.dataLayer = window.dataLayer || [];
                window.gtag = window.gtag || function(){dataLayer.push(arguments);};
                gtag('js', new Date());
                gtag('config', '{{ theme('google_analytics_id') }}');
                @endif
                @if(theme('facebook_pixel_id'))
                !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', '{{ theme('facebook_pixel_id') }}');
                fbq('track', 'PageView');
                @endif
                @if(theme('microsoft_clarity_id'))
                (function(c,l,a,r,i,t,y){c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y)})(window,document,"clarity","script","{{ theme('microsoft_clarity_id') }}");
                @endif
            };
            try {
                if (localStorage.getItem('pm_cookie_consent') === 'granted') {
                    window.pmLoadAnalytics();
                }
            } catch (e) {}
        })();
    </script>
    @endif

    @if(theme('custom_css'))
    <style>{!! theme('custom_css') !!}</style>
    @endif

    @if(theme('custom_head_html'))
    {!! theme('custom_head_html') !!}
    @endif

    {!! hook('head') !!}
</head>

@php
    $forceTheme = theme('force_theme_mode', 'none');
    $defaultTheme = theme('default_theme_mode', 'dark');
@endphp
<body class="bg-background text-base min-h-screen flex flex-col antialiased relative overflow-x-hidden" x-cloak x-data="{
    forceTheme: '{{ $forceTheme }}',
    defaultTheme: '{{ $defaultTheme }}',
    darkMode: null,
    init() {
        if (this.forceTheme === 'dark') {
            this.darkMode = true;
        } else if (this.forceTheme === 'light') {
            this.darkMode = false;
        } else {
            const stored = localStorage.getItem('darkMode');
            if (stored !== null) {
                this.darkMode = stored === 'true';
            } else if (this.defaultTheme === 'system') {
                this.darkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            } else {
                this.darkMode = this.defaultTheme === 'dark';
            }
        }
        if (this.forceTheme === 'none') {
            this.$watch('darkMode', value => localStorage.setItem('darkMode', value));
        }
    }
}" :class="{'dark': darkMode}">
    @php
        $isHomePage = request()->routeIs('home') || request()->path() === '/';
        $hasFullBleedHero = $isHomePage || request()->routeIs('category.show');
        $isDashboardPage = isset($sidebar) && $sidebar;
    @endphp
    @if($isHomePage && theme('home_background_image'))
        <x-page-background page="home" />
    @elseif($isDashboardPage && theme('dashboard_background_image'))
        <x-page-background page="dashboard" />
    @endif
    {!! hook('body') !!}
    <x-navigation />
    @php
        $sidebarEnabled = (isset($sidebar) && $sidebar);
        $showSidebar = $sidebarEnabled
            && !(theme('pterodactyl-enabled')
                && \Illuminate\Support\Str::of(request()->path())->startsWith('services/'));
        $showMx4 = !theme('pterodactyl-enabled') || !\Illuminate\Support\Str::of(request()->path())->startsWith('services/');
        $dashboardLayout = theme('dashboard-layout', 'default');
        $isWideLayout = $sidebarEnabled && $dashboardLayout === 'wide';
        $isHeaderLayout = $sidebarEnabled && $dashboardLayout === 'header';
        $isDefaultLayout = !$isWideLayout && !$isHeaderLayout;
        $hasBanner = !empty(theme('announcement_banner')) && theme('announcement_banner_enabled');
        $bannerOffset = $hasBanner && ($isWideLayout || $isHeaderLayout);
    @endphp

    @if($isWideLayout && $showSidebar)
        <x-navigation.sidebar-wide :bannerOffset="$bannerOffset" />
    @endif

    <div class="relative z-1 mx-auto w-full {{ $isWideLayout ? 'layout-wide-content' : '' }} {{ $isHeaderLayout ? '' : ($sidebarEnabled && $isDefaultLayout ? 'container' : '') }}" style="{{ $sidebarEnabled && $isDefaultLayout && !$isHeaderLayout ? 'max-width: var(--container-max-width)' : '' }}">

        @php
            $showBroadcast = isset($sidebar) && $sidebar && theme('global_broadcast_enabled');
            $broadcastStyle = theme('global_broadcast_style', 'style1');
        @endphp

        <div class="flex flex-grow w-full min-w-0 {{ $sidebarEnabled && $isDefaultLayout ? 'mt-16 mx-auto' : '' }} {{ $isWideLayout ? 'mx-auto mt-8 px-4 xl:px-0' : '' }}" style="{{ $isWideLayout ? 'max-width: var(--container-max-width)' : '' }}{{ $bannerOffset && $isWideLayout ? '; margin-top: calc(2rem + 2.5rem)' : '' }}{{ $bannerOffset && $isHeaderLayout ? 'margin-top: 2.5rem' : '' }}">
            @if ($showSidebar && $isDefaultLayout)
                <x-navigation.sidebar title="$title" />
            @endif
            <div class="
                {{ $showSidebar && $isDefaultLayout ? 'rtl:ml-0 rtl:md:mr-60' : '' }}
                {{ $showMx4 ? 'mx-0' : '' }}
                {{ $isDefaultLayout ? '' : '' }}
                flex flex-col flex-grow min-w-0
            ">
                @if($showBroadcast)
                    <div
                        x-data="{
                          get key() {
                            const raw = '{{ Str::slug(theme('global_broadcast_title') ?? '') }}';
                            return `alert_open_${raw}`;
                          },
                          get initial() {
                            return localStorage.getItem(this.key) !== 'false';
                          },
                          show: null,
                          close() {
                            this.show = false;
                            localStorage.setItem(this.key, 'false');
                          },
                          init() {
                            this.show = this.initial;
                          }
                        }"
                        x-init="init()"
                        x-show="show"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        @if ($isHeaderLayout)
                            class="w-full mx-auto mt-6 mb-3 px-4 md:px-4{{ !$isHeaderLayout ? ' mb-6' : '' }}"
                            style="max-width: var(--container-max-width)"
                        @elseif ($isWideLayout)
                            class=""
                        @elseif ($isDefaultLayout)
                            class="mx-4 {{ !$isHeaderLayout ? 'mb-6' : '' }}"
                        @else
                            class="{{ !$isHeaderLayout ? 'mb-6' : '' }}"
                        @endif
                    >
                        @if($broadcastStyle === 'style1')
                            <div class="p-5 rounded-[var(--card-radius)] shadow-[var(--card-shadow)] overflow-hidden relative bg-primary/10 border border-primary text-base">
                                <button @click="close()" class="absolute top-4 right-4 text-primary/60 hover:text-primary transition-opacity" type="button">
                                    <i class="fa fa-times"></i>
                                </button>
                                <div class="flex items-center gap-3 mb-3">
                                    <i class="fa {{ theme('global_broadcast_icon', 'fa-bullhorn') }} text-lg text-primary"></i>
                                    <div class="font-semibold text-base">{{ theme('global_broadcast_title') }}</div>
                                </div>
                                <div class="text-sm text-base/60 {{ theme('global_broadcast_button_url') ? 'mb-4' : '' }} prose prose-sm dark:prose-invert max-w-none">{!! \Illuminate\Support\Str::markdown(theme('global_broadcast_content') ?? '') !!}</div>
                                @if(theme('global_broadcast_button_url'))
                                    <a href="{{ theme('global_broadcast_button_url') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-[var(--button-radius)] hover:bg-primary/90 transition-colors">
                                        {{ theme('global_broadcast_button_text', 'Learn More') }}
                                    </a>
                                @endif
                            </div>
                        @elseif($broadcastStyle === 'style2')
                            <div class="p-5 rounded-[var(--card-radius)] shadow-[var(--card-shadow)] overflow-hidden relative bg-primary/10 border-l-4 border-primary text-base">
                                <button @click="close()" class="absolute top-4 right-4 text-primary/60 hover:text-primary transition-opacity" type="button">
                                    <i class="fa fa-times"></i>
                                </button>
                                <div class="flex items-center gap-3 mb-3">
                                    <i class="fa {{ theme('global_broadcast_icon', 'fa-bullhorn') }} text-lg text-primary"></i>
                                    <div class="font-semibold text-base">{{ theme('global_broadcast_title') }}</div>
                                </div>
                                <div class="text-sm text-base/60 {{ theme('global_broadcast_button_url') ? 'mb-4' : '' }} prose prose-sm dark:prose-invert max-w-none">{!! \Illuminate\Support\Str::markdown(theme('global_broadcast_content') ?? '') !!}</div>
                                @if(theme('global_broadcast_button_url'))
                                    <a href="{{ theme('global_broadcast_button_url') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-[var(--button-radius)] hover:bg-primary/90 transition-colors">
                                        {{ theme('global_broadcast_button_text', 'Learn More') }}
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="p-5 rounded-[var(--card-radius)] shadow-[var(--card-shadow)] overflow-hidden relative bg-primary text-white">
                                <button @click="close()" class="absolute top-4 right-4 text-white/60 hover:text-white transition-opacity" type="button">
                                    <i class="fa fa-times"></i>
                                </button>
                                <div class="flex items-center gap-3 mb-3">
                                    <i class="fa {{ theme('global_broadcast_icon', 'fa-bullhorn') }} text-lg text-white"></i>
                                    <div class="font-semibold text-white">{{ theme('global_broadcast_title') }}</div>
                                </div>
                                <div class="text-sm text-white/80 {{ theme('global_broadcast_button_url') ? 'mb-4' : '' }} prose prose-sm prose-invert max-w-none">{!! \Illuminate\Support\Str::markdown(theme('global_broadcast_content') ?? '') !!}</div>
                                @if(theme('global_broadcast_button_url'))
                                    <a href="{{ theme('global_broadcast_button_url') }}" class="inline-flex items-center px-4 py-2 bg-white text-gray-900 text-sm font-medium rounded-[var(--button-radius)] hover:bg-white/90 transition-colors">
                                        {{ theme('global_broadcast_button_text', 'Learn More') }}
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                <main class="pb-8 min-w-0 {{ !$sidebarEnabled && !$hasFullBleedHero ? 'pt-16' : '' }} {{ $isHeaderLayout ? 'mx-auto w-full px-4 mt-6' : ($isWideLayout ? '' : ($sidebarEnabled ? 'mx-4' : '')) }}" style="{{ $isHeaderLayout ? 'max-width: var(--container-max-width)' : '' }}">
                    {{ $slot }}
                </main>
                <div class="fixed bottom-0 right-0">
                    <x-notification />
                </div>
            </div>
            <x-impersonating />
        </div>
    </div>
    @if(!$sidebarEnabled)
        <x-navigation.footer />
    @endif
    @livewireScriptConfig
    {!! hook('footer') !!}
    @if(theme('custom_js'))
    <script>{!! theme('custom_js') !!}</script>
    @endif

    {{-- Cookie consent banner: only rendered when analytics IDs are configured --}}
    @if($analyticsConfigured ?? false)
    <div id="pm-cookie-banner" style="display: none;"
        class="fixed bottom-4 inset-x-4 sm:inset-x-auto sm:right-6 sm:max-w-md z-50 bg-background-secondary border border-neutral rounded-[var(--card-radius)] shadow-[var(--card-shadow)] p-4 sm:p-5">
        <p class="text-sm text-base mb-4">{{ __('general.cookie_notice') }}</p>
        <div class="flex flex-col sm:flex-row gap-2">
            <button type="button" id="pm-cookie-accept"
                class="flex-1 bg-primary text-white text-sm font-semibold py-2 px-4 rounded-[var(--button-radius)] hover:bg-primary/90 transition-colors cursor-pointer">
                {{ __('general.cookie_accept') }}
            </button>
            <button type="button" id="pm-cookie-decline"
                class="flex-1 bg-background border border-neutral text-base text-sm font-semibold py-2 px-4 rounded-[var(--button-radius)] hover:bg-background/70 transition-colors cursor-pointer">
                {{ __('general.cookie_decline') }}
            </button>
        </div>
    </div>
    <script>
        (function () {
            var banner = document.getElementById('pm-cookie-banner');
            if (!banner) return;
            var stored = null;
            try { stored = localStorage.getItem('pm_cookie_consent'); } catch (e) {}
            if (!stored) banner.style.display = 'block';
            document.getElementById('pm-cookie-accept').addEventListener('click', function () {
                try { localStorage.setItem('pm_cookie_consent', 'granted'); } catch (e) {}
                banner.style.display = 'none';
                if (window.pmLoadAnalytics) window.pmLoadAnalytics();
            });
            document.getElementById('pm-cookie-decline').addEventListener('click', function () {
                try { localStorage.setItem('pm_cookie_consent', 'denied'); } catch (e) {}
                banner.style.display = 'none';
            });
        })();
    </script>
    @endif
</body>

</html>
