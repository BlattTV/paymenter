@props(['name', 'label' => null, 'required' => false, 'divClass' => null, 'class' => null,'placeholder' => null, 'id' => null, 'type' => null, 'hideRequiredIndicator' => false, 'dirty' => false, 'labelSize' => 'sm', 'withSecondaryBg' => false])
<fieldset class="flex flex-col relative w-full {{ $divClass ?? '' }}">
    @if ($label)
    <legend class="mb-2">
        <label for="{{ $name }}"
            class="text-{{ $labelSize }} font-medium text-base/90 ml-0.5">
            {{ $label }}
            @if ($required && !$hideRequiredIndicator)
            <span class="text-error">*</span>
            @endif
        </label>
    </legend>
    @endif
    <input type="{{ $type ?? 'text' }}" id="{{ $id ?? $name }}" name="{{ $name }}"
        class="block w-full text-sm text-base {{ $withSecondaryBg ? 'bg-background-secondary/50 dark:bg-background-secondary/50' : 'bg-background' }} border border-neutral dark:border-background/30 rounded-[var(--input-radius)] focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 ease-out disabled:bg-background-secondary/50 disabled:cursor-not-allowed disabled:opacity-60 {{ $class ?? '' }} @if ($type !== 'color') px-3 py-2 @endif"
        placeholder="{{ $placeholder ?? '' }}"
        @if ($dirty && isset($attributes['wire:model'])) wire:dirty.class="!border-warning !ring-warning/20" @endif
        {{ $attributes->except(['placeholder', 'label', 'id', 'name', 'type', 'class', 'divClass', 'required', 'hideRequiredIndicator', 'dirty']) }} @required($required) />
    @error($name)
        <p class="text-error text-xs mt-1.5 ml-0.5">{{ $message }}</p>
@enderror
</fieldset>