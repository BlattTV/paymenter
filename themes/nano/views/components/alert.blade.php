@props(['type' => 'warning', 'title' => null, 'link' => null, 'linkText' => null])

@php
    $styles = [
        'warning' => [
            'container' => 'bg-warning/10 border-warning/40 text-warning',
            'iconWrap' => 'bg-warning/15 text-warning',
            'icon' => 'fa-triangle-exclamation',
            'link' => 'text-warning hover:text-warning/80',
        ],
        'success' => [
            'container' => 'bg-success/10 border-success/40 text-success',
            'iconWrap' => 'bg-success/15 text-success',
            'icon' => 'fa-circle-check',
            'link' => 'text-success hover:text-success/80',
        ],
        'error' => [
            'container' => 'bg-error/10 border-error/40 text-error',
            'iconWrap' => 'bg-error/15 text-error',
            'icon' => 'fa-circle-xmark',
            'link' => 'text-error hover:text-error/80',
        ],
        'info' => [
            'container' => 'bg-info/10 border-info/40 text-info',
            'iconWrap' => 'bg-info/15 text-info',
            'icon' => 'fa-circle-info',
            'link' => 'text-info hover:text-info/80',
        ],
    ];
    $style = $styles[$type] ?? $styles['warning'];
@endphp

<div class="p-5 rounded-[var(--card-radius)] shadow-[var(--card-shadow)] overflow-hidden relative border {{ $style['container'] }}">
    <div class="flex items-start gap-3">
        <div class="size-8 rounded-[var(--button-radius)] flex items-center justify-center shrink-0 {{ $style['iconWrap'] }}">
            <i class="fa-solid {{ $style['icon'] }} text-sm"></i>
        </div>
        <div class="min-w-0">
            @if($title)
                <p class="font-semibold text-sm">{{ $title }}</p>
            @endif
            @if($slot->isNotEmpty())
                <div class="{{ $title ? 'mt-1.5' : '' }} text-sm text-base/80 prose prose-sm dark:prose-invert max-w-none">
                    {{ $slot }}
                </div>
            @endif
            @if($link && $linkText)
                <a href="{{ $link }}" class="text-sm font-medium underline underline-offset-2 mt-2 inline-block transition-colors {{ $style['link'] }}">
                    {{ $linkText }}
                </a>
            @endif
        </div>
    </div>
</div>
