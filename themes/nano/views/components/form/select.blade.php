@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'multiple' => false,
    'required' => false,
    'divClass' => null,
    'hideRequiredIndicator' => false,
    'placeholder' => null,
    'labelSize' => 'sm',
    'withSecondaryBg' => false,
])
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

    <select id="{{ $id ?? $name }}" {{ $multiple ? 'multiple' : '' }} {{ $attributes->only([
    'required',
    'wire:model',
    'wire:dirty.class',
    'wire:model.live'
]) }}
        class="block px-3 py-2 w-full text-sm text-base {{ $withSecondaryBg ? 'bg-background-secondary/50 dark:bg-background-secondary/50' : 'bg-background' }} border border-neutral rounded-[var(--input-radius)] outline-none focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 ease-out">
        @if ($placeholder && !$required)
        <option value="" disabled {{ !$selected ? 'selected' : '' }}>{{ $placeholder }}</option>
        @endif
        @if (count($options) == 0 && $slot)
        {{ $slot }}
        @else
        @foreach ($options as $key => $option)
        <option value="{{ gettype($options) == 'array' ? $option : $key }}" {{ ($multiple && $selected ? in_array(
            $key,
            $selected
        ) : $selected == $option) ? 'selected' : '' }}>
            {{ $option }}</option>
        @endforeach
        @endif
    </select>
    @if ($multiple)
    <p class="text-xs text-muted mt-1.5 ml-0.5">
        {{ translate('form.pro_tip', 'Pro tip: Hold down the Ctrl (Windows) / Command (Mac) button to select multiple options.') }}</p>
    @endif

    @error($name)
    <p class="text-error text-xs mt-1.5 ml-0.5">{{ $message }}</p>
    @enderror
</fieldset>