@props([
    'name',
    'price',
    'link',
    'image' => null,
    'description' => null,
    'billingCycle' => null,
    'stock' => null,
    'stockInStockLabel' => null,
    'stockOutOfStockLabel' => null,
    'showImage' => true,
    'buttonText' => null,
    'categoryImage' => null,
    'label' => null,
])

@php
    $stockInStockLabel = $stockInStockLabel ?? (translate('product.in_stock', 'In Stock'));
    $stockOutOfStockLabel = $stockOutOfStockLabel ?? (translate('product.out_of_stock', 'Out of Stock'));
    $buttonText = $buttonText ?? (translate('product.configure', 'Configure'));
    $setupFeeText = translate('product.setup_fee', 'setup fee');
@endphp

@php
$isInStock = $stock === null || $stock > 0;
$stockLabel = $isInStock ? $stockInStockLabel : $stockOutOfStockLabel;
$cardLayout = theme('pricing-card-layout', 'default');
$showCategoryBackgroundRaw = theme('show-pricing-card-category-background', false);
$showCategoryBackground = $showCategoryBackgroundRaw === true || $showCategoryBackgroundRaw === 'true' || $showCategoryBackgroundRaw === '1' || $showCategoryBackgroundRaw === 1;

if ($image) {
    if (str_starts_with($image, 'http') || str_starts_with($image, '/')) {
        $imageUrl = $image;
    } else {
        $imageUrl = asset('storage/' . $image);
    }
} else {
    $imageUrl = null;
}

if ($categoryImage) {
    if (str_starts_with($categoryImage, 'http') || str_starts_with($categoryImage, '/')) {
        $categoryImageUrl = $categoryImage;
    } else {
        $categoryImageUrl = asset('storage/' . $categoryImage);
    }
} else {
    $categoryImageUrl = null;
}

$hasCategoryBg = $showCategoryBackground && $categoryImageUrl;
$hasLabel = !empty($label['text']);
$labelBg = $label['bg'] ?? '#7C3AED';
$labelColor = $label['color'] ?? '#ffffff';
@endphp

@if($cardLayout === 'compact')
    <div class="relative h-full">
        @if($hasLabel)
            <div class="absolute top-0 left-0 right-0 -translate-y-1/2 z-20 pointer-events-none flex justify-center px-3">
                <span class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-[var(--card-radius)] text-xs font-semibold shadow-md tracking-wide text-center max-w-full whitespace-normal" style="background-color: {{ $labelBg }}; color: {{ $labelColor }};">
                    {{ $label['text'] }}
                </span>
            </div>
        @endif
        <div class="h-full bg-background-secondary rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)] flex flex-col overflow-hidden relative">
            @if($hasCategoryBg)
                <div class="absolute top-0 left-0 right-0 z-0">
                    <img src="{{ $categoryImageUrl }}" alt="" class="w-full h-auto opacity-40">
                    <div class="absolute inset-0 bg-gradient-to-t from-background-secondary via-background-secondary/80 to-transparent"></div>
                </div>
            @endif
            <div class="p-6 relative z-10">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-semibold">{{ $name }}</h3>
                    <div class="flex items-center gap-1.5">
                        <div class="size-1.5 rounded-full {{ $isInStock ? 'bg-success' : 'bg-error' }}"></div>
                        <span class="text-xs text-muted">{{ $stockLabel }}</span>
                    </div>
                </div>

                <div class="flex items-baseline gap-2 mt-4">
                    <span class="text-4xl font-bold text-primary">
                        @if(is_object($price) && isset($price->formatted))
                            {{ $price->formatted->price }}
                        @else
                            {{ $price }}
                        @endif
                    </span>
                    @if($billingCycle)
                        <span class="text-sm text-base/70">{{ $billingCycle }}</span>
                    @endif
                </div>
                @if(is_object($price) && isset($price->has_setup_fee) && $price->has_setup_fee)
                    <div class="text-sm text-base/70 mt-1">
                        + {{ $price->formatted->setup_fee }} {{ $setupFeeText }}
                    </div>
                @endif
            </div>

            <div class="border-t border-neutral relative z-10"></div>

            <div class="p-6 flex flex-col flex-grow relative z-10">
                @if($description)
                    <div class="text-sm text-muted prose prose-sm dark:prose-invert max-w-none">
                        {!! $description !!}
                    </div>
                @endif

                <div class="mt-6">
                    <a href="{{ $link }}" wire:navigate class="block">
                        <x-button.primary class="w-full h-12" size="md">
                            {{ $slot }}
                            {{ $buttonText }}
                        </x-button.primary>
                    </a>
                </div>
            </div>
        </div>
    </div>

