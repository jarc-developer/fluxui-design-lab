<?php

namespace Tests\Feature;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    /**
     * @return array<string, array{0: string}>
     */
    public static function removedModuleRoutes(): array
    {
        return [
            'forms' => ['/components/forms'],
            'actions' => ['/components/actions'],
            'data' => ['/components/data'],
            'overlays' => ['/components/overlays'],
            'editor' => ['/components/editor'],
            'composer' => ['/components/composer'],
            'kanban' => ['/components/kanban'],
            'carousel' => ['/components/carousel'],
            'phone' => ['/components/phone'],
            'calendar' => ['/components/calendar'],
            'charts' => ['/components/charts'],
            'theme' => ['/theme'],
            'layouts' => ['/layouts'],
        ];
    }

    public function test_landing_page_renders_flux_themes_sections_and_live_export(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Flux Themes');
        $response->assertSee('Craft expressive themes for');
        $response->assertSee('Start Customizing');
        $response->assertSee('id="generator"', false);
        $response->assertSee('id="features"', false);
        $response->assertSee('id="quickstart"', false);
        $response->assertSee('Everything Flux UI reads. Zero CSS guesswork.');
        $response->assertSee('Drop into any Flux UI app in 30 seconds.');
        $response->assertSee('@theme {', false);
    }

    public function test_outer_application_shell_has_no_sidebar(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $dom = new DOMDocument;
        $dom->loadHTML((string) $html, LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new DOMXPath($dom);

        $sidebars = $xpath->query('//*[@data-flux-sidebar]');

        // The only sidebar in the document belongs to the embedded Component Preview window.
        $this->assertNotFalse($sidebars);
        $this->assertSame(1, $sidebars->length);

        $sidebar = $sidebars->item(0);
        $this->assertInstanceOf(DOMNode::class, $sidebar);
        $this->assertTrue(
            $this->hasPreviewAncestor($sidebar),
            'Found a <flux:sidebar> outside [data-theme-preview]; the outer app sidebar should be removed.',
        );
    }

    public function test_hero_preset_strip_loads_theme_and_updates_quickstart_snippet(): void
    {
        Livewire::test('pages::theme-generator')
            ->assertSee('data-hero-preset="emerald"', false)
            ->call('loadPreset', 'emerald')
            ->assertSet('name', 'Emerald')
            ->assertSee('resources/css/app.css — Emerald')
            ->assertSee('--color-accent: #059669;', false);
    }

    #[DataProvider('removedModuleRoutes')]
    public function test_removed_sidebar_module_routes_return_not_found(string $path): void
    {
        $this->get($path)->assertNotFound();
    }

    private function hasPreviewAncestor(DOMNode $node): bool
    {
        for ($parent = $node->parentNode; $parent !== null; $parent = $parent->parentNode) {
            if ($parent instanceof DOMElement && $parent->hasAttribute('data-theme-preview')) {
                return true;
            }
        }

        return false;
    }
}
