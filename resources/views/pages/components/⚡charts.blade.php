<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Charts')] class extends Component {
    public array $usage = [
        ['label' => 'Jan', 'web' => 120, 'mobile' => 80],
        ['label' => 'Feb', 'web' => 180, 'mobile' => 95],
        ['label' => 'Mar', 'web' => 240, 'mobile' => 140],
        ['label' => 'Apr', 'web' => 310, 'mobile' => 160],
        ['label' => 'May', 'web' => 280, 'mobile' => 190],
        ['label' => 'Jun', 'web' => 420, 'mobile' => 230],
    ];

    public array $share = [
        ['label' => 'Chrome', 'value' => 61],
        ['label' => 'Safari', 'value' => 19],
        ['label' => 'Firefox', 'value' => 12],
        ['label' => 'Other', 'value' => 8],
    ];

    // A series that crosses zero, so the zero-line example has something to draw.
    public array $delta = [
        ['label' => 'Jan', 'web' => 40, 'mobile' => -12],
        ['label' => 'Feb', 'web' => 65, 'mobile' => -4],
        ['label' => 'Mar', 'web' => 38, 'mobile' => 22],
        ['label' => 'Apr', 'web' => 90, 'mobile' => 10],
        ['label' => 'May', 'web' => 72, 'mobile' => 30],
        ['label' => 'Jun', 'web' => 110, 'mobile' => 18],
    ];
}; ?>

    <div class="flex w-full flex-1 flex-col gap-8">
        <x-lab.section heading="Charts"
                      subheading="Declarative SVG charts. Each series is a template the chart element consumes. Flux Pro only.">
            <x-lab.example src="lab.examples.charts.series"
                           :data="['usage' => $usage, 'share' => $share]"
                           title="Series types"
                           subheading="line, area, bar, point and pie. The pie reads its slice labels from label-field; inner-radius turns it into a donut, and only a pie colours the tooltip indicator." />

            <x-lab.example src="lab.examples.charts.group-stack"
                           :data="['usage' => $usage]"
                           title="Multiple fields"
                           subheading="chart.group overlays series on shared axes; chart.stack accumulates them. Legends are hand-composed, so each indicator mirrors the colour of its bar." />

            <x-lab.example src="lab.examples.charts.axes"
                           :data="['usage' => $usage]"
                           title="Axes"
                           subheading="Axis children are axis-aware, so grid, line, mark and tick follow the axis they sit in. tick-values thins the x axis; horizontal swaps the axes." />

            <x-lab.example src="lab.examples.charts.annotations"
                           :data="['usage' => $usage, 'delta' => $delta]"
                           title="Annotations"
                           subheading="summary readouts, the full tooltip set, a cursor, a zero line, the chart gutter, and a sparkline." />
        </x-lab.section>

        <x-lab.section heading="How a chart is assembled"
                      subheading="Every chart on this page follows the same four layers, the last of which is optional.">
            <div class="grid gap-4 md:grid-cols-2">
                <flux:card class="flex flex-col gap-2">
                    <flux:card.heading>1. The root</flux:card.heading>

                    <flux:text size="sm">
                        <code class="font-mono text-xs">flux:chart</code> takes the data through
                        <code class="font-mono text-xs">:value</code>. It renders a
                        <code class="font-mono text-xs">ui-chart</code> custom element and
                        <code class="font-mono text-xs">wire:ignore.children</code>, so the chart never
                        round-trips through Livewire after the first render.
                    </flux:text>
                </flux:card>

                <flux:card class="flex flex-col gap-2">
                    <flux:card.heading>2. The SVG layer</flux:card.heading>

                    <flux:text size="sm">
                        <code class="font-mono text-xs">flux:chart.svg</code> is mandatory. It renders the
                        <code class="font-mono text-xs">&lt;template name="svg"&gt;</code> that the chart JS reads
                        every mark out of, and its <code class="font-mono text-xs">gutter</code> is the padding
                        that keeps tick labels and strokes from being clipped.
                    </flux:text>
                </flux:card>

                <flux:card class="flex flex-col gap-2">
                    <flux:card.heading>3. The series</flux:card.heading>

                    <flux:text size="sm">
                        Each series is a child of the SVG layer and declares the <code class="font-mono text-xs">field</code>
                        it plots, coloured with a plain Tailwind text class. The chart JS
                        reads the templates and draws them; nothing is pre-rendered as SVG markup by Blade.
                    </flux:text>
                </flux:card>

                <flux:card class="flex flex-col gap-2">
                    <flux:card.heading>4. The viewport</flux:card.heading>

                    <flux:text size="sm">
                        <code class="font-mono text-xs">flux:chart.viewport</code> is optional. It wraps
                        <code class="font-mono text-xs">flux:chart.svg</code> so a legend or a summary can sit
                        beside the plot — put the size class on the viewport when you have one, and on the
                        root when you do not. The
                        <code class="font-mono text-xs">tooltip</code> and
                        <code class="font-mono text-xs">summary</code> belong to the chart, outside that SVG layer.
                    </flux:text>
                </flux:card>
            </div>

            <flux:callout icon="information-circle" variant="secondary">
                <flux:text size="sm">
                    Chart components carry no <code class="rounded bg-white/20 px-1">data-flux-*</code> attributes.
                    The only stable handles are the <code class="rounded bg-white/20 px-1">&lt;template name="…"&gt;</code>
                    elements each one renders, which is what the test suite asserts against. That matters because
                    the chart JS finds its series, axes and overlays by querying
                    <code class="rounded bg-white/20 px-1">template[name="svg"]</code> — a
                    <code class="rounded bg-white/20 px-1">flux:chart</code> with no
                    <code class="rounded bg-white/20 px-1">flux:chart.svg</code> inside it renders nothing at all.
                </flux:text>
            </flux:callout>
        </x-lab.section>
    </div>
