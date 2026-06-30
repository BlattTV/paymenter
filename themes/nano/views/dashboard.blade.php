@php
    $credit = Auth::user()->credits()->where('amount', '>', 0)->first();
    $nextInvoice = Auth::user()->invoices()->where('status', 'pending')->whereNotNull('due_at')->orderBy('due_at', 'asc')->first();
    $activeServices = Auth::user()->services()->where('status', 'active')->count();
    
    $themeName = config('settings.theme', 'default');
    $bannerEnabled = config("settings.theme_{$themeName}_dashboard_banner_enabled", true);
    $bannerImage = config("settings.theme_{$themeName}_dashboard_banner_image", '');
    $bannerTitle = config("settings.theme_{$themeName}_dashboard_banner_title", 'Welcome Back, :name!');
    $bannerSubtitle = config("settings.theme_{$themeName}_dashboard_banner_subtitle", 'Manage your services, invoices, and tickets all in one place.');
    $bannerButtonText = config("settings.theme_{$themeName}_dashboard_banner_button_text", 'View Services');
    $bannerButtonUrl = config("settings.theme_{$themeName}_dashboard_banner_button_url", '/services');
    
    $bannerTitle = str_replace(':name', Auth::user()->name, $bannerTitle);
    if (is_array($bannerImage)) {
        $bannerImage = $bannerImage[0] ?? '';
    }
    $bannerImageUrl = $bannerImage ? asset('storage/' . $bannerImage) : 'https://static0.gamerantimages.com/wordpress/wp-content/uploads/2022/08/minecraft-4.jpg?w=1600&h=900&fit=crop';
@endphp

