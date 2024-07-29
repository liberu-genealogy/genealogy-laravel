<?php

namespace App\Services;

use App\Models\SiteSettings;
use Illuminate\Support\Facades\Cache;

class SiteSettingsService
{
    public function get($key = null)
    {
        $settings = Cache::remember(config('site-settings.cache_key'), config('site-settings.cache_duration'), function () {
            return SiteSettings::first() ?? new SiteSettings();
        });

        return $key ? $settings->$key : $settings;
    }

    public function clear()
    {
        Cache::forget(config('site-settings.cache_key'));
    }
}