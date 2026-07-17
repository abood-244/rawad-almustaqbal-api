<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    private const CACHE_KEY = 'app_settings';

    /**
     * Retrieve all settings, cached.
     */
    public function getAllSettings(): array
    {
        return Cache::remember(self::CACHE_KEY, 60 * 60 * 24, function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Update multiple settings.
     */
    public function updateSettings(array $data): void
    {
        $allowedKeys = config('settings.allowed_keys', []);

        foreach ($data as $key => $value) {
            if (!in_array($key, $allowedKeys, true)) {
                throw new \InvalidArgumentException("Invalid setting key attempted: {$key}");
            }
            $this->saveSetting($key, $value);
        }

        $this->refreshCache();
    }

    /**
     * Save a single setting.
     */
    private function saveSetting(string $key, ?string $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Refresh the settings cache.
     */
    private function refreshCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        $this->getAllSettings(); // prime the cache immediately
    }
}
