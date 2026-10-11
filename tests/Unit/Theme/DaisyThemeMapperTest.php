<?php

namespace Tests\Unit\Theme;

use App\Support\Theme\DaisyThemeMapper;
use App\Support\Theme\ThemeTokens;
use Tests\TestCase;

class DaisyThemeMapperTest extends TestCase
{
    private const SILK_CODE = 'eJyVlOuOmzAQRl_FslSplYLlKxjexjHDxopjRzZRd7fqu1dAE9g4TdSfzJxvjjUYfuFgToA7nJ0_4h220cdUZXuAuerd22HEO1xVS2NvMlSMUtzhePT28L1tviFKKBUK1Q1p9I97mG9htcCaoZoRyQtYbGH6ArYxjBDGW0A-CZyTO5n0cWO5IHw5OZct4loS8QAvFK0knE8xrqhCjDVEym0ug42h_w_RLVCoGkHaRcWEQoqS9ovJWLulX2gWunBofXXQmiGmW9JuUwEuYzJ-lUwLRvQBUk5-8i5cGOIGJKKdWMakRlwyUut7uJguXoTyxVrIeZVcd0lbyhATNdHNA77w8Be5nyYFF95KD6NaIU0fsOWtfZKBlGJab4QibAY1k4hzIpqCLTf1j0wyvbvkKoMHO84OnuC0bQ0OfI87TIm66-zjO-4wu1az-4TtoOnj-NJbJ62NfUw9zNrz-1zo4TweprHzU4guT78fine4h8Fc_Ii7wfgMO3xOMEDKvUnHv7XffwCcjFZ9';

    public function test_imports_the_silk_theme_from_a_share_code(): void
    {
        $result = DaisyThemeMapper::map(self::SILK_CODE);

        $this->assertNotNull($result);
        $this->assertSame('silk', $result['name']);

        $tokens = ThemeTokens::fromArray($result['tokens']);

        // Cream surfaces pinned from base-100/200/300.
        $this->assertSame('#f7f5f3', $tokens->light->zinc['50']);
        $this->assertSame('#f3ede9', $tokens->light->zinc['100']);
        $this->assertSame('#e2ddd9', $tokens->light->zinc['200']);
        $this->assertSame('#4b4743', $tokens->light->zinc['800']); // base-content

        // The signature silk look: ink buttons with lime text.
        $this->assertSame('#1c1c29', $tokens->light->accent);
        $this->assertSame('#e2fd00', $tokens->light->accentForeground);

        // Pill selectors and daisyUI radius/spacing.
        $this->assertSame(2.0, $tokens->radiusSelector);
        $this->assertSame(0.5, $tokens->radiusField);
        $this->assertSame(1.0, $tokens->radiusBox);
        $this->assertSame(0.25, $tokens->spacing);

        // Pastel status seeds.
        $this->assertSame('#7cc8ff', $tokens->light->status['info']);
        $this->assertSame('#afd89e', $tokens->light->status['success']);
        $this->assertSame('#efc375', $tokens->light->status['warning']);
        $this->assertSame('#ff8482', $tokens->light->status['danger']);

        // By default the dark scheme is synthesized, not taken from neutral.
        $this->assertSame('auto', $tokens->darkMode);
        $this->assertNull($tokens->dark);
        $this->assertContains('--color-neutral', $result['dropped']);
        $this->assertContains('--color-neutral-content', $result['dropped']);

        // The synthesized dark side still meets contrast requirements.
        $dark = $tokens->darkScheme();
        $contrast = \App\Support\Theme\Color::fromHex($dark->accentForeground)
            ->contrastWith(\App\Support\Theme\Color::fromHex($dark->accent));
        $this->assertGreaterThanOrEqual(4.5, $contrast);
    }

    public function test_neutral_option_seeds_a_manual_dark_ramp_without_collapsing(): void
    {
        $result = DaisyThemeMapper::map(self::SILK_CODE, useNeutralForDark: true);

        $tokens = ThemeTokens::fromArray($result['tokens']);

        $this->assertSame('manual', $tokens->darkMode);
        $this->assertNotNull($tokens->dark);
        $this->assertNotContains('--color-neutral', $result['dropped']);

        // Exact neutral pinned at 900, 950 derived darker, 100 from neutral-content.
        $this->assertSame('#161616', $tokens->dark->zinc['900']);
        $this->assertSame('#c2bdb9', $tokens->dark->zinc['100']);
        $this->assertNotSame($tokens->dark->zinc['900'], $tokens->dark->zinc['950']);

        // No flat run: the three dark surface steps are all distinct.
        $this->assertNotSame($tokens->dark->zinc['800'], $tokens->dark->zinc['900']);
    }

    public function test_import_accepts_a_full_theme_generator_url(): void
    {
        $result = DaisyThemeMapper::map('https://daisyui.com/theme-generator/#theme='.self::SILK_CODE);

        $this->assertNotNull($result);
        $this->assertSame('silk', $result['name']);
    }

    public function test_dropped_fields_are_reported(): void
    {
        $result = DaisyThemeMapper::map(self::SILK_CODE);

        foreach (['--color-secondary', '--color-accent', '--border', '--depth', '--noise'] as $field) {
            $this->assertContains($field, $result['dropped']);
        }
    }

    public function test_rejects_invalid_payloads(): void
    {
        $this->assertNull(DaisyThemeMapper::map(''));
        $this->assertNull(DaisyThemeMapper::map('not valid!'));
        $this->assertNull(DaisyThemeMapper::map('{"name":"incomplete"}'));
        $this->assertNull(DaisyThemeMapper::map(str_repeat('a', 9000)));
    }
}
