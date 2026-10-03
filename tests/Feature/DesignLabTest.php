<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DesignLabTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The catalog pages that make up the lab.
     *
     * @return array<string, array{0: string}>
     */
    public static function labPages(): array
    {
        return [
            'forms' => ['/components/forms'],
            'actions' => ['/components/actions'],
            'data' => ['/components/data'],
            'overlays' => ['/components/overlays'],
            'theme' => ['/theme'],
            'layouts' => ['/layouts'],
        ];
    }

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/components/forms')->assertRedirect('/login');
    }

    public function test_root_sends_guests_to_the_login_page(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    public function test_root_sends_authenticated_users_to_the_dashboard(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/')
            ->assertRedirect(route('dashboard'));
    }

    #[DataProvider('labPages')]
    public function test_catalog_pages_render_for_authenticated_users(string $path): void
    {
        $this->actingAs(User::factory()->create())
            ->get($path)
            ->assertOk();
    }

    #[DataProvider('labPages')]
    public function test_catalog_pages_emit_livewire_and_flux_assets(string $path): void
    {
        $response = $this->actingAs(User::factory()->create())->get($path);

        $response->assertSee('flux', false);
        $response->assertSee('data-flux-', false);
    }

    public function test_forms_page_renders_interactive_inputs(): void
    {
        $html = $this->actingAs(User::factory()->create())
            ->get('/components/forms')
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('data-flux-input', $html);
        $this->assertStringContainsString('data-flux-select', $html);
        $this->assertStringContainsString('data-flux-date-picker', $html);
    }

    public function test_overlays_page_renders_a_modal(): void
    {
        $html = $this->actingAs(User::factory()->create())
            ->get('/components/overlays')
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('data-flux-modal', $html);
    }

    public function test_data_page_live_filters_the_table(): void
    {
        Livewire::test('pages::components.data')
            ->set('search', 'flux:button')
            ->assertSee('flux:button')
            ->assertDontSee('flux:kanban');
    }

    public function test_seeded_roles_are_available(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->assertTrue($admin->hasRole('admin'));
        $this->assertTrue($admin->can('manage theme'));
        $this->assertFalse($admin->can('does-not-exist'));
    }
}
