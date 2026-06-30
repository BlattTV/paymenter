<?php

namespace Paymenter\Extensions\Others\SitemapGenerator;

use App\Classes\Extension\Extension;
use App\Helpers\ExtensionHelper;
use Illuminate\Support\HtmlString;

class SitemapGenerator extends Extension 
{
    public function getConfig($values = [])
    {
        return [
            [
                'name' => 'Notice',
                'type' => 'placeholder',
                'label' => new HtmlString('This extension generates a sitemap.xml file for your website. Go to the Sitemap Generator page in the admin panel to manage your sitemap.'),
            ],
        ];
    }

    public function installed()
    {
        ExtensionHelper::runMigrations('extensions/Others/SitemapGenerator/database/migrations');
    }

    public function uninstalled()
    {
        ExtensionHelper::rollbackMigrations('extensions/Others/SitemapGenerator/database/migrations');
    }

    public function boot()
    {
        \Illuminate\Support\Facades\View::addNamespace('sitemap-generator', __DIR__ . '/resources/views');
    }
}

