@props([
'name',
'label' => null,
'required' => false,
'divClass' => null,
'class' => null,
'placeholder' => null,
'id' => null,
'type' => null,
'hideRequiredIndicator' => false,
'dirty' => false,
'withSecondaryBg' => false,
])
<fieldset class="flex flex-col relative w-full {{ $divClass ?? '' }}">
    @if ($label)
    <legend class="mb-2">
        <label for="{{ $name }}"
            class="text-sm font-medium text-base/90 ml-0.5">
            {{ $label }}
            @if ($required && !$hideRequiredIndicator)
            <span class="text-error">*</span>
            @endif
        </label>
    </legend>
    @endif
    <textarea type="{{ $type ?? 'text' }}" id="{{ $id ?? $name }}" name="{{ $name }}"
        class="block w-full text-sm text-base {{ $withSecondaryBg ? 'bg-background-secondary' : 'bg-background-secondary/50' }} border border-neutral rounded-[var(--input-radius)] outline-none focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 ease-out disabled:bg-background-secondary/50 disabled:cursor-not-allowed disabled:opacity-60 resize-y min-h-[100px] {{ $class ?? '' }} @if ($type !== 'color') px-3 py-2 @endif"
        placeholder="{{ $placeholder ?? '' }}" @if ($dirty && isset($attributes['wire:model']))
        wire:dirty.class="!border-warning !ring-warning/20" @endif {{ $attributes->except(['placeholder', 'label', 'id', 'name', 'type', 'class', 'divClass', 'required', 'hideRequiredIndicator', 'dirty']) }}
        @required($required)>{{ $slot }}</textarea>
    @error($name)
    <p class="text-error text-xs mt-1.5 ml-0.5">{{ $message }}</p>
    @enderror
</fieldset>