<div class="space-y-6">
    <a href="{{ route('services') }}" wire:navigate class="inline-flex items-center gap-2 text-base/70 hover:text-primary transition-colors text-sm font-medium mb-2">
        <x-ri-arrow-left-s-line class="size-4" />
        <span>{{ translate('services.back_to_services', 'Back to Services') }}</span>
    </a>

    @if($invoice = $service->invoices()->where('status', 'pending')->first())
        <x-alert 
            type="warning" 
            :title="translate('services.outstanding_invoice', 'Outstanding Invoice')"
            :link="route('invoices.show', $invoice)"
            :linkText="translate('services.view_and_pay', 'View and Pay')" 
        />
    @endif

    <div class="flex flex-col gap-4 bg-background-secondary p-6 rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)]">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex flex-col gap-2">
                <div class="flex items-center gap-3">
                    <h1 class="text-base text-2xl md:text-3xl font-bold leading-tight tracking-tight">{{ $service->product->name }}</h1>
                    @if ($service->status == 'active')
                        <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold bg-success/10 text-success uppercase tracking-wide">
                            {{ translate('services.statuses.' . $service->status, $service->status) }}
                        </span>
                    @elseif ($service->status == 'cancelled')
                        <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold bg-error/10 text-error uppercase tracking-wide">
                            {{ __('services.statuses.' . $service->status) }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold bg-warning/10 text-warning uppercase tracking-wide">
                            {{ translate('services.statuses.' . $service->status, $service->status) }}
                        </span>
                    @endif
                </div>
                <div class="flex items-center gap-3 text-sm text-base/50">
                    <span>{{ translate('services.price', 'Price') }}: {{ $service->formattedPrice }}</span>
                    @if($service->plan->type == 'recurring')
                        <span class="w-1 h-1 rounded-full bg-neutral"></span>
                        <span>{{ translate('services.billing_cycle', 'Billing Cycle') }}: {{ translate('services.every_period', 'Every Period', [
                            'period' => $service->plan->billing_period > 1 ? $service->plan->billing_period : '',
                            'unit' => trans_choice(translate('services.billing_cycles.' . $service->plan->billing_unit), $service->plan->billing_period)
                        ]) }}</span>
                    @endif
                </div>
                @if(count($fields) > 0)
                    <div class="flex items-center gap-3 text-sm text-base/50">
                        @foreach ($fields as $index => $field)
                            @if($index > 0)
                                <span class="w-1 h-1 rounded-full bg-neutral"></span>
                            @endif
                            <span>{{ $field['text'] }}</span>
                            @if($index >= 2)
                                @break
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-background-secondary rounded-[var(--card-radius)] border border-neutral p-8 shadow-[var(--card-shadow)]">
        <div class="flex items-center gap-3 mb-8">
            <h2 class="text-xl font-bold text-base">{{ translate('services.billing_overview', 'Billing Overview') }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="flex flex-col gap-2 p-5 rounded-[var(--card-radius)] bg-background border border-neutral">
                <span class="text-xs text-base/50 font-semibold uppercase tracking-wider">{{ translate('services.price', 'Price') }}</span>
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl font-bold text-base">{{ $service->formattedPrice }}</span>
                </div>
            </div>

            @if($service->plan->type == 'recurring')
                <div class="flex flex-col gap-2 p-5 rounded-[var(--card-radius)] bg-background border border-neutral">
                    <span class="text-xs text-base/50 font-semibold uppercase tracking-wider">{{ translate('services.billing_cycle', 'Billing Cycle') }}</span>
                    <div class="flex items-baseline gap-1">
                        <span class="text-lg font-bold text-base">
                            {{ translate('services.every_period', 'Every Period', [
                                'period' => $service->plan->billing_period > 1 ? $service->plan->billing_period : '',
                                'unit' => trans_choice(translate('services.billing_cycles.' . $service->plan->billing_unit), $service->plan->billing_period)
                            ]) }}
                        </span>
                    </div>
                </div>
            @endif

            <div class="flex flex-col gap-2 p-5 rounded-[var(--card-radius)] bg-background border border-neutral">
                <span class="text-xs text-base/50 font-semibold uppercase tracking-wider">{{ translate('services.expires_at', 'Expires at') }}</span>
                <div class="flex items-baseline gap-1">
                    @if($service->expires_at)
                        <span class="text-lg font-bold text-base">{{ $service->expires_at->format('M d, Y') }}</span>
                    @else
                        <span class="text-lg font-bold text-base">{{ translate('common.na', 'N/A') }}</span>
                    @endif
                </div>
            </div>
        </div>

        @if($service->upgradable || $service->cancellable)
            <div class="flex flex-col gap-6 pt-6 border-t border-neutral">
                @if($service->upgradable)
                    <div class="flex items-center justify-between py-2">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-sm font-bold text-base">{{ translate('services.upgrade_service', 'Upgrade service') }}</span>
                            <span class="text-xs text-base/50">{{ translate('services.upgrade_service_description', 'Upgrade your service to a higher plan') }}</span>
                        </div>
                        @if($service->upgrade()->where('status', 'pending')->exists())
                            <x-button.primary class="max-w-25 py-3" 
                                @click="Alpine.store('notifications').addNotification([{message: '{{ translate('services.upgrade_pending', 'Upgrade pending') }}', type: 'error'}])">
                                {{ translate('services.upgrade', 'Upgrade') }}
                            </x-button.primary>
                        @else
                            <a href="{{ route('services.upgrade', $service->id) }}">
                                <x-button.primary class="max-w-25 py-3">
                                    {{ translate('services.upgrade', 'Upgrade') }}
                                </x-button.primary>
                            </a>
                        @endif
                    </div>
                @endif

                @if($service->cancellable)
                    <div class="flex items-center justify-between py-2">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-sm font-bold text-base">{{ translate('services.cancel', 'Cancel') }}</span>
                            <span class="text-xs text-base/50">{{ translate('services.cancel_service_description', 'Cancel your service') }}</span>
                        </div>
                        <x-button.danger class="max-w-25 py-3" wire:click="$set('showCancel', true)">
                            <span wire:loading.remove wire:target="$set('showCancel', true)">{{ translate('services.cancel', 'Cancel') }}</span>
                            <x-loading target="$set('showCancel', true)" />
                        </x-button.danger>
                    </div>
                @endif
            </div>
        @endif
    </div>

    @if (count($views) > 0)
        <div class="bg-background-secondary border border-neutral rounded-[var(--card-radius)] shadow-[var(--card-shadow)] overflow-hidden">
            @if (count($views) > 1)
                <div class="flex border-b border-neutral overflow-x-auto">
                    @foreach ($views as $view)
                        <button wire:click="changeView('{{ $view['name'] }}')" 
                            class="px-4 py-3 text-sm font-medium transition-colors whitespace-nowrap {{ $view['name'] == $currentView ? 'border-b-2 border-primary text-primary -mb-px' : 'text-base/50 hover:text-base' }}">
                            {{ $view['label'] }}
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="p-6">
                <x-loading target="changeView" />
                <div wire:loading.remove wire:target="changeView">
                    {!! $extensionView !!}
                </div>
            </div>
        </div>
    @endif

    @if (count($buttons) > 0)
        <div class="bg-background-secondary border border-neutral rounded-[var(--card-radius)] shadow-[var(--card-shadow)] p-6">
            <div class="text-sm font-bold text-base mb-4">{{ translate('services.actions', 'Actions') }}</div>
            <div class="flex flex-col gap-3">
                @foreach ($buttons as $button)
                    @if (isset($button['function']))
                        <x-button.primary class="w-full justify-center py-2.5" wire:click="goto('{{ $button['function'] }}')">
                            {{ $button['label'] }}
                        </x-button.primary>
                    @else
                        <a href="{{ $button['url'] }}" class="block">
                            <x-button.primary class="w-full justify-center py-2.5">
                                {{ $button['label'] }}
                            </x-button.primary>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    @if($showCancel)
        <x-modal open="true" title="{{ translate('services.cancellation', ['service' => $service->product->name]) }}" width="max-w-2xl">
            <livewire:services.cancel :service="$service" />
            <x-slot name="closeTrigger">
                <button wire:click="$set('showCancel', false)" @click="open = false" class="text-base/50 hover:text-base transition-colors">
                    <x-ri-close-fill class="size-6" />
                </button>
            </x-slot>
        </x-modal>
    @endif
</div>