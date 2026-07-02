@php
    use App\Models\Category;
    use App\Models\Product;

    if (theme('disable-home-page', false)) {
        $redirectUrl = trim((string) theme('home-page-redirect-url', '/dashboard'));
        if ($redirectUrl === '') {
            $redirectUrl = '/dashboard';
        }
        if (!str_starts_with($redirectUrl, '/') && !str_starts_with($redirectUrl, 'http://') && !str_starts_with($redirectUrl, 'https://')) {
            $redirectUrl = '/' . ltrim($redirectUrl, '/');
        }
        echo '<script>window.location.href = ' . json_encode($redirectUrl) . ';</script>';
        exit;
    }

    $pricingProducts = collect();
    $pricingCategories = collect();
    $pricingCategory = null;
    $pricingMode = theme('pricing-plans-mode', 'category');
    $pricingSectionTitle = theme('pricing-plans-title', '');
    $pricingSectionDescription = theme('pricing-plans-description', '');
    
    if (theme('show-pricing-plans', false)) {
        if ($pricingMode === 'categories') {
            $selectedCategoryIds = theme('pricing-plans-categories', []);
            if (!empty($selectedCategoryIds) && is_array($selectedCategoryIds)) {
                $pricingCategories = Category::whereIn('id', $selectedCategoryIds)
                    ->with(['products' => function ($q) {
                        $q->where('hidden', false);
                    }])
                    ->get()
                    ->sortBy(function($cat) use ($selectedCategoryIds) {
                        return array_search($cat->id, $selectedCategoryIds);
                    });
            }
        } elseif ($pricingMode === 'products') {
            $selectedProductIds = theme('pricing-plans-products', []);
            if (!empty($selectedProductIds) && is_array($selectedProductIds)) {
                $pricingProducts = Product::whereIn('id', $selectedProductIds)
                    ->where('hidden', false)
                    ->get()
                    ->sortBy(function($product) use ($selectedProductIds) {
                        return array_search($product->id, $selectedProductIds);
                    });
            }
        } else {
            $pricingCategorySlug = theme('pricing-plans-category');
            if ($pricingCategorySlug) {
                $pricingCategory = Category::where('slug', $pricingCategorySlug)->first();
                if ($pricingCategory) {
                    $pricingProducts = $pricingCategory->products()->where('hidden', false)->orderBy('sort')->get();
                }
            }
        }
    }

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

    $reasons = theme('reasons', $defaultReasons);
    if (!is_array($reasons)) $reasons = $defaultReasons;
    $reasons = array_map(function ($item) {
        return [
            'icon' => $item['icon'] ?? '',
            'title' => $item['title'] ?? '',
            'description' => $item['description'] ?? '',
            'stat' => $item['stat'] ?? '',
            'statLabel' => $item['stat_label'] ?? '',
        ];
    }, $reasons);

    $faqs = theme('faqs', $defaultFaqs);
    if (!is_array($faqs)) $faqs = $defaultFaqs;
    $faqs = array_map(function ($item) {
        return [
            'question' => $item['question'] ?? '',
            'answer' => $item['answer'] ?? '',
        ];
    }, $faqs);

    $reasons = array_values(array_filter($reasons, function ($item) {
        return trim(($item['title'] ?? '') . ($item['description'] ?? '') . ($item['icon'] ?? '')) !== '';
    }));
    $faqs = array_values(array_filter($faqs, function ($item) {
        return trim(($item['question'] ?? '') . ($item['answer'] ?? '')) !== '';
    }));

    $hasFeatureHighlight = trim(theme('feature_big_title', '') . theme('feature_big_description', '') . theme('feature_big_icon', '')) !== '';

    $hasFeatures = trim(
        theme('feature_1_title', '') . theme('feature_1_description', '') .
        theme('feature_2_title', '') . theme('feature_2_description', '') .
        theme('feature_3_title', '') . theme('feature_3_description', '') .
        theme('feature_4_title', '') . theme('feature_4_description', '')
    ) !== '';

    $hasReasons = count($reasons) > 0;
    $hasFaqs = count($faqs) > 0;

    $productLabelsRaw = theme('product_labels', []);
    $productLabelsMap = [];
    if (is_array($productLabelsRaw)) {
        foreach ($productLabelsRaw as $lbl) {
            $pid = (string) ($lbl['product_id'] ?? '');
            if ($pid !== '') {
                $productLabelsMap[$pid] = [
                    'text'  => $lbl['label_text'] ?? '',
                    'bg'    => $lbl['label_bg'] ?? '#7C3AED',
                    'color' => $lbl['label_color'] ?? '#ffffff',
                ];
            }
        }
    }
