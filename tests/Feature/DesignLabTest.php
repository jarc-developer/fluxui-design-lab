<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DesignLabTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Output buffer level at the start of the test.
     *
     * flux:editor and flux:phone end with an @assets block, and that block leaves
     * an output buffer open, which PHPUnit reports as a risky test. Track the level
     * so tearDown() can close anything the test itself leaked.
     */
    private int $bufferLevel = 0;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bufferLevel = ob_get_level();
    }

    protected function tearDown(): void
    {
        while (ob_get_level() > $this->bufferLevel) {
            ob_end_clean();
        }

        parent::tearDown();
    }

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
            'editor' => ['/components/editor'],
            'composer' => ['/components/composer'],
            'kanban' => ['/components/kanban'],
            'carousel' => ['/components/carousel'],
            'phone' => ['/components/phone'],
            'calendar' => ['/components/calendar'],
            'charts' => ['/components/charts'],
        ];
    }

    /**
     * Route path to Livewire component name.
     *
     * Blaze defers pages whose content is built entirely from Blaze components
     * (flux:editor and flux:phone), so the initial server HTML for those routes is
     * only the shell. Content assertions therefore render the component directly.
     *
     * @return array<string, array{0: string}>
     */
    public static function labComponents(): array
    {
        return [
            'forms' => ['pages::components.forms'],
            'actions' => ['pages::components.actions'],
            'data' => ['pages::components.data'],
            'overlays' => ['pages::components.overlays'],
            'theme' => ['pages::theme'],
            'layouts' => ['pages::layouts'],
            'editor' => ['pages::components.editor'],
            'composer' => ['pages::components.composer'],
            'kanban' => ['pages::components.kanban'],
            'carousel' => ['pages::components.carousel'],
            'phone' => ['pages::components.phone'],
            'calendar' => ['pages::components.calendar'],
            'charts' => ['pages::components.charts'],
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

    /**
     * Each Flux Pro page has to prove its root component actually rendered.
     *
     * @return array<string, array{0: string, 1: string}>
     */
    public static function proPageMarkers(): array
    {
        return [
            'editor' => ['pages::components.editor', 'data-flux-editor'],
            'composer' => ['pages::components.composer', 'data-flux-composer'],
            'kanban' => ['pages::components.kanban', 'data-flux-kanban'],
            'carousel' => ['pages::components.carousel', 'data-flux-carousel'],
            'phone' => ['pages::components.phone', 'data-flux-phone'],
            'calendar' => ['pages::components.calendar', 'data-flux-calendar'],
        ];
    }

    #[DataProvider('proPageMarkers')]
    public function test_pro_pages_render_their_root_component(string $component, string $marker): void
    {
        $this->actingAs(User::factory()->create());

        $this->assertStringContainsString($marker, Livewire::test($component)->html());
    }

    public function test_charts_page_renders_every_series_and_axis_type(): void
    {
        $this->actingAs(User::factory()->create());

        $html = Livewire::test('pages::components.charts')->html();

        // Chart components carry no data-flux-* attributes, so the <template
        // name="..."> elements each one renders are the only stable handles.
        foreach ([
            'line', 'area', 'bar', 'pie', 'point', 'group', 'stack', 'cursor',
            'zero-line', 'grid-line', 'axis-line', 'tick-label', 'tick-mark',
            'summary', 'tooltip',
        ] as $series) {
            $this->assertStringContainsString('<template name="'.$series.'"', $html, "Missing the {$series} chart template.");
        }
    }

    public function test_catalog_covers_the_free_components_that_were_missing(): void
    {
        $markers = [
            'pages::components.forms' => [
                'data-flux-input-group',
                'data-flux-checkbox-group-buttons',
                'data-flux-checkbox-group-cards',
                'data-flux-checkbox-group-pills',
                'data-flux-radio-group-segmented',
                'data-flux-radio-group-cards',
                'data-flux-radio-group-pills',
                'data-flux-otp-input',
                'data-flux-fieldset',
                'data-flux-file-upload-dropzone',
                'data-flux-file-item',
            ],
            'pages::components.actions' => [
                'data-flux-navbar',
                'data-flux-navmenu',
                'data-flux-breadcrumbs',
                'data-flux-breadcrumbs-item',
                'data-flux-button-group',
                'data-flux-context',
                'data-flux-popover',
                'data-flux-command-input',
                'data-flux-badge-close',
            ],
            'pages::components.data' => [
                'data-flux-card-header',
                'data-flux-card-body',
                'data-flux-card-footer',
                'data-flux-card-actions',
                'data-flux-card-bleed',
                'data-flux-card-subheading',
                'data-flux-table-sortable',
                'data-flux-timeline-horizontal',
                'data-flux-timeline-block',
                'data-flux-timeline-subgrid',
                'data-flux-navlist-badge',
                'data-flux-navlist-group',
                'data-flux-skeleton-group',
            ],
            'pages::components.overlays' => [
                'data-flux-modal-trigger',
                'data-flux-modal-close',
            ],
            'pages::layouts' => [
                'data-flux-container',
                'data-flux-aside',
                'data-flux-header',
                'data-flux-footer',
                'data-flux-brand',
            ],
        ];

        $this->actingAs(User::factory()->create());

        foreach ($markers as $component => $expected) {
            $html = Livewire::test($component)->html();

            foreach ($expected as $marker) {
                $this->assertStringContainsString($marker, $html, "{$component} is missing {$marker}.");
            }
        }
    }

    public function test_forms_page_covers_the_pro_select_variants(): void
    {
        $this->actingAs(User::factory()->create());

        $html = Livewire::test('pages::components.forms')->html();

        foreach ([
            'data-flux-select-button',
            'data-flux-select-search',
            'data-flux-listbox-options',
            'data-flux-listbox-option',
            'data-flux-listbox-empty',
            'data-flux-option-group',
            'data-flux-option-create',
        ] as $marker) {
            $this->assertStringContainsString($marker, $html, "The forms page is missing {$marker}.");
        }
    }

    #[DataProvider('labComponents')]
    public function test_every_catalog_page_renders_at_least_one_copyable_snippet(string $component): void
    {
        $this->actingAs(User::factory()->create());

        $html = Livewire::test($component)->html();

        $this->assertStringContainsString('data-lab-example', $html);
        $this->assertStringContainsString('navigator.clipboard.writeText', $html);
        $this->assertMatchesRegularExpression(
            '/data-lab-example.*?<pre[^>]*>\s*<code>\s*\S/s',
            $html,
            "{$component} rendered an example wrapper with an empty snippet block.",
        );
    }

    public function test_every_referenced_example_fragment_exists(): void
    {
        $fragments = [];

        foreach (File::allFiles(resource_path('views/pages')) as $file) {
            preg_match_all('/src="(lab\.examples\.[a-z0-9._-]+)"/i', $file->getContents(), $matches);

            $fragments = array_merge($fragments, $matches[1]);
        }

        $this->assertNotEmpty($fragments, 'No example fragments are referenced by any page.');

        foreach (array_unique($fragments) as $fragment) {
            $path = resource_path('views/'.str_replace('.', '/', $fragment).'.blade.php');

            $this->assertFileExists($path, "The fragment {$fragment} is referenced but does not exist.");
        }
    }

    public function test_examples_only_use_components_that_ship_with_flux(): void
    {
        $stubRoots = [
            base_path('vendor/livewire/flux/stubs/resources/views/flux'),
            base_path('vendor/livewire/flux-pro/stubs/resources/views/flux'),
        ];

        $available = [];

        foreach ($stubRoots as $root) {
            foreach (File::allFiles($root) as $file) {
                $relative = substr($file->getPathname(), strlen($root) + 1);
                $relative = substr($relative, 0, -strlen('.blade.php'));

                // icon/<name> resolves as <flux:icon>, <name>/index as <flux:name>,
                // and a bare <name>.blade.php as <flux:name> too.
                if (str_starts_with($relative, 'icon/') && ! str_ends_with($relative, '/index')) {
                    $available[substr($relative, 5)] = true;
                } elseif (str_ends_with($relative, '/index')) {
                    $available[substr($relative, 0, -6)] = true;
                } else {
                    $available[$relative] = true;
                }
            }
        }

        $this->assertNotEmpty($available);

        $unknown = [];

        foreach (File::allFiles(resource_path('views/lab/examples')) as $file) {
            preg_match_all('/<flux:([a-z0-9][a-z0-9.-]*)/i', $file->getContents(), $matches);

            foreach ($matches[1] as $component) {
                // Nested tags are covered by their parent, e.g. chart.axis.tick
                // is reached through chart.axis.
                $root = explode('.', $component)[0];

                if (! isset($available[$root])) {
                    $unknown[] = $component.' in '.$file->getRelativePathname();
                }
            }
        }

        $this->assertSame([], array_values(array_unique($unknown)));
    }

    public function test_the_icon_gallery_discovers_icons_and_filters(): void
    {
        $component = Livewire::test('pages::components.actions');

        $icons = $component->instance()->getIconNamesProperty();

        // The Flux package plus this app's own icon overrides.
        $this->assertGreaterThan(300, count($icons));
        $this->assertContains('calendar-days', $icons);
        $this->assertContains('layout-grid', $icons);
        $this->assertNotContains('index', $icons);

        $component
            ->set('iconSearch', 'calendar')
            ->assertSee('calendar-days')
            ->assertDontSee('adjustments-horizontal');
    }

    public function test_every_chart_wraps_its_marks_in_an_svg_template(): void
    {
        $this->actingAs(User::factory()->create());

        // The chart JS finds its series, axes and overlays by querying
        // template[name="svg"] inside the <ui-chart>, then hydrating that
        // template. A chart with no <flux:chart.svg> inside it throws in
        // mount() and paints nothing, while a mark sitting outside the svg
        // template is simply never discovered.
        $marks = [
            'line', 'area', 'bar', 'point', 'pie', 'group', 'stack', 'axis',
            'axis-line', 'grid-line', 'tick-mark', 'tick-label', 'cursor', 'zero-line',
        ];

        foreach (['pages::components.charts', 'pages::components.data'] as $component) {
            $html = Livewire::test($component)->html();

            $dom = new DOMDocument;
            $dom->loadHTML(
                '<!DOCTYPE html><html><head><meta charset="utf-8"></head><body>'.$html.'</body></html>',
                LIBXML_NOERROR | LIBXML_NOWARNING,
            );

            $xpath = new DOMXPath($dom);

            $charts = $xpath->query('//ui-chart');
            $svgs = $xpath->query('//template[@name="svg"]');

            $this->assertSame(
                $charts->length,
                $svgs->length,
                "Every <ui-chart> needs exactly one <template name=\"svg\"> in {$component}.",
            );
            $this->assertGreaterThan(0, $charts->length, "No charts found in {$component}.");

            foreach ($marks as $mark) {
                foreach ($xpath->query('//template[@name="'.$mark.'"]') as $template) {
                    $this->assertTrue(
                        $this->hasSvgAncestor($template),
                        "A \"{$mark}\" template sits outside the svg template in {$component}.",
                    );
                }
            }
        }
    }

    private function hasSvgAncestor(DOMNode $node): bool
    {
        for ($parent = $node->parentNode; $parent !== null; $parent = $parent->parentNode) {
            if ($parent instanceof DOMElement && $parent->tagName === 'template' && $parent->getAttribute('name') === 'svg') {
                return true;
            }
        }

        return false;
    }

    public function test_chart_examples_pass_format_options_as_arrays(): void
    {
        // The chart JS calls JSON.parse() on the format attribute with no guard,
        // so a string like format="number" throws a SyntaxError and aborts the
        // whole chart. Only :format="[...]" survives.
        $offenders = [];

        foreach (File::allFiles(resource_path('views/lab/examples/charts')) as $file) {
            preg_match_all('/<flux:chart[.a-z]*\b[^>]*\sformat="(?!\[)/i', $file->getContents(), $matches);

            if ($matches[0] !== []) {
                $offenders[] = $file->getRelativePathname();
            }
        }

        $this->assertSame([], $offenders, 'Pass :format as an array, never as a string.');
    }
}
