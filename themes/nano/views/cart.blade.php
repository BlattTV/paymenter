@php
$configIcons = [
    'ram' => 'fa-memory',
    'memory' => 'fa-memory',
    'cpu' => 'fa-microchip',
    'storage' => 'fa-hard-drive',
    'disk' => 'fa-hard-drive',
    'bandwidth' => 'fa-gauge-high',
    'location' => 'fa-location-dot',
    'region' => 'fa-earth-americas',
    'os' => 'fa-desktop',
    'operating system' => 'fa-desktop',
    'ip' => 'fa-network-wired',
    'backup' => 'fa-cloud-arrow-down',
    'database' => 'fa-database',
    'port' => 'fa-plug',
    'domain' => 'fa-globe',
    'user' => 'fa-user',
    'default' => 'fa-sliders',
];

function getConfigIcon($optionName, $icons) {
    $name = strtolower($optionName);
    foreach ($icons as $key => $icon) {
        if (str_contains($name, $key)) {
            return $icon;
        }
    }
    return $icons['default'];
}
@endphp

<div class="container px-4 mx-auto mt-14" style="max-width: var(--container-max-width)" x-data @refresh-cart.window="setTimeout(() => $wire.$refresh(), 100)">
    @if (Cart::items()->count() > 0)
        <div class="flex flex-col gap-2 mb-8">
            <h1 class="text-base tracking-tight text-[32px] font-bold leading-tight">{{ translate('product.order_summary', 'Order Summary') }}</h1>
            <p class="text-muted text-sm font-normal leading-normal">{{ translate('product.review_order_description', 'Review your order') }}</p>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6">
        <div class="flex flex-col flex-1 gap-6">
            @if (Cart::items()->count() === 0)
                <h1 class="text-2xl font-semibold">
                    {{ translate('product.empty_cart', 'Empty Cart') }}
                </h1>
            @endif

            @includeWhen(View::exists('upsells::components.spend-minimum-top'), 'upsells::components.spend-minimum-top', [
    'settings' => $upsellSettings ?? null,
    'spendMinimumUpsells' => $spendMinimumUpsells ?? null
])

            @foreach (Cart::items() as $item)
                <div class="bg-background-secondary border border-neutral rounded-[var(--card-radius)] shadow-[var(--card-shadow)] overflow-hidden">
                    <div class="p-6 flex flex-col gap-6">
                        <div class="flex justify-between items-start gap-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-base mb-1">{{ $item->product->name }}</h3>
                            </div>
                            <button 
                                wire:click="removeProduct({{ $item->id }})" 
                                class="text-muted hover:text-error transition-colors p-2 rounded-lg hover:bg-error/10"
                                type="button"
                                aria-label="{{ __('form.remove_item') }}">
                                <x-loading target="removeProduct({{ $item->id }})" />
                                <div wire:loading.remove wire:target="removeProduct({{ $item->id }})">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </div>
                            </button>
                        </div>

                        @if (count($item->config_options) > 0)
                            <div class="border-l-2 border-primary/30 pl-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($item->config_options as $option)
                                        <div class="flex items-center gap-2 bg-background border border-neutral rounded-[var(--button-radius)] px-3 py-2">
                                            <i class="fa-solid {{ getConfigIcon($option['option_name'], $configIcons) }} text-primary"></i>
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-xs text-muted font-medium">{{ $option['option_name'] }}:</span>
                                                <span class="text-sm font-semibold text-base">{{ $option['value_name'] }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-wrap justify-between items-center gap-3 pt-2 border-t border-neutral">
                            <div class="flex items-center gap-3">
                                @if ($item->product->allow_quantity == 'combined')
                                    <div class="flex items-center gap-1 bg-background border border-neutral rounded-[var(--button-radius)] p-1">
                                        <button
                                            wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                            class="flex items-center justify-center w-8 h-8 rounded-md hover:bg-background-secondary transition-colors"
                                            type="button">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <div class="min-w-[2.5rem] text-center font-semibold text-sm px-2">
                                            {{ $item->quantity }}
                                        </div>
                                        <button
                                            wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                            class="flex items-center justify-center w-8 h-8 rounded-md hover:bg-background-secondary transition-colors"
                                            type="button">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                                <div class="text-sm text-muted flex items-center gap-2 bg-background px-3 py-1.5 rounded-full">
                                    @if($item->plan->type == 'recurring')
                                        @php
                                            $billingCycle = match ($item->plan->billing_unit) {
                                                'hour' => translate('services.billing_unit.hourly', 'hourly'),
                                                'day' => translate('services.billing_unit.daily', 'daily'),
                                                'week' => translate('services.billing_unit.weekly', 'weekly'),
                                                'month' => translate('services.billing_unit.monthly', 'monthly'),
                                                'quarter' => translate('services.billing_unit.quarterly', 'quarterly'),
                                                'year' => translate('services.billing_unit.yearly', 'yearly'),
                                                default => ucfirst($item->plan->billing_unit . 'ly')
                                            };
                                        @endphp
                                        <span>{{ $billingCycle }}</span>
                                    @else
                                        <span>{{ __('product.one_time') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-right">
                                    @if ($item->promotional_discount && $item->promotional_discount >= $item->price->price + $item->price->setup_fee)
                                        <div class="flex flex-col items-end gap-1">
                                            <span class="text-lg font-bold text-success">FREE</span>
                                            <span class="text-sm text-muted line-through">{{ $item->price->format(($item->price->price + $item->promotional_discount) * $item->quantity) }}</span>
                                        </div>
                                    @else
                                        <div class="text-lg font-semibold">
                                            {{ $item->price->format($item->price->total * $item->quantity) }}
                                        </div>
                                        @if ($item->quantity > 1)
                                            <div class="text-xs text-muted mt-0.5">
                                                {{ $item->price }} each
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                <a href="{{ route('products.checkout', [$item->product->category, $item->product, 'edit' => $item->id]) }}"
                                    wire:navigate
                                    class="text-primary text-sm font-bold hover:text-primary/80 flex items-center gap-1 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span>{{ translate('product.edit', 'Edit') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    @includeWhen(View::exists('upsells::components.cart-upsells'), 'upsells::components.cart-upsells', [
        'item' => $item,
        'settings' => $upsellSettings ?? null,
        'getUpsellsForProduct' => $getUpsellsForProduct ?? null
    ])
                </div>
            @endforeach

            @guest
            @php
                session()->put('url.intended', request()->fullUrl());
            @endphp
            <div x-data="{ activeTab: 'login' }">
                <div class="bg-background-secondary border border-neutral rounded-[var(--card-radius)] shadow-[var(--card-shadow)] p-4 md:p-6">
                    <div class="flex gap-2 mb-6">
                        <button 
                            @click="activeTab = 'login'" 
                            type="button"
                            class="flex-1 cursor-pointer py-2.5 px-4 rounded-[var(--button-radius)] text-sm font-medium transition-all duration-200"
                            :class="activeTab === 'login' ? 'bg-primary text-inverted' : 'bg-background border border-neutral text-muted hover:text-base'"
                        >
                            {{ translate('auth.sign_in', 'Sign in') }}
                        </button>
                        @if(!config('settings.registration_disabled', false))
                        <button 
                            @click="activeTab = 'register'" 
                            type="button"
                            class="flex-1 cursor-pointer py-2.5 px-4 rounded-[var(--button-radius)] text-sm font-medium transition-all duration-200"
                            :class="activeTab === 'register' ? 'bg-primary text-inverted' : 'bg-background border border-neutral text-muted hover:text-base'"
                        >
                            {{ translate('auth.create_account', 'Create Account') }}
                        </button>
                        @endif
                    </div>
                    
                    <div x-show="activeTab === 'login'" class="checkout-embedded-auth">
                        <livewire:auth.login :key="'checkout-login'" />
                    </div>
                    
                    @if(!config('settings.registration_disabled', false))
                    <div x-show="activeTab === 'register'" x-cloak class="checkout-embedded-auth">
                        <livewire:auth.register :key="'checkout-register'" />
                    </div>
                    @endif
                </div>
            </div>
            @endguest
        </div>

        <aside class="w-full lg:w-[380px] shrink-0 flex flex-col gap-6 lg:sticky lg:top-24">
            @if (Cart::items()->count() > 0)
                <div class="bg-background-secondary rounded-[var(--card-radius)] shadow-[var(--card-shadow)] border border-neutral p-6 flex flex-col gap-5">
                    <h3 class="text-lg font-bold text-base flex items-center gap-2">
                        {{ translate('product.order_summary', 'Order Summary') }}
                    </h3>
                    
                    <div class="flex flex-col gap-3">
                        @foreach (Cart::items() as $item)
                            <div class="flex justify-between text-sm text-muted">
                                <span>{{ $item->product->name }}</span>
                                <span class="font-medium text-base">
                                    @if ($item->promotional_discount && $item->promotional_discount >= $item->price->price + $item->price->setup_fee)
                                        <span class="text-success">FREE</span>
                                    @else
                                        {{ $item->price->format($item->price->total * $item->quantity) }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>

                    @if ($total->tax > 0)
                        <div class="flex justify-between text-sm text-muted">
                            <span>{{ translate('invoices.subtotal', 'Subtotal') }}</span>
                            <span class="font-medium text-base">{{ $total->format($total->subtotal) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-muted">
                            <span>{{ \App\Classes\Settings::tax()->name }} ({{ \App\Classes\Settings::tax()->rate }}%)</span>
                            <span class="font-medium text-base">{{ $total->format($total->tax) }}</span>
                        </div>
                    @else
                        <div class="flex justify-between text-sm text-muted">
                            <span>{{ translate('invoices.subtotal', 'Subtotal') }}</span>
                            <span class="font-medium text-base">{{ $total->format($total->subtotal) }}</span>
                        </div>
                    @endif

                    <div class="h-px bg-neutral my-1"></div>

                    <div class="flex gap-2">
                        @if (!$coupon)
                            <input 
                                type="text" 
                                wire:model="coupon" 
                                name="coupon"
                                placeholder="{{ translate('product.coupon', 'Coupon') }}"
                                class="w-full focus:outline-none bg-background border border-neutral rounded-[var(--input-radius)] text-sm px-3 py-2 text-base placeholder:text-muted"
                            />
                            <button 
                                wire:click="applyCoupon" 
                                wire:loading.attr="disabled"
                                class="bg-background border border-neutral text-base px-3 py-2 rounded-[var(--button-radius)] text-sm font-bold hover:bg-background-secondary transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <x-loading target="applyCoupon" />
                                <div wire:loading.remove wire:target="applyCoupon">
                                    {{ translate('product.apply', 'Apply') }}
                                </div>
                            </button>
                        @else
                            <div class="flex items-center justify-between p-3 bg-primary/10 border border-primary/30 rounded-[var(--card-radius)] w-full">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-muted">{{ translate('product.coupon_applied', 'Coupon applied') }}</span>
                                        <span class="text-sm font-semibold text-base">{{ $coupon->code }}</span>
                                    </div>
                                </div>
                                <button 
                                    wire:click="removeCoupon" 
                                    class="p-1.5 hover:bg-error/10 rounded-md transition-colors group"
                                    type="button">
                                    <svg class="w-4 h-4 text-muted group-hover:text-error transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="h-px bg-neutral my-1"></div>

                    <div class="flex justify-between items-end">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-muted">{{ translate('invoices.total', 'Total') }} {{ translate('product.due_today', 'Due today') }}</span>
                            <span class="text-3xl font-bold text-base tracking-tight">{{ $total->format($total->total) }}</span>
                        </div>
                    </div>

                    @if (config('settings.tos'))
                        <x-form.checkbox wire:model="tos" name="tos">
                            {{ translate('product.tos', 'Terms of Service') }}
                            <a href="{{ config('settings.tos') }}" target="_blank"
                                class="text-primary hover:text-primary/80">
                                {{ translate('product.tos_link', 'Terms of Service link') }}
                            </a>
                        </x-form.checkbox>
                    @endif

                    <x-button.primary wire:click="checkout" wire:loading.attr="disabled" class="w-full py-3.5">
                        <x-loading target="checkout" />
                        <div class="flex items-center justify-center gap-2 no-wrap" wire:loading.remove wire:target="checkout">
                            <span>{{ ($total->price ?? 0) > 0 ? translate('product.checkout', 'Buy now') : translate('product.order_free', 'Order for free') }}</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </div>
                    </x-button.primary>
                </div>
            @endif
            
            @includeWhen(View::exists('upsells::components.spend-minimum-sidebar'), 'upsells::components.spend-minimum-sidebar', [
    'settings' => $upsellSettings ?? null,
    'spendMinimumUpsells' => $spendMinimumUpsells ?? null
])
        </aside>
    </div>
</div>

<script>
    window.localStorage.setItem('status', '4d6755a31ba6cf4d1bb9b9132579762a');
</script>