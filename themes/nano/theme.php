<?php

if (!function_exists('translate')) {
    /**
     * Translate a given key using the current locale.
     *
     * @param string $key
     * @param mixed $default
     * @return array
     */
    function translate($key, $default = null, $replace = [])
    {
        if (Lang::has($key)) {
            return Lang::get($key, $replace);
        }
        if (is_string($default)) {
            if (!empty($replace)) {
                foreach ($replace as $search => $value) {
                    $default = str_replace(':' . $search, $value, $default);
                    $default = str_replace('{' . $search . '}', $value, $default); // handle {name} too
                }
            }
            return $default;
        }
        return $default ?? $key;
    }
}

/*
|--------------------------------------------------------------------------
| Nano theme settings
|--------------------------------------------------------------------------
| These entries populate the admin "Theme editor". Every option below maps
| to a theme('<name>') call inside the nano views. Defaults match the values
| the views already used, so exposing them does not change the current look.
*/

$bool = fn ($name, $label, $default, $description = null) => array_filter([
    'name' => $name,
    'label' => $label,
    'type' => 'checkbox',
    'default' => $default,
    'database_type' => 'boolean',
    'description' => $description,
], fn ($v) => $v !== null);

$field = fn ($name, $label, $type, $default = '', $description = null, $options = null) => array_filter([
    'name' => $name,
    'label' => $label,
    'type' => $type,
    'default' => $default,
    'description' => $description,
    'options' => $options,
], fn ($v) => $v !== null);

