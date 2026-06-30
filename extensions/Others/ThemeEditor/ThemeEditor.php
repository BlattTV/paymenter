<?php

namespace Paymenter\Extensions\Others\ThemeEditor;

use App\Classes\Extension\Extension;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use App\Helpers\ExtensionHelper;
use Filament\Facades\Filament;
use Paymenter\Extensions\Others\ThemeEditor\Admin\Pages\ThemeEditor as ThemeEditorPage;
use Paymenter\Extensions\Others\ThemeEditor\Models\ThemeEditorSetting;

class ThemeEditor extends Extension
{
    public function boot()
    {
        View::addNamespace('themeeditor', __DIR__ . '/resources/views');
        Filament::registerPages([
            ThemeEditorPage::class,
        ]);

        Route::get('/extensions/theme-editor/css/theme-editor.css', function () {
            $path = __DIR__ . '/resources/css/theme-editor.css';
            return response()->file($path, ['Content-Type' => 'text/css']);
        })->name('theme-editor.css');

        if (!Schema::hasTable('ext_theme_editor_settings')) {
            $this->runExtensionMigrations();
        }
    }

    public function getConfig($values = [])
    {
        return [];
    }

    public function installed()
    {
        $this->runExtensionMigrations();
    }

    public function uninstalled()
    {
        $this->rollbackExtensionMigrations();
    }

    public function upgraded($oldVersion = null)
    {
        $this->runExtensionMigrations();
    }

    public function enabled()
    {
        $this->runExtensionMigrations();
    }

    private function runExtensionMigrations(): void
    {
        if (method_exists(ExtensionHelper::class, 'runMigrations')) {
            ExtensionHelper::runMigrations(__DIR__ . '/database/migrations');
            return;
        }
        Artisan::call('migrate', [
            '--path' => 'extensions/Others/ThemeEditor/database/migrations',
            '--force' => true,
        ]);
    }

    private function rollbackExtensionMigrations(): void
    {
        if (method_exists(ExtensionHelper::class, 'rollbackMigrations')) {
            ExtensionHelper::rollbackMigrations(__DIR__ . '/database/migrations');
            return;
        }
        Artisan::call('migrate:rollback', [
            '--path' => 'extensions/Others/ThemeEditor/database/migrations',
            '--force' => true,
        ]);
    }

    public static function getSettings(): array
    {
        if (!Schema::hasTable('ext_theme_editor_settings')) {
            return [];
        }

        $model = ThemeEditorSetting::query()->first();
        if (!$model) {
            return [];
        }

        return (array) ($model->config ?? []);
    }

    public static function getSetting(string $key, $default = null)
    {
        $settings = self::getSettings();
        return $settings[$key] ?? $default;
    }
}
