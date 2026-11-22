<?php

namespace Tests\Feature;

use App\Support\Theme\Legacy\LegacyThemeTokens;
use App\Support\Theme\Presets;
use App\Support\Theme\ThemeCodec;
use App\Support\Theme\ThemeTokens;
use Illuminate\Contracts\Console\Kernel;
use Livewire\Livewire;
use Tests\TestCase;

class ThemeGeneratorTest extends TestCase
{
    private const SILK_CODE = 'eJyVlOuOmzAQRl_FslSplYLlKxjexjHDxopjRzZRd7fqu1dAE9g4TdSfzJxvjjUYfuFgToA7nJ0_4h220cdUZXuAuerd22HEO1xVS2NvMlSMUtzhePT28L1tviFKKBUK1Q1p9I97mG9htcCaoZoRyQtYbGH6ArYxjBDGW0A-CZyTO5n0cWO5IHw5OZct4loS8QAvFK0knE8xrqhCjDVEym0ug42h_w_RLVCoGkHaRcWEQoqS9ovJWLulX2gWunBofXXQmiGmW9JuUwEuYzJ-lUwLRvQBUk5-8i5cGOIGJKKdWMakRlwyUut7uJguXoTyxVrIeZVcd0lbyhATNdHNA77w8Be5nyYFF95KD6NaIU0fsOWtfZKBlGJab4QibAY1k4hzIpqCLTf1j0wyvbvkKoMHO84OnuC0bQ0OfI87TIm66-zjO-4wu1az-4TtoOnj-NJbJ62NfUw9zNrz-1zo4TweprHzU4guT78fine4h8Fc_Ii7wfgMO3xOMEDKvUnHv7XffwCcjFZ9';

    public function test_theme_generator_is_publicly_accessible(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Flux Themes');
        $response->assertSee('Theme Generator');
        $response->assertSee('Live Component Preview');
    }

    public function test_livewire_component_updates_preview_css_on_token_change(): void
    {
        Livewire::test('pages::theme-generator')
            ->assertSet('tokens.scheme.light.accent', '#262626')
            ->set('tokens.scheme.light.accent', '#e11d48')
            ->assertSeeHtml('--color-accent:#e11d48');
    }

    public function test_color_slots_use_the_official_flux_color_picker(): void
    {
        $html = Livewire::test('pages::theme-generator')->html();

        $this->assertStringContainsString('data-flux-color-picker', $html);
        $this->assertStringNotContainsString('type="color"', $html);
    }

    public function test_custom_accent_foreground_and_local_storage_theme_loading(): void
    {
        $component = Livewire::test('pages::theme-generator')
            ->set('tokens.scheme.light.accent', '#1c1c29')
            ->set('tokens.scheme.light.accentForeground', '#e2fd00')
            ->assertSeeHtml('--color-accent-foreground:#e2fd00');

        /** @var array<string, mixed> $savedTokens */
        $savedTokens = $component->get('tokens');

        Livewire::test('pages::theme-generator')
            ->call('loadLocalTheme', 'Silkish', $savedTokens)
            ->assertSet('name', 'Silkish')
            ->assertSet('tokens.scheme.light.accent', '#1c1c29')
            ->assertSet('tokens.scheme.light.accentForeground', '#e2fd00');
    }

    public function test_invalid_local_storage_payload_leaves_state_unchanged(): void
    {
        Livewire::test('pages::theme-generator')
            ->call('loadLocalTheme', 'Corrupted', ['not' => 'valid'])
            ->assertSet('name', 'Flux default')
            ->assertSet('tokens.scheme.light.accent', '#262626');
    }

    public function test_pill_radius_renders_in_preview(): void
    {
        Livewire::test('pages::theme-generator')
            ->set('tokens.radius.selector', 2)
            ->assertSeeHtml('--radius-selector:2rem');
    }

    public function test_fill_ramp_accepts_client_hue_and_chroma_and_writes_all_eleven_zinc_steps(): void
    {
        Livewire::test('pages::theme-generator')
            ->call('fillRamp', 'light', 284.0, 0.02)
            ->assertSet('fillHue', 284.0)
            ->assertSet('fillChroma', 0.02)
            ->assertNotSet('tokens.scheme.light.zinc.50', '#fafafa');
    }

