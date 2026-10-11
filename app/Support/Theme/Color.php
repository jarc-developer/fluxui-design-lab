<?php

namespace App\Support\Theme;

use InvalidArgumentException;

/**
 * An immutable colour stored in OKLCH.
 *
 * OKLCH is perceptually uniform: equal steps in lightness look like equal steps to
 * the eye, and changing lightness does not shift the hue. That is what makes it
 * possible to grow a whole 50–950 ramp out of a single seed colour, which is how
 * Tailwind v4 defines its own palettes.
 *
 * Conversion matrices are Björn Ottosson's reference OKLab implementation.
 */
final readonly class Color
{
    /**
     * @param  float  $l  Lightness, 0–1.
     * @param  float  $c  Chroma, 0–~0.4.
     * @param  float  $h  Hue in degrees, 0–360.
     */
    public function __construct(
        public float $l,
        public float $c,
        public float $h,
    ) {}

    /**
     * Parse a CSS oklch() colour, e.g. `oklch(97% 0.0035 67.78)` — the format
     * daisyUI themes are written in. Alpha is rejected: themes are opaque.
     */
    public static function fromOklch(string $oklch): self
    {
        if (! preg_match('/^oklch\(\s*([0-9.]+%?|none)\s+([0-9.]+|none)\s+(-?[0-9.]+|none)\s*\)$/i', trim($oklch), $m)) {
            throw new InvalidArgumentException("Invalid oklch colour [{$oklch}].");
        }

        // Lightness may be a percentage or a plain 0–1 number.
        $l = $m[1] === 'none' ? 0.0 : (str_ends_with($m[1], '%') ? ((float) $m[1]) / 100 : (float) $m[1]);
        $c = $m[2] === 'none' ? 0.0 : (float) $m[2];
        $h = $m[3] === 'none' ? 0.0 : fmod((float) $m[3] + 360, 360);

        return new self(max(0.0, min(1.0, $l)), max(0.0, $c), $h);
    }

    public static function fromHex(string $hex): self
    {
        $hex = ltrim(strtolower(trim($hex)), '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        if (! preg_match('/^[0-9a-f]{6}$/', $hex)) {
            throw new InvalidArgumentException("Invalid hex colour [{$hex}].");
        }

        return self::fromRgb(
            hexdec(substr($hex, 0, 2)) / 255,
            hexdec(substr($hex, 2, 2)) / 255,
            hexdec(substr($hex, 4, 2)) / 255,
        );
    }

    /**
     * Build a colour from gamma-encoded sRGB channels in the 0–1 range.
     */
    public static function fromRgb(float $r, float $g, float $b): self
    {
        [$r, $g, $b] = array_map(self::toLinear(...), [$r, $g, $b]);

        $l = 0.4122214708 * $r + 0.5363325363 * $g + 0.0514459929 * $b;
        $m = 0.2119034982 * $r + 0.6806995451 * $g + 0.1073969566 * $b;
        $s = 0.0883024619 * $r + 0.2817188376 * $g + 0.6299787005 * $b;

        $l = self::cbrt($l);
        $m = self::cbrt($m);
        $s = self::cbrt($s);

        $okL = 0.2104542553 * $l + 0.7936177850 * $m - 0.0040720468 * $s;
        $okA = 1.9779984951 * $l - 2.4285922050 * $m + 0.4505937099 * $s;
        $okB = 0.0259040371 * $l + 0.7827717662 * $m - 0.8086757660 * $s;

        $chroma = sqrt($okA ** 2 + $okB ** 2);
        $hue = $chroma < 1e-4 ? 0.0 : fmod(rad2deg(atan2($okB, $okA)) + 360, 360);

        return new self($okL, $chroma < 1e-4 ? 0.0 : $chroma, $hue);
    }

    public function withLightness(float $l): self
    {
        return new self(max(0.0, min(1.0, $l)), $this->c, $this->h);
    }

    public function withChroma(float $c): self
    {
        return new self($this->l, max(0.0, $c), $this->h);
    }

    /**
     * Gamma-encoded sRGB channels, unclamped (values outside 0–1 are out of gamut).
     *
     * @return array{0: float, 1: float, 2: float}
     */
    public function toRgb(): array
    {
        $hue = deg2rad($this->h);
        $a = $this->c * cos($hue);
        $b = $this->c * sin($hue);

        $l = ($this->l + 0.3963377774 * $a + 0.2158037573 * $b) ** 3;
        $m = ($this->l - 0.1055613458 * $a - 0.0638541728 * $b) ** 3;
        $s = ($this->l - 0.0894841775 * $a - 1.2914855480 * $b) ** 3;

        return [
            self::toGamma(4.0767416621 * $l - 3.3077115913 * $m + 0.2309699292 * $s),
            self::toGamma(-1.2684380046 * $l + 2.6097574011 * $m - 0.3413193965 * $s),
            self::toGamma(-0.0041960863 * $l - 0.7034186147 * $m + 1.7076147010 * $s),
        ];
    }

    public function inGamut(): bool
    {
        foreach ($this->toRgb() as $channel) {
            if ($channel < -0.0005 || $channel > 1.0005) {
                return false;
            }
        }

        return true;
    }

    /**
     * Pull the colour into sRGB by lowering chroma only, so hue and lightness survive.
     */
    public function toGamut(): self
    {
        if ($this->inGamut()) {
            return $this;
        }

        $low = 0.0;
        $high = $this->c;

        for ($i = 0; $i < 24; $i++) {
            $mid = ($low + $high) / 2;

            if ($this->withChroma($mid)->inGamut()) {
                $low = $mid;
            } else {
                $high = $mid;
            }
        }

        return $this->withChroma($low);
    }

    public function toHex(): string
    {
        $channels = array_map(
            fn (float $channel): string => str_pad(dechex((int) round(max(0, min(1, $channel)) * 255)), 2, '0', STR_PAD_LEFT),
            $this->toGamut()->toRgb(),
        );

        return '#'.implode('', $channels);
    }

    /**
     * WCAG 2 relative luminance.
     */
    public function luminance(): float
    {
        [$r, $g, $b] = array_map(
            fn (float $channel): float => self::toLinear(max(0, min(1, $channel))),
            $this->toGamut()->toRgb(),
        );

        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }

    /**
     * WCAG 2 contrast ratio, 1–21.
     */
    public function contrastWith(self $other): float
    {
        $a = $this->luminance();
        $b = $other->luminance();

        return (max($a, $b) + 0.05) / (min($a, $b) + 0.05);
    }

    /**
     * White, or a near-black tinted with this colour's hue, whichever reads better on top.
     */
    public function readableForeground(): self
    {
        $white = new self(1.0, 0.0, 0.0);
        $dark = new self(0.21, min($this->c, 0.03), $this->h);

        return $this->contrastWith($white) >= $this->contrastWith($dark) ? $white : $dark;
    }

    private static function toLinear(float $channel): float
    {
        return $channel <= 0.04045
            ? $channel / 12.92
            : (($channel + 0.055) / 1.055) ** 2.4;
    }

    private static function toGamma(float $channel): float
    {
        $sign = $channel < 0 ? -1 : 1;
        $channel = abs($channel);

        return $sign * ($channel <= 0.0031308
            ? 12.92 * $channel
            : 1.055 * ($channel ** (1 / 2.4)) - 0.055);
    }

    private static function cbrt(float $value): float
    {
        return $value < 0 ? -((-$value) ** (1 / 3)) : $value ** (1 / 3);
    }
}
