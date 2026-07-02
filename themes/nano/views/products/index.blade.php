<div>
@php
$heroText = theme('products_hero_text');
if (!$heroText || $heroText === 'Choose the perfect plan for your needs. All plans come with our industry-leading support and reliability.') { $heroText = 'Individuell konfigurierbare Gameserver – wähle RAM, CPU und Speicher genau nach deinem Bedarf.'; }
$heroCtaText = theme('products_hero_cta_text');
if (!$heroCtaText || $heroCtaText === 'View Plans') { $heroCtaText = 'Zu den Tarifen'; }
$heroImage = theme('products_hero_image', '/nano/hero.png');

$emptyProductsTitle = theme('products_empty_title');
if (!$emptyProductsTitle || $emptyProductsTitle === 'Empty! No products found.') { $emptyProductsTitle = 'Keine Produkte gefunden.'; }
$emptyProductsIcon = theme('products_empty_icon', 'ri-shopping-bag-3-line');

$stockInStockLabel = theme('products_stock_in_stock');
if (!$stockInStockLabel || $stockInStockLabel === 'In Stock') { $stockInStockLabel = translate('product.in_stock', 'Auf Lager'); }
$stockOutOfStockLabel = theme('products_stock_out_of_stock');
if (!$stockOutOfStockLabel || $stockOutOfStockLabel === 'Out of Stock') { $stockOutOfStockLabel = translate('product.out_of_stock', 'Nicht auf Lager'); }

$faqSectionTitle = theme('faq_section_title');
if (!$faqSectionTitle || $faqSectionTitle === 'Frequently Asked Questions') { $faqSectionTitle = 'Häufige Fragen zu Minecraft-Servern'; }
$faqSectionSubtitle = theme('faq_section_subtitle');
if (!$faqSectionSubtitle || $faqSectionSubtitle === "Got questions? We've got answers. Find everything you need to know about our hosting services.") { $faqSectionSubtitle = 'Alles Wichtige rund um dein Minecraft-Hosting bei Hoelni-Hosting.'; }