@elseif($cardLayout === 'modern')
    <div class="relative h-full">
        @if($hasLabel)
            <div class="absolute top-0 left-0 right-0 -translate-y-1/2 z-20 pointer-events-none flex justify-center px-3">
                <span class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-[var(--card-radius)] text-xs font-semibold shadow-md tracking-wide text-center max-w-full whitespace-normal" style="background-color: {{ $labelBg }}; color: {{ $labelColor }};">
                    {{ $label['text'] }}
                </span>
            </div>
        @endif
        <div class="h-full bg-background-secondary rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)] overflow-hidden flex flex-col relative">
            @if($hasCategoryBg)
                <div class="absolute top-0 left-0 right-0 z-0">
                    <img src="{{ $categoryImageUrl }}" alt="" class="w-full h-auto opacity-40">
                    <div class="absolute inset-0 bg-gradient-to-t from-background-secondary via-background-secondary/80 to-transparent"></div>
                </div>
            @endif
            <div class="p-6 relative z-10">
                <div class="flex items-start justify-between">
                    <h3 class="text-3xl font-bold">{{ $name }}</h3>
                    <div class="flex items-center gap-1.5">
                        <div class="size-2 rounded-full {{ $isInStock ? 'bg-success' : 'bg-error' }}"></div>
                        <span class="text-sm text-muted">{{ $stockLabel }}</span>
                    </div>
                </div>

                <div class="flex items-baseline gap-2 mt-4">
                    <span class="text-5xl font-bold text-primary">
                        @if(is_object($price) && isset($price->formatted))
                            {{ $price->formatted->price }}
                        @else
                            {{ $price }}
                        @endif
                    </span>
                    @if($billingCycle)
                        <span class="text-sm text-base/70">{{ $billingCycle }}</span>
                    @endif
                </div>
                @if(is_object($price) && isset($price->has_setup_fee) && $price->has_setup_fee)
                    <div class="text-sm text-base/70 mt-1">
                        +{{ $price->formatted->setup_fee }} {{ $setupFeeText }}
                    </div>
                @endif

                <div class="mt-6">
                    <a href="{{ $link }}" wire:navigate class="block">
                        <x-button.primary class="w-full h-12" size="md">
                            {{ $slot }}
                            {{ $buttonText }}
                        </x-button.primary>
                    </a>
                </div>
            </div>

            @if($description)
                <div class="p-6 flex-grow relative z-10">
                    <div class="text-sm text-muted prose prose-sm dark:prose-invert max-w-none">
                        {!! $description !!}
                    </div>
                </div>
            @endif
        </div>
    </div>

@elseif($cardLayout === 'featured')
    <div class="relative h-full">
        @if($hasLabel)
            <div class="absolute top-0 left-0 right-0 -translate-y-1/2 z-20 pointer-events-none flex justify-center px-3">
                <span class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-[var(--card-radius)] text-xs font-semibold shadow-md tracking-wide text-center max-w-full whitespace-normal" style="background-color: {{ $labelBg }}; color: {{ $labelColor }};">
                    {{ $label['text'] }}
                </span>
            </div>
        @endif
        <div class="h-full bg-background-secondary rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)] overflow-hidden flex flex-col relative">
            @if($hasCategoryBg)
                <div class="absolute top-0 left-0 right-0 z-0">
                    <img src="{{ $categoryImageUrl }}" alt="" class="w-full h-auto opacity-40">
                    <div class="absolute inset-0 bg-gradient-to-t from-background-secondary via-background-secondary/80 to-transparent"></div>
                </div>
            @endif
            <div class="p-6 text-center relative z-10">
                <div class="flex items-center gap-1.5 mb-4 {{ $hasLabel ? 'justify-center' : '' }}">
                    <div class="size-2 rounded-full {{ $isInStock ? 'bg-success' : 'bg-error' }}"></div>
                    <span class="text-sm text-muted">{{ $stockLabel }}</span>
                </div>

                <h3 class="text-3xl font-bold mb-4">{{ $name }}</h3>

                <div class="text-5xl font-bold text-primary">
                    @if(is_object($price) && isset($price->formatted))
                        {{ $price->formatted->price }}
                    @else
                        {{ $price }}
                    @endif
                </div>
                @if($billingCycle)
                    <div class="text-sm text-base/70 mt-1">{{ $billingCycle }}</div>
                @endif
                @if(is_object($price) && isset($price->has_setup_fee) && $price->has_setup_fee)
                    <div class="text-sm text-base/70 mt-1">
                        +{{ $price->formatted->setup_fee }} {{ $setupFeeText }}
                    </div>
                @endif

                <div class="mt-6">
                    <a href="{{ $link }}" wire:navigate class="block">
                        <x-button.primary class="w-full h-12" size="md">
                            {{ $slot }}
                            {{ $buttonText }}
                        </x-button.primary>
                    </a>
                </div>
            </div>

            @if($description)
                <div class="p-6 pt-0 flex-grow relative z-10">
                    <div class="text-sm text-muted prose prose-sm dark:prose-invert max-w-none">
                        {!! $description !!}
                    </div>
                </div>
            @endif
        </div>
    </div>

