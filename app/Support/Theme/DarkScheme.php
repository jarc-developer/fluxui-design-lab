<?php

namespace App\Support\Theme;

/**
 * Synthesizes a theme's dark scheme from its light scheme (design.md D2).
 *
 * The neutral ramp and status seeds are copied verbatim: both are positional
 * ramps whose light and dark ends serve the two schemes, so copying preserves
 * hue identity and contrast relationships. What actually flips is the accent
 * trio — a dark accent button becomes a light one in dark mode, Flux-style.
 *
 * Each flipped colour keeps its oklch hue, loses 20% chroma, has its lightness
 * inverted, and lands in the band appropriate for its role. The foreground's
 * band is chosen opposite to the synthesized accent's lightness so the pair
 * can always reach WCAG AA contrast.
 */
final class DarkScheme
{
    private const ACCENT_BAND = [0.65, 0.80];

    private const CONTENT_BAND = [0.65, 0.85];

    private const FOREGROUND_LIGHT_BAND = [0.85, 0.97];

    private const FOREGROUND_DARK_BAND = [0.14, 0.30];

    public static function synthesize(ThemeScheme $light): ThemeScheme
    {
        $accent = self::invert(Color::fromHex($light->accent), ...self::ACCENT_BAND);

        [$fgMin, $fgMax] = $accent->l >= 0.6
            ? self::FOREGROUND_DARK_BAND
            : self::FOREGROUND_LIGHT_BAND;

        $foreground = self::ensureContrast(
            self::invert(Color::fromHex($light->accentForeground), $fgMin, $fgMax),
            $accent,
            $fgMin,
            $fgMax,
        );

        return new ThemeScheme(
            accent: $accent->toHex(),
            accentContent: self::invert(Color::fromHex($light->accentContent), ...self::CONTENT_BAND)->toHex(),
            accentForeground: $foreground->toHex(),
            zinc: $light->zinc,
            status: $light->status,
        );
    }

    private static function invert(Color $color, float $min, float $max): Color
    {
        $lightness = max($min, min($max, 1.0 - $color->l));

        return new Color($lightness, $color->c * 0.8, $color->h);
    }

    /**
     * Nudge the foreground's lightness away from the background until the pair
     * reaches WCAG AA (4.5:1). If the band cannot converge, fall back to the
     * guaranteed-readable white / tinted near-black.
     */
    private static function ensureContrast(Color $foreground, Color $background, float $min, float $max): Color
    {
        $step = $foreground->l > $background->l ? 0.02 : -0.02;

        for ($i = 0; $i < 40 && $foreground->contrastWith($background) < 4.5; $i++) {
            $next = max($min, min($max, $foreground->l + $step));

            if (abs($next - $foreground->l) < 1e-9) {
                break;
            }

            $foreground = $foreground->withLightness($next);
        }

        if ($foreground->contrastWith($background) < 4.5) {
            return $background->readableForeground();
        }

        return $foreground;
    }
}
