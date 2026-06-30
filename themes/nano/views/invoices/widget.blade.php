<div>
    @foreach ($invoices as $invoice)
        <a href="{{ route('invoices.show', $invoice) }}" wire:navigate>
            <div class="bg-background-secondary hover:bg-background-secondary/80 border border-neutral p-4 rounded-xl mb-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <span class="font-medium">{{ __('invoices.invoice', ['id' => $invoice->number]) }}</span>
                        <span class="text-base/50 font-semibold">
                            <x-ri-circle-fill class="size-1 text-base/20" />
                        </span>
                        <span class="text-base text-sm">{{ $invoice->formattedTotal }}</span>
                    </div>
                </div>
                @foreach ($invoice->items as $item)
                    <p class="text-base text-sm text-wrap">{{ __('invoices.item') }}: {{ $item->description }} ({{ __('invoices.invoice_date')}}:
                        {{
                    $invoice->created_at->format('d M Y') }})</p>
                @endforeach
            </div>
        </a>
    @endforeach
</div>