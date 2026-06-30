@php
    $currentRoute = request()->livewireUrl();

    $navigation = [
        \App\Classes\Navigation::getLinks(),
        \App\Classes\Navigation::getAccountDropdownLinks(),
        \App\Classes\Navigation::getDashboardLinks(),
    ];

    function findBreadcrumb($items, $currentRoute) {
        foreach ($items as $item) {
            if (isset($item['url']) && $item['url'] === $currentRoute) {
                return [$item];
            }

            if (!empty($item['children'])) {
                $childTrail = findBreadcrumb($item['children'], $currentRoute);
                if (!empty($childTrail)) {
                    return array_merge([$item], $childTrail);
                }
            }
        }

        return [];
    }

    $breadcrumbs = [];
    foreach ($navigation as $group) {
        $breadcrumbs = findBreadcrumb($group, $currentRoute);
        if (!empty($breadcrumbs)) {
            break;
        }
    }
@endphp

<div class="flex items-center gap-2 mb-6">
    <a href="{{ route('dashboard') }}" class="text-base/70 hover:text-primary transition">
        <x-ri-home-4-line class="size-5" />
    </a>
    
    @if (!empty($breadcrumbs))
        @foreach ($breadcrumbs as $index => $breadcrumb)
            <x-ri-arrow-right-s-line class="size-4 text-base/30" />
            
            @if ($index === count($breadcrumbs) - 1)
                <span class="text-base text-base">
                    {{ $breadcrumb['name'] ?? '' }}
                </span>
            @else
                <a href="{{ isset($breadcrumb['route']) ? route($breadcrumb['route'], $breadcrumb['params'] ?? []) : '#' }}" 
                   class="text-base/70 hover:text-primary transition font-medium">
                    {{ $breadcrumb['name'] ?? '' }}
                </a>
            @endif
        @endforeach
    @endif
</div>