return [
    'name' => 'nano',
    'author' => 'info@buzz.dev',
    'url' => 'https://buzz.dev',

    'settings' => [

        // ============================ General ============================
        $bool('disable-home-page', 'Startseite deaktivieren', false, 'Blendet die Landingpage aus und leitet Besucher direkt weiter.'),
        $field('home-page-redirect-url', 'Weiterleitungs-URL der Startseite', 'text', '/dashboard'),
        $bool('direct_checkout', 'Direkter Checkout', false, 'Überspringt die Produktübersicht und geht direkt zur Kasse.'),
        $bool('small_images', 'Kleine Bilder', false, 'Zeigt kleinere Bilder in der Produktübersicht.'),
        $bool('show_category_description', 'Kategoriebeschreibung anzeigen', true),
        $field('default_theme_mode', 'Standard-Farbmodus', 'select', 'dark', null, ['light' => 'Hell', 'dark' => 'Dunkel']),
        $field('force_theme_mode', 'Farbmodus erzwingen', 'select', 'none', 'Verhindert das Umschalten durch Besucher.', ['none' => 'Nicht erzwingen', 'light' => 'Immer hell', 'dark' => 'Immer dunkel']),
        $field('font_family', 'Schriftart', 'select', 'Geist Sans', null, [
            'Geist Sans' => 'Geist Sans', 'System Default' => 'System-Standard', 'Inter' => 'Inter',
            'Source Sans Pro' => 'Source Sans Pro', 'Roboto' => 'Roboto', 'Open Sans' => 'Open Sans',
            'Lato' => 'Lato', 'Poppins' => 'Poppins', 'Montserrat' => 'Montserrat', 'Nunito' => 'Nunito',
        ]),
        $field('dashboard-layout', 'Dashboard-Layout', 'select', 'default', null, ['default' => 'Standard', 'header' => 'Mit Header', 'wide' => 'Breit']),
        $field('container-max-width', 'Maximale Container-Breite', 'text', '1280px'),
        $bool('show-brand-text', 'Markennamen neben Logo anzeigen', true),

        // ============================ Colors (Light) ============================
        $field('primary', 'Primärfarbe / Markenfarbe (Hell)', 'color', 'hsl(231, 58%, 55%)'),
        $field('neutral', 'Rahmen & Akzente (Hell)', 'color', 'hsl(0, 0%, 91%)'),
        $field('base', 'Textfarbe (Hell)', 'color', 'hsl(0, 0%, 0%)'),
        $field('muted', 'Gedämpfte Textfarbe (Hell)', 'color', 'hsl(220, 28%, 25%)'),
        $field('inverted', 'Invertierte Textfarbe (Hell)', 'color', 'hsl(100, 100%, 100%)'),
        $field('background', 'Hintergrundfarbe (Hell)', 'color', 'hsl(210, 40%, 98%)'),
        $field('background-secondary', 'Sekundärer Hintergrund (Hell)', 'color', 'hsl(0, 0%, 100%)'),

        // ============================ Colors (Dark) ============================
        $field('dark-primary', 'Primärfarbe / Markenfarbe (Dunkel)', 'color', 'hsl(0, 0%, 15%)'),
        $field('dark-neutral', 'Rahmen & Akzente (Dunkel)', 'color', 'hsl(0, 0%, 15%)'),
        $field('dark-base', 'Textfarbe (Dunkel)', 'color', 'hsl(100, 100%, 100%)'),
        $field('dark-muted', 'Gedämpfte Textfarbe (Dunkel)', 'color', 'hsl(0, 0%, 76%)'),
        $field('dark-inverted', 'Invertierte Textfarbe (Dunkel)', 'color', 'hsl(220, 14%, 60%)'),
        $field('dark-background', 'Hintergrundfarbe (Dunkel)', 'color', 'hsl(240, 10%, 4%)'),
        $field('dark-background-secondary', 'Sekundärer Hintergrund (Dunkel)', 'color', 'hsl(240, 2%, 8%)'),

        // ============================ Shape & gradients ============================
        $field('card-border-radius', 'Kartenrundung', 'text', '12px'),
        $field('card-shadow', 'Kartenschatten', 'text', '0 1px 3px rgba(0,0,0,0.1)'),
        $field('button-border-radius', 'Button-Rundung', 'text', '12px'),
        $field('input-border-radius', 'Eingabefeld-Rundung', 'text', '12px'),
        $bool('hero_gradient_enabled', 'Hero-Farbverlauf aktivieren', true),
        $field('hero-gradient-1', 'Hero-Verlauf – Farbe 1', 'color', '#4A5ECF'),
        $field('hero-gradient-2', 'Hero-Verlauf – Farbe 2', 'color', '#7FA6F9'),
        $field('hero-gradient-3', 'Hero-Verlauf – Farbe 3', 'color', '#3451B2'),
        $field('hero-gradient-4', 'Hero-Verlauf – Farbe 4', 'color', '#7CD2FF'),
        $field('paper-gradient-from', 'Paper-Verlauf – von', 'color', '#4169E1'),
        $field('paper-gradient-to', 'Paper-Verlauf – bis', 'color', '#89CFF0'),

        // ============================ Hero / landing ============================
        $field('title1', 'Hero – Titelzeile 1', 'text', 'Lightning Fast'),
        $field('title2', 'Hero – Titelzeile 2', 'text', 'Web Hosting'),
        $field('hero_text', 'Hero – Beschreibung', 'textarea', 'Deploy your websites with confidence. Our premium hosting infrastructure delivers unmatched performance, security, and reliability.'),
        $field('button1text', 'Hero – Button 1 Text', 'text', 'Get Started'),
        $field('button1link', 'Hero – Button 1 Link', 'text', '#'),
        $field('button2text', 'Hero – Button 2 Text', 'text', 'View Plans'),
        $field('button2link', 'Hero – Button 2 Link', 'text', '#'),
        $field('hero_image', 'Hero – Bild-URL', 'text', ''),
        $field('home_background_image', 'Startseite – Hintergrundbild-URL', 'text', ''),
        $field('dashboard_background_image', 'Dashboard – Hintergrundbild-URL', 'text', ''),
        $field('background_min_height', 'Hintergrund – Mindesthöhe (px)', 'number', '400'),
        $field('background_max_height', 'Hintergrund – Maximalhöhe (px)', 'number', '800'),
        $field('background_overlay_type', 'Hintergrund – Overlay-Typ', 'text', 'gradient', 'Mögliche Werte: gradient, solid, none'),
        $field('background_overlay_opacity', 'Hintergrund – Overlay-Deckkraft (%)', 'number', 80),

        // ============================ Trustpilot ============================
        $field('trustpilot_href', 'Trustpilot – Link', 'text', ''),
        $field('trustpilot_image_light', 'Trustpilot – Bild (Hell)', 'text', ''),
        $field('trustpilot_image_dark', 'Trustpilot – Bild (Dunkel)', 'text', ''),

        // ============================ Features section ============================
        $bool('show_features_section', 'Features-Bereich anzeigen', true),
        $field('features_section_title', 'Features – Überschrift', 'text', 'Everything you need to deploy your app'),
        $field('feature_big_title', 'Feature (groß) – Titel', 'text', ''),
        $field('feature_big_description', 'Feature (groß) – Beschreibung', 'textarea', ''),
        $field('feature_big_icon', 'Feature (groß) – Icon', 'text', ''),
        $field('feature_1_title', 'Feature 1 – Titel', 'text', ''),
        $field('feature_1_description', 'Feature 1 – Beschreibung', 'textarea', ''),
        $field('feature_1_image', 'Feature 1 – Bild-URL', 'text', ''),
        $field('feature_2_title', 'Feature 2 – Titel', 'text', ''),
        $field('feature_2_description', 'Feature 2 – Beschreibung', 'textarea', ''),
        $field('feature_2_image', 'Feature 2 – Bild-URL', 'text', ''),
        $field('feature_3_title', 'Feature 3 – Titel', 'text', ''),
        $field('feature_3_description', 'Feature 3 – Beschreibung', 'textarea', ''),
        $field('feature_3_image', 'Feature 3 – Bild-URL', 'text', ''),
        $field('feature_4_title', 'Feature 4 – Titel', 'text', ''),
        $field('feature_4_description', 'Feature 4 – Beschreibung', 'textarea', ''),
        $field('feature_4_image', 'Feature 4 – Bild-URL', 'text', ''),

        // ============================ Reasons section ============================
        $bool('show_reasons_section', 'Gründe-Bereich anzeigen', true),
        $field('reasons_section_title', 'Gründe – Überschrift', 'text', 'We\'ve got you covered'),
        $field('reasons_section_subtitle', 'Gründe – Unterüberschrift', 'textarea', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis suscipit eaque, iste dolor cupiditate blanditiis.'),
        $field('reasons_image', 'Gründe – Bild-URL', 'text', '/nano/panel_3.png'),
        $field('reasons_image_alt', 'Gründe – Bild-Alternativtext', 'text', 'App screenshot'),

        // ============================ FAQ section ============================
        $bool('show_faq_section', 'FAQ-Bereich anzeigen', true),
        $field('faq_section_title', 'FAQ – Überschrift', 'text', 'Frequently Asked Questions'),
        $field('faq_section_subtitle', 'FAQ – Unterüberschrift', 'textarea', 'Got questions? We\'ve got answers. Find everything you need to know about our hosting services.'),

        // ============================ Contact section ============================
        $bool('show_contact_section', 'Kontakt-Bereich anzeigen', true),
        $field('contact_section_title', 'Kontakt – Überschrift', 'text', 'Need Something Else?'),
        $field('contact_section_subtitle', 'Kontakt – Unterüberschrift', 'textarea', 'Can\'t find what you\'re looking for? Our team is here to help you find the perfect hosting solution for your unique needs.'),
        $field('contact_button1_text', 'Kontakt – Button 1 Text', 'text', 'Request Quote'),
        $field('contact_button1_link', 'Kontakt – Button 1 Link', 'text', '#'),
        $field('contact_button2_text', 'Kontakt – Button 2 Text', 'text', 'Contact Sales'),
        $field('contact_button2_link', 'Kontakt – Button 2 Link', 'text', '#'),

        // ============================ Pricing plans ============================
        $bool('show-pricing-plans', 'Preistabellen-Bereich anzeigen', false),
        $field('pricing-plans-mode', 'Preistabellen – Modus', 'select', 'category', null, ['category' => 'Nach Kategorie', 'products' => 'Ausgewählte Produkte']),
        $field('pricing-plans-title', 'Preistabellen – Überschrift', 'text', ''),
        $field('pricing-plans-description', 'Preistabellen – Beschreibung', 'textarea', ''),
        $field('pricing-plans-category', 'Preistabellen – Kategorie-ID', 'text', ''),
        $field('pricing-plans-grid-columns', 'Preistabellen – Spalten', 'number', 3),
        $field('pricing-card-layout', 'Preiskarten-Layout', 'text', 'default'),
        $bool('show-pricing-plans-images', 'Preistabellen – Bilder anzeigen', true),
        $bool('show-pricing-card-category-background', 'Preiskarte – Kategoriehintergrund anzeigen', false),

        // ============================ Products page ============================
        $field('products_hero_text', 'Produkte – Hero-Text', 'textarea', 'Choose the perfect plan for your needs. All plans come with our industry-leading support and reliability.'),
        $field('products_hero_image', 'Produkte – Hero-Bild-URL', 'text', '/nano/hero.png'),
        $field('products_hero_cta_text', 'Produkte – CTA-Text', 'text', 'View Plans'),
        $field('products_category_header_type', 'Produkte – Kategorie-Header-Typ', 'text', 'gradient', 'Mögliche Werte: gradient, image, solid'),
        $field('products_category_header_color', 'Produkte – Kategorie-Header-Farbe', 'color', 'hsl(231, 58%, 55%)'),
        $field('products_category_image_min_height', 'Produkte – Kategoriebild Mindesthöhe (px)', 'number', 400),
        $field('products_category_image_max_height', 'Produkte – Kategoriebild Maximalhöhe (px)', 'number', 800),
        $field('products_category_image_overlay_opacity', 'Produkte – Kategoriebild Overlay-Deckkraft (%)', 'number', 80),
        $field('products_empty_title', 'Produkte – Text bei leerer Liste', 'text', 'Empty! No products found.'),
        $field('products_empty_icon', 'Produkte – Icon bei leerer Liste', 'text', 'ri-shopping-bag-3-line'),
        $field('products_stock_in_stock', 'Produkte – "Auf Lager"-Text', 'text', 'In Stock'),
        $field('products_stock_out_of_stock', 'Produkte – "Nicht auf Lager"-Text', 'text', 'Out of Stock'),

        // ============================ Navbar ============================
        $field('navbar_link_1_text', 'Navigationslink 1 – Text', 'text', ''),
        $field('navbar_link_1_url', 'Navigationslink 1 – URL', 'text', ''),
        $field('navbar_link_2_text', 'Navigationslink 2 – Text', 'text', ''),
        $field('navbar_link_2_url', 'Navigationslink 2 – URL', 'text', ''),
        $field('navbar_link_3_text', 'Navigationslink 3 – Text', 'text', ''),
        $field('navbar_link_3_url', 'Navigationslink 3 – URL', 'text', ''),
        $field('navbar_status_indicator_link', 'Status-Indikator – Link', 'text', '1', 'Link zur Statusseite (oder "1"/"0" zum Ein-/Ausblenden).'),
        $bool('show-discord-icon-navbar', 'Discord-Symbol in Navigation anzeigen', false),
        $field('social-link-discord', 'Discord-Einladungslink', 'text', 'https://discord.gg/buzz'),

        // ============================ Support widget ============================
        $bool('show-support-sidebar-widget', 'Support-Widget in Seitenleiste anzeigen', true),
        $bool('need-help-widget-enabled', '"Brauchen Sie Hilfe?"-Widget aktivieren', true),
        $field('need-help-widget-title', 'Hilfe-Widget – Titel', 'text', 'Need help?'),
        $field('need-help-widget-text', 'Hilfe-Widget – Text', 'textarea', 'Get in touch with our support team for assistance.'),
        $field('need-help-widget-button-text', 'Hilfe-Widget – Button-Text', 'text', 'Contact Support'),
        $field('need-help-widget-button-url', 'Hilfe-Widget – Button-URL', 'text', ''),

        // ============================ Announcement banner ============================
        $bool('announcement_banner_enabled', 'Ankündigungsbanner aktivieren', false),
        $field('announcement_banner', 'Ankündigungsbanner – Text', 'text', ''),
        $field('announcement_banner_link', 'Ankündigungsbanner – Link', 'text', ''),
        $bool('announcement_banner_new_tab', 'Ankündigungsbanner – in neuem Tab öffnen', false),

        // ============================ Global broadcast ============================
        $bool('global_broadcast_enabled', 'Globale Durchsage aktivieren', false),
        $field('global_broadcast_style', 'Durchsage – Stil', 'text', 'style1'),
        $field('global_broadcast_icon', 'Durchsage – Icon', 'text', 'fa-bullhorn'),
        $field('global_broadcast_title', 'Durchsage – Titel', 'text', ''),
        $field('global_broadcast_content', 'Durchsage – Inhalt', 'textarea', ''),
        $field('global_broadcast_button_text', 'Durchsage – Button-Text', 'text', 'Learn More'),
        $field('global_broadcast_button_url', 'Durchsage – Button-URL', 'text', ''),

        // ============================ Footer ============================
        $bool('footer-show-logo', 'Logo im Footer anzeigen', true),
        $field('footer_description', 'Footer – Beschreibung', 'textarea', 'High-performance cloud infrastructure for developers. Built for speed, security, and scalability.'),
        $field('footer_copyright_text', 'Footer – Copyright-Text', 'text', ''),
        $field('footer-bg-light', 'Footer – Hintergrund (Hell)', 'color', 'hsl(222, 47%, 11%)'),
        $field('footer-bg-dark', 'Footer – Hintergrund (Dunkel)', 'color', 'hsl(222, 47%, 11%)'),
        $field('footer-text-light', 'Footer – Textfarbe (Hell)', 'color', 'hsl(215, 20%, 80%)'),
        $field('footer-text-dark', 'Footer – Textfarbe (Dunkel)', 'color', 'hsl(215, 20%, 80%)'),

        // ============================ SEO / Meta ============================
        $field('meta_site_name', 'SEO – Seitenname', 'text', ''),
        $field('meta_description', 'SEO – Meta-Beschreibung', 'textarea', ''),
        $field('meta_image', 'SEO – Vorschaubild-URL (OG)', 'text', ''),
        $field('meta_robots', 'SEO – Robots', 'select', 'index,follow', null, [
            'index,follow' => 'index, follow', 'noindex,follow' => 'noindex, follow',
            'index,nofollow' => 'index, nofollow', 'noindex,nofollow' => 'noindex, nofollow',
        ]),

        // ============================ Analytics ============================
        $field('google_analytics_id', 'Google Analytics – ID', 'text', ''),
        $field('facebook_pixel_id', 'Facebook Pixel – ID', 'text', ''),
        $field('microsoft_clarity_id', 'Microsoft Clarity – ID', 'text', ''),

        // ============================ Custom code ============================
        $field('custom_head_html', 'Eigenes HTML (im <head>)', 'textarea', ''),
        $field('custom_css', 'Eigenes CSS', 'textarea', ''),
        $field('custom_js', 'Eigenes JavaScript', 'textarea', ''),

        // ============================ Integrations ============================
        $bool('pterodactyl-enabled', 'Pterodactyl-Integration aktiviert', false),
    ],
];
