@props(['page' => 'home'])

@php
    if ($page === 'home') {
        $bgImage = theme('home_background_image', '');
    } else {
        $bgImage = theme('dashboard_background_image', '');
    }
    
    if (is_array($bgImage)) {
        $bgImage = $bgImage[0] ?? '';
    }
    
    $overlayType = theme('background_overlay_type', 'gradient');
    $overlayOpacity = theme('background_overlay_opacity', 80);
    $bgMinHeight = theme('background_min_height', '400');
    $bgMaxHeight = theme('background_max_height', '800');
    
    $topOpacity = $overlayOpacity / 100;
    
    $bgUrl = '';
    if ($bgImage) {
        if (str_starts_with($bgImage, 'http') || str_starts_with($bgImage, '/')) {
            $bgUrl = $bgImage;
        } else {
            $bgUrl = asset('storage/' . $bgImage);
        }
    }
@endphp

@if($bgUrl)
    <div class="absolute top-0 left-0 right-0 z-0 pointer-events-none overflow-hidden" style="min-height: {{ $bgMinHeight }}px; max-height: {{ $bgMaxHeight }}px;">
        <img src="{{ $bgUrl }}" alt="" class="w-full h-full object-cover">
        @if($overlayType === 'gradient')
            <div class="absolute inset-0 page-bg-gradient" style="--bg-top-opacity: {{ $topOpacity }};"></div>
        @else
            <div class="absolute inset-0 bg-background" style="opacity: {{ $topOpacity }};"></div>
        @endif
    </div>
@endif
