<?php

namespace Tests\Unit\Theme;

use App\Support\Theme\Presets;
use App\Support\Theme\ThemeCompiler;
use App\Support\Theme\ThemeTokens;
use Tests\TestCase;

class ThemeCompilerTest extends TestCase
{
    private ThemeCompiler $compiler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->compiler = new ThemeCompiler;
    }

    public function test_runtime_compiles_flux_default_preset(): void
    {
        $tokens = ThemeTokens::fromArray(Presets::tokens('flux-default'));
        $css = $this->compiler->runtime($tokens);

        $this->assertStringStartsWith(':root{', $css);
        $this->assertStringContainsString('--color-accent:#262626', $css);
        $this->assertStringContainsString('--color-zinc-50:#fafafa', $css);
        $this->assertStringContainsString('--color-zinc-950:#0a0a0a', $css);
        $this->assertStringContainsString('.dark{', $css);
        $this->assertStringContainsString('--color-accent:#ffffff', $css);
        $this->assertStringContainsString('--radius-selector:0.25rem', $css);
        $this->assertStringContainsString('--spacing:0.25rem', $css);
    }

    public function test_scoped_preview_generates_scoped_selector(): void
    {
        $tokens = ThemeTokens::fromArray(Presets::tokens('ocean'));
        $css = $this->compiler->runtime($tokens, '[data-theme-preview]');

        $this->assertStringStartsWith('[data-theme-preview]{', $css);
        $this->assertStringContainsString(':is(.dark [data-theme-preview], [data-theme-preview].dark){', $css);
        $this->assertStringContainsString('--color-accent:#0369a1', $css);
    }

    public function test_export_contains_tailwind_theme_and_dark_layer(): void
    {
        $tokens = ThemeTokens::fromArray(Presets::tokens('indigo'));
        $export = $this->compiler->export($tokens, 'Indigo');

        $this->assertStringContainsString('Theme: Indigo', $export);
        $this->assertStringContainsString('@theme {', $export);
        $this->assertStringContainsString('@layer theme {', $export);
        $this->assertStringContainsString('.dark {', $export);
        $this->assertStringContainsString('--color-accent: #4f46e5;', $export);
        $this->assertStringContainsString('[data-flux-checkbox-indicator]', $export);
    }

    public function test_status_palette_generates_matching_sub_palettes(): void
    {
        $tokens = ThemeTokens::fromArray(array_merge(ThemeTokens::defaults(), [
            'status' => [
                'info' => '#0284c7',
                'success' => null,
                'warning' => null,
                'danger' => null,
            ],
        ]));

        $vars = $this->compiler->variables($tokens)['light'];

        // info drives both blue and sky palettes
        $this->assertArrayHasKey('--color-blue-500', $vars);
        $this->assertArrayHasKey('--color-sky-500', $vars);
        // success not overridden, so green and emerald are absent
        $this->assertArrayNotHasKey('--color-green-500', $vars);
        $this->assertArrayNotHasKey('--color-emerald-500', $vars);
    }
}