    public function test_editing_a_dark_slot_under_auto_promotes_to_manual(): void
    {
        $tokens = ThemeTokens::defaults();
        $tokens['darkMode'] = 'auto';
        $tokens['scheme']['dark'] = null;

        $component = Livewire::test('pages::theme-generator')
            ->set('tokens', $tokens)
            ->call('setDarkSlot', 'accent', '#123456')
            ->assertSet('tokens.darkMode', 'manual')
            ->assertSet('tokens.scheme.dark.accent', '#123456');

        $this->assertNotEmpty($component->get('tokens.scheme.dark.zinc.50'));
        $this->assertNotEmpty($component->get('tokens.scheme.dark.accentForeground'));
    }

    public function test_advanced_variables_panel_lazy_renders_on_demand(): void
    {
        Livewire::test('pages::theme-generator')
            ->assertSet('showVariables', false)
            ->assertDontSee(':root (light)')
            ->set('showVariables', true)
            ->assertSee(':root (light)')
            ->assertSee('--color-accent');
    }

    public function test_importing_the_daisyui_silk_link_maps_tokens_and_reports_drops(): void
    {
        Livewire::test('pages::theme-generator')
            ->set('importPayload', 'https://daisyui.com/theme-generator/#theme='.self::SILK_CODE)
            ->call('importTheme')
            ->assertSet('tokens.scheme.light.accent', '#1c1c29')
            ->assertSet('tokens.scheme.light.accentForeground', '#e2fd00')
            ->assertSet('tokens.scheme.light.zinc.50', '#f7f5f3')
            ->assertSet('tokens.radius.selector', 2.0);

        $component = Livewire::test('pages::theme-generator')
            ->set('importPayload', self::SILK_CODE)
            ->call('importTheme');

        /** @var list<string> $report */
        $report = $component->get('importReport');
        $this->assertContains('--color-secondary', $report);
        $this->assertContains('--border', $report);
    }

    public function test_importing_garbage_leaves_state_unchanged(): void
    {
        Livewire::test('pages::theme-generator')
            ->set('importPayload', 'definitely not a theme')
            ->call('importTheme')
            ->assertSet('tokens.scheme.light.accent', '#262626')
            ->assertSet('importReport', []);
    }

    public function test_visitor_sessions_do_not_leak_working_theme_between_requests(): void
    {
        // Visitor A customizes a theme in Livewire
        Livewire::test('pages::theme-generator')
            ->call('loadPreset', 'cupcake')
            ->set('tokens.scheme.light.accent', '#e11d48');

        // Visitor B opening / fresh gets clean Flux default tokens, not Visitor A's edits
        Livewire::test('pages::theme-generator')
            ->assertSet('name', 'Flux default')
            ->assertSet('tokens.scheme.light.accent', '#262626');
    }

    public function test_share_code_in_url_hydrates_tokens_on_mount(): void
    {
        $customTokens = ThemeTokens::fromArray(Presets::tokens('rose'));
        $code = ThemeCodec::encode($customTokens);

        Livewire::withQueryParams(['theme' => $code])
            ->test('pages::theme-generator')
            ->assertSet('tokens.scheme.light.accent', '#e11d48');
    }

    public function test_legacy_v1_share_code_in_url_upcasts_on_mount(): void
    {
        $legacyPayload = array_replace(LegacyThemeTokens::defaults(), [
            'accent' => ['light' => '#e11d48', 'dark' => '#fb7185'],
            'accentForeground' => ['light' => null, 'dark' => null],
        ]);

        $code = rtrim(strtr(base64_encode((string) gzdeflate((string) json_encode($legacyPayload), 9)), '+/', '-_'), '=');

        Livewire::withQueryParams(['theme' => $code])
            ->test('pages::theme-generator')
            ->assertSet('tokens.scheme.light.accent', '#e11d48')
            ->assertSet('tokens.darkMode', 'manual');
    }

