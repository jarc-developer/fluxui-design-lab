<?php

namespace Tests\Unit\Theme;

use App\Support\Theme\Color;
use App\Support\Theme\Palette;
use PHPUnit\Framework\TestCase;

class PaletteTest extends TestCase
{
    public function test_palette_contains_all_eleven_tailwind_steps(): void
    {
        $ramp = Palette::fromSeed(Color::fromHex('#3b82f6'));

        $this->assertCount(11, $ramp);
        $this->assertSame(Palette::STEPS, array_map('strval', array_keys($ramp)));
    }

    public function test_lightness_is_strictly_decreasing_down_the_ramp(): void
    {
        $ramp = Palette::fromSeed(Color::fromHex('#10b981'));

        $previousL = 1.0;
        foreach ($ramp as $step => $color) {
            $this->assertLessThan($previousL, $color->l, "Step {$step} lightness was not less than previous.");
            $previousL = $color->l;
        }
    }

    public function test_neutral_ramp_with_zero_chroma_produces_pure_monochrome_greys(): void
    {
        $ramp = Palette::neutral(0, 0);

        foreach ($ramp as $step => $color) {
            $this->assertEqualsWithDelta(0.0, $color->c, 0.001, "Neutral step {$step} had non-zero chroma.");
            // In hex, R=G=B for pure greys
            $hex = ltrim($color->toHex(), '#');
            $r = substr($hex, 0, 2);
            $g = substr($hex, 2, 2);
            $b = substr($hex, 4, 2);
            $this->assertSame($r, $g);
            $this->assertSame($g, $b);
        }
    }

    public function test_tinted_neutral_ramp_preserves_hue_angle(): void
    {
        $hue = 255.0;
        $ramp = Palette::neutral($hue, 0.03);

        foreach ($ramp as $step => $color) {
            $this->assertEqualsWithDelta($hue, $color->h, 0.5, "Tinted neutral step {$step} drifted in hue.");
        }
    }
}
