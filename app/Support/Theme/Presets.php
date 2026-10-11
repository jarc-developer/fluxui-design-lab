<?php

namespace App\Support\Theme;

use App\Support\Theme\Legacy\LegacyThemeTokens;

/**
 * Starting points for the generator. They are also seeded as editable themes.
 *
 * Presets are defined as compact v1-style seeds and returned as precompiled v2
 * token arrays via the legacy upcast, so their appearance is byte-identical to
 * what the parametric generator produced.
 */
final class Presets
{
    /**
     * @return array<string, array{name: string, tokens: array<string, mixed>}>
     */
    public static function all(): array
    {
        $defaults = LegacyThemeTokens::defaults();

        return self::compile([
            'flux-default' => [
                'name' => 'Flux default',
                'tokens' => $defaults,
            ],
            'indigo' => [
                'name' => 'Indigo',
                'tokens' => self::with($defaults, [
                    'accent' => ['light' => '#4f46e5', 'dark' => '#818cf8'],
                    'accentForeground' => ['light' => null, 'dark' => null],
                    'base' => ['hue' => 275, 'chroma' => 0.012],
                ]),
            ],
            'emerald' => [
                'name' => 'Emerald',
                'tokens' => self::with($defaults, [
                    'accent' => ['light' => '#059669', 'dark' => '#34d399'],
                    'accentForeground' => ['light' => null, 'dark' => null],
                    'base' => ['hue' => 165, 'chroma' => 0.008],
                    'radius' => ['selector' => 0.25, 'field' => 1, 'box' => 1],
                    'font' => 'figtree',
                ]),
            ],
            'rose' => [
                'name' => 'Rose',
                'tokens' => self::with($defaults, [
                    'accent' => ['light' => '#e11d48', 'dark' => '#fb7185'],
                    'accentForeground' => ['light' => null, 'dark' => null],
                    'base' => ['hue' => 15, 'chroma' => 0.006],
                    'font' => 'dm-sans',
                ]),
            ],
            'ocean' => [
                'name' => 'Ocean',
                'tokens' => self::with($defaults, [
                    'accent' => ['light' => '#0369a1', 'dark' => '#38bdf8'],
                    'accentForeground' => ['light' => null, 'dark' => null],
                    'base' => ['hue' => 255, 'chroma' => 0.03],
                    'status' => ['info' => '#0ea5e9', 'success' => null, 'warning' => null, 'danger' => null],
                    'radius' => ['selector' => 0, 'field' => 0.25, 'box' => 0.25],
                    'font' => 'ibm-plex-sans',
                ]),
            ],
            'cupcake' => [
                'name' => 'Cupcake',
                'tokens' => self::with($defaults, [
                    'accent' => ['light' => '#0d9488', 'dark' => '#2dd4bf'],
                    'accentForeground' => ['light' => null, 'dark' => null],
                    'base' => ['hue' => 60, 'chroma' => 0.012],
                    'status' => ['info' => '#38bdf8', 'success' => '#10b981', 'warning' => '#f59e0b', 'danger' => '#f43f5e'],
                    'radius' => ['selector' => 1, 'field' => 1, 'box' => 2],
                    'spacing' => 0.3,
                    'font' => 'nunito',
                ]),
            ],
        ]);
    }

    /**
     * A random but coherent theme: one hue drives the accent and tints the base.
     *
     * @return array<string, mixed>
     */
    public static function random(): array
    {
        $hue = random_int(0, 359);
        $chroma = random_int(140, 220) / 1000;

        $light = new Color(random_int(50, 60) / 100, $chroma, $hue);
        $dark = new Color(random_int(70, 78) / 100, $chroma * 0.85, $hue);

        $radius = LegacyThemeTokens::RADIUS_STEPS;
        $field = $radius[random_int(0, 3)];

        return ThemeTokens::fromArray(self::with(LegacyThemeTokens::defaults(), [
            'accent' => ['light' => $light->toHex(), 'dark' => $dark->toHex()],
            'accentForeground' => ['light' => null, 'dark' => null],
            'base' => ['hue' => ($hue + random_int(-30, 30) + 360) % 360, 'chroma' => random_int(0, 30) / 1000],
            'radius' => [
                'selector' => min($field, 1),
                'field' => $field,
                'box' => $radius[min(array_search($field, $radius, true) + random_int(0, 1), 4)],
            ],
            'spacing' => (float) array_rand(LegacyThemeTokens::SPACING_STEPS),
            'font' => array_rand(ThemeTokens::FONTS),
        ]))->toArray();
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function tokens(string $key): ?array
    {
        return self::all()[$key]['tokens'] ?? null;
    }

    /**
     * @param  array<string, array{name: string, tokens: array<string, mixed>}>  $presets
     * @return array<string, array{name: string, tokens: array<string, mixed>}>
     */
    private static function compile(array $presets): array
    {
        foreach ($presets as $slug => $preset) {
            $presets[$slug]['tokens'] = ThemeTokens::fromArray($preset['tokens'])->toArray();
        }

        return $presets;
    }

    /**
     * @param  array<string, mixed>  $base
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private static function with(array $base, array $overrides): array
    {
        return array_replace($base, $overrides);
    }
}
