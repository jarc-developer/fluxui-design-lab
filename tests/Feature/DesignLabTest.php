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
            'theme-generator' => ['/theme/generator'],
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

    public function test_charts_page_offers_the_official_preset_taxonomy(): void
    {
        $this->actingAs(User::factory()->create());

        $html = Livewire::test('pages::components.charts')->html();

        // The switcher mirrors the official page's grouping — line, bar and
        // pie charts — plus a reference tab for everything that does not fit
        // a family. A family name with no matching panel renders an empty
        // switcher, so both halves have to be present.
        foreach ([
            'Line charts' => 'line',
            'Bar charts' => 'bar',
            'Pie charts' => 'pie',
            'Reference' => 'reference',
        ] as $label => $name) {
            $this->assertStringContainsString($label, $html, "The charts page is missing the \"{$label}\" tab.");
            $this->assertStringContainsString('name="'.$name.'"', $html, "No tab panel is bound to \"{$name}\".");
        }

        // Every preset is a file, and the snippet underneath it is read back
        // out of that same file, so asserting on the source is the strongest
        // check available: rename or delete a fragment and this fails.
        // conflicts is excluded — it is prose and renders with snippet="false".
        foreach ([
            'line', 'area', 'points', 'sparkline',
            'bars', 'grouped', 'stacked', 'horizontal',
            'pie', 'donut', 'ring',
            'axes', 'data-shapes', 'annotations',
        ] as $fragment) {
            $source = trim(File::get(resource_path('views/lab/examples/charts/'.$fragment.'.blade.php')));

            $this->assertStringContainsString(
                e($source),
                $html,
                "The \"{$fragment}\" preset is missing from the charts page.",
            );
        }
    }

    public function test_charts_page_covers_the_documented_engine_attributes(): void
    {
        $this->actingAs(User::factory()->create());

        $html = Livewire::test('pages::components.charts')->html();

        // Each of these is a knob the chart engine actually reads. They are
        // why the reference tab exists — drop one and it should fail here.
        foreach ([
            'curve=',
            'interval=',
            'tick-count=',
            'tick-prefix=',
            'tick-suffix=',
            'tick-start=',
            'tick-end=',
            'position=',
            'min-height=',
            'inner-radius=',
            'stroke-dasharray=',
            'type="area"',
            'fallback=',
            'locale=',
        ] as $attribute) {
            $this->assertStringContainsString($attribute, $html, "The charts page no longer demonstrates {$attribute}.");
        }

        // The props the engine accepts and then ignores, plus the one that
        // throws. The trailing "=" is deliberate: the reference tab names them
        // in prose, and this only catches one being used as an attribute.
        foreach (['tick-step=', 'target-tick-count=', 'scale="log"'] as $noOp) {
            $this->assertStringNotContainsString($noOp, $html, "{$noOp} does nothing, so no preset should set it.");
        }
    }

    public function test_charts_page_never_mixes_a_pie_with_cartesian_marks(): void
    {
        $this->actingAs(User::factory()->create());

        $html = Livewire::test('pages::components.charts')->html();

        $dom = new DOMDocument;
        $dom->loadHTML(
            '<!DOCTYPE html><html><head><meta charset="utf-8"></head><body>'.$html.'</body></html>',
            LIBXML_NOERROR | LIBXML_NOWARNING,
        );

        $xpath = new DOMXPath($dom);

        $pies = $xpath->query('//ui-chart[.//template[@name="pie"]]');

        $this->assertGreaterThan(0, $pies->length, 'No pie charts found on the charts page.');

        // The engine warns and paints nothing when a pie shares a chart with
        // any cartesian mark or chrome, and it only implements a single pie.
        foreach (['bar', 'line', 'area', 'point', 'axis', 'cursor', 'zero-line'] as $mark) {
            foreach ($pies as $chart) {
                $this->assertSame(
                    0,
                    $xpath->query('.//template[@name="'.$mark.'"]', $chart)->length,
                    "A pie cannot share a chart with a \"{$mark}\" mark.",
                );
            }
        }

        foreach ($pies as $chart) {
            $this->assertSame(
                1,
                $xpath->query('.//template[@name="pie"]', $chart)->length,
                'A chart may only hold one pie.',
            );
        }
    }

    public function test_the_limits_example_renders_prose_without_a_snippet(): void
    {
        $this->actingAs(User::factory()->create());

        $html = Livewire::test('pages::components.charts')->html();

        $dom = new DOMDocument;
        $dom->loadHTML(
            '<!DOCTYPE html><html><head><meta charset="utf-8"></head><body>'.$html.'</body></html>',
            LIBXML_NOERROR | LIBXML_NOWARNING,
        );

        $xpath = new DOMXPath($dom);
        $examples = $xpath->query('//*[@data-lab-example]');

        $this->assertSame(15, $examples->length, 'Unexpected number of examples on the charts page.');

        $withoutSnippet = 0;

        foreach ($examples as $example) {
            if ($xpath->query('.//pre', $example)->length === 0) {
                $withoutSnippet++;
            }
        }

        // Blade hands snippet="false" to the component as the string "false",
        // which is truthy unless it is normalised first. The limits example is
        // prose, and it is the only one that opts out of a snippet.
        $this->assertSame(1, $withoutSnippet, 'Exactly one example should render without a copyable snippet.');
    }

    public function test_editor_examples_use_toolbar_prop_on_root_component(): void
    {
        foreach (File::allFiles(resource_path('views/lab/examples/editor')) as $file) {
            $contents = $file->getContents();

            $this->assertDoesNotMatchRegularExpression(
                '/<flux:editor(?!\.)\b[^>]*\sitems=/i',
                $contents,
                "{$file->getRelativePathname()} mistakenly uses items= on <flux:editor> instead of toolbar=.",
            );
        }
    }

    public function test_command_wrapper_does_not_declare_icon(): void
    {
        foreach (File::allFiles(resource_path('views/lab/examples/actions')) as $file) {
            $this->assertDoesNotMatchRegularExpression(
                '/<flux:command(?!\.)\b[^>]*\sicon=/i',
                $file->getContents(),
                "{$file->getRelativePathname()} mistakenly declares icon= on <flux:command> instead of <flux:command.input>.",
            );
        }
    }

    public function test_dropdowns_use_valid_position_prop_not_placement(): void
    {
        foreach (File::allFiles(resource_path('views')) as $file) {
            $this->assertDoesNotMatchRegularExpression(
                '/<flux:dropdown\b[^>]*\splacement=/i',
                $file->getContents(),
                "{$file->getRelativePathname()} mistakenly uses placement= instead of position= on <flux:dropdown>.",
            );
        }
    }

    public function test_carousel_examples_specify_slide_widths(): void
    {
        foreach (File::allFiles(resource_path('views/lab/examples/carousel')) as $file) {
            preg_match_all('/<flux:carousel\.slide\b([^>]*)>/i', $file->getContents(), $matches);

            $this->assertNotEmpty($matches[0], "No carousel slides found in {$file->getRelativePathname()}.");

            foreach ($matches[1] as $attributes) {
                $this->assertMatchesRegularExpression(
                    '/class="[^"]*w-(?:full|[0-9]+\/[0-9]+)/',
                    $attributes,
                    "A slide in {$file->getRelativePathname()} is missing a width class (e.g. w-full or w-1/2).",
                );
            }
        }
    }

    public function test_external_controls_carousel_disables_built_in_arrows(): void
    {
        $content = File::get(resource_path('views/lab/examples/carousel/external-controls.blade.php'));

        $this->assertMatchesRegularExpression(
            '/<flux:carousel\b[^>]*:arrows="false"/',
            $content,
            'The external-controls carousel must specify :arrows="false" to prevent double arrows.',
        );
    }

    public function test_carousel_page_demonstrates_key_flux_pro_attributes(): void
    {
        $this->actingAs(User::factory()->create());

        $html = Livewire::test('pages::components.carousel')->html();

        foreach ([
            'indicators',
            'advance="page"',
            'wrap="rewind"',
            'arrows:position=',
            'name="gallery"',
            ':arrows=',
        ] as $needle) {
            $this->assertStringContainsString(
                $needle,
                $html,
                "The carousel catalog page is missing \"{$needle}\".",
            );
        }
    }

    public function test_charts_never_wrap_lines_in_a_group(): void
    {
        foreach (File::allFiles(resource_path('views/lab/examples/charts')) as $file) {
            $contents = $file->getContents();
            $this->assertFalse(
                (bool) preg_match('/<flux:chart\.group[^>]*>[\s\S]*?<flux:chart\.line/i', $contents),
                "File {$file->getFilename()} incorrectly wraps <flux:chart.line> in <flux:chart.group>. Groups are only for bars.",
            );
        }
    }

    public function test_cartesian_chart_examples_with_axes_declare_ticks(): void
    {
        // Without <flux:chart.axis.tick>, the chart JS engine never hydrates tick labels,
        // leaving the axes blank. All examples declaring axes should render ticks.
        foreach (['axes', 'data-shapes', 'horizontal'] as $fragment) {
            $contents = File::get(resource_path("views/lab/examples/charts/{$fragment}.blade.php"));
            $this->assertStringContainsString(
                '<flux:chart.axis.tick',
                $contents,
                "Example {$fragment}.blade.php must include <flux:chart.axis.tick> to render tick labels.",
            );
        }
    }

    public function test_input_groups_order_suffix_after_input(): void
    {
        $contents = File::get(resource_path('views/lab/examples/forms/input-group.blade.php'));

        $inputPos = strpos($contents, '<flux:input placeholder="Grouped input" />');
        $suffixPos = strpos($contents, '<flux:input.group.suffix>');

        $this->assertNotFalse($inputPos);
        $this->assertNotFalse($suffixPos);
        $this->assertGreaterThan($inputPos, $suffixPos, '<flux:input.group.suffix> must follow <flux:input>.');
    }

    public function test_custom_select_indicators_are_configured_via_attributes(): void
    {
        $contents = File::get(resource_path('views/lab/examples/forms/select-custom.blade.php'));

        $this->assertFalse(
            (bool) preg_match('/<flux:select\.indicator/i', $contents),
            'Stray <flux:select.indicator> tags found outside options. Indicators should be set via indicator="..." on <flux:select> or <flux:select.option>.',
        );
        $this->assertStringContainsString('indicator="checkbox"', $contents);
        $this->assertStringContainsString('indicator="radio"', $contents);
    }

    public function test_otp_and_slider_examples_use_built_in_label_attributes(): void
    {
        $otpContents = File::get(resource_path('views/lab/examples/forms/otp.blade.php'));
        $this->assertFalse(
            (bool) preg_match('/<flux:label/i', $otpContents),
            'otp.blade.php should use label="..." directly on <flux:otp> instead of redundant <flux:label>.',
        );

        $sliderContents = File::get(resource_path('views/lab/examples/forms/slider.blade.php'));
        $this->assertFalse(
            (bool) preg_match('/<flux:label/i', $sliderContents),
            'slider.blade.php should use label="..." directly on <flux:slider> instead of redundant <flux:label>.',
        );
    }

    public function test_viewable_input_uses_password_type(): void
    {
        $contents = File::get(resource_path('views/lab/examples/forms/input-affixes.blade.php'));
        $this->assertTrue(
            (bool) preg_match('/<flux:input[^>]*type="password"[^>]*viewable/i', $contents),
            'Viewable inputs must specify type="password" so contents start masked.',
        );
    }

    public function test_date_picker_range_mode_binds_to_date_range(): void
    {
        $contents = File::get(resource_path('views/lab/examples/forms/date-picker.blade.php'));
        $this->assertTrue(
            (bool) preg_match('/<flux:date-picker[^>]*wire:model="dateRange"[^>]*mode="range"/i', $contents)
            || (bool) preg_match('/<flux:date-picker[^>]*mode="range"[^>]*wire:model="dateRange"/i', $contents),
            'Range mode date-picker must bind to an array property (dateRange) instead of single string property.',
        );
    }

    public function test_modals_render_action_buttons_without_dropped_footer_slot(): void
    {
        $overlays = File::get(resource_path('views/pages/components/⚡overlays.blade.php'));
        $this->assertFalse(
            (bool) preg_match('/<x-slot\s+name=["\']footer["\']/i', $overlays),
            '⚡overlays.blade.php uses <x-slot name="footer"> inside <flux:modal>, which is silently dropped because flux:modal does not declare a footer slot.',
        );

        $triggers = File::get(resource_path('views/lab/examples/overlays/modal-triggers.blade.php'));
        $this->assertFalse(
            (bool) preg_match('/<x-slot\s+name=["\']footer["\']/i', $triggers),
            'modal-triggers.blade.php uses <x-slot name="footer"> inside <flux:modal>, which is silently dropped.',
        );
    }

    public function test_toast_examples_use_official_flux_toast_api(): void
    {
        $contents = File::get(resource_path('views/lab/examples/overlays/toasts.blade.php'));
        $this->assertFalse(
            (bool) preg_match('/\$dispatch\([\'"]toast[\'"]/i', $contents),
            'toasts.blade.php should use $flux.toast() instead of broken $dispatch("toast", ...).',
        );
        $this->assertTrue(
            (bool) preg_match('/\$flux\.toast\(/i', $contents),
            'toasts.blade.php must use $flux.toast(...) to trigger toast notifications.',
        );
    }

    public function test_popover_examples_are_hosted_in_dropdown_and_context_menus_use_menu(): void
    {
        $contents = File::get(resource_path('views/lab/examples/actions/popover.blade.php'));

        $this->assertTrue(
            (bool) preg_match('/<flux:dropdown[^>]*>[\s\S]*?<flux:popover/i', $contents),
            'flux:popover must be hosted inside flux:dropdown per official Flux specifications.',
        );

        $this->assertTrue(
            (bool) preg_match('/<flux:context>[\s\S]*?<flux:menu/i', $contents),
            'flux:context must host a flux:menu for right-click context menus.',
        );
    }

    public function test_tooltip_examples_declare_string_kbd_shortcut(): void
    {
        $contents = File::get(resource_path('views/lab/examples/actions/tooltips.blade.php'));

        $this->assertFalse(
            (bool) preg_match('/<flux:tooltip[^>]*\skbd\s/i', $contents)
            || (bool) preg_match('/<flux:tooltip[^>]*\skbd>/i', $contents),
            'tooltips.blade.php should pass a string shortcut (e.g. kbd="⌘K") to avoid rendering digit 1.',
        );
        $this->assertStringContainsString('kbd="⌘K"', $contents);
    }

    public function test_command_examples_declare_input_element(): void
    {
        $contents = File::get(resource_path('views/lab/examples/actions/command.blade.php'));

        $this->assertFalse(
            (bool) preg_match('/<flux:command\s[^>]*placeholder=/i', $contents),
            'placeholder must be declared on <flux:command.input>, not <flux:command>.',
        );
        $this->assertStringContainsString('<flux:command.input placeholder="Search components…"', $contents);
    }

    public function test_actions_component_uses_flux_toast(): void
    {
        $contents = File::get(resource_path('views/pages/components/⚡actions.blade.php'));

        $this->assertStringContainsString('Flux::toast(', $contents);
        $this->assertFalse(
            (bool) preg_match('/\$this->dispatch\([\'"]toast[\'"]/i', $contents),
            '⚡actions.blade.php notify() should use Flux::toast instead of broken $this->dispatch("toast", ...).',
        );
    }

    public function test_actions_component_loading_button_calls_valid_component_method(): void
    {
        Livewire::test('pages::components.actions')
            ->call('simulateAsyncAction')
            ->assertOk();

        $contents = File::get(resource_path('views/pages/components/⚡actions.blade.php'));
        $this->assertFalse(
            (bool) preg_match('/wire:click=[\'"]\$sleep/i', $contents),
            'actions page should not call non-existent $sleep() magic action.',
        );
        $this->assertStringContainsString('wire:click="simulateAsyncAction"', $contents);
    }

    public function test_card_examples_use_body_treatments_and_valid_variants(): void
    {
        $contents = File::get(resource_path('views/lab/examples/data/cards.blade.php'));

        $this->assertFalse(
            (bool) preg_match('/<flux:card[^>]*variant=[\'"]separated[\'"]/i', $contents),
            'separated is a body treatment (body="separated"), not a variant.',
        );
        $this->assertStringContainsString('body="separated"', $contents);
        $this->assertStringContainsString('body="divided"', $contents);
    }

    public function test_tab_examples_bind_wire_model_on_tabs_not_tab_group(): void
    {
        $contents = File::get(resource_path('views/lab/examples/data/tabs.blade.php'));

        $this->assertFalse(
            (bool) preg_match('/<flux:tab\.group[^>]*wire:model/i', $contents),
            'wire:model must be bound on <flux:tabs>, not <flux:tab.group>.',
        );
        $this->assertTrue(
            (bool) preg_match('/<flux:tabs[^>]*wire:model=/i', $contents),
            '<flux:tabs> must bind to wire:model.',
        );
        $this->assertStringContainsString('variant="segmented"', $contents);
    }

    public function test_table_sortable_columns_bind_sorted_and_direction(): void
    {
        $contents = File::get(resource_path('views/lab/examples/data/table-sortable.blade.php'));

        $this->assertStringContainsString(':sorted="$sortField === \'name\'"', $contents);
        $this->assertStringContainsString(':direction="$sortDirection"', $contents);
        $this->assertStringContainsString(':key="$row[\'name\']"', $contents);
    }

    public function test_accordion_examples_use_valid_flux_attributes(): void
    {
        $contents = File::get(resource_path('views/lab/examples/data/accordion.blade.php'));

        $this->assertFalse(
            (bool) preg_match('/<flux:accordion[^>]*wire:model/i', $contents),
            'flux:accordion does not support wire:model.',
        );
        $this->assertFalse(
            (bool) preg_match('/<flux:accordion[^>]*variant=[\'"]separated[\'"]/i', $contents),
            'flux:accordion does not have a separated variant.',
        );
        $this->assertFalse(
            (bool) preg_match('/<flux:accordion\.heading>[\s\S]*?<flux:accordion\.icon/i', $contents),
            'flux:accordion.heading automatically renders the chevron icon; manual nested icon is redundant.',
        );
        $this->assertStringContainsString('exclusive', $contents);
        $this->assertStringContainsString('variant="reverse"', $contents);
    }

    public function test_timeline_examples_do_not_use_invalid_small_size(): void
    {
        $contents = File::get(resource_path('views/lab/examples/data/timeline.blade.php'));

        $this->assertFalse(
            (bool) preg_match('/<flux:timeline[^>]*size=[\'"]sm[\'"]/i', $contents),
            'flux:timeline only supports size="lg" or default; size="sm" is invalid.',
        );
        $this->assertStringContainsString('size="lg"', $contents);
    }

    public function test_sidebar_layout_uses_official_flux_sidebar_primitives(): void
    {
        $contents = File::get(resource_path('views/layouts/app/sidebar.blade.php'));

        $this->assertStringContainsString('<flux:sidebar.spacer />', $contents);
        $this->assertFalse(
            (bool) preg_match('/<flux:sidebar\.group[^>]*class=[\'"][^\'"]*grid/i', $contents),
            'Non-expandable sidebar groups should not specify class="grid" which conflicts with flex flex-col.',
        );
        $this->assertStringContainsString('<flux:dropdown position="bottom" align="end">', $contents);
    }

    public function test_desktop_user_menu_uses_official_flux_dropdown_and_profile_props(): void
    {
        $contents = File::get(resource_path('views/components/desktop-user-menu.blade.php'));

        $this->assertStringContainsString('position="top"', $contents);
        $this->assertStringContainsString('align="start"', $contents);
        $this->assertStringContainsString('{{ $attributes', $contents);
        $this->assertFalse(
            (bool) preg_match('/<flux:menu\.radio\.group>/i', $contents),
            'Navigation items like Settings and Logout should not be wrapped in flux:menu.radio.group.',
        );
    }
}