@elseif($cardLayout === 'minimal')
    <div class="relative h-full">
        @if($hasLabel)
            <div class="absolute top-0 left-0 right-0 -translate-y-1/2 z-20 pointer-events-none flex justify-center px-3">
                <span class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-[var(--card-radius)] text-xs font-semibold shadow-md tracking-wide text-center max-w-full whitespace-normal" style="background-color: {{ $labelBg }}; color: {{ $labelColor }};">
                    {{ $label['text'] }}
                </span>
            </div>
        @endif
        <div class="h-full bg-background-secondary rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)] overflow-hidden flex flex-col relative">
            @if($hasCategoryBg)
                <div class="absolute top-0 left-0 right-0 z-0">
                    <img src="{{ $categoryImageUrl }}" alt="" class="w-full h-auto opacity-40">
                    <div class="absolute inset-0 bg-gradient-to-t from-background-secondary via-background-secondary/80 to-transparent"></div>
                </div>
            @endif
            <div class="p-6 text-center relative z-10">
                <div class="flex items-center gap-1.5 mb-4 {{ $hasLabel ? 'justify-center' : '' }}">
                    <div class="size-2 rounded-full {{ $isInStock ? 'bg-success' : 'bg-error' }}"></div>
                    <span class="text-sm text-muted">{{ $stockLabel }}</span>
                </div>

                <h3 class="text-3xl font-bold mb-2">{{ $name }}</h3>

                <div class="text-5xl font-bold text-primary">
                    @if(is_object($price) && isset($price->formatted))
                        {{ $price->formatted->price }}
                    @else
                        {{ $price }}
                    @endif
                </div>
                @if($billingCycle)
                    <div class="text-sm text-base/70 mt-1">{{ $billingCycle }}</div>
                @endif
                @if(is_object($price) && isset($price->has_setup_fee) && $price->has_setup_fee)
                    <div class="text-sm text-base/70 mt-1">
                        +{{ $price->formatted->setup_fee }} {{ $setupFeeText }}
                    </div>
                @endif
            </div>

            @if($description)
                <div class="border-t border-neutral relative z-10"></div>
                <div class="p-6 flex-grow relative z-10">
                    <div class="text-sm text-muted prose prose-sm dark:prose-invert max-w-none">
                        {!! $description !!}
                    </div>
                </div>
            @endif

            <a href="{{ $link }}" wire:navigate class="block mt-auto relative z-10">
                <x-button.primary class="w-full h-12 rounded-t-none" size="md">
                    {{ $slot }}
                    {{ $buttonText }}
                </x-button.primary>
            </a>
        </div>
    </div>

@else
    <div class="relative h-full">
        @if($hasLabel)
            <div class="absolute top-0 left-0 right-0 -translate-y-1/2 z-20 pointer-events-none flex justify-center px-3">
                <span class="inline-flex items-center justify-center px-3.5 py-1.5 rounded-[var(--card-radius)] text-xs font-semibold shadow-md tracking-wide text-center max-w-full whitespace-normal" style="background-color: {{ $labelBg }}; color: {{ $labelColor }};">
                    {{ $label['text'] }}
                </span>
            </div>
        @endif
        <div class="h-full bg-background-secondary rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)] flex flex-col overflow-hidden relative">
            @if($hasCategoryBg)
                <div class="absolute top-0 left-0 right-0 z-0">
                    <img src="{{ $categoryImageUrl }}" alt="" class="w-full h-auto opacity-40">
                    <div class="absolute inset-0 bg-gradient-to-t from-background-secondary via-background-secondary/80 to-transparent"></div>
                </div>
            @endif
            <div class="p-6 relative z-10">
                <div class="flex items-center gap-4">
                    @if($showImage)
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $name }}" class="size-14 rounded-[var(--card-radius)] object-cover flex-shrink-0">
                        @else
                            <div class="size-14 rounded-[var(--card-radius)] bg-background border border-neutral flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-server text-primary text-xl"></i>
                            </div>
                        @endif
                    @endif
                    <div>
                        <h3 class="text-2xl font-semibold">{{ $name }}</h3>
                        <div class="flex items-center gap-1.5 mt-1">
                            <div class="size-1.5 rounded-full {{ $isInStock ? 'bg-success' : 'bg-error' }}"></div>
                            <span class="text-xs text-muted">{{ $stockLabel }}</span>
                        </div>
                    </div>
                </div>

                @if($description)
                    <div class="mt-4 text-sm text-muted prose prose-sm dark:prose-invert max-w-none">
                        {!! $description !!}
                    </div>
                @endif
            </div>

            <div class="border-t border-neutral relative z-10"></div>

            <div class="p-6 flex flex-col flex-grow relative z-10">
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-bold text-primary">
                        @if(is_object($price) && isset($price->formatted))
                            {{ $price->formatted->price }}
                        @else
                            {{ $price }}
                        @endif
                    </span>
                    @if($billingCycle)
                        <span class="text-sm text-base/70">{{ $billingCycle }}</span>
                    @endif
                </div>
                @if(is_object($price) && isset($price->has_setup_fee) && $price->has_setup_fee)
                    <div class="text-sm text-base/70 mt-1">
                        + {{ $price->formatted->setup_fee }} {{ $setupFeeText }}
                    </div>
                @endif
            </div>

            <a href="{{ $link }}" wire:navigate class="block mt-auto relative z-10">
                <x-button.primary class="w-full h-12 rounded-t-none" size="md">
                    {{ $slot }}
                    {{ $buttonText }}
                </x-button.primary>
            </a>
        </div>
    </div>
@endif
