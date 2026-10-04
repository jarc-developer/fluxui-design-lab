<?php

namespace App\Observers;

use App\Models\Theme;
use App\Support\Theme\ActiveTheme;
use Illuminate\Support\Facades\Cache;

/**
 * Keeps the cached, compiled CSS of the active theme in step with the table.
 */
class ThemeObserver
{
    public function saved(Theme $theme): void
    {
        self::flush();
    }

    public function deleted(Theme $theme): void
    {
        self::flush();
    }

    public static function flush(): void
    {
        Cache::forget(ActiveTheme::CACHE_KEY);
        app(ActiveTheme::class)->forget();
    }
}
