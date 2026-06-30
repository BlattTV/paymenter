<div>
    @if($setupModalVisible)
    <x-modal :title="__('account.payment_methods')" open="true">
        <x-slot name="closeTrigger">
            <div class="flex gap-4">
                <button wire:click="$set('setupModalVisible', false)" class="text-primary-100">
                    <x-ri-close-fill class="size-6" />
                </button>
            </div>
        </x-slot>
        @if(count($this->gateways ?? []) > 1)
        <x-form.select name="gateway" :label="__('account.input.payment_gateway')" wire:model.live="gateway"
            required>
            @foreach($this->gateways as $gateway)
            <option value="{{ $gateway->id }}">{{ $gateway->name }}</option>
            @endforeach
        </x-form.select>
        @elseif(count($this->gateways ?? []) === 0)
        <p class="text-sm text-red-500">{{ __('account.no_payment_gateways_available') }}</p>
        @endif
        <x-button.primary class="w-full mt-4" wire:click="createBillingAgreement" wire:loading.attr="disabled">
            <x-loading target="createBillingAgreement" />
            <div wire:loading.remove wire:target="createBillingAgreement">
                {{ __('account.setup_payment_method') }}
            </div>
        </x-button.primary>
        @if ($this->setup)
        <x-modal :title="__('account.setup_payment_method')" open>
            <div class="mt-8">
                {{ $this->setup }}
            </div>
            <x-slot name="closeTrigger">
                <div class="flex gap-4">
                    <button wire:confirm="Are you sure?" wire:click="cancelSetup" wire:loading.attr="disabled"
                        wire:target="cancelSetup" class="text-primary-100">
                        <x-ri-close-fill class="size-6" />
                    </button>
                </div>
            </x-slot>
        </x-modal>
        @endif
    </x-modal>
    @endif

    <div class="mt-6">
        <div class="bg-background-secondary border border-neutral rounded-xl overflow-hidden">
            <div class="p-6 border-b border-neutral">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold">{{ __('account.saved_payment_methods') }}</h2>
                        <p class="text-xs text-base/50">{{ __('account.saved_payment_methods_description') }}</p>
                    </div>
                    @if(count($this->gateways ?? []) > 0)
                    <x-button.primary wire:click="$set('setupModalVisible', true)"
                        wire:loading.attr="disabled" wire:target="setupModalVisible" class="px-6">
                        <x-ri-add-line class="size-4" />
                        {{ __('account.add_payment_method') }}
                    </x-button.primary>
                    @endif
                </div>
            </div>

            <div class="p-6">
                @php
                $groupedAgreements = $billingAgreements->groupBy('gateway.name');
                @endphp

                @if($groupedAgreements->count() > 0)
                <div class="space-y-6">
                    @foreach($groupedAgreements as $gatewayName => $agreements)
                    <div class="pb-6 border-b border-neutral/50 last:border-b-0 last:pb-0">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-background-secondary border border-neutral rounded-xl overflow-hidden size-9 flex items-center justify-center">
                                @if($agreements->first()?->gateway?->meta?->icon)
                                <img src="{{ $agreements->first()->gateway->meta->icon }}" alt="{{ $gatewayName }}" class="size-9" />
                                @else
                                <x-ri-secure-payment-line class="size-5 text-primary" />
                                @endif
                            </div>
                            <h3 class="text-lg font-semibold">{{ $gatewayName }}</h3>
                            <span class="bg-primary flex items-center justify-center font-semibold rounded-xl px-2 py-0.5 text-xs text-white">
                                {{ $agreements->count() }}
                            </span>
                        </div>

                        <div class="space-y-3">
                            @foreach($agreements as $agreement)
                            <div class="bg-background-secondary border border-neutral p-4 rounded-xl hover:bg-background-secondary/80 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="rounded-xl overflow-hidden flex items-center justify-center">
                                            @switch(strtolower($agreement->type))
                                            @case('visa')<x-icons.visa class="size-9" />@break
                                            @case('mastercard')<x-icons.mastercard class="size-9" />@break
                                            @case('amex')<x-icons.amex class="size-9" />@break
                                            @case('american express')<x-icons.american-express class="size-9" />@break
                                            @case('discover')<x-icons.discover class="size-9" />@break
                                            @case('paypal')<x-icons.paypal class="size-9" />@break
                                            @case('sepa_debit')<x-icons.sepa class="size-9" />@break
                                            @case('ideal')<x-icons.ideal class="size-9" />@break
                                            @case('bancontact')<x-icons.bancontact class="size-9" />@break
                                            @case('sofort')<x-icons.sofort class="size-9" />@break
                                            @case('us_bank_account')
                                            @case('bacs_debit')
                                            @case('au_becs_debit')<x-icons.bank-debit class="size-9" />@break
                                            @default<x-ri-bank-card-line class="size-6 text-primary" />
                                            @endswitch
                                        </div>
                                        <div>
                                            <div class="font-semibold text-base">
                                                {{ $agreement->name }}
                                            </div>
                                            @if($agreement->expiry)
                                            <div class="text-sm text-base/50 mt-1">
                                                {{ __('invoices.expires') }}: {{ \Carbon\Carbon::parse($agreement->expiry)->format('m/Y') }}
                                            </div>
                                            @endif
                                            @if($agreement->services()->count() > 0)
                                            <div class="text-xs text-base/50 mt-1">
                                                {{ __('account.services_linked', ['count' => $agreement->services()->count()]) }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <x-button.danger class="!px-2" x-on:click="$store.confirmation.confirm({
                                            title: '{{ __('account.remove_payment_method') }}',
                                            message: '{{ __('account.remove_payment_method_confirm', ['name' => $agreement->name]) }}',
                                            confirmText: '{{ __('account.confirm') }}',
                                            cancelText: '{{ __('account.cancel') }}',
                                            callback: () => $wire.removePaymentMethod('{{ $agreement->ulid }}')
                                        })">
                                            <x-ri-delete-bin-line class="size-4" />
                                        </x-button.danger>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <x-ri-bank-card-line class="size-12 text-base/30 mx-auto mb-3" />
                    <p class="text-base/70">{{ __('account.no_saved_payment_methods') }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-background-secondary border border-neutral rounded-xl overflow-hidden mt-6">
            <div class="p-6 border-b border-neutral">
                <div class="flex items-center gap-3">
                    <div>
                        <h2 class="text-xl font-semibold">{{ __('account.recent_transactions') }}</h2>
                        <p class="text-xs text-base/50">{{ __('account.recent_transactions_description') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="space-y-3">
                    @foreach ($transactions as $transaction)
                    <a href="{{ route('invoices.show', $transaction->invoice) }}" wire:navigate>
                        <div class="bg-background-secondary hover:bg-background-secondary/80 border border-neutral p-4 rounded-xl transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="bg-secondary/10 p-2 rounded-xl">
                                        <x-ri-bill-line class="size-5 text-secondary" />
                                    </div>
                                    <div>
                                        <div class="font-medium text-sm">
                                            {{ $transaction->transaction_id ? __('invoices.transaction_id') . ': ' . $transaction->transaction_id : __('account.transaction_id_na') }}
                                        </div>
                                        <div class="text-xs text-base/50 mt-1">
                                            {{ __('account.paid_via', ['amount' => $transaction->formattedAmount, 'gateway' => $transaction->gateway ? $transaction->gateway->name : __('common.na')]) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-base/70">
                                        @if($transaction->status === \App\Enums\InvoiceTransactionStatus::Succeeded)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                            <x-ri-check-line class="size-3 mr-1" />
                                            {{ __('invoices.transaction_statuses.succeeded') }}
                                        </span>
                                        @elseif($transaction->status === \App\Enums\InvoiceTransactionStatus::Processing)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">
                                            <x-ri-loader-5-fill class="size-3 mr-1 fill-yellow-600 animate-spin" />
                                            {{ __('invoices.transaction_statuses.processing') }}
                                        </span>
                                        @elseif($transaction->status === \App\Enums\InvoiceTransactionStatus::Failed)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">
                                            <x-ri-close-line class="size-3 mr-1" />
                                            {{ __('invoices.transaction_statuses.failed') }}
                                        </span>
                                        @endif
                                    </span>
                                    <span class="text-xs text-base/50">
                                        {{ $transaction->created_at->format('d M Y H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

