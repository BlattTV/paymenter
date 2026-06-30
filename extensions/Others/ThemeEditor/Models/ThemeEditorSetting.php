<?php

namespace Paymenter\Extensions\Others\ThemeEditor\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeEditorSetting extends Model
{
    protected $table = 'ext_theme_editor_settings';
    protected $guarded = [];
    protected $casts = [
        'config' => 'array',
    ];
}
