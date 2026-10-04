<?php

namespace Tests\Unit\Theme;

use App\Support\Theme\Color;
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
}
