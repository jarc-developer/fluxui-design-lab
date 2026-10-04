<?php

namespace App\Support\Theme;

/**
 * Grows 11-step (50–950) ramps out of a single colour.
 *
 * Lightness stops are taken from Tailwind v4's own palettes, so a generated ramp
 * slots in next to the stock ones and Flux's `zinc-200` / `dark:zinc-800` choices
 * keep the contrast they were designed for.
 */
final class Palette
{
    public const STEPS = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '900', '950'];

    /** Tailwind v4 lightness for chromatic palettes (blue, red, ...). */
    private const CHROMATIC_L = [0.970, 0.932, 0.882, 0.809, 0.707, 0.623, 0.546, 0.488, 0.424, 0.379, 0.282];

    /** Chroma relative to the seed: muted at the ends, peaking around 600. */
    private const CHROMATIC_C = [0.07, 0.15, 0.29, 0.53, 0.77, 1.00, 1.10, 1.05, 0.90, 0.70, 0.45];

    /** Tailwind v4 lightness for neutral palettes (neutral, zinc, slate, ...). */
    private const NEUTRAL_L = [0.985, 0.970, 0.922, 0.870, 0.708, 0.556, 0.439, 0.371, 0.269, 0.205, 0.145];

    /** Chroma relative to the requested tint, modelled on slate / zinc. */
    private const NEUTRAL_C = [0.10, 0.20, 0.35, 0.50, 0.90, 1.00, 1.00, 0.95, 0.90, 0.90, 0.85];

    /**
     * A chromatic ramp. The step whose lightness is closest to the seed is the seed
     * itself, so the exact colour the user picked always appears in the ramp.
     *
     * @return array<string, Color>
     */
    public static function fromSeed(Color $seed): array
    {
        $ramp = [];

        foreach (self::STEPS as $i => $step) {
            $ramp[$step] = (new Color(self::CHROMATIC_L[$i], $seed->c * self::CHROMATIC_C[$i], $seed->h))->toGamut();
        }

        $ramp[self::nearestStep($seed->l, self::CHROMATIC_L)] = $seed->toGamut();

        return $ramp;
    }

    /**
     * A neutral ramp tinted towards a hue. Chroma 0 reproduces Tailwind's `neutral`.
     *
     * @return array<string, Color>
     */
    public static function neutral(float $hue, float $chroma): array
    {
        $ramp = [];

        foreach (self::STEPS as $i => $step) {
            $ramp[$step] = (new Color(self::NEUTRAL_L[$i], $chroma * self::NEUTRAL_C[$i], $chroma > 0 ? $hue : 0.0))->toGamut();
        }

        return $ramp;
    }

    /**
     * @param  list<float>  $stops
     */
    private static function nearestStep(float $lightness, array $stops): string
    {
        $best = 0;

        foreach ($stops as $i => $stop) {
            if (abs($stop - $lightness) < abs($stops[$best] - $lightness)) {
                $best = $i;
            }
        }

        return self::STEPS[$best];
    }
}
