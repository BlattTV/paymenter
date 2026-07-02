<?php

namespace Paymenter\Extensions\Others\Sitemap;

use App\Classes\Extension\Extension;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/**
 * Sitemap extension
 *
 * Serves /sitemap.xml with the public storefront URLs (home, categories,
 * products) so search engines can discover every page. The XML is cached
 * for one hour; saving a product or category simply ages out naturally.
 */
class Sitemap extends Extension
{
    public function boot()
    {
        Route::get('/sitemap.xml', function () {
            $xml = Cache::remember('sitemap_xml', 3600, function () {
                return $this->buildXml();
            });

            return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
        })->name('sitemap');
    }

    public function getConfig($values = [])
    {
        return [];
    }

    private function buildXml(): string
    {
        $urls = [];

        $urls[] = [
            'loc' => rtrim(config('app.url'), '/'),
            'priority' => '1.0',
        ];

        foreach (Category::query()->get() as $category) {
            $slug = $category->full_slug ?: $category->slug;
            $urls[] = [
                'loc' => url('/products/' . $slug),
                'lastmod' => optional($category->updated_at)->toAtomString(),
                'priority' => '0.8',
            ];
        }

        $products = Product::query()
            ->where('hidden', false)
            ->with('category')
            ->get();

        foreach ($products as $product) {
            if (!$product->category || !$product->slug) {
                continue;
            }
            $categorySlug = $product->category->full_slug ?: $product->category->slug;
            $urls[] = [
                'loc' => url('/products/' . $categorySlug . '/' . $product->slug),
                'lastmod' => optional($product->updated_at)->toAtomString(),
                'priority' => '0.7',
            ];
        }

        $out = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $out .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $url) {
            $out .= "  <url>\n";
            $out .= '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1) . "</loc>\n";
            if (!empty($url['lastmod'])) {
                $out .= '    <lastmod>' . $url['lastmod'] . "</lastmod>\n";
            }
            $out .= '    <priority>' . $url['priority'] . "</priority>\n";
            $out .= "  </url>\n";
        }
        $out .= '</urlset>';

        return $out;
    }
}
