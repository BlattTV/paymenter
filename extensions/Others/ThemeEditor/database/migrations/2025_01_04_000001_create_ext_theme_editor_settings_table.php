<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ext_theme_editor_settings', function (Blueprint $table) {
            $table->id();
            $table->json('config')->nullable();
            $table->timestamps();
        });

        if (!\DB::table('ext_theme_editor_settings')->exists()) {
            $defaults = [
                'direct_checkout' => false,
                'small_images' => false,
                'show_category_description' => true,
                'disable-home-page' => false,
                'pterodactyl-enabled' => false,
                'show-brand-text' => true,
                'show-discord-icon-navbar' => true,
                'show-support-sidebar-widget' => true,
                'need-help-widget-enabled' => true,

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

                'card-border-radius' => '12px',
                'card-shadow' => '0 1px 3px rgba(0,0,0,0.1)',
                'button-border-radius' => '12px',
                'input-border-radius' => '12px',

                'dashboard-layout' => 'default',
                'shop-product-grid-columns' => 2,
                'show-pricing-plans' => false,
                'pricing-plans-category' => '',
                'pricing-plans-grid-columns' => 3,
                'show-pricing-plans-images' => true,

                'announcement_banner' => '',
                'announcement_banner_enabled' => false,
                'global_broadcast_enabled' => false,
                'global_broadcast_title' => '',
                'global_broadcast_content' => '',
                'global_broadcast_type' => 'info',
                'global_broadcast_icon' => 'fa-bullhorn',

                'social-link-instagram' => '',
                'social-link-discord' => 'https://discord.gg/buzz',
                'social-link-twitter' => '',
                'social-link-linkedin' => '',
                'social-link-youtube' => '',
                'footer_terms_url' => '',
                'footer_privacy_url' => '',
                'footer_refund_url' => '',
                'footer_copyright_text' => '© :year Buzz Development. | All rights reserved.',

                'need-help-widget-title' => 'Need help?',
                'need-help-widget-text' => 'Get in touch with our support team for assistance.',
                'need-help-widget-button-text' => 'Contact Support',
                'need-help-widget-button-url' => '',
                'navbar_link_1_text' => 'Server Status',
                'navbar_link_1_url' => '',
                'navbar_link_2_text' => 'Network',
                'navbar_link_2_url' => '',
                'navbar_link_3_text' => 'Contact Us',
                'navbar_link_3_url' => '',
                'navbar_status_indicator_link' => '1',
            ];

            \DB::table('ext_theme_editor_settings')->insert([
                'config' => json_encode($defaults),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ext_theme_editor_settings');
    }
};
