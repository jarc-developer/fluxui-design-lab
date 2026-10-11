<?php

namespace Tests\Unit\Theme;

use App\Support\Theme\Color;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ColorTest extends TestCase
{
    public function test_white_and_black_have_expected_lightness_and_zero_chroma(): void
    {
        $white = Color::fromHex('#ffffff');
        $this->assertEqualsWithDelta(1.0, $white->l, 0.001);
        $this->assertEqualsWithDelta(0.0, $white->c, 0.001);
        $this->assertSame('#ffffff', $white->toHex());

        $black = Color::fromHex('#000000');
        $this->assertEqualsWithDelta(0.0, $black->l, 0.001);
        $this->assertEqualsWithDelta(0.0, $black->c, 0.001);
        $this->assertSame('#000000', $black->toHex());
    }

    public function test_hex_conversion_round_trips_accurately(): void
    {
        $samples = [
            '#ffffff',
            '#000000',
            '#3b82f6',
            '#ef4444',
            '#10b981',
            '#eab308',
            '#8b5cf6',
            '#ec4899',
            '#262626',
            '#4f46e5',
        ];

        foreach ($samples as $hex) {
            $color = Color::fromHex($hex);
            $this->assertSame($hex, $color->toHex(), "Hex [{$hex}] did not round-trip identically.");
        }
    }

    public function test_wcag_contrast_ratio_calculation(): void
    {
        $white = Color::fromHex('#ffffff');
        $black = Color::fromHex('#000000');

        $this->assertEqualsWithDelta(21.0, $white->contrastWith($black), 0.1);
        $this->assertEqualsWithDelta(1.0, $white->contrastWith($white), 0.01);
    }

    public function test_readable_foreground_guarantees_sufficient_contrast(): void
    {
        $darkBackground = Color::fromHex('#1e1b4b');
        $foregroundOnDark = $darkBackground->readableForeground();
        $this->assertGreaterThanOrEqual(4.5, $darkBackground->contrastWith($foregroundOnDark));

        $lightBackground = Color::fromHex('#fef08a');
        $foregroundOnLight = $lightBackground->readableForeground();
        $this->assertGreaterThanOrEqual(4.5, $lightBackground->contrastWith($foregroundOnLight));
    }

    public function test_oklch_parsing_supports_percent_and_unit_lightness(): void
    {
        $percent = Color::fromOklch('oklch(97% 0.0035 67.78)');
        $unit = Color::fromOklch('oklch(0.97 0.0035 67.78)');

        $this->assertEqualsWithDelta(0.97, $percent->l, 0.001);
        $this->assertEqualsWithDelta(0.97, $unit->l, 0.001);
        $this->assertEqualsWithDelta(0.0035, $percent->c, 0.0001);
        $this->assertEqualsWithDelta(67.78, $percent->h, 0.001);
    }

    public function test_oklch_parsing_rejects_alpha_and_garbage(): void
    {
        $rejected = 0;

        foreach (['oklch(97% 0.0035 67.78 / 0.5)', 'not-a-color', 'oklch()'] as $bad) {
            try {
                Color::fromOklch($bad);
            } catch (InvalidArgumentException) {
                $rejected++;
            }
        }

        $this->assertSame(3, $rejected);
    }

    public function test_silk_oklch_values_convert_to_expected_hex(): void
    {
        $this->assertSame('#1c1c29', Color::fromOklch('oklch(23.27% 0.0249 284.3)')->toHex());
        $this->assertSame('#e2fd00', Color::fromOklch('oklch(94.22% 0.2505 117.44)')->toHex());
        $this->assertSame('#f7f5f3', Color::fromOklch('oklch(97% 0.0035 67.78)')->toHex());
    }

    public function test_lightness_and_chroma_changes_preserve_hue(): void
    {
        $color = Color::fromHex('#3b82f6');

        $this->assertEqualsWithDelta($color->h, $color->withLightness(0.5)->h, 0.0001);
        $this->assertEqualsWithDelta($color->h, $color->withChroma(0.01)->h, 0.0001);
    }

    public function test_gamut_clamping_reduces_chroma_and_keeps_lightness_and_hue(): void
    {
        $neon = new Color(0.9, 0.4, 117.44);
        $this->assertFalse($neon->inGamut());

        $clamped = $neon->toGamut();
        $this->assertTrue($clamped->inGamut());
        $this->assertLessThan($neon->c, $clamped->c);
        $this->assertEqualsWithDelta($neon->l, $clamped->l, 0.0001);
        $this->assertEqualsWithDelta($neon->h, $clamped->h, 0.0001);
    }
}
