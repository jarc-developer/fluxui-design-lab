<?php

namespace App\Support\Theme;

use App\Models\Theme;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * The compiled CSS of the active theme, as injected into every page's <head>.
 *
 * Compiling a theme runs a few hundred colour conversions, so the result is cached
 * until a theme is saved, activated or deleted (see ThemeObserver). With no active
 * theme everything is empty and the app falls back to resources/css/app.css.
 */
class ActiveTheme
{
    public const CACHE_KEY = 'theme.active';

    /** @var array{css: string, font: string|null, name: string|null}|null */
    private ?array $resolved = null;

    public function __construct(private ThemeCompiler $compiler) {}

    public function css(): string
    {
        return $this->resolve()['css'];
    }

    public function fontUrl(): ?string
    {
        return $this->resolve()['font'];
    }

    public function name(): ?string
    {
        return $this->resolve()['name'];
    }

    public function forget(): void
    {
        $this->resolved = null;
    }

    /**
     * @return array{css: string, font: string|null, name: string|null}
     */
    private function resolve(): array
    {
        return $this->resolved ??= Cache::rememberForever(self::CACHE_KEY, function (): array {
            $theme = $this->activeTheme();

            if ($theme === null) {
                return ['css' => '', 'font' => null, 'name' => null];
            }

            return [
                'css' => $this->compiler->runtime($theme->tokens),
                'font' => $theme->tokens->fontUrl(),
                'name' => $theme->name,
            ];
        });
    }

    private function activeTheme(): ?Theme
    {
        try {
            return Theme::query()->active()->first();
        } catch (Throwable $e) {
            // Before `php artisan migrate` there is no themes table yet; the pages
            // (including the error pages) must still render. Anything else is real.
            if (Schema::hasTable('themes')) {
                throw $e;
            }

            return null;
        }
    }
}
