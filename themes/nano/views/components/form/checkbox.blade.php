<div class="flex items-start gap-3 {{ $divClass ?? '' }}">
    <input type="checkbox" name="{{ $name }}" id="{{ $id ?? $name }}"
        {{ $attributes->whereStartsWith('wire:model') }}
        {{ $attributes->only(['checked', 'wire:dirty.class']) }}
        class="form-checkbox size-5 mt-0.5 text-primary rounded-md focus:ring-primary focus:ring-2 focus:ring-offset-0 border-neutral transition-all duration-200 ease-out cursor-pointer" />
    <label class="text-sm text-base/90 cursor-pointer" for="{{ $id ?? $name }}">
        @if(isset($label))
            {{ $label }}
        @else
            {{ $slot }}
        @endif
    </label>

    @error($name)
        <p class="text-error text-xs mt-1.5 ml-0.5">{{ $message }}</p>
    @enderror
</div>
