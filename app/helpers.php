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
        // Cache setting agar tidak membebani query database berulang-ulang
        static $settings = null;
        if (is_null($settings)) {
            try {
                $settings = Setting::pluck('value', 'key')->all();
            } catch (\Exception $e) {
                $settings = [];
            }
        }

        return $settings[$key] ?? $default;
    }
}
