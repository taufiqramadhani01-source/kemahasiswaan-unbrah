<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Helper untuk mengambil data setting tema & informasi website secara dinamis.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        try {
            return Setting::get($key, $default);
        } catch (\Exception $e) {
            return $default;
        }
    }
}
