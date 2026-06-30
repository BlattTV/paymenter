@php
    $orderingText = translate('product.ordering', 'Ordering :name', ['name' => $product->name]);
    $configureOptionsText = translate('product.configure_options', 'Configure your options');
    $setupFeeText = translate('product.setup_fee', 'setup fee');
    $orderSummaryText = translate('product.order_summary', 'Order Summary');
    $subtotalText = translate('invoices.subtotal', 'Subtotal');
    $planText = translate('services.plan', 'Plan');
    $totalTodayText = translate('product.total_today', 'Total today');
    $checkoutText = translate('product.checkout', 'Checkout');
@endphp

<div>
<div class="container px-4 md:mx-auto mt-14" style="max-width: var(--container-max-width)">
    <div class="rounded-[var(--card-radius)] mb-4 gradient-paper w-full p-4 md:p-8">
        <h1 class="mb-4 text-3xl font-bold relative z-10 text-white">{{ $orderingText }}</h1>
        <p class="text-white relative z-10">{{ $configureOptionsText }}</p>
    </div>
<div class="flex flex-col lg:grid lg:grid-cols-11 lg:items-start gap-6">
    <div class="flex flex-col gap-4 w-full lg:col-span-8 rounded-[var(--card-radius)]">
        <div class="bg-background-secondary border border-neutral rounded-[var(--card-radius)] shadow-[var(--card-shadow)] p-4 md:p-8">
                @if ($product->description)
                    <article class="prose dark:prose-invert prose-sm">
                        {!! $product->description !!}
                    </article>
                @endif
            @if ($product->availablePlans()->count() > 1)
                <x-form.select wire:model.live="plan_id" class="text-white bg-primary-800 px-2.5 py-2.5 rounded-[var(--input-radius)] w-full"
                    name="plan_id" :label="__('general.select_plan')">
                    @foreach ($product->availablePlans() as $availablePlan)
                        <option value="{{ $availablePlan->id }}">
                            {{ $availablePlan->name }} -
                            {{ $availablePlan->price()->formatted->price }}
                            @if ($availablePlan->price()->has_setup_fee)
                                + {{ $availablePlan->price()->formatted->setup_fee }} {{ $setupFeeText }}
                            @endif
                        </option>
                    @endforeach
                </x-form.select>
            @endif
        </div>

        @foreach ($product->configOptions as $configOption)
            @php
    $showPriceTag = $configOption->children->filter(fn($value) => !$value->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->is_free)->count() > 0;
            @endphp
            <div class="mt-10">
                <x-form.configoption :config="$configOption" :name="'configOptions.' . $configOption->id"
                    :showPriceTag="$showPriceTag" :plan="$plan">
                    @if ($configOption->type == 'select')
                        @foreach ($configOption->children as $configOptionValue)
                            <option value="{{ $configOptionValue->id }}">
                                {{ $configOptionValue->name }}
                                {{ ($showPriceTag && $configOptionValue->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->available) ? ' - ' . $configOptionValue->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit) : '' }}
                            </option>
                        @endforeach
                    @elseif($configOption->type == 'radio')
                        @foreach ($configOption->children as $configOptionValue)
                            <label class="custom-radio">
                                <input type="radio" id="{{ $configOptionValue->id }}" name="{{ $configOption->id }}"
                                    wire:model.live="configOptions.{{ $configOption->id }}" value="{{ $configOptionValue->id }}" />
                                <span class="radio-btn">
                                    <div class="option-content">
                                        @php
            $imageName = str_replace(' ', '_', $configOptionValue->name) . '.png';
            $imageDir = public_path('config_options');
            $imageExists = false;
            if (is_dir($imageDir)) {
                $files = scandir($imageDir);
                foreach ($files as $file) {
                    if (strcasecmp($file, $imageName) === 0) {
                        $imageName = $file;
                        $imageExists = true;
                        break;
                    }
                }
            }
                                        @endphp
                                        @if($imageExists)
                                            <div class="flex justify-center mb-3" wire:ignore>
                                                <img src="/config_options/{{ $imageName }}" 
                                                     alt="{{ $configOptionValue->name }}" 
                                                     class="h-16 w-auto"
                                                     loading="eager" />
                                            </div>
                                        @endif
                                        <h3>{{ $configOptionValue->name }}</h3>
                                        @if ($showPriceTag && $configOptionValue->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->available)
                                            <p>{{ $configOptionValue->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit) }}</p>
                                        @endif
                                    </div>
                                </span>
                            </label>
                        @endforeach
                    @endif
                </x-form.configoption>
            </div>
        @endforeach
        @foreach ($this->getCheckoutConfig() as $configOption)
            @php $configOption = (object) $configOption; @endphp
            <div class="mt-16">
                <x-form.configoption :config="$configOption" :name="'checkoutConfig.' . $configOption->name">
                    @if ($configOption->type == 'select')
                        @foreach ($configOption->options as $configOptionValue => $configOptionValueName)
                            <option value="{{ $configOptionValue }}">
                                {{ $configOptionValueName }}
                            </option>
                        @endforeach
                    @elseif($configOption->type == 'radio')
                        @foreach ($configOption->options as $configOptionValue => $configOptionValueName)
                            <label class="custom-radio">
                                <input type="radio" id="{{ $configOptionValue }}" name="{{ $configOption->name }}"
                                    wire:model.live="checkoutConfig.{{ $configOption->name }}" value="{{ $configOptionValue }}" />
                                <span class="radio-btn">
                                    <div class="option-content">
                                        @php
            $imageName = str_replace(' ', '_', $configOptionValueName) . '.png';
            $imageDir = public_path('config_options');
            $imageExists = false;
            if (is_dir($imageDir)) {
                $files = scandir($imageDir);
                foreach ($files as $file) {
                    if (strcasecmp($file, $imageName) === 0) {
                        $imageName = $file;
                        $imageExists = true;
                        break;
                    }
                }
            }
                                        @endphp
                                        @if($imageExists)
                                            <div class="flex justify-center mb-3" wire:ignore>
                                                <img src="/config_options/{{ $imageName }}" 
                                                     alt="{{ $configOptionValueName }}" 
                                                     class="h-16 w-auto"
                                                     loading="eager" />
                                            </div>
                                        @endif
                                        <h3>{{ $configOptionValueName }}</h3>
                                    </div>
                                </span>
                            </label>
                        @endforeach
                    @endif
                </x-form.configoption>
            </div>
        @endforeach
    </div>
    <div class="flex flex-col gap-6 w-full lg:col-span-3 h-fit lg:sticky lg:top-30">
        <div class="flex flex-col bg-background-secondary border border-neutral p-3 md:p-6 rounded-[var(--card-radius)] shadow-[var(--card-shadow)]">
            <h3 class="mb-4 text-xl font-semibold mb-2">
                {{ $orderSummaryText }}
            </h3>

            @if ($total->total_tax > 0)
                <div class="font-semibold flex justify-between">
                    <h4>{{ $subtotalText }}:</h4> {{ $total->format($total->subtotal) }}
                </div>
                <div class="font-semibold flex justify-between">
                    <h4>{{ \App\Classes\Settings::tax()->name }} ({{ \App\Classes\Settings::tax()->rate }}%):</h4>
                    {{ $total->formatted->total_tax }}
                </div>
            @endif

            @if ($product->availablePlans()->count() > 1)
                <div class="flex justify-between text-sm pb-2 mb-2">
                    <span class="text-gray-400">{{ $planText }}</span>
                    <span class="font-medium">{{ $plan->name }}</span>
                </div>
            @endif

            @foreach ($product->configOptions as $configOption)
                @if (isset($configOptions[$configOption->id]))
                    @php
            $selectedValue = $configOption->children->firstWhere('id', $configOptions[$configOption->id]);
                    @endphp
                    @if ($selectedValue)
                        <div class="flex justify-between text-sm pb-2 mb-2">
                            <span class="text-gray-400">{{ $configOption->name }}</span>
                            <span class="font-medium">{{ $selectedValue->name }}</span>
                        </div>
                    @endif
                @endif
            @endforeach

            <div class="border-t border-neutral my-3"></div>

            <div class="font-semibold flex justify-between">
                <h4>{{ $totalTodayText }}:</h4> {{ $total }}
            </div>

            @if ($total->setup_fee && $plan->type == 'recurring')
                @php
                    $billingUnitKey = 'services.billing_cycles.' . $plan->billing_unit;
                    $billingUnitFallback = $plan->billing_period == 1 ? $plan->billing_unit : $plan->billing_unit . 's';
                    $billingUnitText = Lang::has($billingUnitKey) ? trans_choice(__($billingUnitKey), $plan->billing_period) : $billingUnitFallback;
                    $timeString = $plan->billing_period . ' ' . $billingUnitText;
                    $thenAfterText = Lang::has('product.then_after_x') ? __('product.then_after_x', ['time' => $timeString]) : 'Then after ' . $timeString;
                @endphp
                <div class="mt-4 font-semibold flex justify-between ">
                    <h4>{{ $thenAfterText }}:</h4> {{ $total->format($total->price) }}
                </div>
            @endif
            @if (($product->stock > 0 || !$product->stock) && $product->price()->available)
                <div class="mt-4">
                    <x-button.primary wire:click="checkout" wire:loading.attr="disabled">
                        {{ $checkoutText }}
                    </x-button.primary>
                </div>
            @endif
        </div>
        @if(theme('need-help-widget-enabled', true))
            <div class="bg-background-secondary border border-neutral rounded-[var(--card-radius)] shadow-[var(--card-shadow)] p-5">
                <div class="text-base font-semibold">{{ theme('need-help-widget-title', 'Need help?') }}</div>
                <p class="text-sm text-base/50 mb-2">
                    {!! \Illuminate\Support\Str::markdown(theme('need-help-widget-text', 'Get in touch with our support team for assistance.')) !!}
                </p>
                @if(theme('need-help-widget-button-url'))
                    <a href="{{ theme('need-help-widget-button-url') }}" @if(str_starts_with(theme('need-help-widget-button-url'), 'http')) target="_blank" rel="noopener noreferrer" @else wire:navigate @endif>
                        <button class="mt-2 w-full bg-background border border-primary text-primary hover:bg-primary/10 hover:border-primary/70 text-sm font-medium px-3 py-2.5 rounded-[var(--button-radius)] transition-colors">
                            {{ theme('need-help-widget-button-text', 'Contact Support') }}
                        </button>
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        function scrollToFirstError() {
            const errorMessages = document.querySelectorAll('.text-error.text-xs');
            
            if (errorMessages.length > 0) {
                const firstError = errorMessages[0];
                let scrollTarget = null;
                
                const fieldset = firstError.closest('fieldset');
                
                if (fieldset) {
                    const field = fieldset.querySelector('input, select, textarea');
                    
                    if (field) {
                        scrollTarget = field;
                    } else {
                        scrollTarget = fieldset;
                    }
                } else {
                    const configOptionContainer = firstError.closest('.mt-10, .mt-16');
                    if (configOptionContainer) {
                        scrollTarget = configOptionContainer;
                    } else {
                        scrollTarget = firstError;
                    }
                }
                
                if (scrollTarget) {
                    scrollTarget.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center',
                        inline: 'nearest'
                    });
                    
                    if (scrollTarget.tagName === 'INPUT' || scrollTarget.tagName === 'SELECT' || scrollTarget.tagName === 'TEXTAREA') {
                        setTimeout(() => {
                            scrollTarget.focus();
                            if (scrollTarget.type === 'radio' || scrollTarget.type === 'checkbox') {
                                const label = scrollTarget.closest('label');
                                if (label) {
                                    label.scrollIntoView({ 
                                        behavior: 'smooth', 
                                        block: 'center' 
                                    });
                                }
                            }
                        }, 300);
                    }
                }
            }
        }

        const checkoutButton = document.querySelector('[wire\\:click="checkout"]');
        if (checkoutButton) {
            checkoutButton.addEventListener('click', () => {
                setTimeout(() => {
                    scrollToFirstError();
                }, 100);
            });
        }

        Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
            const method = commit?.method || '';
            
            if (method === 'checkout') {
                fail(() => {
                    setTimeout(() => {
                        scrollToFirstError();
                    }, 200);
                });
            }
        });
    });
</script>
</div>