$networkSectionTitle = theme('reasons_section_title');
if (!$networkSectionTitle || $networkSectionTitle === "We've got you covered") { $networkSectionTitle = 'Warum Hoelni-Hosting?'; }
$networkSectionSubtitle = theme('reasons_section_subtitle');
if (!$networkSectionSubtitle || $networkSectionSubtitle === 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis suscipit eaque, iste dolor cupiditate blanditiis.') { $networkSectionSubtitle = 'Leistungsstarkes Minecraft-Hosting aus Deutschland – schnell, zuverlässig und ohne versteckte Kosten.'; }

$defaultReasons = [
    ['title' => 'Sofort startklar', 'description' => 'Dein Minecraft-Server wird automatisch eingerichtet und ist in der Regel in unter einer Minute spielbereit.', 'icon' => 'fa-solid fa-bolt', 'stat' => '< 1 Min', 'stat_label' => 'Einrichtung'],
    ['title' => 'NVMe-SSD-Speicher', 'description' => 'Moderne NVMe-SSDs sorgen für kurze Ladezeiten und flüssiges Gameplay.', 'icon' => 'fa-solid fa-hard-drive', 'stat' => 'NVMe', 'stat_label' => 'Speicher'],
    ['title' => 'DDoS-Schutz', 'description' => 'Der Traffic wird über einen abgesicherten Tunnel geleitet – dein Server bleibt auch bei Angriffen erreichbar.', 'icon' => 'fa-solid fa-shield-halved', 'stat' => '24/7', 'stat_label' => 'Schutz'],
    ['title' => 'Standort Deutschland', 'description' => 'Unsere Hardware steht in Grub am Forst bei Coburg – niedrige Latenz für Spieler aus dem DACH-Raum.', 'icon' => 'fa-solid fa-location-dot', 'stat' => 'DE', 'stat_label' => 'Serverstandort'],
    ['title' => 'Eigenes Panel', 'description' => 'Starten, stoppen, konfigurieren und Mods verwalten – alles über unser übersichtliches, selbst entwickeltes Panel.', 'icon' => 'fa-solid fa-sliders', 'stat' => '100%', 'stat_label' => 'Kontrolle'],
    ['title' => 'Durchgehend online', 'description' => 'Dein Server ist rund um die Uhr erreichbar, damit du und deine Community jederzeit spielen könnt.', 'icon' => 'fa-solid fa-server', 'stat' => '24/7', 'stat_label' => 'Erreichbar'],
];

$defaultFaqs = [
    ['question' => 'Wie schnell ist mein Minecraft-Server bereit?', 'answer' => 'Nach der Bestellung wird dein Server automatisch eingerichtet – in der Regel in unter einer Minute. Danach kannst du sofort loslegen.'],
    ['question' => 'Wo stehen die Server?', 'answer' => 'Unsere Hardware steht in Deutschland, in Grub am Forst bei Coburg. Das sorgt für niedrige Latenzen, besonders für Spieler aus dem DACH-Raum.'],
    ['question' => 'Ist mein Server vor DDoS-Angriffen geschützt?', 'answer' => 'Ja. Der Traffic wird über einen abgesicherten Tunnel geleitet, sodass dein Server auch bei Angriffen erreichbar bleibt.'],
    ['question' => 'Kann ich RAM, CPU und Speicher später anpassen?', 'answer' => 'Ja, du kannst die Ausstattung deines Servers jederzeit bequem in deinem Kundenbereich erweitern oder reduzieren.'],
    ['question' => 'Welche Hardware kommt zum Einsatz?', 'answer' => 'Wir setzen auf moderne NVMe-SSDs für kurze Ladezeiten und ein flüssiges Spielerlebnis.'],
    ['question' => 'Wie verwalte ich meinen Server?', 'answer' => 'Über unser eigenes, übersichtliches Panel kannst du deinen Server starten, stoppen, konfigurieren sowie Plugins und Mods verwalten.'],
    ['question' => 'Was kostet ein Minecraft-Server?', 'answer' => 'Unsere Minecraft-Server starten ab 5 € im Monat. Den genauen Preis bestimmst du selbst über die gewählten RAM-, CPU- und Speicheroptionen.'],
];

$faqs = theme('faqs', $defaultFaqs);
if (!is_array($faqs)) {
    $faqs = $defaultFaqs;
}
$faqs = array_map(function ($item) {
    return [
        'question' => $item['question'] ?? '',
        'answer' => $item['answer'] ?? '',
    ];
}, $faqs);
$faqs = array_values(array_filter($faqs, function ($item) {
    return trim(($item['question'] ?? '') . ($item['answer'] ?? '')) !== '';
}));
$hasFaqs = count($faqs) > 0;

$networkStats = theme('reasons', $defaultReasons);

$networkStats = array_values(array_filter($networkStats, function ($item) {
    return trim(($item['title'] ?? '') . ($item['description'] ?? '')) !== '';
}));

$hasNetworkStats = count($networkStats) > 0;

$productLabelsRaw = theme('product_labels', []);
$productLabelsMap = [];
if (is_array($productLabelsRaw)) {
    foreach ($productLabelsRaw as $lbl) {
        $pid = (string) ($lbl['product_id'] ?? '');
        if ($pid !== '') {
            $productLabelsMap[$pid] = [
                'text' => $lbl['label_text'] ?? '',
                'bg'   => $lbl['label_bg'] ?? '#7C3AED',
                'color' => $lbl['label_color'] ?? '#ffffff',
            ];
        }
    }
}
@endphp

<style>
    html {
        scroll-behavior: smooth;
    }
</style>

@php
    $headerType = theme('products_category_header_type', 'gradient');
    $headerColor = theme('products_category_header_color', 'hsl(231, 58%, 55%)');
    $imageOverlayOpacity = theme('products_category_image_overlay_opacity', 80);
    $imageMinHeight = theme('products_category_image_min_height', 400);
    $imageMaxHeight = theme('products_category_image_max_height', 800);
    
    $categoryImageUrl = '';
    if ($headerType === 'image' && $category->image) {
        $categoryImageUrl = Storage::disk('public')->url($category->image);
    }
    
    $topOpacity = $imageOverlayOpacity / 100;
@endphp

<section class="relative mb-12 -mt-16 pt-10">
    @if($headerType === 'solid')
        <div class="absolute inset-0" style="background-color: {{ $headerColor }};"></div>
    @elseif($headerType === 'gradient')
        <div class="floating-hero-gradient"></div>
    @elseif($headerType === 'image' && $categoryImageUrl)
        <div class="absolute top-0 left-0 right-0 z-0 pointer-events-none" style="height: {{ $imageMinHeight }}px; max-height: {{ $imageMaxHeight }}px;">
            <img src="{{ $categoryImageUrl }}" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 page-bg-gradient" style="--bg-top-opacity: {{ $topOpacity }};"></div>
        </div>
    @endif
    
    <div class="container mx-auto relative z-10 pt-16 pb-8 md:pt-28 md:pb-16" style="max-width: var(--container-max-width);">
        <div class="mt-8 md:mt-0 mx-4 2xl:mx-0 space-y-6">
            <div class="space-y-4">
                <h1 class="text-4xl md:text-5xl font-semibold tracking-tight leading-[1.05]">
                    <span class="{{ $headerType === 'gradient' || $headerType === 'solid' ? 'text-white' : 'text-base' }} block font-[700]">{{ $category->name }}</span>
                </h1>

                @if($category->description)
                    <p class="text-lg md:text-xl {{ ($headerType === 'gradient' || $headerType === 'solid') ? 'text-white' : 'text-base/70' }} leading-relaxed max-w-2xl">
                        {!! $category->description !!}
                    </p>
                @else
                    <p class="text-lg md:text-xl {{ $headerType === 'gradient' || $headerType === 'solid' ? 'text-white' : 'text-base/70' }} leading-relaxed max-w-2xl">
                        {!! $heroText !!}
                    </p>
                @endif
            </div>

            <div class="flex flex-row gap-3 sm:gap-5">
                <a href="#plans" class="text-white bg-primary hover:bg-primary/90 px-4 py-2 sm:px-6 sm:py-3 rounded-xl font-semibold transition flex gap-2 sm:gap-3 items-center text-sm">
                    <i class="fa fa-rocket mr-2 sm:mr-3"></i>
                    {{ $heroCtaText }}
                </a>
            </div>
        </div>
    </div>
</section>

<div class="px-4 md:mx-auto" style="max-width: var(--container-max-width)">
    @if (count($childCategories) >= 1)
                <div class="mb-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($childCategories as $childCategory)
                            <a href="{{ route('category.show', ['category' => $childCategory->slug]) }}" 
                               wire:navigate 
                               class="group flex items-center gap-4 bg-background-secondary border border-neutral rounded-xl p-4 transition-all duration-200 hover:border-primary">
                                @if ($childCategory->image)
                                    <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0 border border-neutral">
                                        <img src="{{ Storage::disk('public')->url($childCategory->image) }}" 
                                             alt="{{ $childCategory->name }}"
                                             class="w-full h-full object-cover" />
                                    </div>
                                @else
                                    <div class="w-14 h-14 rounded-xl bg-neutral/30 flex items-center justify-center flex-shrink-0">
                                        <x-ri-folder-line class="size-6 text-muted" />
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-base group-hover:text-primary transition-colors truncate">
                                        {{ $childCategory->name }}
                                    </h3>
                                    @if(theme('show_category_description', true) && $childCategory->description)
                                        <p class="text-sm text-muted truncate mt-0.5">
                                            {{ Str::limit(strip_tags($childCategory->description), 50) }}
                                        </p>
                                    @endif
                                </div>
                                <x-ri-arrow-right-s-line class="size-5 text-muted group-hover:text-primary transition-colors flex-shrink-0" />
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <section id="plans" class="scroll-mt-8">
            @if(count($products) === 0 && count($childCategories) === 0)
                <div class="bg-background-secondary border border-neutral border-dashed rounded-2xl p-12 text-center">
                    <div class="w-14 h-14 bg-neutral/30 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <x-ri-shopping-bag-3-line class="size-7 text-muted" />
                    </div>
                    <h3 class="text-lg font-semibold text-base mb-1">{{ $emptyProductsTitle }}</h3>
                </div>
            @elseif(count($products) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ theme('pricing-plans-grid-columns', 3) }} gap-6">
                    @foreach($products as $product)
                        @php
                            $pricingLink = route('products.checkout', ['category' => $category, 'product' => $product->slug]);

                            $isAvailable = ($product->stock > 0 || !$product->stock) && $product->price()->available;

                            $billingCycle = '';
                            $plan = $product->availablePlans()->first();
                            if ($plan && $plan->type === 'recurring') {
                                $period = $plan->billing_period;
                                $unit = $plan->billing_unit;
                                if ($period == 1) {
                                    $billingCycle = match ($unit) {
                                        'hour' => 'pro Stunde',
                                        'day' => 'pro Tag',
                                        'week' => 'pro Woche',
                                        'month' => 'pro Monat',
                                        'year' => 'pro Jahr',
                                        default => 'pro Abrechnungszeitraum'
                                    };
                                } else {
                                    $unitPlural = match ($unit) {
                                        'hour' => 'Stunden',
                                        'day' => 'Tage',
                                        'week' => 'Wochen',
                                        'month' => 'Monate',
                                        'year' => 'Jahre',
                                        default => $unit . 's'
                                    };
                                    $billingCycle = 'alle ' . $period . ' ' . $unitPlural;
                                }
                            } elseif ($plan && $plan->type === 'one-time') {
                                $billingCycle = 'einmalig';
                            } elseif ($plan && $plan->type === 'free') {
                                $billingCycle = '';
                            } else {
                                $billingCycle = 'pro Abrechnungszeitraum';
                            }
                        @endphp

                        @php $productLabel = $productLabelsMap[(string) $product->id] ?? null; @endphp
                        <x-pricing-card
                            :name="$product->name"
                            :price="$product->price()"
                            :link="$pricingLink"
                            :image="$product->image"
                            :description="$product->description"
                            :billingCycle="$billingCycle"
                            :stock="$product->stock"
                            :stockInStockLabel="$stockInStockLabel"
                            :stockOutOfStockLabel="$stockOutOfStockLabel"
                            :showImage="theme('show-pricing-plans-images', true)"
                            :buttonText="translate('product.configure', 'Configure')"
                            :categoryImage="$category->image ?? null"
                            :label="$productLabel"
                        >
                            <x-ri-arrow-right-line class="size-4" />
                        </x-pricing-card>
                    @endforeach
                </div>
            @endif
            </section>

    @if(theme('show_faq_section', true) && $hasFaqs)
        <section class="py-16 md:py-54">
            <div class="text-center space-y-4 mb-10">
                <h2 class="text-3xl md:text-4xl font-semibold tracking-tight">{{ $faqSectionTitle }}</h2>
                <p class="text-lg text-muted leading-relaxed max-w-2xl mx-auto">{{ $faqSectionSubtitle }}</p>
            </div>

            <div class="w-full max-w-3xl mx-auto space-y-3">
                @foreach($faqs as $index => $faq)
                    <details class="group rounded-2xl bg-background-secondary px-6 py-5" @if($index === 0) open @endif>
                        <summary class="cursor-pointer list-none flex items-start justify-between gap-6">
                            <span class="text-base font-semibold">{{ $faq['question'] }}</span>
                                <svg class="size-5 text-base/60 transition-transform group-open:rotate-180" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                        </summary>
                        <div class="pt-4 text-sm text-muted leading-relaxed">
                            {{ $faq['answer'] }}
                        </div>
                    </details>
                @endforeach
            </div>
        </section>
    @endif

    @if($hasNetworkStats)
        <section class="py-16 md:py-14">
            <div class="text-center space-y-4 mb-10">
                <h2 class="text-3xl md:text-4xl font-semibold tracking-tight">{{ $networkSectionTitle }}</h2>
                <p class="text-lg text-muted leading-relaxed max-w-2xl mx-auto">
                    {{ $networkSectionSubtitle }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($networkStats as $stat)
                    <div class="max-w-80 mx-auto flex flex-col items-center gap-2 mb-2">
                        <i class="{{ $stat['icon'] }} text-primary text-xl"></i>
                        <h3 class="text-lg text-center font-semibold">{{ $stat['title'] }}</h3>
                        <p class="text-center text-sm text-muted leading-relaxed">
                            {{ $stat['description'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
