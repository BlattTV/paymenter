<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ext_sitemap_urls', function (Blueprint $table) {
            if (!Schema::hasColumn('ext_sitemap_urls', 'is_custom')) {
                $table->boolean('is_custom')->default(false)->after('enabled');
            }
            if (!Schema::hasColumn('ext_sitemap_urls', 'priority')) {
                $table->string('priority')->default('0.5')->after('is_custom');
            }
            if (!Schema::hasColumn('ext_sitemap_urls', 'changefreq')) {
                $table->string('changefreq')->default('monthly')->after('priority');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ext_sitemap_urls', function (Blueprint $table) {
            if (Schema::hasColumn('ext_sitemap_urls', 'is_custom')) {
                $table->dropColumn('is_custom');
            }
            if (Schema::hasColumn('ext_sitemap_urls', 'priority')) {
                $table->dropColumn('priority');
            }
            if (Schema::hasColumn('ext_sitemap_urls', 'changefreq')) {
                $table->dropColumn('changefreq');
            }
        });
    }
};