@endphp

@php
    $heroGradientEnabled = theme('hero_gradient_enabled', true);
    if (theme('home_background_image')) {
        $heroGradientEnabled = false;
    }
@endphp

<div class="w-full">
    <div class="text-base">
        <section class="relative overflow-hidden">
            @if($heroGradientEnabled)
                <div class="floating-hero-gradient"></div>
            @endif
            <div class="container mx-auto relative z-10 pt-36 pb-14 md:pt-60 md:pb-36" style="max-width: var(--container-max-width)">
                <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                    <div class="mx-4 2xl:mx-0 space-y-8">
                        <div class="space-y-5">
                            @php
                                $trustpilotImageLight = theme('trustpilot_image_light', '');
                                if (is_array($trustpilotImageLight)) {
                                    $trustpilotImageLight = $trustpilotImageLight[0] ?? '';
                                }
                                $trustpilotImageLightUrl = '';
                                if ($trustpilotImageLight) {
                                    if (str_starts_with($trustpilotImageLight, 'http') || str_starts_with($trustpilotImageLight, '/')) {
                                        $trustpilotImageLightUrl = $trustpilotImageLight;
                                    } else {
                                        $trustpilotImageLightUrl = asset('storage/' . $trustpilotImageLight);
                                    }
                                }

                                $trustpilotImageDark = theme('trustpilot_image_dark', '');
                                if (is_array($trustpilotImageDark)) {
                                    $trustpilotImageDark = $trustpilotImageDark[0] ?? '';
                                }
                                $trustpilotImageDarkUrl = '';
                                if ($trustpilotImageDark) {
                                    if (str_starts_with($trustpilotImageDark, 'http') || str_starts_with($trustpilotImageDark, '/')) {
                                        $trustpilotImageDarkUrl = $trustpilotImageDark;
                                    } else {
                                        $trustpilotImageDarkUrl = asset('storage/' . $trustpilotImageDark);
                                    }
                                }

                                $trustpilotHref = theme('trustpilot_href', '');
                                $hasTrustpilotImage = $trustpilotImageLightUrl || $trustpilotImageDarkUrl;
                            @endphp
                            @if($hasTrustpilotImage)
                                <div class="mb-4">
                                    @if($trustpilotHref)
                                        <a href="{{ $trustpilotHref }}" target="_blank" rel="noopener noreferrer" class="inline-block">
                                            @if($trustpilotImageLightUrl)
                                                <img src="{{ $trustpilotImageLightUrl }}" alt="Trustpilot" class="h-auto max-w-[240px] md:max-w-[280px] dark:hidden" loading="lazy" />
                                            @endif
                                            @if($trustpilotImageDarkUrl)
                                                <img src="{{ $trustpilotImageDarkUrl }}" alt="Trustpilot" class="h-auto max-w-[240px] md:max-w-[280px] hidden dark:inline-block" loading="lazy" />
                                            @endif
                                        </a>
                                    @else
                                        @if($trustpilotImageLightUrl)
                                            <img src="{{ $trustpilotImageLightUrl }}" alt="Trustpilot" class="h-auto max-w-[240px] md:max-w-[280px] dark:hidden" loading="lazy" />
                                        @endif
                                        @if($trustpilotImageDarkUrl)
                                            <img src="{{ $trustpilotImageDarkUrl }}" alt="Trustpilot" class="h-auto max-w-[240px] md:max-w-[280px] hidden dark:inline-block" loading="lazy" />
                                        @endif
                                    @endif
                                </div>
                            @endif
                            <h1 class="text-4xl md:text-6xl font-semibold tracking-tight leading-[1.05]">
                                <span class="{{ $heroGradientEnabled ? 'text-white' : 'text-base' }} block font-[700]">{{ theme('title1', 'Minecraft Server mieten') }}</span>
                                <span class="{{ $heroGradientEnabled ? 'text-white' : 'text-base' }} block font-[700]">{{ theme('title2', 'aus Deutschland') }}</span>
                            </h1>

                            <p class="text-lg md:text-xl {{ $heroGradientEnabled ? 'text-white' : 'text-base/70' }} leading-relaxed max-w-xl">
                                {{ theme('hero_text', 'Starte deinen eigenen Minecraft-Server in unter einer Minute – auf leistungsstarker NVMe-Hardware mit DDoS-Schutz, eigenem Panel und Serverstandort in Deutschland. Sofort einsatzbereit und jederzeit erweiterbar.') }}
                            </p>
                        </div>

                        <div class="flex flex-row gap-3 sm:gap-5">
                            <a href="{{ theme('button1link', '#') }}" class="text-white bg-primary hover:bg-primary/90 px-4 py-2 sm:px-6 sm:py-3 rounded-[var(--button-radius)] font-semibold transition flex gap-2 sm:gap-3 items-center text-sm">
                                <i class="fa fa-rocket mr-1"></i>
                                {{ theme('button1text', 'Server mieten') }}
                            </a>
                            <a href="{{ theme('button2link', '#') }}" class="{{ $heroGradientEnabled ? 'bg-white text-black' : 'bg-background-secondary text-base border border-neutral' }} px-4 py-2 sm:px-6 sm:py-3 rounded-[var(--button-radius)] font-semibold transition flex gap-2 sm:gap-3 items-center text-sm">
                                <i class="fa fa-list-alt mr-1"></i>
                                {{ theme('button2text', 'Preise ansehen') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
                    @php
                        $defaultHeroImage = 'https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/hero_panel.png';
                        $heroImage = theme('hero_image', $defaultHeroImage);
                        if (is_array($heroImage)) {
                            $heroImage = $heroImage[0] ?? null;
                        }
                        if (empty($heroImage)) {
                            $heroImageUrl = $defaultHeroImage;
                        } else {
                            if (str_starts_with($heroImage, 'http') || str_starts_with($heroImage, '/')) {
                                $heroImageUrl = $heroImage;
                            } else {
                                $heroImageUrl = asset('storage/' . $heroImage);
                            }
                        }
                    @endphp
                    <div class="hidden display-none lg:block lg:absolute lg:top-20 lg:-right-85 lg:top-25 xl:top-20 xl:-right-70 2xl:-right-50 h-auto lg:w-[900px] xl:w-[1000px]">
                        <img
                            src="{{ $heroImageUrl }}"
                            alt="Cloud infrastructure illustration"
                            class="w-full h-auto object-contain rounded-xl"
                            loading="lazy"
                        />
                    </div>
        </section>
        

        @if(theme('show_features_section', true) && ($hasFeatures || $hasFeatureHighlight))
            <section class="py-16 md:py-24 md:pt-12">
                <div class="container mx-auto px-6 lg:max-w-7xl lg:px-8">
                    <p class="mx-auto mt-2 max-w-lg text-center text-4xl font-semibold tracking-tight text-balance sm:text-5xl">
                        {{ theme('features_section_title', 'Alles, was dein Minecraft-Server braucht') }}
                    </p>
                    <div class="mt-10 grid gap-4 sm:mt-16 lg:grid-cols-3 lg:grid-rows-2">
                        <div class="relative lg:row-span-2">
                            <div class="absolute inset-px rounded-[var(--card-radius)] bg-background-secondary lg:rounded-l-[calc(var(--card-radius)+8px)]"></div>
                            <div class="relative flex h-full flex-col overflow-hidden rounded-[var(--card-radius)] lg:rounded-l-[calc(var(--card-radius)+9px)]">
                                <div class="px-8 pt-8 pb-3 sm:px-10 sm:pt-10 sm:pb-0">
                                    <p class="mt-2 text-lg font-medium tracking-tight max-lg:text-center">
                                        {{ theme('feature_1_title', 'Modern Panel') }}
                                    </p>
                                    <p class="mt-2 max-w-lg text-sm text-muted max-lg:text-center">
                                        {{ theme('feature_1_description', 'Our modern panel is designed to be easy to use and navigate.') }}
                                    </p>
                                </div>
                                <div class="relative min-h-[30rem] w-full grow max-lg:mx-auto max-lg:max-w-sm">
                                    <div class="mb-6 md:mb-0 absolute inset-x-10 top-10 bottom-0 overflow-hidden rounded-[var(--card-radius)] lg:rounded-b-none shadow-[var(--card-shadow)]">
                                        <img
                                            alt="{{ theme('feature_1_title', 'Mobile friendly') }}"
                                            src="{{ theme('feature_1_image', 'https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/panel_sidebar.png') }}"
                                            class="rounded-[var(--card-radius)] lg:rounded-b-none size-full object-cover object-top"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="pointer-events-none absolute inset-px rounded-[var(--card-radius)] shadow-[var(--card-shadow)] ring-1 ring-black/5 lg:rounded-l-[calc(var(--card-radius)+8px)]"></div>
                        </div>
                        <div class="relative max-lg:row-start-1">
                            <div class="absolute inset-px rounded-[var(--card-radius)] bg-background-secondary max-lg:rounded-t-[calc(var(--card-radius)+8px)]"></div>
                            <div class="relative flex h-full flex-col overflow-hidden rounded-[var(--card-radius)] max-lg:rounded-t-[calc(var(--card-radius)+9px)]">
                                <div class="px-8 pt-8 sm:px-10 sm:pt-10">
                                    <p class="mt-2 text-lg font-medium tracking-tight max-lg:text-center">
                                        {{ theme('feature_2_title', 'Performance') }}
                                    </p>
                                    <p class="mt-2 max-w-lg text-sm text-muted max-lg:text-center">
                                        {{ theme('feature_2_description', 'Our infrastructure is built for performance and reliability.') }}
                                    </p>
                                </div>
                                <div class="flex flex-1 items-center justify-center px-8 max-lg:pt-10 max-lg:pb-12 sm:px-10 lg:pb-2">
                                    <img
                                        alt="{{ theme('feature_2_title', 'Performance') }}"
                                        src="{{ theme('feature_2_image', 'https://tailwindcss.com/plus-assets/img/component-images/bento-03-performance.png') }}"
                                        class="w-full max-lg:max-w-xs"
                                    />
                                </div>
                            </div>
                            <div class="pointer-events-none absolute inset-px rounded-[var(--card-radius)] shadow-[var(--card-shadow)] ring-1 ring-black/5 max-lg:rounded-t-[calc(var(--card-radius)+8px)]"></div>
                        </div>
                        <div class="relative max-lg:row-start-3 lg:col-start-2 lg:row-start-2">
                            <div class="absolute inset-px rounded-[var(--card-radius)] bg-background-secondary"></div>
                            <div class="relative flex h-full flex-col overflow-hidden rounded-[var(--card-radius)]">
                                <div class="px-8 pt-8 sm:px-10 sm:pt-10">
                                    <p class="mt-2 text-lg font-medium tracking-tight max-lg:text-center">
                                        {{ theme('feature_3_title', 'Security') }}
                                    </p>
                                    <p class="mt-2 max-w-lg text-sm text-muted max-lg:text-center">
                                        {{ theme('feature_3_description', 'Our security features are designed to protect your data and information.') }}
                                    </p>
                                </div>
                                <div class="flex flex-1 items-center max-lg:py-6 lg:pb-2">
                                    <img
                                        alt="{{ theme('feature_3_title', 'Security') }}"
                                        src="{{ theme('feature_3_image', 'https://tailwindcss.com/plus-assets/img/component-images/bento-03-security.png') }}"
                                        class="h-[min(152px,40cqw)] object-cover"
                                    />
                                </div>
                            </div>
                            <div class="pointer-events-none absolute inset-px rounded-[var(--card-radius)] shadow-[var(--card-shadow)] ring-1 ring-black/5"></div>
                        </div>
                        <div class="relative lg:row-span-2">
                            <div class="absolute inset-px rounded-[var(--card-radius)] bg-background-secondary max-lg:rounded-b-[calc(var(--card-radius)+8px)] lg:rounded-r-[calc(var(--card-radius)+8px)]"></div>
                            <div class="relative flex h-full flex-col overflow-hidden rounded-[var(--card-radius)] max-lg:rounded-b-[calc(var(--card-radius)+9px)] lg:rounded-r-[calc(var(--card-radius)+9px)]">
                                <div class="px-8 pt-8 pb-3 sm:px-10 sm:pt-10 sm:pb-0">
                                    <p class="mt-2 text-lg font-medium tracking-tight max-lg:text-center">
                                        {{ theme('feature_4_title', 'Priority Support') }}
                                    </p>
                                    <p class="mt-2 max-w-lg text-sm text-muted max-lg:text-center">
                                        {{ theme('feature_4_description', 'Our support team is available 24/7 to assist you with any questions or issues you may have.') }}
                                    </p>
                                </div>
                                <div class="relative min-h-[30rem] w-full grow">
                                    <div class="absolute top-10 right-0 bottom-0 left-10 overflow-hidden rounded-tl-[var(--card-radius)] bg-background-secondary shadow-[var(--card-shadow)]">
                                            <img
                                                alt="{{ theme('feature_4_title', 'Priority Support') }}"
                                                src="{{ theme('feature_4_image', 'https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/tickets_sidebar.png') }}"
                                                class="w-full"
                                            />
                                    </div>
                                </div>
                            </div>
                            <div class="pointer-events-none absolute inset-px rounded-[var(--card-radius)] shadow-[var(--card-shadow)] ring-1 ring-black/5 max-lg:rounded-b-[calc(var(--card-radius)+8px)] lg:rounded-r-[calc(var(--card-radius)+8px)]"></div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if(theme('show-pricing-plans', false) && ($pricingMode === 'categories' ? count($pricingCategories) > 0 : (isset($pricingCategory) || count($pricingProducts) > 0)))
            @php
                $sectionTitle = !empty($pricingSectionTitle) ? $pricingSectionTitle : ($pricingCategory ? $pricingCategory->name : 'Our Products');
                $sectionDescription = !empty($pricingSectionDescription) ? $pricingSectionDescription : ($pricingCategory ? $pricingCategory->description : '');
            @endphp
            <section id="pricing" class="py-16 md:py-24">
                <div class="container mx-auto px-6" style="max-width: var(--container-max-width)">
                    <div class="text-center space-y-4 mb-10">
                        <h2 class="text-3xl md:text-4xl font-semibold tracking-tight">{{ $sectionTitle }}</h2>
                        @if(theme('show_category_description', true) && $sectionDescription)
                            <article class="prose dark:prose-invert text-sm text-muted max-w-2xl mx-auto">
                                {!! $sectionDescription !!}
                            </article>
                        @endif
                    </div>

                    @if($pricingMode === 'categories')
                        <div class="grid grid-cols-1 md:grid-cols-{{ theme('pricing-plans-grid-columns', 3) }} gap-6">
                            @foreach($pricingCategories as $cat)
                                @php
                                    $catProducts = $cat->products->sortBy('sort');
                                    $productCount = $catProducts->count();
                                    $startingPrice = null;
                                    foreach ($catProducts as $p) {
                                        foreach ($p->availablePlans() as $plan) {
                                            $planPrice = $plan->price();
                                            if (!$planPrice || !$planPrice->available) {
                                                continue;
                                            }

                                            $totalPrice = (float) ($planPrice->price ?? 0);
                                            $totalSetupFee = (float) ($planPrice->setup_fee ?? 0);
                                            $planValidForDisplay = true;

                                            foreach ($p->configOptions as $option) {
                                                if (in_array($option->type, ['text', 'number', 'checkbox'], true)) {
                                                    continue;
                                                }

                                                $optionLowestTotal = null;
                                                $optionLowestPrice = 0.0;
                                                $optionLowestSetupFee = 0.0;

                                                foreach ($option->children as $childOption) {
                                                    $childPrice = $childOption->price(
                                                        billing_period: $plan->billing_period,
                                                        billing_unit: $plan->billing_unit
                                                    );

                                                    if (!$childPrice || !$childPrice->available) {
                                                        continue;
                                                    }

                                                    $childBasePrice = (float) ($childPrice->price ?? 0);
                                                    $childSetupFee = (float) ($childPrice->setup_fee ?? 0);
                                                    $childTotal = $childBasePrice + $childSetupFee;

                                                    if ($optionLowestTotal === null || $childTotal < $optionLowestTotal) {
                                                        $optionLowestTotal = $childTotal;
                                                        $optionLowestPrice = $childBasePrice;
                                                        $optionLowestSetupFee = $childSetupFee;
                                                    }
                                                }

                                                if ($optionLowestTotal === null) {
                                                    $planValidForDisplay = false;
                                                    break;
                                                }

                                                $totalPrice += $optionLowestPrice;
                                                $totalSetupFee += $optionLowestSetupFee;
                                            }

                                            if (!$planValidForDisplay) {
                                                continue;
                                            }

                                            $displayPrice = new \App\Classes\Price([
                                                'price' => $totalPrice,
                                                'setup_fee' => $totalSetupFee,
                                                'currency' => $planPrice->currency,
                                            ], false, true);

                                            if (!$displayPrice->available) {
                                                continue;
                                            }

                                            if ($startingPrice === null || $displayPrice->total < $startingPrice->total) {
                                                $startingPrice = $displayPrice;
                                            }
                                        }
                                    }

                                    $catImageUrl = null;
                                    if ($cat->image) {
                                        if (str_starts_with($cat->image, 'http') || str_starts_with($cat->image, '/')) {
                                            $catImageUrl = $cat->image;
                                        } else {
                                            $catImageUrl = asset('storage/' . $cat->image);
                                        }
                                    }

                                    $catLink = route('category.show', ['category' => $cat->slug]);
                                @endphp
                                <div class="relative h-full">
                                    <div class="h-full bg-background-secondary rounded-[var(--card-radius)] border border-neutral shadow-[var(--card-shadow)] flex flex-col overflow-hidden">
                                        @if($catImageUrl && theme('show-pricing-plans-images', true))
                                            <div class="relative h-40 overflow-hidden">
                                                <img src="{{ $catImageUrl }}" alt="{{ $cat->name }}" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-gradient-to-t from-background-secondary to-transparent"></div>
                                            </div>
                                        @endif
                                        <div class="p-6 flex flex-col flex-grow">
                                            <h3 class="text-2xl font-semibold">{{ $cat->name }}</h3>
                                            @if($cat->description && theme('show_category_description', true))
                                                <div class="mt-2 text-sm text-muted prose prose-sm dark:prose-invert max-w-none line-clamp-3">
                                                    {!! $cat->description !!}
                                                </div>
                                            @endif
                                            <div class="mt-4 flex items-baseline gap-2">
                                                @if($startingPrice)
                                                    @if(!$startingPrice->is_free)
                                                        <span class="text-sm text-muted">{{ __('general.from') }}</span>
                                                    @endif
                                                    <span class="text-3xl font-bold text-primary">{{ $startingPrice->formatted->price }}</span>
                                                @endif
                                            </div>
                                            <div class="mt-2 text-sm text-muted">
                                                {{ $productCount }} {{ $productCount === 1 ? 'plan' : 'plans' }} available
                                            </div>
                                        </div>
                                        <a href="{{ $catLink }}" wire:navigate class="block mt-auto">
                                            <x-button.primary class="w-full h-12 rounded-t-none" size="md">
                                                <x-ri-arrow-right-line class="size-5 mr-1.5" />
                                                Zu den Tarifen
                                            </x-button.primary>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-{{ theme('pricing-plans-grid-columns', 3) }} gap-6">
                            @if(count($pricingProducts) === 0)
                                <div class="md:col-span-{{ theme('pricing-plans-grid-columns', 3) }} text-center text-base/60 py-12">
                                    {{ __('product.no_plans_found') }}
                                </div>
                            @endif

                            @foreach($pricingProducts as $index => $product)
                                @php
                                    $productCategory = $product->category;
                                    $pricingLink = ($product->stock > 0 || !$product->stock) && $product->price()->available && theme('direct_checkout', false)
                                        ? route('products.checkout', ['category' => $productCategory, 'product' => $product->slug])
                                        : route('products.show', ['category' => $productCategory, 'product' => $product->slug]);
                                    
                                    $isAvailable = ($product->stock > 0 || !$product->stock) && $product->price()->available;
                                    
                                    $billingCycle = '';
                                    $plan = $product->availablePlans()->first();
                                    if ($plan && $plan->type === 'recurring') {
                                        $period = $plan->billing_period;
                                        $unit = $plan->billing_unit;
                                        if ($period == 1) {
                                            $billingCycle = match($unit) {
                                                'hour' => 'pro Stunde',
                                                'day' => 'pro Tag',
                                                'week' => 'pro Woche',
                                                'month' => 'pro Monat',
                                                'year' => 'pro Jahr',
                                                default => 'pro Abrechnungszeitraum'
                                            };
                                        } else {
                                            $unitPlural = match($unit) {
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
                                    
                                    $buttonText = $isAvailable && theme('direct_checkout', false) ? __('product.add_to_cart') : 'Configure';
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
                                    :showImage="theme('show-pricing-plans-images', true)"
                                    :buttonText="$buttonText"
                                    :categoryImage="$productCategory->image ?? null"
                                    :label="$productLabel"
                                >
                                    @if ($isAvailable && theme('direct_checkout', false))
                                        <x-ri-shopping-bag-4-fill class="size-5 mr-1.5" />
                                    @else
                                        <x-ri-settings-4-line class="size-5 mr-1.5" />
                                    @endif
                                </x-pricing-card>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        @endif

        @if(theme('show_reasons_section', true) && $hasReasons)
            <section class="py-16 md:py-24">
                <div class="container mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="mx-auto max-w-2xl sm:text-center">
                        <p class="mt-2 text-4xl font-semibold tracking-tight text-pretty sm:text-5xl sm:text-balance">
                            {{ theme('reasons_section_title', 'Warum Hoelni-Hosting?') }}
                        </p>
                        <p class="mt-6 text-lg text-muted">
                            {{ theme('reasons_section_subtitle', 'Leistungsstarkes Minecraft-Hosting aus Deutschland – schnell, zuverlässig und ohne versteckte Kosten.') }}
                        </p>
                    </div>
                </div>
                <div class="relative overflow-hidden pt-16">
                    <div class="container mx-auto max-w-7xl px-6 lg:px-8">
                        <img
                            alt="{{ theme('reasons_image_alt', 'App screenshot') }}"
                            src="{{ theme('reasons_image', '/nano/panel_3.png') }}"
                            class="mb-[-8%] w-full h-auto object-fit rounded-[var(--card-radius)] shadow-[var(--card-shadow)] ring-1 ring-black/10"
                        />
                        <div aria-hidden="true" class="relative">
                            <div class="absolute -inset-x-20 bottom-0 bg-gradient-to-t from-background pt-[7%]"></div>
                        </div>
                    </div>
                </div>
                <div class="container mx-auto mt-16 max-w-5xl px-6 sm:mt-20 md:mt-24 lg:px-8">
                    <dl class="mx-auto grid max-w-2xl grid-cols-1 gap-x-6 gap-y-10 text-base text-muted sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-3 lg:gap-x-8 lg:gap-y-16">
                        @foreach($reasons as $reason)
                            <div class="relative pl-9">
                                <dt class="inline font-semibold">
                                    <i class="{{ $reason['icon'] }} absolute top-1 left-1 text-primary" aria-hidden="true"></i>
                                    {{ $reason['title'] }}
                                </dt>
                                <dd class="inline">{{ $reason['description'] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </section>
        @endif

        @if(theme('show_faq_section', true) && $hasFaqs)
            <section class="py-16 md:py-24">
                <div class="container mx-auto" style="max-width: var(--container-max-width) px-6">
                    <div class="text-center space-y-4 mb-10">
                        <h2 class="text-3xl md:text-4xl font-semibold tracking-tight">{{ theme('faq_section_title', 'Häufige Fragen zu Minecraft-Servern') }}</h2>
                        <p class="text-lg text-muted leading-relaxed max-w-2xl mx-auto">{{ theme('faq_section_subtitle', 'Alles Wichtige rund um dein Minecraft-Hosting bei Hoelni-Hosting.') }}</p>
                    </div>

                    <div class="w-full max-w-3xl mx-auto space-y-3">
                        @foreach($faqs as $index => $faq)
                            <details class="group rounded-[var(--card-radius)] bg-background-secondary px-6 py-5" @if($index === 0) open @endif>
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
                </div>
            </section>
        @endif

        @if(theme('show_contact_section', true))
            <section class="py-16 md:py-24">
                <div class="container mx-auto" style="max-width: var(--container-max-width) px-6">
                    <div class="text-center space-y-4 mb-10">
                        <h2 class="text-3xl md:text-4xl font-semibold tracking-tight">{{ theme('contact_section_title', 'Need Something Else?') }}</h2>
                        <p class="text-lg text-muted leading-relaxed max-w-2xl mx-auto">{{ theme('contact_section_subtitle', "Can't find what you're looking for? Our team is here to help you find the perfect hosting solution for your unique needs.") }}</p>
                    </div>

                    <div class="rounded-[var(--card-radius)] bg-background-secondary p-8 md:p-12 max-w-3xl mx-auto">
                        <div class="flex flex-col gap-3 items-center">
                            <a href="{{ theme('contact_button1_link', '#') }}" class="text-white bg-primary hover:bg-primary/90 px-6 py-3 rounded-[var(--button-radius)] font-semibold transition w-full lg:w-auto">
                                {{ theme('contact_button1_text', 'Request Quote') }}
                            </a>
                            <a href="{{ theme('contact_button2_link', '#') }}" class="px-6 py-3 rounded-[var(--button-radius)] font-semibold border border-neutral bg-background hover:bg-background-secondary transition w-full lg:w-auto">
                                {{ theme('contact_button2_text', 'Contact Sales') }}
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {!! hook('home.hero') !!}
    </div>
</div>