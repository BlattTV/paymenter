@props([
'name',
'label' => null,
'options' => [],
'selected' => null,
'multiple' => false,
'required' => false,
'divClass' => null,
'hideRequiredIndicator' => false,
])
<fieldset class="flex flex-col w-full {{ $divClass ?? '' }}" name="{{ $name }}">
    @if ($label)
    <label for="{{ $name }}" class="text-lg font-semibold text-base mb-4">
        {{ $label }}
        @if ($required && !$hideRequiredIndicator)
        <span class="text-error">*</span>
        @endif
    </label>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 w-full">
        @if (count($options) == 0 && $slot)
        {{ $slot }}
        @else
        @foreach ($options as $key => $option)
        <label class="custom-radio">
            <input type="radio" id="{{ $name }}_{{ $key }}" name="{{ $name }}"
                value="{{ gettype($options) == 'array' ? $option : $key }}" 
                {{ ($multiple && $selected ? in_array($key, $selected) : $selected==$option) ? 'checked' : '' }} />
            <span class="radio-btn">
                <div class="option-content">
                    <h3>{{ $option }}</h3>
                </div>
            </span>
        </label>
        @endforeach
        @endif
    </div>

    @error($name)
    <p class="text-error text-xs mt-2 ml-0.5">{{ $message }}</p>
    @enderror
</fieldset>
