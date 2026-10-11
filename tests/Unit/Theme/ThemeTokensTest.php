<?php

namespace Tests\Unit\Theme;

use App\Support\Theme\Color;
use App\Support\Theme\Legacy\LegacyThemeCompiler;
use App\Support\Theme\Legacy\LegacyThemeTokens;
use App\Support\Theme\Presets;
use App\Support\Theme\ThemeCompiler;
use App\Support\Theme\ThemeTokens;
use Tests\TestCase;

class ThemeTokensTest extends TestCase
{
    public function test_v2_validation_rejects_non_hex_colours(): void
    {
        $data = ThemeTokens::defaults();
        $data['scheme']['light']['accent'] = '#262626; body { display: none }';

        $this->assertNull(ThemeTokens::tryFrom($data));
    }

    public function test_v2_validation_rejects_out_of_range_radius_and_accepts_pill(): void
    {
        $data = ThemeTokens::defaults();
        $data['radius']['selector'] = 2.5;
        $this->assertNull(ThemeTokens::tryFrom($data));

        $data['radius']['selector'] = 2.0;
        $this->assertNotNull(ThemeTokens::tryFrom($data));
    }

    public function test_v2_validation_requires_all_zinc_steps(): void
    {
        $data = ThemeTokens::defaults();
        $data['scheme']['light']['zinc']['500'] = null;

        $this->assertNull(ThemeTokens::tryFrom($data));
    }

    public function test_manual_dark_scheme_is_used_verbatim(): void
    {
        $data = ThemeTokens::defaults();
        $data['darkMode'] = 'manual';
        $data['scheme']['dark']['accent'] = '#e2fd00';

        $vars = (new ThemeCompiler)->variables(ThemeTokens::fromArray($data));

        $this->assertSame('#e2fd00', $vars['dark']['--color-accent']);
    }

    public function test_auto_dark_scheme_is_synthesized_with_wcag_contrast(): void
    {
        // Silk's light scheme: near-black indigo accent, lime foreground.
        $data = ThemeTokens::defaults();
        $data['darkMode'] = 'auto';
        $data['scheme']['dark'] = null;
        $data['scheme']['light']['accent'] = '#1c1c29';
        $data['scheme']['light']['accentContent'] = '#1c1c29';
        $data['scheme']['light']['accentForeground'] = '#e2fd00';

        $tokens = ThemeTokens::fromArray($data);
        $this->assertNull($tokens->dark);

        $dark = $tokens->darkScheme();
        $contrast = Color::fromHex($dark->accentForeground)->contrastWith(Color::fromHex($dark->accent));

        $this->assertGreaterThanOrEqual(4.5, $contrast);

        // The dark accent flips light (Flux dark-mode convention) but keeps the hue.
        $darkAccent = Color::fromHex($dark->accent);
        $this->assertGreaterThanOrEqual(0.65, $darkAccent->l);
        $this->assertEqualsWithDelta(Color::fromHex('#1c1c29')->h, $darkAccent->h, 3);
    }

    public function test_every_preset_is_valid_v2(): void
    {
        foreach (Presets::all() as $slug => $preset) {
            $tokens = ThemeTokens::tryFrom($preset['tokens']);
            $this->assertNotNull($tokens, "Preset [{$slug}] is not valid v2.");
            $this->assertSame($preset['tokens'], $tokens->toArray(), "Preset [{$slug}] does not round-trip.");
        }
    }

    public function test_legacy_payload_upcasts_to_valid_v2(): void
    {
        $legacy = $this->legacyIndigo();

        $tokens = ThemeTokens::fromArray($legacy);

        $this->assertSame('manual', $tokens->darkMode);
        $this->assertSame('#4f46e5', $tokens->light->accent);
        $this->assertNotNull($tokens->dark);
        $this->assertSame('#818cf8', $tokens->dark->accent);
        $this->assertCount(11, $tokens->light->zinc);
    }

    public function test_upcast_preserves_compiled_output(): void
    {
        $legacyCompiler = new LegacyThemeCompiler;
        $compiler = new ThemeCompiler;

        foreach (['flux-default' => LegacyThemeTokens::defaults(), 'indigo' => $this->legacyIndigo(), 'cupcake' => $this->legacyCupcake()] as $name => $definition) {
            $legacyVars = $legacyCompiler->variables(LegacyThemeTokens::fromArray($definition));
            $vars = $compiler->variables(ThemeTokens::fromArray($definition));

            $this->assertSame([], array_diff_assoc($legacyVars['light'], $vars['light']), "[{$name}] light scope drifted.");

            $darkShared = array_intersect_key($vars['dark'], $legacyVars['dark']);
            $this->assertSame($legacyVars['dark'], $darkShared, "[{$name}] dark scope drifted.");

            // Keys v1 did not emit in .dark must be copies of the light values,
            // so emitting them is a visual no-op.
            $darkExtra = array_diff_key($vars['dark'], $legacyVars['dark']);
            $this->assertSame(
                array_intersect_key($vars['light'], $darkExtra),
                array_intersect_key($vars['dark'], $darkExtra),
                "[{$name}] dark extras are not light copies."
            );
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function legacyIndigo(): array
    {
        return array_replace(LegacyThemeTokens::defaults(), [
            'accent' => ['light' => '#4f46e5', 'dark' => '#818cf8'],
            'accentForeground' => ['light' => null, 'dark' => null],
            'base' => ['hue' => 275, 'chroma' => 0.012],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function legacyCupcake(): array
    {
        return array_replace(LegacyThemeTokens::defaults(), [
            'accent' => ['light' => '#0d9488', 'dark' => '#2dd4bf'],
            'accentForeground' => ['light' => null, 'dark' => null],
            'base' => ['hue' => 60, 'chroma' => 0.012],
            'status' => ['info' => '#38bdf8', 'success' => '#10b981', 'warning' => '#f59e0b', 'danger' => '#f43f5e'],
            'radius' => ['selector' => 1, 'field' => 1, 'box' => 2],
            'spacing' => 0.3,
            'font' => 'nunito',
        ]);
    }
}