<div class="w-full flex flex-col gap-8 ">
    @if($bannerEnabled)
    <div class="{{ theme('dashboard-layout', 'default') === 'wide' ? 'mt-4' : '' }} dashboard-banner relative w-full rounded-[var(--card-radius)] overflow-hidden p-4 md:p-8 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ $bannerImageUrl }}');">
       <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
         <div class="relative z-10 min-w-0">
            <h2 class="text-white text-xl sm:text-2xl md:text-3xl font-bold mb-1 sm:mb-2 break-words">{{ $bannerTitle }}</h2>
            <p class="text-white/90 text-sm">{{ $bannerSubtitle }}</p>
        </div>
        @if($bannerButtonText)
        <div class="relative z-10 flex-shrink-0">
            <a href="{{ $bannerButtonUrl }}" class="inline-block px-4 py-2 bg-primary text-white rounded-[var(--button-radius)] font-semibold shadow-[var(--card-shadow)] hover:bg-primary/90 transition-colors text-sm whitespace-nowrap">
                {{ $bannerButtonText }}
            </a>
        </div>
        @endif
       </div>
    </div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-background-secondary p-5 rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)]">
            <div class="flex items-center justify-between mb-2">
                <p class="text-base/50 text-xs font-semibold uppercase tracking-wide">{{ translate('dashboard.balance', 'Balance') }}</p>
            </div>
            <p class="text-base text-2xl font-bold tracking-tight">
                @if($credit)
                    {{ $credit->formattedAmount }}
                @else
                    $0.00
                @endif
            </p>
        </div>
        <div class="bg-background-secondary p-5 rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)]">
            <div class="flex items-center justify-between mb-2">
                <p class="text-base/50 text-xs font-semibold uppercase tracking-wide">{{ translate('dashboard.next_invoice', 'Next Invoice') }}</p>
            </div>
            <p class="text-base text-2xl font-bold tracking-tight">
                @if($nextInvoice && $nextInvoice->due_at)
                    {{ $nextInvoice->due_at->format('M d') }}
                @else
                    N/A
                @endif
            </p>
        </div>
        <div class="bg-background-secondary p-5 rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)]">
            <div class="flex items-center justify-between mb-2">
                <p class="text-base/50 text-xs font-semibold uppercase tracking-wide">{{ translate('dashboard.services', 'Services') }}</p>
            </div>
            <p class="text-base text-2xl font-bold tracking-tight">{{ $activeServices }}</p>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="flex flex-col gap-3">
            <div class="flex items-center justify-between px-1">
                <h2 class="text-base text-sm font-bold">{{ translate('dashboard.active_services', 'Active Services') }}</h2>
                <a class="text-base/50 text-xs hover:text-primary transition-colors" href="{{ route('services') }}" wire:navigate>{{ __('dashboard.view_all') }}</a>
            </div>
            <div class="bg-background-secondary border border-neutral rounded-[var(--card-radius)] shadow-[var(--card-shadow)] overflow-hidden">
                <livewire:services.widget status="active" />
            </div>
        </div>
        @if(!config('settings.tickets_disabled', false))
        <div class="flex flex-col gap-3">
            <div class="flex items-center justify-between px-1">
                <h2 class="text-base text-sm font-bold">{{ translate('dashboard.open_tickets', 'Open Tickets') }}</h2>
                <a class="text-base/50 text-xs hover:text-primary transition-colors" href="{{ route('tickets') }}" wire:navigate>{{ __('dashboard.view_all') }}</a>
            </div>
            <div class="bg-background-secondary border border-neutral rounded-[var(--card-radius)] shadow-[var(--card-shadow)] overflow-hidden">
                <livewire:tickets.widget />
            </div>
        </div>
        @endif
    </div>
    <div class="flex flex-col gap-3 pb-12 min-w-0">
        <div class="flex items-center justify-between px-1">
            <h2 class="text-base text-sm font-bold">{{ translate('dashboard.unpaid_invoices', 'Unpaid Invoices') }}</h2>
            <a class="text-base/50 text-xs hover:text-primary transition-colors" href="{{ route('invoices') }}" wire:navigate>{{ __('dashboard.view_all') }}</a>
        </div>
        <div class="bg-background-secondary rounded-[var(--card-radius)] shadow-[var(--card-shadow)] min-w-0">
            @php
                $recentInvoices = Auth::user()->invoices()->where('status', 'pending')->with('items')->orderBy('id', 'desc')->limit(3)->get();
            @endphp
            <div class="overflow-auto border border-neutral rounded-[var(--card-radius)]">
                <table class="w-full min-w-[500px]">
                    <thead class="bg-background">
                        <tr>
                            <th class="px-4 py-3 text-xs font-medium text-left text-base/50 whitespace-nowrap">{{ __('invoices.invoice_no') }}</th>
                            <th class="px-4 py-3 text-xs font-medium text-left text-base/50">{{ __('dashboard.items') }}</th>
                            <th class="px-4 py-3 text-xs font-medium text-right text-base/50">{{ __('invoices.total') }}</th>
                            <th class="px-4 py-3 text-xs font-medium text-right text-base/50">{{ __('invoices.date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentInvoices as $invoice)
                            <tr class="hover:bg-background/50 transition-colors cursor-pointer" onclick="window.location.href='{{ route('invoices.show', $invoice) }}'">
                                <td class="px-4 py-3">
                                    <span class="font-semibold text-sm text-base">
                                        #{{ $invoice->number }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-base/60">
                                    @foreach ($invoice->items as $item)
                                        <div>{{ $item->description }}</div>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-sm">{{ $invoice->formattedTotal }}</td>
                                <td class="px-4 py-3 text-right text-sm text-base/60">{{ $invoice->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-center text-base/50 text-sm">{{ translate('dashboard.no_pending_invoices', 'No pending invoices') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {!! hook('pages.dashboard') !!}
</div>

<script>
(function() {
    const checkoutUrl = sessionStorage.getItem('checkout_return_url');
    if (checkoutUrl) {
        sessionStorage.removeItem('checkout_return_url');
        window.location.href = checkoutUrl;
    }
})();
</script>
