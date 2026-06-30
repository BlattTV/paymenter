<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ext_sitemap_urls', function (Blueprint $table) {
            $table->id();
            $table->string('url')->unique();
            $table->string('route_name')->nullable();
            $table->boolean('enabled')->default(true);
            $table->boolean('is_custom')->default(false);
            $table->string('priority')->default('0.5');
            $table->string('changefreq')->default('monthly');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ext_sitemap_urls');
    }
};

