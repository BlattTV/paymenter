<?php

if (!function_exists('translate')) {
    /**
     * Translate a given key using the current locale.
     *
     * @param string $key
     * @param mixed $default
     * @return array
     */
    function translate($key, $default = null, $replace = [])
    {
        if (Lang::has($key)) {
            return Lang::get($key, $replace);
        }
        if (is_string($default)) {
            if (!empty($replace)) {
                foreach ($replace as $search => $value) {
                    $default = str_replace(':' . $search, $value, $default);
                    $default = str_replace('{' . $search . '}', $value, $default); // handle {name} too
                }
            }
            return $default;
        }
        return $default ?? $key;
    }
}

return [
    'name' => 'nano',
    'author' => 'info@buzz.dev',
    'url' => 'https://buzz.dev',
    'settings' => [],
];
