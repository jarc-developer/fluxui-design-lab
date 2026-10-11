<?php

namespace App\Support\Theme;

/**
 * Imports a daisyUI theme (theme-generator share URL, bare share code, or theme
 * JSON) into the v2 token model.
 *
 * The transport matches ThemeCodec (base64url of deflated JSON), except daisyUI
 * wraps deflate in a zlib header while we use raw deflate, so both are tried.
 * daisyUI concepts with no Flux equivalent (secondary/accent roles, border
 * width, depth, noise) are dropped and reported to the caller.
 *
 * The dark scheme is synthesized (auto) by default. With $useNeutralForDark the
 * daisyUI neutral/neutral-content pair seeds a manual dark scheme instead —
 * neutral grows a fresh dark ramp rather than stamping single steps, so dark
 * surfaces keep their separation.
 */
final class DaisyThemeMapper
{
    /** daisyUI fields mapped into the v2 model. */
    private const MAPPED = [
        '--color-base-100', '--color-base-200', '--color-base-300', '--color-base-content',
        '--color-primary', '--color-primary-content',
        '--color-neutral', '--color-neutral-content',
        '--color-info', '--color-success', '--color-warning', '--color-error',
        '--radius-selector', '--radius-field', '--radius-box', '--size-field',
    ];

    /** daisyUI fields we recognize but cannot express in Flux. */
    private const DROPPED = [
        '--color-secondary', '--color-secondary-content', '--color-accent', '--color-accent-content',
        '--border', '--depth', '--noise', '--size-selector',
    ];

    /**
     * @return array{name: string, tokens: array<string, mixed>, dropped: list<string>}|null
     */
    public static function map(string $payload, bool $useNeutralForDark = false): ?array
    {
        $theme = self::extract($payload);

        if ($theme === null) {
            return null;
        }

        $colors = [];
        $dropped = [];

        foreach ($theme as $key => $value) {
            if (! is_string($key) || ! is_string($value)) {
                continue;
            }

            if (str_starts_with($key, '--color-')) {
                $color = self::color($value);

                if ($color === null) {
                    continue;
                }

                $mapped = in_array($key, self::MAPPED, true)
                    && ($useNeutralForDark || ! in_array($key, ['--color-neutral', '--color-neutral-content'], true));

                if (! $mapped) {
                    $dropped[] = $key;

                    continue;
                }

                $colors[$key] = $color;
            } elseif (in_array($key, self::DROPPED, true)) {
                $dropped[] = $key;
            }
        }

        // A daisyUI theme is only recognizable by its colours.
        if (! isset($colors['--color-base-100'], $colors['--color-primary'])) {
            return null;
        }

        return [
            'name' => is_string($theme['name'] ?? null) ? $theme['name'] : 'DaisyUI import',
            'tokens' => self::tokens($colors, $theme, $useNeutralForDark),
            'dropped' => $dropped,
        ];
    }

