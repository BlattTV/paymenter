<?php

namespace Paymenter\Extensions\Others\ThemeEditor\Admin\Pages;

use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Paymenter\Extensions\Others\ThemeEditor\Models\ThemeEditorSetting;
use BackedEnum;
use Illuminate\Support\Facades\Schema as DbSchema;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

class ThemeEditor extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Theme Editor';
    protected static ?string $slug = 'theme-editor';
    protected static string|\UnitEnum|null $navigationGroup = 'Extensions';
    protected static string|BackedEnum|null $navigationIcon = \Filament\Support\Icons\Heroicon::OutlinedPaintBrush;
    protected static ?string $navigationLabel = 'Theme Editor';
    protected string $view = 'themeeditor::admin.theme-editor';

    public ?array $data = [];
    
    #[Url(as: 'tab')]
    public string $activeTab = 'general';
    
    protected ?array $allSettings = null;

    protected array $fileUploadFields = [
        'dashboard_banner_image',
        'home_background_image',
        'dashboard_background_image',
        'meta_image',
        'hero_image',
        'trustpilot_image_light',
        'trustpilot_image_dark',
    ];

    protected function prepareDataForForm(array $data): array
    {
        foreach ($this->fileUploadFields as $field) {
            if (isset($data[$field]) && is_string($data[$field]) && $data[$field] !== '') {
                $data[$field] = [$data[$field]];
            }
        }
        return $data;
    }

    public function mount(): void
    {
        $this->allSettings = $this->getFormData();
        $this->form->fill($this->prepareDataForForm($this->allSettings));
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function setActiveTab(string $tab): void
    {
        $currentFormData = $this->form->getState();
        if ($this->allSettings === null) {
            $this->allSettings = $this->getFormData();
        }
        $this->allSettings = array_merge($this->allSettings, $currentFormData);
        
        $this->activeTab = $tab;
        $this->form->fill($this->prepareDataForForm($this->allSettings));
    }

    protected function getGeneralSchema(): array
    {
        return [
            Toggle::make('direct_checkout')
                ->label('Direct Checkout')
                ->helperText('Don\'t show the product overview page, go directly to the checkout page'),
            Toggle::make('small_images')
                ->label('Small Images')
                ->helperText('Show small images in the product overview page'),
            Toggle::make('show_category_description')
                ->label('Show Category Description')
                ->helperText('Show the category description in the product overview page/homepage'),
            Toggle::make('show-brand-text')
                ->label('Show Brand Text')
                ->helperText('Show or hide the brand text next to the logo in the navigation'),
            Toggle::make('show-discord-icon-navbar')
                ->label('Show Discord Icon in Navbar')
                ->helperText('Show the Discord support icon in the navigation bar'),
            Toggle::make('show-support-sidebar-widget')
                ->label('Show Support Sidebar Widget')
                ->helperText('Show the support sidebar widget on the dashboard'),
            Toggle::make('need-help-widget-enabled')
                ->label('Enable Need Help Widget')
                ->helperText('Show the need help widget below the sidebar'),
            Toggle::make('pterodactyl-enabled')
                ->label('Pterodactyl Enabled')
                ->helperText('Enable if you have a Pterodactyl Server Extension installed'),
        ];
    }

    protected function getColoursSchema(): array
    {
        return [
            Section::make('Theme Mode')
                ->schema([
                    Select::make('default_theme_mode')
                        ->label('Default Theme')
                        ->options([
                            'dark' => 'Dark',
                            'light' => 'Light',
                            'system' => 'System (follows user device)',
                        ])
                        ->default('dark')
                        ->native(false)
                        ->helperText('Default theme for new visitors'),
                    Select::make('force_theme_mode')
                        ->label('Force Theme')
                        ->options([
                            'none' => 'None (users can switch)',
                            'dark' => 'Force Dark',
                            'light' => 'Force Light',
                        ])
                        ->default('none')
                        ->native(false)
                        ->helperText('Force a theme for all users (disables theme switcher)'),
                ]),
            Section::make('Light Mode')
                ->schema([
                    ColorPicker::make('secondary')
                        ->label('Secondary - Brand Colour')
                        ->hsl()
                        ->default('hsl(237, 33%, 60%)'),
                    ColorPicker::make('neutral')
                        ->label('Borders & Accents')
                        ->hsl()
                        ->default('hsl(0, 0%, 89%)'),
                    ColorPicker::make('base')
                        ->label('Base Text Colour')
                        ->hsl()
                        ->default('hsl(0, 0%, 0%)'),
                    ColorPicker::make('muted')
                        ->label('Muted Text Colour')
                        ->hsl()
                        ->default('hsl(220, 28%, 25%)'),
                    ColorPicker::make('inverted')
                        ->label('Inverted Text Colour')
                        ->hsl()
                        ->default('hsl(100, 100%, 100%)'),
                    ColorPicker::make('background')
                        ->label('Background Colour')
                        ->hsl()
                        ->default('hsl(210, 40%, 98%)'),
                    ColorPicker::make('background-secondary')
                        ->label('Secondary Background')
                        ->hsl()
                        ->default('hsl(0, 0%, 100%)'),
                ]),
            Section::make('Dark Mode')
                ->schema([
                    ColorPicker::make('dark-primary')
                        ->label('Primary - Brand Colour')
                        ->hsl()
                        ->default('hsl(231, 58%, 55%)'),
                    ColorPicker::make('dark-neutral')
                        ->label('Borders & Accents')
                        ->hsl()
                        ->default('hsl(60, 2%, 12%)'),
                    ColorPicker::make('dark-base')
                        ->label('Base Text Colour')
                        ->hsl()
                        ->default('hsl(100, 100%, 100%)'),
                    ColorPicker::make('dark-muted')
                        ->label('Muted Text Colour')
                        ->hsl()
                        ->default('hsl(0, 0%, 76%)'),
                    ColorPicker::make('dark-inverted')
                        ->label('Inverted Text Colour')
                        ->hsl()
                        ->default('hsl(220, 14%, 60%)'),
                    ColorPicker::make('dark-background')
                        ->label('Background Colour')
                        ->hsl()
                        ->default('hsl(240, 10%, 4%)'),
                    ColorPicker::make('dark-background-secondary')
                        ->label('Secondary Background')
                        ->hsl()
                        ->default('hsl(60, 2%, 12%)'),
                ]),
            Section::make('Hero Gradient (Login/Register background)')
                ->schema([
                    ColorPicker::make('hero-gradient-1')
                        ->label('Colour 1 (Top Left)')
                        ->hex()
                        ->default('#4A5ECF'),
                    ColorPicker::make('hero-gradient-2')
                        ->label('Colour 2')
                        ->hex()
                        ->default('#7FA6F9'),
                    ColorPicker::make('hero-gradient-3')
                        ->label('Colour 3')
                        ->hex()
                        ->default('#3451B2'),
                    ColorPicker::make('hero-gradient-4')
                        ->label('Colour 4 (Bottom Right)')
                        ->hex()
                        ->default('#7CD2FF'),
                ]),
            Section::make('Paper Gradient (Cards, banners)')
                ->schema([
                    ColorPicker::make('paper-gradient-from')
                        ->label('From Colour')
                        ->hex()
                        ->default('#4169E1'),
                    ColorPicker::make('paper-gradient-to')
                        ->label('To Colour')
                        ->hex()
                        ->default('#89CFF0'),
                ]),
            Section::make('Footer')
                ->schema([
                    ColorPicker::make('footer-bg-light')
                        ->label('Background (Light Mode)')
                        ->hsl()
                        ->default('hsl(222, 47%, 11%)'),
                    ColorPicker::make('footer-text-light')
                        ->label('Text Colour (Light Mode)')
                        ->hsl()
                        ->default('hsl(215, 20%, 80%)'),
                    ColorPicker::make('footer-bg-dark')
                        ->label('Background (Dark Mode)')
                        ->hsl()
                        ->default('hsl(222, 47%, 11%)'),
                    ColorPicker::make('footer-text-dark')
                        ->label('Text Colour (Dark Mode)')
                        ->hsl()
                        ->default('hsl(215, 20%, 80%)'),
                ]),
        ];
    }

    protected function getThemeSchema(): array
    {
        return [
            TextInput::make('card-border-radius')
                ->label('Card Border Radius')
                ->default('12px')
                ->placeholder('12px')
                ->helperText('Border radius for cards and containers'),
            TextInput::make('card-shadow')
                ->label('Card Shadow')
                ->default('0 1px 3px rgba(0,0,0,0.1)')
                ->placeholder('0 1px 3px rgba(0,0,0,0.1)')
                ->helperText('Box shadow for cards'),
            TextInput::make('button-border-radius')
                ->label('Button Border Radius')
                ->default('8px')
                ->placeholder('8px')
                ->helperText('Border radius for buttons'),
            TextInput::make('input-border-radius')
                ->label('Input Border Radius')
                ->default('8px')
                ->placeholder('8px')
                ->helperText('Border radius for form inputs'),
            Section::make('Typography')
                ->schema([
                    Select::make('font_family')
                        ->label('Font Family')
                        ->options([
                            'Geist Sans' => 'Geist Sans',
                            'Inter' => 'Inter',
                            'Roboto' => 'Roboto',
                            'Open Sans' => 'Open Sans',
                            'Lato' => 'Lato',
                            'Montserrat' => 'Montserrat',
                            'Poppins' => 'Poppins',
                            'Raleway' => 'Raleway',
                            'Nunito' => 'Nunito',
                            'Source Sans Pro' => 'Source Sans Pro',
                            'Ubuntu' => 'Ubuntu',
                            'Playfair Display' => 'Playfair Display',
                            'Merriweather' => 'Merriweather',
                            'Oswald' => 'Oswald',
                            'Lora' => 'Lora',
                            'PT Sans' => 'PT Sans',
                            'Noto Sans' => 'Noto Sans',
                            'Work Sans' => 'Work Sans',
                            'DM Sans' => 'DM Sans',
                            'Manrope' => 'Manrope',
                            'Plus Jakarta Sans' => 'Plus Jakarta Sans',
                            'Space Grotesk' => 'Space Grotesk',
                            'Outfit' => 'Outfit',
                            'Sora' => 'Sora',
                            'System Default' => 'System Default (No import needed)',
                        ])
                        ->default('Geist Sans')
                        ->native(false)
                        ->helperText('Select a font family. Google Fonts will be automatically imported.'),
                ]),
            Section::make('Dashboard Banner')
                ->schema([
                    Toggle::make('dashboard_banner_enabled')
                        ->label('Show Dashboard Banner')
                        ->helperText('Show the welcome banner on the dashboard')
                        ->default(true),
                    FileUpload::make('dashboard_banner_image')
                        ->label('Banner Image')
                        ->image()
                        ->disk('public')
                        ->directory('theme-editor')
                        ->visibility('public')
                        ->helperText('Upload a banner image (recommended: 1600x400px)'),
                    TextInput::make('dashboard_banner_title')
                        ->label('Banner Title')
                        ->default('Welcome Back, :name!')
                        ->helperText('Use :name as placeholder for user name'),
                    TextInput::make('dashboard_banner_subtitle')
                        ->label('Banner Subtitle')
                        ->default('Manage your services, invoices, and tickets all in one place.'),
                    TextInput::make('dashboard_banner_button_text')
                        ->label('Button Text')
                        ->default('View Services'),
                    TextInput::make('dashboard_banner_button_url')
                        ->label('Button URL')
                        ->default('/services')
                        ->placeholder('/services'),
                ]),
            Section::make('Page Background Images')
                ->schema([
                    FileUpload::make('home_background_image')
                        ->label('Home Page Background')
                        ->image()
                        ->disk('public')
                        ->directory('theme-editor')
                        ->visibility('public')
                        ->helperText('Background image for the home page'),
                    FileUpload::make('dashboard_background_image')
                        ->label('Dashboard Background')
                        ->image()
                        ->disk('public')
                        ->directory('theme-editor')
                        ->visibility('public')
                        ->helperText('Background image for dashboard pages'),
                    Select::make('background_overlay_type')
                        ->label('Overlay Type')
                        ->options([
                            'gradient' => 'Gradient (top to bottom)',
                            'solid' => 'Solid Overlay',
                        ])
                        ->default('gradient')
                        ->native(false)
                        ->helperText('Gradient fades from transparent at top to solid at bottom'),
                    TextInput::make('background_overlay_opacity')
                        ->label('Overlay Opacity')
                        ->numeric()
                        ->default(80)
                        ->minValue(0)
                        ->maxValue(100)
                        ->suffix('%')
                        ->helperText('Opacity of the overlay (0-100%)'),
                    TextInput::make('background_min_height')
                        ->label('Min Height')
                        ->numeric()
                        ->default(400)
                        ->suffix('px')
                        ->helperText('Minimum height of the background image'),
                    TextInput::make('background_max_height')
                        ->label('Max Height')
                        ->numeric()
                        ->default(800)
                        ->suffix('px')
                        ->helperText('Maximum height of the background image'),
                ]),
            Section::make('Products Category Header')
                ->schema([
                    Textarea::make('products_hero_text')
                        ->label('Hero Description')
                        ->placeholder('Choose the perfect plan for your needs. All plans come with our industry-leading support and reliability.')
                        ->rows(2)
                        ->helperText('Default description shown when category has no description'),
                    TextInput::make('products_hero_cta_text')
                        ->label('Button Text')
                        ->placeholder('View Plans'),
                    Select::make('products_category_header_type')
                        ->label('Header Background Type')
                        ->options([
                            'solid' => 'Solid Color',
                            'gradient' => 'Gradient',
                            'image' => 'Image (Category Image)',
                        ])
                        ->default('gradient')
                        ->native(false)
                        ->helperText('Choose the background style for product category pages')
                        ->live(),
                    ColorPicker::make('products_category_header_color')
                        ->label('Solid Color')
                        ->hsl()
                        ->default('hsl(231, 58%, 55%)')
                        ->visible(fn ($get) => $get('products_category_header_type') === 'solid'),
                    TextInput::make('products_category_image_overlay_opacity')
                        ->label('Image Overlay Opacity')
                        ->numeric()
                        ->default(80)
                        ->minValue(0)
                        ->maxValue(100)
                        ->suffix('%')
                        ->helperText('Opacity of the background color overlay on the category image (0-100%)')
                        ->visible(fn ($get) => $get('products_category_header_type') === 'image'),
                    TextInput::make('products_category_image_min_height')
                        ->label('Min Height')
                        ->numeric()
                        ->default(400)
                        ->suffix('px')
                        ->helperText('Minimum height of the category image background')
                        ->visible(fn ($get) => $get('products_category_header_type') === 'image'),
                    TextInput::make('products_category_image_max_height')
                        ->label('Max Height')
                        ->numeric()
                        ->default(800)
                        ->suffix('px')
                        ->helperText('Maximum height of the category image background')
                        ->visible(fn ($get) => $get('products_category_header_type') === 'image'),
                ]),
        ];
    }

    protected function getLayoutSchema(): array
    {
        return [
            TextInput::make('container-max-width')
                ->label('Main Content Width')
                ->default('1280px')
                ->placeholder('1280px')
                ->helperText('Maximum width for the main content container (e.g. 1280px, 1440px, 1600px)'),
            Section::make('Dashboard Layout')
                ->schema([
                    \Filament\Forms\Components\ViewField::make('dashboard-layout')
                        ->view('themeeditor::components.layout-selector')
                        ->default('default'),
                ]),
            Section::make('Shop Settings')
                ->schema([
                    TextInput::make('shop-product-grid-columns')
                        ->label('Shop Product Grid Columns')
                        ->numeric()
                        ->default(2)
                        ->helperText('Number of columns on shop product page'),
                    TextInput::make('pricing-plans-grid-columns')
                        ->label('Pricing Plans Grid Columns')
                        ->numeric()
                        ->default(3)
                        ->helperText('Number of columns in pricing plans section'),
                    Toggle::make('show-pricing-plans-images')
                        ->label('Show Pricing Plans Images')
                        ->helperText('Show product images in pricing plans'),
                ]),
            Section::make('Pricing Card Style')
                ->schema([
                    \Filament\Forms\Components\ViewField::make('pricing-card-layout')
                        ->view('themeeditor::components.pricing-card-selector')
                        ->default('default'),
                ]),
            Section::make('Category Image Background')
                ->schema([
                    \Filament\Forms\Components\ViewField::make('show-pricing-card-category-background')
                        ->view('themeeditor::components.category-bg-selector')
                        ->default(false),
                ]),
            Section::make('Product Labels')
                ->description('Attach a custom label (e.g. "Most Popular", "Best Value") to any product. It will appear as a pill on the pricing card.')
                ->schema([
                    Repeater::make('product_labels')
                        ->label('')
                        ->schema([
                            Select::make('product_id')
                                ->label('Product')
                                ->searchable()
                                ->options(function () {
                                    $options = [];
                                    $categories = \App\Models\Category::with('products')->orderBy('name')->get();
                                    foreach ($categories as $category) {
                                        foreach ($category->products()->where('hidden', false)->orderBy('sort')->get() as $product) {
                                            $options[(string) $product->id] = $category->name . ' > ' . $product->name;
                                        }
                                    }
                                    return $options;
                                })
                                ->native(false)
                                ->required(),
                            TextInput::make('label_text')
                                ->label('Label Text')
                                ->default('Most Popular')
                                ->required(),
                            ColorPicker::make('label_bg')
                                ->label('Background Colour')
                                ->hex()
                                ->default('#7C3AED'),
                            ColorPicker::make('label_color')
                                ->label('Text Colour')
                                ->hex()
                                ->default('#FFFFFF'),
                        ])
                        ->defaultItems(0)
                        ->collapsible()
                        ->collapsed()
                        ->itemLabel(fn (array $state): ?string => $state['label_text'] ?? 'New Label')
                        ->addActionLabel('Add Product Label')
                        ->reorderable()
                        ->columnSpanFull(),
                ]),
        ];
    }

    protected function getAnnouncementsSchema(): array
    {
        return [
            Section::make('Announcement Banner')
                ->schema([
                    Toggle::make('announcement_banner_enabled')
                        ->label('Enable Announcement Banner')
                        ->helperText('Show banner above the navigation bar'),
                    MarkdownEditor::make('announcement_banner')
                        ->label('Banner Content')
                        ->helperText('HTML content for the announcement banner')
                        ->toolbarButtons(['bold', 'italic', 'link', 'undo', 'redo'])
                        ->columnSpanFull(),
                    TextInput::make('announcement_banner_link')
                        ->label('Banner Link (Optional)')
                        ->placeholder('https://example.com')
                        ->helperText('If set, the entire banner becomes clickable'),
                    Toggle::make('announcement_banner_new_tab')
                        ->label('Open Link in New Tab')
                        ->default(false),
                ]),
            Section::make('Global Broadcast Alert')
                ->schema([
                    Toggle::make('global_broadcast_enabled')
                        ->label('Enable Global Broadcast')
                        ->helperText('Show alert at the top of sidebar/main content'),
                    \Filament\Forms\Components\ViewField::make('global_broadcast_style')
                        ->label('Alert Style')
                        ->view('themeeditor::components.broadcast-style-selector')
                        ->default('style1'),
                    TextInput::make('global_broadcast_title')
                        ->label('Broadcast Title'),
                    MarkdownEditor::make('global_broadcast_content')
                        ->label('Broadcast Content')
                        ->toolbarButtons(['bold', 'italic', 'link', 'undo', 'redo'])
                        ->columnSpanFull(),
                    TextInput::make('global_broadcast_icon')
                        ->label('Broadcast Icon')
                        ->default('fa-bullhorn')
                        ->helperText('FontAwesome icon class (e.g. fa-bullhorn)'),
                    TextInput::make('global_broadcast_button_text')
                        ->label('Button Text')
                        ->default('Learn More'),
                    TextInput::make('global_broadcast_button_url')
                        ->label('Button URL')
                        ->placeholder('/announcements'),
                ]),
        ];
    }

    protected function getMetaSchema(): array
    {
        return [
            Section::make('Analytics')
                ->schema([
                    TextInput::make('google_analytics_id')
                        ->label('Google Analytics Tracking ID')
                        ->placeholder('G-XXXXXXXXXX')
                        ->helperText('Your Google Analytics 4 measurement ID'),
                    TextInput::make('facebook_pixel_id')
                        ->label('Facebook Pixel ID')
                        ->placeholder('123456789012345')
                        ->helperText('Your Meta/Facebook Pixel ID'),
                    TextInput::make('microsoft_clarity_id')
                        ->label('Microsoft Clarity Project ID')
                        ->placeholder('abcde12345')
                        ->helperText('Your Microsoft Clarity project ID'),
                ]),
            Section::make('Social Sharing')
                ->schema([
                    FileUpload::make('meta_image')
                        ->label('Default Meta Image')
                        ->image()
                        ->disk('public')
                        ->directory('theme-editor')
                        ->visibility('public')
                        ->helperText('Default image for social sharing (Twitter, Discord, etc.). Recommended: 1200x630px'),
                    TextInput::make('meta_site_name')
                        ->label('Site Name')
                        ->placeholder('My Hosting Company')
                        ->helperText('Used in og:site_name meta tag'),
                    Textarea::make('meta_description')
                        ->label('Default Meta Description')
                        ->rows(2)
                        ->helperText('Default description for pages without a specific description'),
                ]),
            Section::make('Search Engine')
                ->schema([
                    Select::make('meta_robots')
                        ->label('Robots Meta Tag')
                        ->options([
                            'index,follow' => 'Index, Follow (default — fully visible to search engines)',
                            'noindex,follow' => 'No Index, Follow (pages not indexed but links followed)',
                            'index,nofollow' => 'Index, No Follow (page indexed but links not followed)',
                            'noindex,nofollow' => 'No Index, No Follow (fully hidden from search engines)',
                        ])
                        ->default('index,follow')
                        ->native(false)
                        ->helperText('Controls how search engine crawlers index this site'),
                ]),
            Section::make('Custom Head HTML')
                ->schema([
                    Textarea::make('custom_head_html')
                        ->label('')
                        ->rows(8)
                        ->placeholder('<!-- Paste verification tags, third-party scripts, or any custom <head> HTML here -->')
                        ->helperText('Injected just before </head>. Use for ownership verification tags, additional analytics, etc.'),
                ]),
        ];
    }

    protected function getFooterSchema(): array
    {
        return [
            Section::make('Footer Branding')
                ->schema([
                    Toggle::make('footer-show-logo')
                        ->label('Show Logo in Footer')
                        ->helperText('Show or hide the logo in the footer'),
                ]),
            Section::make('Footer Description')
                ->schema([
                    Textarea::make('footer_description')
                        ->label('Description')
                        ->default('High-performance cloud infrastructure for developers. Built for speed, security, and scalability.')
                        ->rows(3),
                ]),
            Section::make('Footer Link Columns')
                ->schema([
                    Repeater::make('footer_columns')
                        ->label('')
                        ->schema([
                            TextInput::make('title')
                                ->label('Column Title')
                                ->required(),
                            Repeater::make('links')
                                ->label('Links')
                                ->schema([
                                    TextInput::make('label')
                                        ->label('Label')
                                        ->required(),
                                    TextInput::make('url')
                                        ->label('URL')
                                        ->required(),
                                ])
                                ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                                ->collapsible()
                                ->collapsed()
                                ->reorderable()
                                ->addActionLabel('Add Link')
                                ->defaultItems(0),
                        ])
                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                        ->collapsible()
                        ->collapsed()
                        ->reorderable()
                        ->addActionLabel('Add Column')
                        ->default([
                            [
                                'title' => 'Product',
                                'links' => [
                                    ['label' => 'Compute Instances', 'url' => '#'],
                                    ['label' => 'Object Storage', 'url' => '#'],
                                    ['label' => 'Load Balancers', 'url' => '#'],
                                    ['label' => 'Managed Kubernetes', 'url' => '#'],
                                    ['label' => 'API Documentation', 'url' => '#'],
                                ],
                            ],
                            [
                                'title' => 'Company',
                                'links' => [
                                    ['label' => 'About Us', 'url' => '#'],
                                    ['label' => 'Careers', 'url' => '#'],
                                    ['label' => 'Blog', 'url' => '#'],
                                    ['label' => 'Contact Support', 'url' => '#'],
                                    ['label' => 'Status Page', 'url' => '#'],
                                ],
                            ],
                            [
                                'title' => 'Legal',
                                'links' => [
                                    ['label' => 'Terms of Service', 'url' => '#'],
                                    ['label' => 'Privacy Policy', 'url' => '#'],
                                    ['label' => 'SLA', 'url' => '#'],
                                    ['label' => 'Acceptable Use Policy', 'url' => '#'],
                                ],
                            ],
                        ]),
                ]),
            Section::make('Social Links')
                ->schema([
                    TextInput::make('social-link-discord')
                        ->label('Discord')
                        ->default('https://discord.gg/buzz')
                        ->placeholder('https://discord.gg/...'),
                    TextInput::make('social-link-twitter')
                        ->label('Twitter / X')
                        ->placeholder('https://twitter.com/...'),
                    TextInput::make('social-link-instagram')
                        ->label('Instagram')
                        ->placeholder('https://instagram.com/...'),
                    TextInput::make('social-link-youtube')
                        ->label('YouTube')
                        ->placeholder('https://youtube.com/...'),
                    TextInput::make('social-link-linkedin')
                        ->label('LinkedIn')
                        ->placeholder('https://linkedin.com/...'),
                ]),
            Section::make('Payment Methods')
                ->description('Upload images of accepted payment methods (e.g. Visa, Mastercard, PayPal). These will be displayed in the footer.')
                ->schema([
                    FileUpload::make('payment_method_images')
                        ->label('Payment Method Icons')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->disk('public')
                        ->directory('theme-editor')
                        ->visibility('public')
                        ->helperText('Upload payment method logos/icons. Recommended: PNG with transparent background, ~80x50px'),
                ]),
            Section::make('Payment Methods')
                ->description('Upload images of accepted payment methods (e.g. Visa, Mastercard, PayPal). These will be displayed in the footer.')
                ->schema([
                    FileUpload::make('payment_method_images')
                        ->label('Payment Method Icons')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->disk('public')
                        ->directory('theme-editor')
                        ->visibility('public')
                        ->helperText('Upload payment method logos/icons. Recommended: PNG with transparent background, ~80x50px'),
                ]),
            Section::make('Copyright')
                ->schema([
                    TextInput::make('footer_copyright_text')
                        ->label('Copyright Text')
                        ->placeholder('© :year :app_name. All rights reserved.')
                        ->helperText('Use :year and :app_name as placeholders'),
                ]),
        ];
    }

    protected function getHomepageSchema(): array
    {
        return [
            Section::make('Section Visibility')
                ->schema([
                    Toggle::make('disable-home-page')
                        ->label('Disable Home Page')
                        ->helperText('Hide Home link from navigation and homepage'),
                    TextInput::make('home-page-redirect-url')
                        ->label('Disabled Homepage Redirect URL')
                        ->default('/dashboard')
                        ->placeholder('/dashboard')
                        ->helperText('Where to redirect visitors when "Disable Home Page" is enabled'),
                    Toggle::make('show_features_section')
                        ->label('Show Bento Features Section')
                        ->helperText('Show the 4-card bento features grid on the homepage')
                        ->default(true),
                    Toggle::make('show_reasons_section')
                        ->label('Show Reasons Section')
                        ->helperText('Show the reasons/stats grid with screenshot on the homepage')
                        ->default(true),
                    Toggle::make('show_faq_section')
                        ->label('Show FAQ Section')
                        ->helperText('Show the frequently asked questions accordion on the homepage')
                        ->default(true),
                    Toggle::make('show_contact_section')
                        ->label('Show Contact Section')
                        ->helperText('Show the "Need Something Else?" contact block at the bottom of the homepage')
                        ->default(true),
                ]),
            Section::make('Hero Section')
                ->schema([
                    Toggle::make('hero_gradient_enabled')
                        ->label('Show Hero Gradient Background')
                        ->helperText('Display the animated gradient background in the hero section')
                        ->default(false),
                    FileUpload::make('hero_image')
                        ->label('Hero Image')
                        ->image()
                        ->disk('public')
                        ->directory('theme-editor')
                        ->visibility('public')
                        ->helperText('Image displayed on the right side of the hero section'),
                    TextInput::make('title1')
                        ->label('Hero Title Line 1')
                        ->default('Hosting solutions'),
                    TextInput::make('title2')
                        ->label('Hero Title Line 2')
                        ->default('with unmatched performance'),
                    Textarea::make('hero_text')
                        ->label('Hero Description')
                        ->default('Deploy your application with confidence. Our premium hosting infrastructure delivers unmatched performance, security, and reliability.')
                        ->rows(3),
                    TextInput::make('button1text')
                        ->label('Primary Button Text')
                        ->default('Get Started'),
                    TextInput::make('button1link')
                        ->label('Primary Button Link')
                        ->default('#'),
                    TextInput::make('button2text')
                        ->label('Secondary Button Text')
                        ->default('View Plans'),
                    TextInput::make('button2link')
                        ->label('Secondary Button Link')
                        ->default('#'),
                    FileUpload::make('trustpilot_image_light')
                        ->label('Trustpilot Image (Light Mode)')
                        ->image()
                        ->disk('public')
                        ->directory('theme-editor')
                        ->visibility('public')
                        ->helperText('Trustpilot badge/image displayed above the hero title in light mode'),
                    FileUpload::make('trustpilot_image_dark')
                        ->label('Trustpilot Image (Dark Mode)')
                        ->image()
                        ->disk('public')
                        ->directory('theme-editor')
                        ->visibility('public')
                        ->helperText('Trustpilot badge/image displayed above the hero title in dark mode'),
                    TextInput::make('trustpilot_href')
                        ->label('Trustpilot Link')
                        ->url()
                        ->default('')
                        ->helperText('Link URL for the Trustpilot image'),
                ]),
            Section::make('Bento Features (4 Required)')
                ->schema([
                    TextInput::make('features_section_title')
                ->label('Features Section Title')
                ->default('Everything you need to deploy your app'),
            TextInput::make('feature_1_title')
                ->label('Feature 1 Title')
                ->default('Modern Panel'),
            Textarea::make('feature_1_description')
                ->label('Feature 1 Description')
                ->default('Our modern panel is designed to be easy to use and navigate.')
                ->rows(2),
            TextInput::make('feature_1_image')
                ->label('Feature 1 Image URL')
                ->default('/images/panel.png'),
            TextInput::make('feature_2_title')
                ->label('Feature 2 Title')
                ->default('Performance'),
            Textarea::make('feature_2_description')
                ->label('Feature 2 Description')
                ->default('Our infrastructure is built for performance and reliability.')
                ->rows(2),
            TextInput::make('feature_2_image')
                ->label('Feature 2 Image URL')
                ->default('https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/bento1.png'),
            TextInput::make('feature_3_title')
                ->label('Feature 3 Title')
                ->default('Security'),
            Textarea::make('feature_3_description')
                ->label('Feature 3 Description')
                ->default('Our security features are designed to protect your data and information.')
                ->rows(2),
            TextInput::make('feature_3_image')
                ->label('Feature 3 Image URL')
                ->default('https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/bento2.png'),
            TextInput::make('feature_4_title')
                ->label('Feature 4 Title')
                ->default('Priority Support'),
            Textarea::make('feature_4_description')
                ->label('Feature 4 Description')
                ->default('Our support team is available 24/7 to assist you with any questions or issues you may have.')
                ->rows(2),
            TextInput::make('feature_4_image')
                ->label('Feature 4 Image URL')
                ->default('/images/ticket.png'),
                ]),
            Section::make('Pricing Plans Section')
                ->schema([
                    Toggle::make('show-pricing-plans')
                        ->label('Show Pricing Plans Section')
                        ->helperText('Show pricing plans on the home page')
                        ->live(),
                    TextInput::make('pricing-plans-title')
                        ->label('Section Title')
                        ->placeholder('Our Products')
                        ->helperText('Leave empty to use category name (when using category mode)')
                        ->visible(fn ($get) => $get('show-pricing-plans')),
                    Textarea::make('pricing-plans-description')
                        ->label('Section Description')
                        ->placeholder('Choose from our selection of products')
                        ->rows(2)
                        ->helperText('Leave empty to use category description (when using category mode)')
                        ->visible(fn ($get) => $get('show-pricing-plans')),
                    Select::make('pricing-plans-mode')
                        ->label('Display Mode')
                        ->options([
                            'category' => 'Show all products from a category',
                            'products' => 'Select specific products',
                            'categories' => 'Show category cards',
                            'categories' => 'Show category cards',
                        ])
                        ->default('category')
                        ->native(false)
                        ->live()
                        ->visible(fn ($get) => $get('show-pricing-plans')),
                    TextInput::make('pricing-plans-category')
                        ->label('Category Slug')
                        ->helperText('Enter the category slug to display all products from')
                        ->visible(fn ($get) => $get('show-pricing-plans') && $get('pricing-plans-mode') === 'category'),
                    Select::make('pricing-plans-products')
                        ->label('Select Products')
                        ->multiple()
                        ->searchable()
                        ->options(function () {
                            $options = [];
                            $categories = \App\Models\Category::with('products')->orderBy('name')->get();
                            foreach ($categories as $category) {
                                foreach ($category->products()->where('hidden', false)->orderBy('sort')->get() as $product) {
                                    $options[$product->id] = $category->name . ' > ' . $product->name;
                                }
                            }
                            return $options;
                        })
                        ->helperText('Select specific products to showcase')
                        ->visible(fn ($get) => $get('show-pricing-plans') && $get('pricing-plans-mode') === 'products'),
                    Select::make('pricing-plans-categories')
                        ->label('Select Categories')
                        ->multiple()
                        ->searchable()
                        ->options(function () {
                            return \App\Models\Category::orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray();
                        })
                        ->helperText('Select categories to display as cards')
                        ->visible(fn ($get) => $get('show-pricing-plans') && $get('pricing-plans-mode') === 'categories'),
                    Select::make('pricing-plans-categories')
                        ->label('Select Categories')
                        ->multiple()
                        ->searchable()
                        ->options(function () {
                            return \App\Models\Category::orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray();
                        })
                        ->helperText('Select categories to display as cards')
                        ->visible(fn ($get) => $get('show-pricing-plans') && $get('pricing-plans-mode') === 'categories'),
                ]),
            Section::make('Reasons Section')
                ->schema([
            Textarea::make('reasons_section_subtitle')
                ->label('Section Subtitle')
                ->default('Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis suscipit eaque, iste dolor cupiditate blanditiis.')
                ->rows(2),
            TextInput::make('reasons_image')
                ->label('Reasons Image URL')
                ->default('https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/panel.png'),
            Repeater::make('reasons')
                ->label('Reason Cards')
                ->schema([
                    TextInput::make('title')
                        ->label('Title')
                        ->required(),
                    Textarea::make('description')
                        ->label('Description')
                        ->rows(2),
                    TextInput::make('icon')
                        ->label('Icon (FontAwesome)')
                        ->placeholder('fa-solid fa-bolt'),
                    TextInput::make('stat')
                        ->label('Stat Value')
                        ->placeholder('99.9%'),
                    TextInput::make('stat_label')
                        ->label('Stat Label')
                        ->placeholder('Uptime'),
                ])
                ->defaultItems(0)
                ->collapsible()
                ->collapsed()
                ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'New Reason')
                ->addActionLabel('Add Reason')
                ->reorderable()
                ->columnSpanFull(),
                ]),
            Section::make('FAQ Section (Home & Products)')
                ->schema([
                    TextInput::make('faq_section_title')
                        ->label('FAQ Section Title')
                        ->default('Frequently Asked Questions'),
                    Textarea::make('faq_section_subtitle')
                        ->label('FAQ Section Subtitle')
                        ->default("Got questions? We've got answers. Find everything you need to know about our hosting services.")
                        ->rows(2),
                    Repeater::make('faqs')
                        ->label('FAQ Items')
                        ->schema([
                            TextInput::make('question')
                                ->label('Question')
                                ->required(),
                            Textarea::make('answer')
                                ->label('Answer')
                                ->rows(2)
                                ->required(),
                        ])
                        ->defaultItems(0)
                        ->collapsible()
                        ->collapsed()
                        ->itemLabel(fn (array $state): ?string => $state['question'] ?? 'New FAQ')
                        ->addActionLabel('Add FAQ')
                        ->reorderable()
                        ->columnSpanFull(),
                ]),
        ];
    }

    protected function getFaqSchema(): array
    {
        return [
            Section::make('FAQ Section')
                ->schema([
                    TextInput::make('faq_section_title')
                        ->label('FAQ Section Title')
                        ->default('Frequently Asked Questions'),
                    Textarea::make('faq_section_subtitle')
                        ->label('FAQ Section Subtitle')
                        ->default("Got questions? We've got answers. Find everything you need to know about our hosting services.")
                        ->rows(2),
                    Repeater::make('faqs')
                        ->label('FAQ Items')
                        ->schema([
                            TextInput::make('question')
                                ->label('Question')
                                ->required(),
                            Textarea::make('answer')
                                ->label('Answer')
                                ->rows(2)
                                ->required(),
                        ])
                        ->defaultItems(0)
                        ->collapsible()
                        ->collapsed()
                        ->itemLabel(fn (array $state): ?string => $state['question'] ?? 'New FAQ')
                        ->addActionLabel('Add FAQ')
                        ->reorderable()
                        ->columnSpanFull(),
                ]),
            Section::make('Contact Section')
                ->schema([
                    TextInput::make('contact_section_title')
                ->label('Contact Section Title')
                ->default('Need Something Else?'),
            Textarea::make('contact_section_subtitle')
                ->label('Contact Section Subtitle')
                ->default("Can't find what you're looking for? Our team is here to help you find the perfect hosting solution for your unique needs.")
                ->rows(2),
            TextInput::make('contact_button1_text')
                ->label('Contact Button 1 Text')
                ->default('Request Quote'),
            TextInput::make('contact_button1_link')
                ->label('Contact Button 1 Link')
                ->default('#'),
            TextInput::make('contact_button2_text')
                ->label('Contact Button 2 Text')
                ->default('Contact Sales'),
            TextInput::make('contact_button2_link')
                ->label('Contact Button 2 Link')
                ->default('#'),
                ]),
        ];
    }

    protected function getAdvancedSchema(): array
    {
        return [
            Section::make('Need Help Widget')
                ->schema([
                    TextInput::make('need-help-widget-title')
                        ->label('Need Help Widget Title')
                        ->default('Need help?'),
                    MarkdownEditor::make('need-help-widget-text')
                        ->label('Need Help Widget Text')
                        ->toolbarButtons(['bold', 'italic', 'link', 'undo', 'redo'])
                        ->columnSpanFull(),
                    TextInput::make('need-help-widget-button-text')
                        ->label('Need Help Widget Button Text')
                        ->default('Contact Support'),
                    TextInput::make('need-help-widget-button-url')
                        ->label('Need Help Widget Button URL'),
                ]),
            Section::make('Navbar Links')
                ->schema([
                    TextInput::make('navbar_link_1_text')
                        ->label('Navbar Link 1 Text')
                        ->default('Server Status'),
                    TextInput::make('navbar_link_1_url')
                        ->label('Navbar Link 1 URL')
                        ->placeholder('/status'),
                    TextInput::make('navbar_link_2_text')
                        ->label('Navbar Link 2 Text')
                        ->default('Network'),
                    TextInput::make('navbar_link_2_url')
                        ->label('Navbar Link 2 URL')
                        ->placeholder('/network'),
                    TextInput::make('navbar_link_3_text')
                        ->label('Navbar Link 3 Text')
                        ->default('Contact Us'),
                    TextInput::make('navbar_link_3_url')
                        ->label('Navbar Link 3 URL')
                        ->placeholder('/contact'),
                    Select::make('navbar_status_indicator_link')
                        ->label('Status Indicator Link')
                        ->options([
                            '1' => 'Link 1',
                            '2' => 'Link 2',
                            '3' => 'Link 3',
                            'none' => 'None',
                        ])
                        ->default('1')
                        ->native(false)
                        ->helperText('Which navbar link shows the status indicator'),
                ]),
            Section::make('Extra Navbar Links')
                ->schema([
                    Repeater::make('extra_navbar_links')
                        ->label('')
                        ->schema([
                            TextInput::make('text')
                                ->label('Link Text')
                                ->required(),
                            TextInput::make('url')
                                ->label('Link URL')
                                ->required()
                                ->placeholder('/page'),
                            Toggle::make('new_tab')
                                ->label('Open in New Tab')
                                ->default(false),
                        ])
                        ->defaultItems(0)
                        ->collapsible()
                        ->collapsed()
                        ->itemLabel(fn (array $state): ?string => $state['text'] ?? 'New Link')
                        ->addActionLabel('Add Navbar Link')
                        ->reorderable()
                        ->columnSpanFull(),
                ]),
            Section::make('Extra Sidebar Links')
                ->schema([
                    Repeater::make('extra_sidebar_links')
                        ->label('')
                        ->schema([
                            TextInput::make('label')
                                ->label('Link Label')
                                ->required(),
                            TextInput::make('url')
                                ->label('Link URL')
                                ->required()
                                ->placeholder('/page'),
                            TextInput::make('icon')
                                ->label('Icon')
                                ->placeholder('ri-home-2-line')
                                ->helperText('Remix Blade icon without the x- prefix (e.g. ri-link, ri-home-2-line, or shorthand ri-home-2 which resolves to the line SVG). Font Awesome: use full classes with a space (e.g. fa-solid fa-house).'),
                            Toggle::make('new_tab')
                                ->label('Open in New Tab')
                                ->default(false),
                        ])
                        ->defaultItems(0)
                        ->collapsible()
                        ->collapsed()
                        ->itemLabel(fn (array $state): ?string => $state['label'] ?? 'New Link')
                        ->addActionLabel('Add Sidebar Link')
                        ->reorderable()
                        ->columnSpanFull(),
                ]),
            Section::make('Login / Register Review')
                ->description('The testimonial quote shown on the right side of the login and register pages.')
                ->schema([
                    Toggle::make('auth_review_enabled')
                        ->label('Show Review')
                        ->helperText('Show the testimonial quote panel on login and register pages')
                        ->default(true),
                    Textarea::make('auth_review_quote')
                        ->label('Quote')
                        ->default('The best hosting service we\'ve ever used. Lightning fast support and incredible uptime.')
                        ->rows(2),
                    TextInput::make('auth_review_author')
                        ->label('Author Name')
                        ->default('Sarah Johnson'),
                    TextInput::make('auth_review_role')
                        ->label('Author Role')
                        ->default('CEO, TechStart Inc.'),
                ]),
            Section::make('Login / Register Review')
                ->description('The testimonial quote shown on the right side of the login and register pages.')
                ->schema([
                    Toggle::make('auth_review_enabled')
                        ->label('Show Review')
                        ->helperText('Show the testimonial quote panel on login and register pages')
                        ->default(true),
                    Textarea::make('auth_review_quote')
                        ->label('Quote')
                        ->default('The best hosting service we\'ve ever used. Lightning fast support and incredible uptime.')
                        ->rows(2),
                    TextInput::make('auth_review_author')
                        ->label('Author Name')
                        ->default('Sarah Johnson'),
                    TextInput::make('auth_review_role')
                        ->label('Author Role')
                        ->default('CEO, TechStart Inc.'),
                ]),
            Section::make('Custom CSS')
                ->schema([
                    Textarea::make('custom_css')
                        ->label('')
                        ->rows(10)
                        ->placeholder('/* Add your custom CSS here */')
                        ->helperText('Custom CSS will be injected into the <head>'),
                ]),
            Section::make('Custom JavaScript')
                ->schema([
                    Textarea::make('custom_js')
                        ->label('')
                        ->rows(10)
                        ->placeholder('// Add your custom JavaScript here')
                        ->helperText('Injected just before </body>. Do not include <script> tags.'),
                ]),
        ];
    }

    protected function getFormSchema(): array
    {
        return match ($this->activeTab) {
            'general' => $this->getGeneralSchema(),
            'colours' => $this->getColoursSchema(),
            'theme' => $this->getThemeSchema(),
            'layout' => $this->getLayoutSchema(),
            'homepage' => $this->getHomepageSchema(),
            'announcements' => $this->getAnnouncementsSchema(),
            'meta' => $this->getMetaSchema(),
            'footer' => $this->getFooterSchema(),
            'advanced' => $this->getAdvancedSchema(),
            default => $this->getGeneralSchema(),
        };
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components($this->getFormSchema())->statePath('data');
    }

    protected function prepareDataForStorage(array $data): array
    {
        foreach ($this->fileUploadFields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = $data[$field][0] ?? '';
            }
        }
        return $data;
    }

    public function save(): void
    {
        $currentFormData = $this->form->getState();
        
        if ($this->allSettings === null) {
            $this->allSettings = $this->getFormData();
        }
        $this->allSettings = array_merge($this->allSettings, $currentFormData);
        
        $dataToSave = $this->prepareDataForStorage($this->allSettings);
        
        $model = ThemeEditorSetting::query()->first();
        if (!$model) {
            $model = new ThemeEditorSetting();
        }

        $model->config = $dataToSave;
        $model->save();

        $this->syncToThemeSettings($dataToSave);

        Notification::make()
            ->title('Theme settings saved')
            ->success()
            ->send();
        
        $this->dispatch('theme-settings-saved');
    }

    public function resetToDefaults(): void
    {
        ThemeEditorSetting::query()->delete();
        
        $themeName = config('settings.theme', 'default');
        \App\Models\Setting::where('key', 'like', "theme_{$themeName}_%")
            ->whereNull('settingable_type')
            ->whereNull('settingable_id')
            ->delete();
        
        $this->allSettings = $this->getDefaults();
        $this->form->fill($this->prepareDataForForm($this->allSettings));
        
        Notification::make()
            ->title('Theme settings reset to defaults')
            ->success()
            ->send();
    }

    protected function syncToThemeSettings(array $settings): void
    {
        $themeName = config('settings.theme', 'default');
        
        foreach ($settings as $key => $value) {
            $settingKey = "theme_{$themeName}_{$key}";
            
            $type = 'string';
            if (is_bool($value)) {
                $type = 'boolean';
            } elseif (is_int($value)) {
                $type = 'integer';
            } elseif (is_array($value)) {
                $type = 'array';
            }
            
            \App\Models\Setting::updateOrCreate(
                ['key' => $settingKey, 'settingable_type' => null, 'settingable_id' => null],
                ['value' => $value, 'type' => $type, 'encrypted' => false]
            );
            
            config(["settings.{$settingKey}" => $value]);
        }
    }

    protected function getFormData(): array
    {
        $db = ThemeEditorSetting::query()->first();
        $defaults = $this->getDefaults();

        $out = $defaults;
        
        $themeName = config('settings.theme', 'default');
        foreach ($defaults as $key => $defaultValue) {
            $themeValue = config("settings.theme_{$themeName}_{$key}");
            if ($themeValue !== null) {
                $out[$key] = $themeValue;
            }
        }
        
        if ($db) {
            $cfg = (array) ($db->config ?? []);
            foreach ($cfg as $k => $v) {
                $out[$k] = $v;
            }
        }

        return $out;
    }

    protected function getDefaults(): array
    {
        return [
            'direct_checkout' => false,
            'small_images' => false,
            'show_category_description' => true,
            'disable-home-page' => false,
            'home-page-redirect-url' => '/dashboard',
            'pterodactyl-enabled' => false,
            'show-brand-text' => true,
            'show-discord-icon-navbar' => true,
            'show-support-sidebar-widget' => true,
            'need-help-widget-enabled' => true,

            'default_theme_mode' => 'dark',
            'force_theme_mode' => 'none',

            'secondary' => 'hsl(237, 33%, 60%)',
            'neutral' => 'hsl(0, 0%, 89%)',
            'base' => 'hsl(0, 0%, 0%)',
            'muted' => 'hsl(220, 28%, 25%)',
            'inverted' => 'hsl(100, 100%, 100%)',
            'background' => 'hsl(210, 40%, 98%)',
            'background-secondary' => 'hsl(0, 0%, 100%)',
            'dark-primary' => 'hsl(231, 58%, 55%)',
            'dark-neutral' => 'hsl(60, 2%, 12%)',
            'dark-base' => 'hsl(100, 100%, 100%)',
            'dark-muted' => 'hsl(0, 0%, 76%)',
            'dark-inverted' => 'hsl(220, 14%, 60%)',
            'dark-background' => 'hsl(240, 10%, 4%)',
            'dark-background-secondary' => 'hsl(60, 2%, 12%)',

            'hero-gradient-1' => '#4A5ECF',
            'hero-gradient-2' => '#7FA6F9',
            'hero-gradient-3' => '#3451B2',
            'hero-gradient-4' => '#7CD2FF',
            'paper-gradient-from' => '#4169E1',
            'paper-gradient-to' => '#89CFF0',

            'footer-show-logo' => true,
            'footer-bg-light' => 'hsl(222, 47%, 11%)',
            'footer-text-light' => 'hsl(215, 20%, 80%)',
            'footer-bg-dark' => 'hsl(222, 47%, 11%)',
            'footer-text-dark' => 'hsl(215, 20%, 80%)',

            'card-border-radius' => '12px',
            'card-shadow' => '0 1px 3px rgba(0,0,0,0.1)',
            'button-border-radius' => '8px',
            'input-border-radius' => '8px',
            'font_family' => 'Geist Sans',

            'dashboard_banner_enabled' => true,
            'dashboard_banner_image' => '',
            'dashboard_banner_title' => 'Welcome Back, :name!',
            'dashboard_banner_subtitle' => 'Manage your services, invoices, and tickets all in one place.',
            'dashboard_banner_button_text' => 'View Services',
            'dashboard_banner_button_url' => '/services',

            'home_background_image' => '',
            'dashboard_background_image' => '',
            'background_overlay_type' => 'gradient',
            'background_overlay_opacity' => 80,
            'background_min_height' => 400,
            'background_max_height' => 800,

            'products_hero_text' => 'Choose the perfect plan for your needs. All plans come with our industry-leading support and reliability.',
            'products_hero_cta_text' => 'View Plans',
            'products_category_header_type' => 'gradient',
            'products_category_header_color' => 'hsl(231, 58%, 55%)',
            'products_category_image_overlay_opacity' => 80,
            'products_category_image_min_height' => 400,
            'products_category_image_max_height' => 800,

            'container-max-width' => '1280px',
            'dashboard-layout' => 'default',
            'shop-product-grid-columns' => 2,
            'show-pricing-plans' => false,
            'pricing-plans-mode' => 'category',
            'pricing-plans-category' => '',
            'pricing-plans-products' => [],
            'pricing-plans-categories' => [],
            'pricing-plans-categories' => [],
            'pricing-plans-title' => '',
            'pricing-plans-description' => '',
            'pricing-plans-grid-columns' => 3,
            'show-pricing-plans-images' => true,
            'show-pricing-card-category-background' => false,
            'pricing-card-layout' => 'default',
            'product_labels' => [],

            'hero_gradient_enabled' => true,
            'hero_image' => '',
            'title1' => 'Lightning Fast',
            'title2' => 'Web Hosting',
            'hero_text' => 'Deploy your websites with confidence. Our premium hosting infrastructure delivers unmatched performance, security, and reliability.',
            'button1text' => 'Get Started',
            'button1link' => '#',
            'button2text' => 'View Plans',
            'button2link' => '#',
            'trustpilot_image_light' => '',
            'trustpilot_image_dark' => '',
            'trustpilot_href' => '',

            'features_section_title' => 'Everything you need to deploy your app',
            'feature_1_title' => 'Modern Panel',
            'feature_1_description' => 'Our modern panel is designed to be easy to use and navigate.',
            'feature_1_image' => '/images/panel.png',
            'feature_2_title' => 'Performance',
            'feature_2_description' => 'Our infrastructure is built for performance and reliability.',
            'feature_2_image' => 'https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/bento1.png',
            'feature_3_title' => 'Security',
            'feature_3_description' => 'Our security features are designed to protect your data and information.',
            'feature_3_image' => 'https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/bento2.png',
            'feature_4_title' => 'Priority Support',
            'feature_4_description' => 'Our support team is available 24/7 to assist you with any questions or issues you may have.',
            'feature_4_image' => '/images/ticket.png',

            'reasons_section_subtitle' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis suscipit eaque, iste dolor cupiditate blanditiis.',
            'reasons_image' => 'https://aotwpwyblpxejbttgwkx.supabase.co/storage/v1/object/public/utils/paymenter_theme/panel.png',
            'reasons' => [
                ['title' => 'Instant Setup', 'description' => 'Get online in minutes with our instant provisioning.', 'icon' => 'fa-solid fa-bolt', 'stat' => '1 min', 'stat_label' => 'Setup Time'],
                ['title' => '99.9% Uptime', 'description' => 'Our servers are monitored 24/7 for maximum uptime.', 'icon' => 'fa-solid fa-server', 'stat' => '99.9%', 'stat_label' => 'Uptime'],
                ['title' => 'DDoS Protection', 'description' => 'Advanced DDoS protection keeps your site safe.', 'icon' => 'fa-solid fa-shield-alt', 'stat' => '24/7', 'stat_label' => 'Protection'],
                ['title' => 'Global Reach', 'description' => 'Serve your content quickly to users around the world.', 'icon' => 'fa-solid fa-globe', 'stat' => '40+', 'stat_label' => 'Countries Served'],
                ['title' => 'Powerful Databases', 'description' => 'Fast and reliable data storage for demanding applications.', 'icon' => 'fa-solid fa-database', 'stat' => '500GB', 'stat_label' => 'Max Capacity'],
                ['title' => 'Free SSL', 'description' => 'SSL certificates are included at no additional cost.', 'icon' => 'fa-solid fa-lock', 'stat' => '100%', 'stat_label' => 'Encrypted'],
                ['title' => '24/7 Support', 'description' => 'Dedicated team available day and night for all your needs.', 'icon' => 'fa-solid fa-headset', 'stat' => '99%', 'stat_label' => 'Satisfaction'],
                ['title' => 'Seamless Migration', 'description' => 'Move your existing site at no extra cost, without downtime.', 'icon' => 'fa-solid fa-sync-alt', 'stat' => '0', 'stat_label' => 'Downtime'],
                ['title' => 'Eco-Friendly Hosting', 'description' => 'Powered by renewable energy, making a difference for the planet.', 'icon' => 'fa-solid fa-leaf', 'stat' => '100%', 'stat_label' => 'Renewable'],
            ],

            'faq_section_title' => 'Frequently Asked Questions',
            'faq_section_subtitle' => "Got questions? We've got answers. Find everything you need to know about our hosting services.",
            'faqs' => [
                ['question' => 'How do I get started?', 'answer' => 'Simply choose a plan and follow the sign-up process to get started.'],
                ['question' => 'Can I upgrade my plan later?', 'answer' => 'Yes, you can upgrade or downgrade your plan at any time from your dashboard.'],
                ['question' => 'Is there a money-back guarantee?', 'answer' => 'We offer a 30-day money-back guarantee on all plans.'],
                ['question' => 'Do you provide support?', 'answer' => 'Yes, our support team is available 24/7 to assist you.'],
                ['question' => 'Can I transfer my existing website?', 'answer' => 'Absolutely, we offer free website migration for all new customers.'],
                ['question' => 'Are backups included?', 'answer' => 'Daily backups are included with every plan to keep your data safe.'],
            ],

            'contact_section_title' => 'Need Something Else?',
            'contact_section_subtitle' => "Can't find what you're looking for? Our team is here to help you find the perfect hosting solution for your unique needs.",
            'contact_button1_text' => 'Request Quote',
            'contact_button1_link' => '#',
            'contact_button2_text' => 'Contact Sales',
            'contact_button2_link' => '#',

            'announcement_banner' => '',
            'announcement_banner_enabled' => false,
            'announcement_banner_link' => '',
            'announcement_banner_new_tab' => false,
            'global_broadcast_enabled' => false,
            'global_broadcast_style' => 'style1',
            'global_broadcast_title' => '',
            'global_broadcast_content' => '',
            'global_broadcast_icon' => 'fa-bullhorn',
            'global_broadcast_button_text' => 'Learn More',
            'global_broadcast_button_url' => '',

            'social-link-instagram' => '',
            'social-link-discord' => 'https://discord.gg/buzz',
            'social-link-twitter' => '',
            'social-link-linkedin' => '',
            'social-link-youtube' => '',
            'footer_copyright_text' => '© :year :app_name. All rights reserved.',
            'footer_description' => 'High-performance cloud infrastructure for developers. Built for speed, security, and scalability.',
            'footer_columns' => [
                [
                    'title' => 'Product',
                    'links' => [
                        ['label' => 'VPS Hosting', 'url' => '#'],
                        ['label' => 'Dedicated Servers', 'url' => '#'],
                        ['label' => 'Reseller Hosting', 'url' => '#'],
                        ['label' => 'Cloud Hosting', 'url' => '#'],
                        ['label' => 'Domain Registration', 'url' => '#'],
                    ],
                ],
                [
                    'title' => 'Company',
                    'links' => [
                        ['label' => 'Knowledge Base', 'url' => '#'],
                        ['label' => 'Contact Us', 'url' => '#'],
                        ['label' => 'Status', 'url' => '#'],
                    ],
                ],
                [
                    'title' => 'Legal',
                    'links' => [
                        ['label' => 'Terms of Service', 'url' => '#'],
                        ['label' => 'Privacy Policy', 'url' => '#'],
                        ['label' => 'SLA', 'url' => '#'],
                        ['label' => 'Acceptable Use Policy', 'url' => '#'],
                    ],
                ],
            ],

            'need-help-widget-title' => 'Need help?',
            'need-help-widget-text' => 'Get in touch with our support team for assistance.',
            'need-help-widget-button-text' => 'Contact Support',
            'need-help-widget-button-url' => '',
            'navbar_link_1_text' => '',
            'navbar_link_1_url' => '',
            'navbar_link_2_text' => '',
            'navbar_link_2_url' => '',
            'navbar_link_3_text' => '',
            'navbar_link_3_url' => '',
            'navbar_status_indicator_link' => '1',
            'extra_navbar_links' => [],
            'extra_sidebar_links' => [],

            'google_analytics_id' => '',
            'facebook_pixel_id' => '',
            'microsoft_clarity_id' => '',
            'meta_robots' => 'index,follow',
            'custom_head_html' => '',
            'meta_image' => '',
            'meta_site_name' => '',
            'meta_description' => '',
            'custom_css' => '',
            'custom_js' => '',
            'payment_method_images' => [],
            'payment_method_images' => [],

            'show_features_section' => true,
            'show_reasons_section' => true,
            'show_faq_section' => true,
            'show_contact_section' => true,

            'auth_review_enabled' => true,
            'auth_review_quote' => 'The best hosting service we\'ve ever used. Lightning fast support and incredible uptime.',
            'auth_review_author' => 'Sarah Johnson',
            'auth_review_role' => 'CEO, TechStart Inc.',

            'auth_review_enabled' => true,
            'auth_review_quote' => 'The best hosting service we\'ve ever used. Lightning fast support and incredible uptime.',
            'auth_review_author' => 'Sarah Johnson',
            'auth_review_role' => 'CEO, TechStart Inc.',
        ];
    }

    public function getTabs(): array
    {
        return [
            'general' => [
                'label' => 'General',
                'icon' => 'fa-cog',
            ],
            'colours' => [
                'label' => 'Colours',
                'icon' => 'fa-palette',
            ],
            'theme' => [
                'label' => 'Theme',
                'icon' => 'fa-paint-brush',
            ],
            'layout' => [
                'label' => 'Layout',
                'icon' => 'fa-th-large',
            ],
            'homepage' => [
                'label' => 'Homepage',
                'icon' => 'fa-home',
            ],
            'announcements' => [
                'label' => 'Announcements',
                'icon' => 'fa-bullhorn',
            ],
            'meta' => [
                'label' => 'Meta',
                'icon' => 'fa-code',
            ],
            'footer' => [
                'label' => 'Footer',
                'icon' => 'fa-file-alt',
            ],
            'advanced' => [
                'label' => 'Advanced',
                'icon' => 'fa-wrench',
            ],
        ];
    }

    public function getPreviewUrl(): string
    {
        return url('/dashboard');
    }
}
