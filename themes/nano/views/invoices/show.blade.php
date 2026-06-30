<div @if ($checkPayment) wire:poll.5s="checkPaymentStatus" @endif class="space-y-6">
    @if ($this->pay || $showPayModal)
        @include('invoices.partials.payment-modal')
    @endif

    <div>
        <a href="{{ route('invoices') }}" wire:navigate class="inline-flex items-center gap-2 text-sm text-base/50 hover:text-primary transition-colors mb-4">
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>{{ __('invoices.invoices') }}</span>
        </a>

        <div class="bg-background-secondary border border-neutral rounded-[var(--card-radius)] shadow-[var(--card-shadow)] p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 sm:gap-6 mb-6 sm:mb-8">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">
                            {{ !$invoice->number && config('settings.invoice_proforma', false) ? __('invoices.proforma_invoice', ['id' => $invoice->id]) : __('invoices.invoice', ['id' => $invoice->number]) }}
                        </h1>
                        @if ($invoice->status == 'pending')
                            <span class="inline-flex items-center px-3 py-1 bg-yellow-500/20 text-yellow-400 text-xs font-medium rounded-full">
                                {{ translate('invoices.unpaid', 'Unpaid') }}
                            </span>
                        @elseif ($invoice->status == 'paid')
                            <span class="inline-flex items-center px-3 py-1 bg-green-500/20 text-green-400 text-xs font-medium rounded-full">
                                {{ translate('invoices.paid', 'Paid') }}
                            </span>
                        @elseif ($invoice->status == 'cancelled')
                            <span class="inline-flex items-center px-3 py-1 bg-red-500/20 text-red-400 text-xs font-medium rounded-full">
                                {{ translate('invoices.cancelled', 'Cancelled') }}
                            </span>
                        @endif
                    </div>
                    <div class="text-sm text-base/50 mt-2">
                        {{ translate('invoices.invoice_date', 'Invoice Date') }}: {{ $invoice->created_at->format('M d, Y') }}
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                    <button onclick="window.print()" class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 bg-background border border-neutral rounded-[var(--button-radius)] hover:bg-background/80 transition-colors text-sm font-medium">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <span class="text-sm font-medium">{{ __('invoices.print') }}</span>
                    </button>
                    <button wire:click="downloadPDF" class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 bg-background border border-neutral rounded-[var(--button-radius)] hover:bg-background/80 transition-colors text-sm font-medium">
                        <span wire:loading.remove wire:target="downloadPDF">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </span>
                        <span wire:loading wire:target="downloadPDF">
                            <x-ri-loader-5-fill class="size-4 animate-spin" />
                        </span>
                        <span class="text-sm font-medium">{{ __('invoices.download_pdf') }}</span>
                    </button>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-6 sm:gap-8 mb-6 sm:mb-8">
                <div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-base/50 mb-3">{{ __('invoices.from') }}</div>
                    <div class="flex items-center gap-2 mb-2">
                        <div class="flex items-center justify-center size-8 bg-primary/10 rounded-lg">
                            <x-logo class="size-5 text-primary" />
                        </div>
                        <div class="font-semibold text-base">{{ config('settings.company_name', 'BillingApp Hosting Ltd.') }}</div>
                    </div>
                    @if(config('settings.company_address'))
                        <p class="text-sm text-base/50 mb-1">{{ config('settings.company_address') }}</p>
                    @endif
                    @if(config('settings.company_address2'))
                        <p class="text-sm text-base/50 mb-1">{{ config('settings.company_address2') }}</p>
                    @endif
                    @if(config('settings.company_city') || config('settings.company_state') || config('settings.company_country') || config('settings.company_postal_code'))
                        <p class="text-sm text-base/50 mb-1">
                            @if(config('settings.company_city')){{ config('settings.company_city') }}@endif
                            @if(config('settings.company_state')){{ config('settings.company_state') ? ', ' . config('settings.company_state') : '' }}@endif
                            @if(config('settings.company_postal_code')){{ config('settings.company_postal_code') }}@endif
                            @if(config('settings.company_country')){{ config('settings.company_country') ? ', ' . config('settings.company_country') : '' }}@endif
                        </p>
                    @endif
                    @if(config('settings.company_tax_id'))
                        <p class="text-sm text-base/50 mt-2">{{ __('invoices.tax_id') }}: {{ config('settings.company_tax_id') }}</p>
                    @endif
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-base/50 mb-3">{{ __('invoices.bill_to') }}</div>
                    <div class="font-semibold text-base mb-1">{{ $invoice->user_name }}</div>
                    @foreach($invoice->user_properties as $property)
                        <p class="text-sm text-base/50">{{ $property }}</p>
                    @endforeach
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <div>
                    <div class="text-xs text-base/50 mb-1">
                        {{ !$invoice->number && config('settings.invoice_proforma', false) ? __('invoices.proforma_invoice_date') : __('invoices.invoice_date') }}
                    </div>
                    <div class="font-semibold text-sm">{{ $invoice->created_at->format('M d, Y') }}</div>
                </div>
                @if($invoice->due_at)
                    <div>
                        <div class="text-xs text-base/50 mb-1">{{ __('invoices.due_date') }}</div>
                        <div class="font-semibold text-sm">{{ $invoice->due_at->format('M d, Y') }}</div>
                    </div>
                @endif
            </div>

            <div class="bg-background-secondary border border-neutral rounded-[var(--card-radius)] overflow-hidden mb-6 sm:mb-8">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-background border-y border-neutral">
                            <tr>
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold uppercase tracking-wider text-left text-base/50">{{ __('invoices.description') }}</th>
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold uppercase tracking-wider text-center text-base/50">{{ __('invoices.quantity') }}</th>
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold uppercase tracking-wider text-right text-base/50">{{ __('invoices.unit_price') }}</th>
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold uppercase tracking-wider text-right text-base/50">{{ __('invoices.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral">
                            @foreach ($invoice->items as $item)
                                <tr class="hover:bg-background/50 transition-colors">
                                    <td class="px-3 sm:px-6 py-3 sm:py-4">
                                        @if(in_array($item->reference_type, ['App\Models\Service', 'App\Models\ServiceUpgrade']))
                                            <a href="{{ route('services.show', $item->reference_type == 'App\Models\Service' ? $item->reference_id : $item->reference->service_id) }}"
                                                class="text-primary hover:text-primary/80 font-medium text-sm">
                                                {{ $item->description }}
                                            </a>
                                        @else
                                            <span class="font-medium text-sm">{{ $item->description }}</span>
                                        @endif
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-center text-sm text-base/50">{{ $item->quantity }}</td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-right text-sm text-base/50">{{ $item->formattedPrice }}</td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-right font-semibold text-sm">{{ $item->formattedTotal }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-6 sm:gap-8">
                <div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-base/50 mb-3">{{ __('invoices.payment_info') }}</div>
                    <p class="text-sm text-base/50 mb-4">
                        {{ __('invoices.payment_terms', ['days' => $invoice->due_at ? ceil($invoice->created_at->diffInDays($invoice->due_at)) : 15]) }}
                    </p>
                </div>
                <div>
                    <div class="max-w-sm sm:ml-auto space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-base/50">{{ __('invoices.subtotal') }}:</span>
                            <span class="font-medium">{{ $invoice->formattedTotal->format($invoice->formattedTotal->subtotal) }}</span>
                        </div>
                        @if ($invoice->formattedTotal->tax > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-base/50">{{ $invoice->tax->name }} ({{ $invoice->tax->rate }}%):</span>
                                <span class="font-medium">{{ $invoice->formattedTotal->formatted->tax }}</span>
                            </div>
                        @else
                            <div class="flex justify-between text-sm">
                                <span class="text-base/50">{{ __('invoices.tax') }} (0%):</span>
                                <span class="font-medium">{{ $invoice->formattedTotal->format(0) }}</span>
                            </div>
                        @endif
                        <div class="h-px bg-neutral my-2"></div>
                        <div class="flex justify-between items-baseline">
                            <span class="text-base font-semibold">{{ __('invoices.grand_total') }}:</span>
                            <span class="text-xl sm:text-2xl font-bold text-primary">{{ $invoice->formattedTotal }}</span>
                        </div>
                        @if ($invoice->status == 'pending' && !$checkPayment && $invoice->transactions->where('status', \App\Enums\InvoiceTransactionStatus::Processing)->where('created_at', '>=', now()->subDays(1))->count() == 0)
                            <x-button.primary wire:click="$set('showPayModal', true)" class="w-full justify-center mt-4" wire:loading.attr="disabled" wire:target="$set('showPayModal')">
                                <span wire:loading wire:target="pay">{{ __('invoices.processing') }}...</span>
                                <span wire:loading.remove wire:target="pay">
                                    <span class="flex items-center gap-2">
                                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        {{ __('invoices.pay_now') }}
                                    </span>
                                </span>
                            </x-button.primary>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($checkPayment || $invoice->transactions->where('status', \App\Enums\InvoiceTransactionStatus::Processing)->where('created_at', '>=', now()->subDays(1))->count() > 0)
            <div class="mt-6 sm:mt-8 flex items-start gap-3 sm:gap-4 p-3 sm:p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-[var(--card-radius)]">
                <div class="flex items-center justify-center size-6 bg-yellow-500/20 rounded-lg flex-shrink-0 mt-0.5">
                    <x-ri-loader-5-fill class="size-4 text-yellow-400 animate-spin" />
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-sm text-yellow-400 mb-1">{{ __('invoices.payment_processing') }}</div>
                    <div class="text-xs text-base/50">{{ __('invoices.please_wait_processing') }}</div>
                </div>
            </div>
        @endif

        @if ($invoice->transactions->isNotEmpty())
            <div class="bg-background-secondary border border-neutral rounded-[var(--card-radius)] shadow-[var(--card-shadow)] p-4 sm:p-6 mt-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('invoices.transactions') }}</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b border-neutral">
                            <tr>
                                <th class="px-2 sm:px-4 pb-3 text-xs font-medium text-left text-base/50">{{ __('invoices.date') }}</th>
                                <th class="px-2 sm:px-4 pb-3 text-xs font-medium text-left text-base/50">{{ __('invoices.transaction_id') }}</th>
                                <th class="px-2 sm:px-4 pb-3 text-xs font-medium text-left text-base/50">{{ __('invoices.gateway') }}</th>
                                <th class="px-2 sm:px-4 pb-3 text-xs font-medium text-right text-base/50">{{ __('invoices.amount') }}</th>
                                <th class="px-2 sm:px-4 pb-3 text-xs font-medium text-right text-base/50">{{ __('invoices.status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral">
                            @foreach ($invoice->transactions->sortByDesc('created_at') as $transaction)
                                <tr class="hover:bg-background/50 transition-colors">
                                    <td class="px-2 sm:px-4 py-3 text-sm">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-2 sm:px-4 py-3 text-sm font-mono text-base/50">{{ $transaction->transaction_id }}</td>
                                    <td class="px-2 sm:px-4 py-3 text-sm text-base/50">
                                        @if($transaction->is_credit_transaction)
                                            {{ __('invoices.paid_with_credits') }}
                                        @else
                                            {{ $transaction->gateway?->name }}
                                        @endif
                                    </td>
                                    <td class="px-2 sm:px-4 py-3 text-sm text-right font-medium">{{ $transaction->formattedAmount }}</td>
                                    <td class="px-2 sm:px-4 py-3 text-right">
                                        @if($transaction->status == \App\Enums\InvoiceTransactionStatus::Succeeded)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400">
                                                {{ __('invoices.transaction_statuses.succeeded') }}
                                            </span>
                                        @elseif($transaction->status == \App\Enums\InvoiceTransactionStatus::Processing)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-400">
                                                <x-ri-loader-5-fill class="size-3 animate-spin" />
                                                {{ __('invoices.transaction_statuses.processing') }}
                                            </span>
                                        @elseif($transaction->status == \App\Enums\InvoiceTransactionStatus::Failed)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-400">
                                                {{ __('invoices.transaction_statuses.failed') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

</div>
