<?php

namespace Paymenter\Extensions\Others\SitemapGenerator\Models;

use Illuminate\Database\Eloquent\Model;

class SitemapUrl extends Model
{
    protected $table = 'ext_sitemap_urls';

    protected $fillable = [
        'url',
        'route_name',
        'enabled',
        'is_custom',
        'priority',
        'changefreq',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'is_custom' => 'boolean',
    ];
}

