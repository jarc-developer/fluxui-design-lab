<?php

namespace Tests\Feature;

use App\Models\Theme;
use App\Support\Theme\ActiveTheme;
use App\Support\Theme\Legacy\LegacyThemeTokens;
use App\Support\Theme\Presets;
use App\Support\Theme\ThemeCodec;
use App\Support\Theme\ThemeTokens;
use Database\Seeders\ThemeSeeder;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ThemeGeneratorTest extends TestCase
{
    use RefreshDatabase;

    private const SILK_CODE = 'eJyVlOuOmzAQRl_FslSplYLlKxjexjHDxopjRzZRd7fqu1dAE9g4TdSfzJxvjjUYfuFgToA7nJ0_4h220cdUZXuAuerd22HEO1xVS2NvMlSMUtzhePT28L1tviFKKBUK1Q1p9I97mG9htcCaoZoRyQtYbGH6ArYxjBDGW0A-CZyTO5n0cWO5IHw5OZct4loS8QAvFK0knE8xrqhCjDVEym0ug42h_w_RLVCoGkHaRcWEQoqS9ovJWLulX2gWunBofXXQmiGmW9JuUwEuYzJ-lUwLRvQBUk5-8i5cGOIGJKKdWMakRlwyUut7uJguXoTyxVrIeZVcd0lbyhATNdHNA77w8Be5nyYFF95KD6NaIU0fsOWtfZKBlGJab4QibAY1k4hzIpqCLTf1j0wyvbvkKoMHO84OnuC0bQ0OfI87TIm66-zjO-4wu1az-4TtoOnj-NJbJ62NfUw9zNrz-1zo4TweprHzU4guT78fine4h8Fc_Ii7wfgMO3xOMEDKvUnHv7XffwCcjFZ9';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ThemeSeeder::class);
    }

    public function test_theme_generator_is_publicly_accessible(): void
    {
        $response = $this->get('/');

        $response->assertOk();
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

        // Accent trio, zinc steps and status seeds all edit through the
        // official Flux color picker — no native color inputs remain.
        $this->assertStringContainsString('data-flux-color-picker', $html);
        $this->assertStringNotContainsString('type="color"', $html);
    }

    public function test_custom_accent_foreground_is_stored_and_rendered(): void
    {
        Livewire::test('pages::theme-generator')
            ->set('tokens.scheme.light.accent', '#1c1c29')
            ->set('tokens.scheme.light.accentForeground', '#e2fd00')
            ->assertSeeHtml('--color-accent-foreground:#e2fd00')
            ->set('name', 'Silkish')
            ->call('saveAs');

        $theme = Theme::query()->where('slug', 'silkish')->firstOrFail();
        $this->assertSame('#e2fd00', $theme->tokens->light->accentForeground);
    }

    public function test_pill_radius_renders_in_preview(): void
    {
        Livewire::test('pages::theme-generator')
            ->set('tokens.radius.selector', 2)
            ->assertSeeHtml('--radius-selector:2rem');
    }

    public function test_fill_ramp_writes_all_eleven_zinc_steps(): void
    {
        Livewire::test('pages::theme-generator')
            ->set('fillHue', 284)
            ->set('fillChroma', 0.02)
            ->call('fillRamp', 'light')
            ->assertNotSet('tokens.scheme.light.zinc.50', '#fafafa');
    }

    public function test_editing_a_dark_slot_under_auto_promotes_to_manual(): void
    {
        $tokens = ThemeTokens::defaults();
        $tokens['darkMode'] = 'auto';
        $tokens['scheme']['dark'] = null;

        Livewire::test('pages::theme-generator')
            ->set('tokens', $tokens)
            ->call('setDarkSlot', 'accent', '#123456')
            ->assertSet('tokens.darkMode', 'manual')
            ->assertSet('tokens.scheme.dark.accent', '#123456');

        // The rest of the dark scheme was materialized, not left null.
        $component = Livewire::test('pages::theme-generator')
            ->set('tokens', $tokens)
            ->call('setDarkSlot', 'accent', '#123456');

        $this->assertNotEmpty($component->get('tokens.scheme.dark.zinc.50'));
        $this->assertNotEmpty($component->get('tokens.scheme.dark.accentForeground'));
    }

    public function test_manual_dark_edits_persist_through_save_and_load(): void
    {
        $tokens = ThemeTokens::defaults();
        $tokens['darkMode'] = 'auto';
        $tokens['scheme']['dark'] = null;

        $component = Livewire::test('pages::theme-generator')
            ->set('tokens', $tokens)
            ->call('setDarkSlot', 'zinc.50', '#101010')
            ->set('name', 'Dark Tweaked')
            ->call('saveAs');

        $theme = Theme::query()->where('slug', 'dark-tweaked')->firstOrFail();

        Livewire::test('pages::theme-generator')
            ->call('loadTheme', $theme->id)
            ->assertSet('tokens.darkMode', 'manual')
            ->assertSet('tokens.scheme.dark.zinc.50', '#101010');
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

    public function test_activating_a_theme_updates_global_head_styles_across_the_app(): void
    {
        // Initially Flux default is active
        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('<style id="lab-theme">', false);
        $response->assertSee('--color-accent:#262626', false);

        // Activate Emerald theme
        $emerald = Theme::query()->where('slug', 'emerald')->firstOrFail();
        $emerald->activate();

        // The root page now serves the Emerald accent
        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('<style id="lab-theme">', false);
        $response->assertSee('--color-accent:#059669', false);

        // Catalog pages reflect the active theme too
        $catalogResponse = $this->get('/components/forms');
        $catalogResponse->assertOk();
        $catalogResponse->assertSee('<style id="lab-theme">', false);
        $catalogResponse->assertSee('--color-accent:#059669', false);
    }

    public function test_save_as_creates_a_new_theme_record(): void
    {
        Livewire::test('pages::theme-generator')
            ->set('name', 'Sunset Vibe')
            ->set('tokens.scheme.light.accent', '#f97316')
            ->call('saveAs');

        $this->assertDatabaseHas('themes', [
            'name' => 'Sunset Vibe',
            'slug' => 'sunset-vibe',
        ]);
    }

    public function test_cannot_delete_active_theme(): void
    {
        $activeTheme = Theme::query()->active()->firstOrFail();

        Livewire::test('pages::theme-generator')
            ->call('deleteTheme', $activeTheme->id);

        $this->assertDatabaseHas('themes', ['id' => $activeTheme->id]);
    }

    public function test_can_delete_non_active_theme(): void
    {
        $inactive = Theme::query()->where('is_active', false)->firstOrFail();
        $id = $inactive->id;

        Livewire::test('pages::theme-generator')
            ->call('deleteTheme', $id);

        $this->assertDatabaseMissing('themes', ['id' => $id]);
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

    public function test_artisan_export_command_outputs_tailwind_css(): void
    {
        $kernel = $this->app->make(Kernel::class);

        $status = $kernel->call('theme:export');
        $this->assertSame(0, $status);
        $output = $kernel->output();
        $this->assertStringContainsString('@theme {', $output);
        $this->assertStringContainsString('--color-accent: #262626;', $output);

        $statusJson = $kernel->call('theme:export', ['theme' => 'ocean', '--json' => true]);
        $this->assertSame(0, $statusJson);
        $outputJson = $kernel->output();
        $this->assertStringContainsString('"ibm-plex-sans"', $outputJson);
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

    public function test_global_appearance_and_generator_scheme_sync_wiring_is_rendered(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('x-model="$flux.appearance"', false);
        $response->assertSee('$watch(\'$flux.dark\'', false);
        $response->assertSee('$wire.$watch(\'editorScheme\'', false);
    }

    public function test_theme_changes_apply_live_across_all_platform_pages(): void
    {
        // Edit tokens and load a preset without clicking Activate Globally
        Livewire::test('pages::theme-generator')
            ->call('loadPreset', 'cupcake')
            ->set('tokens.scheme.light.accent', '#e11d48')
            ->assertSeeHtml(':root{--color-accent:#e11d48');

        // Catalog and foundation pages immediately reflect the live theme changes
        $this->get('/components/forms')
            ->assertOk()
            ->assertSee('<style id="lab-theme">', false)
            ->assertSee('--color-accent:#e11d48', false)
            ->assertSee('nunito', false);

        $this->get('/components/actions')
            ->assertOk()
            ->assertSee('--color-accent:#e11d48', false);

        $this->get('/theme')
            ->assertOk()
            ->assertSee('#e11d48');

        // Navigating back to the generator restores the live working theme
        Livewire::test('pages::theme-generator')
            ->assertSet('name', 'Cupcake')
            ->assertSet('tokens.scheme.light.accent', '#e11d48');
    }

    public function test_activating_globally_persists_live_theme_to_database(): void
    {
        Livewire::test('pages::theme-generator')
            ->set('name', 'Platform Live')
            ->set('tokens.scheme.light.accent', '#7c3aed')
            ->call('activate');

        $active = Theme::query()->active()->firstOrFail();
        $this->assertSame('Platform Live', $active->name);
        $this->assertSame('#7c3aed', $active->tokens->light->accent);
        $this->assertFalse(app(ActiveTheme::class)->hasWorking());
    }

    public function test_title_renders_in_navbar_and_top_card_consolidates_theme_controls(): void
    {
        $html = $this->get('/')
            ->assertOk()
            ->getContent();

        $this->assertMatchesRegularExpression('/data-flux-navbar[^>]*>[\s\S]*?Theme Generator/i', $html);
        $this->assertStringContainsString('Theme Name', $html);
        $this->assertStringContainsString('Presets', $html);
        $this->assertStringContainsString('Saved Themes', $html);
        $this->assertStringNotContainsString('Every colour Flux reads, editable per scheme.', $html);
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

        // Principal platform body and preview share the same dark:bg-zinc-950 canvas
        $this->get('/')
            ->assertOk()
            ->assertSee('min-h-screen bg-white dark:bg-zinc-950', false);

        // Switching tabs persists across Livewire token updates
        $component
            ->set('previewTab', 'register')
            ->set('tokens.scheme.light.accent', '#0d9488')
            ->assertSet('previewTab', 'register')
            ->assertSee('Create Account')
            ->set('previewTab', 'settings')
            ->assertSee('Workspace Settings');
    }
}
