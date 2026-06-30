@props([
    'label',
    'id' => 'toggle-' . \Illuminate\Support\Str::random(8),
    'disabled' => false,
])

<label for="{{ $id }}" class="flex items-center gap-3 {{ $disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer group' }}">
    <div class="relative">
        <input
            id="{{ $id }}"
            type="checkbox"
            class="sr-only peer"
            {{ $attributes->except('disabled') }}
            {{ $disabled ? 'disabled' : '' }}
        >
        <div class="w-11 h-6 bg-background-secondary border border-neutral rounded-full peer-checked:bg-primary peer-checked:border-primary transition-all duration-200 ease-out"></div>
        <div class="absolute left-[2px] top-[2px] bg-white rounded-full h-5 w-5 
                    peer-checked:translate-x-5 
                    transition-transform duration-200 ease-out">
        </div>
    </div>
    @isset($label)
    <span class="text-sm font-medium text-base/90">{{ $label }}</span>
    @endisset
</label>

