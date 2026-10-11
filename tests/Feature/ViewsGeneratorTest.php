<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ViewsGeneratorTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->user = User::factory()->create();
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $response = $this->get(route('views.generator'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_visit_views_generator(): void
    {
        $response = $this->actingAs($this->user)->get(route('views.generator'));

        $response->assertOk();
        $response->assertSee('Views Generator');
        $response->assertSee('SaaS Dashboard');
    }

    public function test_workspace_switcher_renders_in_sidebar(): void
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Design Lab');
        $response->assertSee(route('views.generator'));

        $generatorResponse = $this->actingAs($this->user)->get(route('views.generator'));
        $generatorResponse->assertOk();
        $generatorResponse->assertSee('Views Generator');
        $generatorResponse->assertSee(route('dashboard'));
    }

    public function test_views_generator_renders_initial_default_state(): void
    {
        Livewire::actingAs($this->user)
            ->test('pages::views-generator')
            ->assertSet('layout', 'saas-dashboard')
            ->assertSet('shellStyle', 'minimal')
            ->assertSet('headerComponent', 'stats')
            ->assertSet('mainComponent', 'charts-and-table')
            ->assertSet('secondaryComponent', 'activity')
            ->assertSee('Total Revenue')
            ->assertSee('Revenue Performance')
            ->assertSee('Recent Transactions');
    }

    public function test_can_switch_layouts_via_action(): void
    {
        // Switch to Data CRUD
        Livewire::actingAs($this->user)
            ->test('pages::views-generator')
            ->call('selectLayout', 'data-crud')
            ->assertSet('layout', 'data-crud')
            ->assertSet('headerComponent', 'search-actions')
            ->assertSet('mainComponent', 'detailed-table')
            ->assertSee('Organization Members')
            ->assertSee('Sarah Connor');

        // Switch to Kanban
        Livewire::actingAs($this->user)
            ->test('pages::views-generator')
            ->call('selectLayout', 'kanban')
            ->assertSet('layout', 'kanban')
            ->assertSet('mainComponent', 'kanban-board')
            ->assertSee('Backlog')
            ->assertSee('Audit design tokens accessibility');

        // Switch to Settings
        Livewire::actingAs($this->user)
            ->test('pages::views-generator')
            ->call('selectLayout', 'settings')
            ->assertSet('layout', 'settings')
            ->assertSet('mainComponent', 'settings-form')
            ->assertSee('Profile Information')
            ->assertSee('Notification Preferences');
    }

    public function test_can_change_component_dropdowns(): void
    {
        Livewire::actingAs($this->user)
            ->test('pages::views-generator')
            // Change header
            ->call('selectHeaderComponent', 'announcement')
            ->assertSet('headerComponent', 'announcement')
            ->assertSee('Welcome back, Alex!')
            // Change main to chart-grid
            ->call('selectMainComponent', 'chart-grid')
            ->assertSet('mainComponent', 'chart-grid')
            ->assertSee('Daily Visitors')
            // Change secondary to schedule
            ->call('selectSecondaryComponent', 'calendar-quick')
            ->assertSet('secondaryComponent', 'calendar-quick')
            ->assertSee("Today's Schedule")
            // Change shell style
            ->call('selectShellStyle', 'header')
            ->assertSet('shellStyle', 'header');
    }

    public function test_viewport_switching_and_dark_mode(): void
    {
        Livewire::actingAs($this->user)
            ->test('pages::views-generator')
            ->call('selectViewport', 'mobile')
            ->assertSet('viewport', 'mobile')
            ->call('selectViewport', 'tablet')
            ->assertSet('viewport', 'tablet')
            ->call('selectViewport', 'desktop')
            ->assertSet('viewport', 'desktop')
            ->call('togglePreviewDark')
            ->assertSet('previewDark', true)
            ->call('togglePreviewDark')
            ->assertSet('previewDark', false);
    }

    public function test_randomize_and_reset_to_defaults(): void
    {
        $test = Livewire::actingAs($this->user)
            ->test('pages::views-generator');

        $test->call('randomize');
        $this->assertNotEmpty($test->get('layout'));

        $test->call('resetToDefaults')
            ->assertSet('layout', 'saas-dashboard')
            ->assertSet('shellStyle', 'minimal')
            ->assertSet('viewport', 'desktop')
            ->assertSet('previewDark', false);
    }

    public function test_code_export_modal_generation(): void
    {
        Livewire::actingAs($this->user)
            ->test('pages::views-generator')
            ->assertSet('showCodeModal', false)
            ->call('toggleCodeModal')
            ->assertSet('showCodeModal', true)
            ->assertSee('Generated View Blade Template')
            ->assertSee('<x-layouts::app');
    }

    public function test_can_switch_to_basketball_player_layout(): void
    {
        Livewire::actingAs($this->user)
            ->test('pages::views-generator')
            ->call('selectLayout', 'basketball-player')
            ->assertSet('layout', 'basketball-player')
            ->assertSet('headerComponent', 'player-hero')
            ->assertSet('mainComponent', 'player-overview')
            ->assertSet('secondaryComponent', 'player-courts')
            ->assertSee("Marcus 'Viper' Vance")
            ->assertSee('@marcusvance')
            ->assertSee('Point Guard (PG)')
            ->assertSee('Shooting Guard (SG)')
            ->assertSee('Eastside Ballers')
            ->assertSee("6'5\"")
            ->assertSee('215 lbs')
            ->assertSee("7'0\"")
            ->assertSee('Photo Reel')
            ->assertSee('The Cage (West 4th St)')
            ->assertSee('Venice Beach Courts');
    }

    public function test_can_switch_basketball_player_main_component(): void
    {
        Livewire::actingAs($this->user)
            ->test('pages::views-generator')
            ->call('selectLayout', 'basketball-player')
            ->call('selectMainComponent', 'player-gallery')
            ->assertSet('mainComponent', 'player-gallery')
            ->assertSee('Player Action Gallery')
            ->assertSee('Favourite Courts Directory')
            ->assertSee('Fastbreak Transition Dunk')
            ->assertSee('Holcombe Rucker Park');
    }

    public function test_responsive_viewport_chrome_rendering(): void
    {
        Livewire::actingAs($this->user)
            ->test('pages::views-generator')
            ->assertSet('viewport', 'desktop')
            ->assertSet('shellStyle', 'minimal')
            ->assertDontSeeHtml('data-test="simulated-sidebar"')
            ->assertDontSeeHtml('data-test="simulated-header"')
            ->call('selectShellStyle', 'header')
            ->assertSet('shellStyle', 'header')
            ->assertSeeHtml('data-test="simulated-header"')
            ->assertDontSeeHtml('data-test="simulated-sidebar"')
            ->call('selectViewport', 'mobile')
            ->assertSet('viewport', 'mobile')
            ->assertSeeHtml('data-test="simulated-header"')
            ->assertDontSeeHtml('data-test="simulated-sidebar"')
            ->call('selectViewport', 'tablet')
            ->assertSet('viewport', 'tablet')
            ->assertSeeHtml('data-test="simulated-header"')
            ->assertDontSeeHtml('data-test="simulated-sidebar"');
    }
}
