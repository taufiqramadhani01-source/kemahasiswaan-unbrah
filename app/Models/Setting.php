<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'label'];

    /**
     * Get a setting value by key, with optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $all = Cache::rememberForever('app_settings_all', function () {
            return static::pluck('value', 'key')->toArray();
        });

        return array_key_exists($key, $all) && $all[$key] !== null ? $all[$key] : $default;
    }

    /**
     * Set a setting value by key (upsert).
     */
    public static function set(string $key, mixed $value, string $group = 'general', string $label = ''): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group, 'label' => $label]
        );
        Cache::forget("setting_{$key}");
        Cache::forget("settings_group_{$group}");
        Cache::forget('app_settings_all');
    }

    /**
     * Get all settings in a group as key => value array.
     */
    public static function group(string $group): array
    {
        return Cache::rememberForever("settings_group_{$group}", function () use ($group) {
            return static::where('group', $group)->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Clear all setting caches.
     */
    public static function clearCache(): void
    {
        Cache::flush();
    }
}
