@php
    $isInStock = $product->stock === null || $product->stock > 0;
    $isAvailable = $isInStock && $product->price()->available;

    // SEO: Product structured data (rich results with price in Google)
    $seoPrice = $product->price();
    $seoProduct = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->name,
        'url' => url()->current(),
    ];
    if ($product->description) {
        $seoProduct['description'] = \Illuminate\Support\Str::limit(trim(strip_tags($product->description)), 300);
    }
    if ($product->image) {
        $seoProduct['image'] = \Storage::url($product->image);
    }
    if ($seoPrice->available) {
        $seoProduct['offers'] = [
            '@type' => 'Offer',
            'price' => number_format((float) $seoPrice->price, 2, '.', ''),
            'priceCurrency' => $seoPrice->currency->code ?? config('settings.default_currency'),
            'availability' => $isInStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'url' => url()->current(),
        ];
    }
    $seoBreadcrumb = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => config('app.name'), 'item' => config('app.url')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => $product->category->name, 'item' => url('/products/' . ($product->category->full_slug ?: $product->category->slug))],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $product->name, 'item' => url()->current()],
        ],
    ];
@endphp

@push('head')
    <script type="application/ld+json">{!! json_encode($seoProduct, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($seoBreadcrumb, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@php
    $stockLabel = $isInStock 
        ? (translate('product.in_stock', 'In Stock'))
        : (translate('product.out_of_stock', 'Out of Stock'));
    $configureText = translate('product.configure', 'Configure');
    
    $plan = $product->availablePlans()->first();
    $billingCycle = '';
    if ($plan && $plan->type === 'recurring') {
        $period = $plan->billing_period;
        $unit = $plan->billing_unit;
        if ($period == 1) {
            $billingCycle = match($unit) {
                'hour' => 'per hour',
                'day' => 'per day',
                'week' => 'per week',
                'month' => 'per month',
                'year' => 'per year',
                default => ''
            };
        } else {
            $unitPlural = match($unit) {
                'hour' => 'hours',
                'day' => 'days',
                'week' => 'weeks',
                'month' => 'months',
                'year' => 'years',
                default => $unit . 's'
            };
            $billingCycle = 'per ' . $period . ' ' . $unitPlural;
        }
    } elseif ($plan && $plan->type === 'one-time') {
        $billingCycle = 'one-time';
    }
    
    $hasImage = $product->image;
    $imageUrl = $hasImage ? Storage::url($product->image) : null;
@endphp

<div class="container mx-auto pb-8 pt-16 px-4" style="max-width: var(--container-max-width)">
    <div class="mb-6">
        <a href="{{ route('category.show', $category) }}" wire:navigate class="inline-flex items-center gap-2 text-sm text-muted hover:text-primary transition-colors">
            <x-ri-arrow-left-line class="size-4" />
            <span>Back to {{ $category->name }}</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @if($hasImage)
            <div class="bg-background-secondary border border-neutral rounded-[var(--card-radius)] shadow-[var(--card-shadow)] overflow-hidden">
                <img 
                    src="{{ $imageUrl }}" 
                    alt="{{ $product->name }}"
                    class="w-full h-auto object-cover aspect-video"
                />
            </div>
        @endif

        <div class="flex flex-col gap-6 {{ !$hasImage ? 'lg:col-span-2 max-w-2xl' : '' }}">
            <div class="bg-background-secondary border border-neutral rounded-[var(--card-radius)] shadow-[var(--card-shadow)] p-6">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-base mb-2">{{ $product->name }}</h1>
                        <div class="flex items-center gap-2">
                            <div class="size-2 rounded-full {{ $isInStock ? 'bg-green-500' : 'bg-red-500' }}"></div>
                            <span class="text-sm text-muted">{{ $stockLabel }}</span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-neutral pt-4 mt-4">
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-primary">{{ $product->price() }}</span>
                        @if($billingCycle)
                            <span class="text-sm text-muted">{{ $billingCycle }}</span>
                        @endif
                    </div>
                    @if($product->price()->has_setup_fee)
                        <p class="text-sm text-muted mt-1">
                            + {{ $product->price()->formatted->setup_fee }} {{ Lang::has('product.setup_fee') ? __('product.setup_fee') : 'setup fee' }}
                        </p>
                    @endif
                </div>

                @if($isAvailable)
                    <div class="mt-6">
                        <a href="{{ route('products.checkout', ['category' => $category, 'product' => $product->slug]) }}" wire:navigate class="block">
                            <x-button.primary class="w-full justify-center">
                                <x-ri-shopping-bag-4-line class="size-5 mr-2" />
                                {{ $configureText }}
                            </x-button.primary>
                        </a>
                    </div>
                @endif
            </div>

            @if($product->description)
                <div class="bg-background-secondary border border-neutral rounded-[var(--card-radius)] shadow-[var(--card-shadow)] p-6">
                    <h2 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">{{ __('product.description') }}</h2>
                    <article class="prose dark:prose-invert prose-sm max-w-none">
                        {!! $product->description !!}
                    </article>
                </div>
            @endif
        </div>
    </div>
</div>