    /**
     * Accepts a full theme-generator URL, a bare share code, or theme JSON.
     *
     * @return array<string, mixed>|null
     */
    private static function extract(string $payload): ?array
    {
        $payload = trim($payload);

        if (preg_match('/#theme=([A-Za-z0-9_-]+)/', $payload, $m)) {
            $payload = $m[1];
        }

        if (str_starts_with($payload, '{')) {
            $decoded = json_decode($payload, true);

            return is_array($decoded) ? $decoded : null;
        }

        if (! preg_match('/^[A-Za-z0-9_-]+$/', $payload) || strlen($payload) > 8192) {
            return null;
        }

        $binary = base64_decode(strtr($payload, '-_', '+/'), true);

        if ($binary === false) {
            return null;
        }

        // daisyUI wraps deflate in a zlib header; our own codec uses raw deflate.
        $json = @gzuncompress($binary) ?: @gzinflate($binary, 16384);

        if ($json === false) {
            return null;
        }

        $decoded = json_decode($json, true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * @param  array<string, Color>  $colors
     * @param  array<string, mixed>  $theme
     * @return array<string, mixed>
     */
    private static function tokens(array $colors, array $theme, bool $useNeutralForDark): array
    {
        $base100 = $colors['--color-base-100'];

        // Seed the neutral ramp from base-100's tint, then pin the exact
        // daisyUI surface steps on top of it.
        $zinc = array_map(
            fn (Color $step): string => $step->toHex(),
            Palette::neutral($base100->h, min($base100->c, 0.08)),
        );

        foreach (['--color-base-100' => '50', '--color-base-200' => '100', '--color-base-300' => '200'] as $key => $step) {
            if (isset($colors[$key])) {
                $zinc[$step] = $colors[$key]->toHex();
            }
        }

        if (isset($colors['--color-base-content'])) {
            $zinc['800'] = $colors['--color-base-content']->toHex();
        }

        $status = array_fill_keys(ThemeTokens::STATUSES, null);

        foreach (['--color-info' => 'info', '--color-success' => 'success', '--color-warning' => 'warning', '--color-error' => 'danger'] as $key => $name) {
            if (isset($colors[$key])) {
                $status[$name] = $colors[$key]->toHex();
            }
        }

        $primary = $colors['--color-primary'];

        $light = new ThemeScheme(
            accent: $primary->toHex(),
            accentContent: $primary->toHex(),
            accentForeground: ($colors['--color-primary-content'] ?? $primary->readableForeground())->toHex(),
            zinc: $zinc,
            status: $status,
        );

        // Dark: synthesized by default. Opt-in: daisyUI's neutral pair seeds a
        // manual dark scheme — a fresh ramp from neutral's tint (so surfaces
        // keep their separation), pinning the exact neutral at its nearest step.
        $dark = null;
        $darkMode = 'auto';
        $neutral = $colors['--color-neutral'] ?? null;
        $neutralContent = $colors['--color-neutral-content'] ?? null;

        if ($useNeutralForDark && ($neutral !== null || $neutralContent !== null)) {
            $synthesized = DarkScheme::synthesize($light);

            $darkZinc = $synthesized->zinc;

            if ($neutral !== null) {
                $darkZinc = array_map(
                    fn (Color $step): string => $step->toHex(),
                    Palette::neutral($neutral->h, min(max($neutral->c, 0.004), 0.08)),
                );

                // Pin the exact neutral at its nearest lightness step, and keep
                // everything below it darker so the ramp never flattens.
                $pin = $neutral->l >= 0.18 ? '900' : '950';
                $darkZinc[$pin] = $neutral->toHex();

                if ($pin === '900') {
                    $darker = (new Color($neutral->l * 0.6, $neutral->c, $neutral->h))->toHex();
                    $darkZinc['950'] = $darker;
                }
            }

            if ($neutralContent !== null) {
                $darkZinc['100'] = $neutralContent->toHex();
            }

            $dark = new ThemeScheme(
                accent: $synthesized->accent,
                accentContent: $synthesized->accentContent,
                accentForeground: $synthesized->accentForeground,
                zinc: $darkZinc,
                status: $synthesized->status,
            );
            $darkMode = 'manual';
        }

        return [
            'scheme' => [
                'light' => $light->toArray(),
                'dark' => $dark?->toArray(),
            ],
            'darkMode' => $darkMode,
            'radius' => [
                'selector' => self::length($theme['--radius-selector'] ?? null, 0.25),
                'field' => self::length($theme['--radius-field'] ?? null, 0.5),
                'box' => self::length($theme['--radius-box'] ?? null, 0.75),
            ],
            'spacing' => max(0.15, min(0.35, self::length($theme['--size-field'] ?? null, 0.25))),
            'font' => 'instrument-sans',
        ];
    }

    private static function color(string $value): ?Color
    {
        try {
            return str_starts_with(trim($value), 'oklch(')
                ? Color::fromOklch($value)
                : Color::fromHex($value);
        } catch (\InvalidArgumentException) {
            return null;
        }
    }

    /**
     * Parse a CSS length like `2rem` or `1px` into rem, clamped to 0–2.
     */
    private static function length(mixed $value, float $default): float
    {
        if (! is_string($value) || ! preg_match('/^([\d.]+)(rem|px)$/', trim($value), $m)) {
            return $default;
        }

        $rem = $m[2] === 'px' ? ((float) $m[1]) / 16 : (float) $m[1];

        return max(0.0, min(2.0, $rem));
    }
}
