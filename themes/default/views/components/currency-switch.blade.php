{{-- @deprecated, removed in 1.6 --}}
<div>
    <strong class="block p-2 text-xs font-semibold uppercase text-base/50"> {{ __('general.currency') }} </strong>
    <x-select wire:model.live="currentCurrency" :options="$this->currencies" :placeholder="__('general.select_currency')" />
</div>