<div class="flex flex-col">
    <strong class="block px-4 pt-2 pb-1 text-xs font-semibold uppercase text-base/50">{{ __('general.currency') }}</strong>
    <div class="flex flex-col max-h-48 overflow-y-auto px-2">
        @foreach($this->currencies as $currency)
            <button
                wire:click="$set('currentCurrency', '{{ $currency['value'] }}')"
                class="w-full text-left px-4 py-2 text-sm rounded-[var(--button-radius)] hover:bg-primary/10 transition {{ $currentCurrency === $currency['value'] ? 'bg-primary/20 font-semibold' : '' }}"
                type="button">
                {{ $currency['label'] }}
            </button>
        @endforeach
    </div>
</div>