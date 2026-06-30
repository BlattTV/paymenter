@props(['href', 'spa' => true])
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'flex flex-row items-center']) }} @if($spa) wire:navigate @endif>
    {{ $slot }}
</a>