    public function test_artisan_export_command_outputs_preset_and_share_code_without_database(): void
    {
        $kernel = $this->app->make(Kernel::class);

        $status = $kernel->call('theme:export');
        $this->assertSame(0, $status);
        $output = $kernel->output();
        $this->assertStringContainsString('@theme {', $output);
        $this->assertStringContainsString('--color-accent: #262626;', $output);

        $statusJson = $kernel->call('theme:export', ['theme' => 'ocean', '--json' => true]);
        $this->assertSame(0, $statusJson);
        $this->assertStringContainsString('"ibm-plex-sans"', $kernel->output());

        $roseCode = ThemeCodec::encode(ThemeTokens::fromArray(Presets::tokens('rose')));
        $statusCode = $kernel->call('theme:export', ['--code' => $roseCode, '--name' => 'Rose Custom']);
        $this->assertSame(0, $statusCode);
        $codeOutput = $kernel->output();
        $this->assertStringContainsString('Theme: Rose Custom', $codeOutput);
        $this->assertStringContainsString('--color-accent: #e11d48;', $codeOutput);
    }

    public function test_changing_editor_scheme_synchronizes_preview_settings_and_ramps(): void
    {
        Livewire::test('pages::theme-generator')
            ->call('loadPreset', 'indigo')
            ->assertSet('editorScheme', 'light')
            ->assertSet('previewDark', false)
            ->assertSee('The brand colour trio for the light scheme.')
            ->assertSee('Light Preview')
            ->set('editorScheme', 'dark')
            ->assertSet('previewDark', true)
            ->assertSee('The brand colour trio for the dark scheme.')
            ->assertSee('Dark Preview');
    }

    public function test_toggling_preview_scheme_synchronizes_editor_settings(): void
    {
        Livewire::test('pages::theme-generator')
            ->assertSet('editorScheme', 'light')
            ->assertSet('previewDark', false)
            ->call('toggleScheme')
            ->assertSet('editorScheme', 'dark')
            ->assertSet('previewDark', true)
            ->assertSee('The brand colour trio for the dark scheme.')
            ->set('previewDark', false)
            ->assertSet('editorScheme', 'light')
            ->assertSee('The brand colour trio for the light scheme.');
    }

    public function test_global_appearance_and_local_storage_wiring_is_rendered(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('x-model="$flux.appearance"', false);
        $response->assertSee('$watch(\'$flux.dark\'', false);
        $response->assertSee('$wire.$watch(\'editorScheme\'', false);
        $response->assertSee('flux_themes_saved_v1', false);
    }

    public function test_header_brand_and_top_card_consolidate_theme_controls(): void
    {
        $html = (string) $this->get('/')
            ->assertOk()
            ->getContent();

        $this->assertMatchesRegularExpression('/data-flux-header[^>]*>[\s\S]*?Flux Themes/i', $html);
        $this->assertMatchesRegularExpression('/data-flux-navbar[^>]*>[\s\S]*?Theme Generator/i', $html);
        $this->assertStringContainsString('Theme Name', $html);
        $this->assertStringContainsString('Save Theme', $html);
        $this->assertStringContainsString('Presets', $html);
        $this->assertStringContainsString('Saved Themes', $html);
    }

    public function test_windowed_live_preview_renders_shell_and_three_layout_tabs(): void
    {
        $component = Livewire::test('pages::theme-generator')
            ->assertSet('previewTab', 'dashboard')
            ->assertSee('Component Preview')
            ->assertSee('Acme Inc.')
            ->assertSee('Total Revenue')
            ->assertSee('Create your account')
            ->assertSee('Delete workspace');

        $html = $component->html();
        $this->assertMatchesRegularExpression('/data-theme-preview[\s\S]*?data-flux-sidebar[\s\S]*?data-flux-header/i', $html);
        $this->assertMatchesRegularExpression('/<ui-tabs[^>]*wire:model="previewTab"[^>]*data-flux-tabs>/i', $html);

        $this->get('/')
            ->assertOk()
            ->assertSee('min-h-screen bg-white dark:bg-zinc-950', false);

        $component
            ->set('previewTab', 'register')
            ->set('tokens.scheme.light.accent', '#0d9488')
            ->assertSet('previewTab', 'register')
            ->assertSee('Create Account')
            ->set('previewTab', 'settings')
            ->assertSee('Workspace Settings');
    }
}
