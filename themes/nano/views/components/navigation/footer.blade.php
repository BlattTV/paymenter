@php
    $footerDescription = theme('footer_description', 'Hoelni-Hosting – leistungsstarke Minecraft-Gameserver aus Deutschland. NVMe-SSDs, DDoS-Schutz, Sofort-Setup und eigenes Panel.');
    $footerColumns = theme('footer_columns', [
        [
            'title' => 'Services',
            'links' => [
                ['label' => 'VPS Hosting', 'url' => '#'],
                ['label' => 'Dedicated Servers', 'url' => '#'],
                ['label' => 'Reseller Hosting', 'url' => '#'],
                ['label' => 'Cloud Hosting', 'url' => '#'],
                ['label' => 'Domain Registration', 'url' => '#'],
            ],
        ],
        [
            'title' => 'Company',
            'links' => [
                ['label' => 'Knowledge Base', 'url' => '#'],
                ['label' => 'Contact Us', 'url' => '#'],
                ['label' => 'Status', 'url' => '#'],
            ],
        ],
        [
            'title' => 'Legal',
            'links' => [
                ['label' => 'Terms of Service', 'url' => '#'],
                ['label' => 'Privacy Policy', 'url' => '#'],
                ['label' => 'SLA', 'url' => '#'],
                ['label' => 'Acceptable Use Policy', 'url' => '#'],
            ],
        ],
    ]);
    $footerCopyrightRaw = theme('footer_copyright_text');
    $footerCopyright = !empty($footerCopyrightRaw) ? $footerCopyrightRaw : '© :year :app_name. All rights reserved.';
    $footerCopyright = str_replace([':year', ':app_name'], [date('Y'), config('app.name')], $footerCopyright);
    
    $columnCount = count($footerColumns) + 1;
    
    $footerBgLight = theme('footer-bg-light', 'hsl(222, 47%, 11%)');
    $footerTextLight = theme('footer-text-light', 'hsl(215, 20%, 80%)');
    $footerBgDark = theme('footer-bg-dark', 'hsl(222, 47%, 11%)');
    $footerTextDark = theme('footer-text-dark', 'hsl(215, 20%, 80%)');
@endphp

<style>
    .theme-footer {
        background-color: var(--footer-bg-light);
        color: var(--footer-text-light);
    }
    .theme-footer .footer-heading {
        color: white;
    }
    .theme-footer .footer-link:hover {
        color: white;
    }
    .theme-footer .footer-muted {
        opacity: 0.95;
        color: var(--footer-text-light);
    }
    .dark .theme-footer .footer-muted {
        opacity: 1;
        color: var(--footer-text-dark);
    }
    .theme-footer .footer-border {
        border-color: var(--footer-text-light);
        opacity: 0.7;
    }
    .dark .theme-footer {
        background-color: var(--footer-bg-dark);
        color: var(--footer-text-dark);
    }
    .dark .theme-footer .footer-border {
        border-color: var(--footer-text-dark);
    }
</style>

<footer
    class="theme-footer py-16"
    style="--footer-bg-light: {{ $footerBgLight }}; --footer-text-light: {{ $footerTextLight }}; --footer-bg-dark: {{ $footerBgDark }}; --footer-text-dark: {{ $footerTextDark }};"
>
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ min($columnCount, 5) }} gap-12 mb-16">
            <div>
                @php
                    $showFooterLogo = theme('footer-show-logo', true);
                    $showBrandText = theme('show-brand-text', true);
                @endphp
                @if($showFooterLogo || $showBrandText)
                <div class="{{ $showFooterLogo && $showBrandText ? 'flex items-center gap-2 ' : '' }}mb-6">
                    @if($showFooterLogo)
                        <x-logo class="h-10" />
                    @endif
                    @if($showBrandText)
                        <span class="text-xl font-bold footer-heading">{{ config('app.name') }}</span>
                    @endif
                </div>
                @endif
                <p class="text-sm leading-relaxed footer-muted mb-6">
                    {{ $footerDescription }}
                </p>
            </div>
            @foreach($footerColumns as $column)
                <div>
                    <h4 class="footer-heading font-bold mb-6">{{ $column['title'] ?? '' }}</h4>
                    <ul class="flex flex-col gap-3 text-sm">
                        @foreach($column['links'] ?? [] as $link)
                            <li><a class="footer-link transition-colors" href="{{ $link['url'] ?? '#' }}">{{ $link['label'] ?? '' }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
        <div class="pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex flex-col gap-3">
                <p class="text-xs footer-muted">{!! $footerCopyright !!}</p>
                @php
                    $paymentImages = theme('payment_method_images', []);
                    if (is_string($paymentImages)) $paymentImages = [];
                @endphp
                @if(!empty($paymentImages))
                    <div class="flex flex-wrap items-center gap-3">
                        @foreach($paymentImages as $img)
                            <img src="{{ asset('storage/' . $img) }}" alt="{{ __('invoices.payment_method') }}" style="height: 2rem;" class="object-contain" loading="lazy" />
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="flex items-center gap-4">
                <x-theme-toggle />
                <a href="https://paymenter.org" target="_blank" class="text-xs footer-muted">{{ __('general.powered_by', ['name' => 'Paymenter']) }}</a>
            </div>
        </div>
    </div>
</footer>