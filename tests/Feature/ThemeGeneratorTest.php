<?php

namespace Tests\Feature;

use App\Models\Theme;
use App\Models\User;
use App\Support\Theme\Presets;
use App\Support\Theme\ThemeCodec;
use App\Support\Theme\ThemeTokens;
use Database\Seeders\RoleSeeder;
use Database\Seeders\ThemeSeeder;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ThemeGeneratorTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(ThemeSeeder::class);

        $this->user = User::factory()->create();
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $response = $this->get('/theme/generator');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_users_can_view_the_theme_generator(): void
    {
        $response = $this->actingAs($this->user)->get('/theme/generator');

        $response->assertOk();
        $response->assertSee('Theme Generator');
        $response->assertSee('Live Component Preview');
    }

    public function test_livewire_component_updates_preview_css_on_token_change(): void
    {
        Livewire::actingAs($this->user)
            ->test('pages::theme-generator')
            ->assertSet('tokens.accent.light', '#262626')
            ->set('tokens.accent.light', '#e11d48')
            ->assertSeeHtml('--color-accent:#e11d48');
    }

    public function test_activating_a_theme_updates_global_head_styles_across_the_app(): void
    {
        // Initially Flux default is active
        $response = $this->actingAs($this->user)->get('/dashboard');
        $response->assertOk();
        $response->assertSee('<style id="lab-theme">', false);
        $response->assertSee('--color-accent:#262626', false);

        // Activate Emerald theme
        $emerald = Theme::query()->where('slug', 'emerald')->firstOrFail();
        $emerald->activate();

        // Check that dashboard now serves Emerald accent
        $response = $this->actingAs($this->user)->get('/dashboard');
        $response->assertOk();
        $response->assertSee('<style id="lab-theme">', false);
        $response->assertSee('--color-accent:#059669', false);

        // Check that auth screens (login) also reflect active theme
        $this->post('/logout');
        $loginResponse = $this->get('/login');
        $loginResponse->assertOk();
        $loginResponse->assertSee('<style id="lab-theme">', false);
        $loginResponse->assertSee('--color-accent:#059669', false);
    }

    public function test_save_as_creates_a_new_theme_record(): void
    {
        Livewire::actingAs($this->user)
            ->test('pages::theme-generator')
            ->set('name', 'Sunset Vibe')
            ->set('tokens.accent.light', '#f97316')
            ->call('saveAs');

        $this->assertDatabaseHas('themes', [
            'name' => 'Sunset Vibe',
            'slug' => 'sunset-vibe',
        ]);
    }

    public function test_cannot_delete_active_theme(): void
    {
        $activeTheme = Theme::query()->active()->firstOrFail();

        Livewire::actingAs($this->user)
            ->test('pages::theme-generator')
            ->call('deleteTheme', $activeTheme->id);

        $this->assertDatabaseHas('themes', ['id' => $activeTheme->id]);
    }

    public function test_can_delete_non_active_theme(): void
    {
        $inactive = Theme::query()->where('is_active', false)->firstOrFail();
        $id = $inactive->id;

        Livewire::actingAs($this->user)
            ->test('pages::theme-generator')
            ->call('deleteTheme', $id);

        $this->assertDatabaseMissing('themes', ['id' => $id]);
    }

    public function test_share_code_in_url_hydrates_tokens_on_mount(): void
    {
        $customTokens = ThemeTokens::fromArray(Presets::tokens('rose'));
        $code = ThemeCodec::encode($customTokens);

        Livewire::withQueryParams(['theme' => $code])
            ->actingAs($this->user)
            ->test('pages::theme-generator')
            ->assertSet('tokens.accent.light', '#e11d48');
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
}
