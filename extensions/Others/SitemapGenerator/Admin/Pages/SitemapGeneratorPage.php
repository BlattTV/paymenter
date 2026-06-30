<?php

namespace Paymenter\Extensions\Others\SitemapGenerator\Admin\Pages;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Route;
use App\Models\Setting;
use Paymenter\Extensions\Others\SitemapGenerator\Models\SitemapUrl;

class SitemapGeneratorPage extends Page implements HasActions, HasTable
{
    use InteractsWithActions, InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'ri-file-list-3-line';

    protected static ?string $navigationLabel = 'Sitemap Generator';

    protected static ?string $title = 'Sitemap Generator';

    protected static string|\UnitEnum|null $navigationGroup = 'Extensions';

    protected static ?string $slug = 'sitemap-generator';

    public array $urls = [];

    public function getView(): string
    {
        return 'sitemap-generator::pages.sitemap-generator';
    }

    public function mount(): void
    {
        $this->loadUrls();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('addCustomUrl')
                ->label('Add Custom URL')
                ->icon('heroicon-o-plus')
                ->form([
                    TextInput::make('slug')
                        ->label('URL Path')
                        ->required()
                        ->placeholder('/custom-page')
                        ->helperText('Enter the path, e.g., /about or /custom-page')
                        ->prefix(Setting::where('key', 'app_url')->first()?->value ?? config('app.url')),
                    TextInput::make('route_name')
                        ->label('Route Name (Optional)')
                        ->placeholder('custom.page'),
                    Select::make('priority')
                        ->label('Priority')
                        ->options([
                            '1.0' => '1.0 (Highest)',
                            '0.8' => '0.8 (High)',
                            '0.7' => '0.7 (Medium-High)',
                            '0.5' => '0.5 (Medium)',
                            '0.3' => '0.3 (Low)',
                        ])
                        ->default('0.5')
                        ->required(),
                    Select::make('changefreq')
                        ->label('Change Frequency')
                        ->options([
                            'always' => 'Always',
                            'hourly' => 'Hourly',
                            'daily' => 'Daily',
                            'weekly' => 'Weekly',
                            'monthly' => 'Monthly',
                            'yearly' => 'Yearly',
                            'never' => 'Never',
                        ])
                        ->default('monthly')
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        $baseUrl = Setting::where('key', 'app_url')->first()?->value ?? config('app.url');
                        $slug = ltrim($data['slug'], '/');
                        $fullUrl = rtrim($baseUrl, '/') . '/' . $slug;

                        SitemapUrl::create([
                            'url' => $fullUrl,
                            'route_name' => $data['route_name'] ?? 'custom',
                            'priority' => $data['priority'],
                            'changefreq' => $data['changefreq'],
                            'is_custom' => true,
                            'enabled' => true,
                        ]);

                        Notification::make()
                            ->title('URL Added')
                            ->success()
                            ->body('The custom URL has been added successfully.')
                            ->send();

                        $this->loadUrls();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Failed to Add URL')
                            ->danger()
                            ->body($e->getMessage())
                            ->send();
                    }
                }),
            Action::make('updateSitemap')
                ->label('Update Sitemap')
                ->color('primary')
                ->action(function () {
                    try {
                        $this->generateSitemap();
                        Notification::make()
                            ->title('Sitemap Updated')
                            ->success()
                            ->body('The sitemap.xml file has been generated successfully.')
                            ->send();
                        $this->loadUrls();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Failed to Generate Sitemap')
                            ->danger()
                            ->body('Could not write sitemap.xml file. Please ensure the public directory is writable by the web server.')
                            ->send();
                    }
                }),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->records(function () {
                $urls = collect($this->urls);
                
                $search = $this->getTableSearch();
                if ($search) {
                    $urls = $urls->filter(function ($url) use ($search) {
                        return str_contains(strtolower($url['url']), strtolower($search)) ||
                               str_contains(strtolower($url['name']), strtolower($search));
                    });
                }
                
                return $urls;
            })
            ->searchable()
            ->columns([
                IconColumn::make('enabled')
                    ->label('Enabled')
                    ->boolean()
                    ->sortable()
                    ->action(function ($record) {
                        $sitemapUrl = SitemapUrl::where('url', $record['url'])->first();
                        if ($sitemapUrl) {
                            $sitemapUrl->update(['enabled' => !$sitemapUrl->enabled]);
                        } else {
                            SitemapUrl::create([
                                'url' => $record['url'],
                                'route_name' => $record['name'],
                                'enabled' => false,
                            ]);
                        }
                        $this->loadUrls();
                        $this->resetTable();
                    }),
                TextColumn::make('url')
                    ->label('URL')
                    ->sortable()
                    ->url(fn ($record) => $record['url'], true),
                TextColumn::make('name')
                    ->label('Route Name')
                    ->sortable(),
                TextColumn::make('priority')
                    ->label('Priority')
                    ->sortable(),
                TextColumn::make('changefreq')
                    ->label('Change Frequency')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (array $record) => ($record['is_custom'] ?? false))
                    ->action(function (array $record) {
                        SitemapUrl::where('url', $record['url'])->delete();
                        Notification::make()
                            ->title('URL Deleted')
                            ->success()
                            ->send();
                        $this->loadUrls();
                    }),
            ])
            ->paginated(false);
    }

    protected function loadUrls(): void
    {
        $routes = Route::getRoutes();
        $baseUrl = Setting::where('key', 'app_url')->first()?->value ?? config('app.url');
        $urls = [];

        foreach ($routes as $route) {
            $methods = $route->methods();
            
            if (!in_array('GET', $methods)) {
                continue;
            }

            $name = $route->getName();
            if (!$name) {
                continue;
            }

            if (str_contains($name, 'filament.') || 
                str_contains($name, 'livewire.') || 
                str_contains($name, 'paymenter.livewire.') ||
                str_contains($name, 'verification.') ||
                str_contains($name, 'password.') ||
                str_contains($name, 'attachments.') ||
                str_contains($name, 'api.')) {
                continue;
            }

            $uri = $route->uri();
            
            if (str_contains($uri, '{') || 
                str_starts_with($uri, 'admin/') || 
                str_starts_with($uri, '_') ||
                str_contains($uri, 'sanctum')) {
                continue;
            }

            try {
                $url = route($name);
                $priority = $this->calculatePriority($uri, $name);
                $changefreq = $this->calculateChangeFreq($uri, $name);

                $sitemapUrl = SitemapUrl::where('url', $url)->first();
                $enabled = $sitemapUrl ? $sitemapUrl->enabled : true;

                $urls[] = [
                    'url' => $url,
                    'name' => $name,
                    'priority' => $priority,
                    'changefreq' => $changefreq,
                    'lastmod' => now()->toAtomString(),
                    'enabled' => $enabled,
                ];
            } catch (\Exception $e) {
                continue;
            }
        }

        $this->addDynamicUrls($urls);
        $this->addCustomUrls($urls);

        $this->urls = $urls;
    }

    protected function addCustomUrls(array &$urls): void
    {
        try {
            $customUrls = SitemapUrl::where('is_custom', true)->get();

            foreach ($customUrls as $customUrl) {
                $urls[] = [
                    'url' => $customUrl->url,
                    'name' => $customUrl->route_name ?? 'custom',
                    'priority' => $customUrl->priority,
                    'changefreq' => $customUrl->changefreq,
                    'lastmod' => $customUrl->updated_at->toAtomString(),
                    'enabled' => $customUrl->enabled,
                    'is_custom' => true,
                ];
            }
        } catch (\Exception $e) {
        }
    }

    protected function addDynamicUrls(array &$urls): void
    {
        try {
            $categories = \App\Models\Category::all();

            foreach ($categories as $category) {
                $categorySlug = $category->full_slug ?? $category->slug;
                $url = url('products/' . $categorySlug);
                $sitemapUrl = SitemapUrl::where('url', $url)->first();
                $enabled = $sitemapUrl ? $sitemapUrl->enabled : true;

                $urls[] = [
                    'url' => $url,
                    'name' => 'category.show',
                    'priority' => '0.8',
                    'changefreq' => 'weekly',
                    'lastmod' => $category->updated_at->toAtomString(),
                    'enabled' => $enabled,
                ];
            }
        } catch (\Exception $e) {
        }

        try {
            $products = \App\Models\Product::with('category')->get();

            foreach ($products as $product) {
                $categorySlug = $product->category->full_slug ?? $product->category->slug;
                $url = url('products/' . $categorySlug . '/' . $product->slug);
                $sitemapUrl = SitemapUrl::where('url', $url)->first();
                $enabled = $sitemapUrl ? $sitemapUrl->enabled : true;

                $urls[] = [
                    'url' => $url,
                    'name' => 'products.show',
                    'priority' => '0.7',
                    'changefreq' => 'weekly',
                    'lastmod' => $product->updated_at->toAtomString(),
                    'enabled' => $enabled,
                ];
            }
        } catch (\Exception $e) {
        }

        if (class_exists('\Paymenter\Extensions\Others\Announcements\Models\Announcement')) {
            try {
                $announcements = \Paymenter\Extensions\Others\Announcements\Models\Announcement::where('is_active', true)
                    ->where('published_at', '<=', now())
                    ->get();

                foreach ($announcements as $announcement) {
                    try {
                        $url = route('announcements.show', ['announcement' => $announcement->slug]);
                        $sitemapUrl = SitemapUrl::where('url', $url)->first();
                        $enabled = $sitemapUrl ? $sitemapUrl->enabled : true;

                        $urls[] = [
                            'url' => $url,
                            'name' => 'announcements.show',
                            'priority' => '0.6',
                            'changefreq' => 'weekly',
                            'lastmod' => $announcement->updated_at->toAtomString(),
                            'enabled' => $enabled,
                        ];
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            } catch (\Exception $e) {
            }
        }
    }

    protected function generateSitemap(): void
    {
        $sitemapPath = public_path('sitemap.xml');
        
        if (!is_writable(public_path())) {
            throw new \Exception('Public directory is not writable');
        }

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        foreach ($this->urls as $urlData) {
            if (!($urlData['enabled'] ?? true)) {
                continue;
            }

            $url = $xml->addChild('url');
            $url->addChild('loc', htmlspecialchars($urlData['url']));
            $url->addChild('lastmod', now()->toAtomString());
            $url->addChild('changefreq', $urlData['changefreq']);
            $url->addChild('priority', $urlData['priority']);
        }

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        $result = @$dom->save($sitemapPath);
        
        if ($result === false) {
            throw new \Exception('Failed to save sitemap.xml file');
        }
    }

    protected function calculatePriority(string $uri, string $name): string
    {
        if ($uri === '/' || $name === 'home') {
            return '1.0';
        }

        if (str_contains($uri, 'login') || str_contains($uri, 'register')) {
            return '0.8';
        }

        if (str_contains($name, 'pagebuilder.page.')) {
            return '0.8';
        }

        if (str_contains($uri, 'dashboard') || str_contains($uri, 'account')) {
            return '0.7';
        }

        return '0.5';
    }

    protected function calculateChangeFreq(string $uri, string $name): string
    {
        if ($uri === '/' || $name === 'home') {
            return 'daily';
        }

        if (str_contains($name, 'pagebuilder.page.')) {
            return 'weekly';
        }

        if (str_contains($uri, 'dashboard') || str_contains($uri, 'account')) {
            return 'weekly';
        }

        return 'monthly';
    }
}

