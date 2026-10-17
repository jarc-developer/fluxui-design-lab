<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Theme & Tokens')] class extends Component {
    public string $accent = '#4f46e5';
    public string $previewRadius = 'rounded-lg';

    public function mount(): void
    {
        $active = \App\Models\Theme::query()->active()->first();
        if ($active) {
            $this->accent = $active->tokens->light->accent;
        }
    }

    public function with(): array
    {
        $active = \App\Models\Theme::query()->active()->first();
        $baseRamp = $active
            ? app(\App\Support\Theme\ThemeCompiler::class)->ramps($active->tokens)['base'] ?? null
            : null;

        $shades = $baseRamp
            ? array_map(fn ($step, $hex) => ['name' => $step, 'hex' => $hex], array_keys($baseRamp), $baseRamp)
            : [
                ['name' => '50', 'hex' => '#fafafa'],
                ['name' => '100', 'hex' => '#f5f5f5'],
                ['name' => '200', 'hex' => '#e5e5e5'],
                ['name' => '300', 'hex' => '#d4d4d4'],
                ['name' => '400', 'hex' => '#a1a1a1'],
                ['name' => '500', 'hex' => '#737373'],
                ['name' => '600', 'hex' => '#525252'],
                ['name' => '700', 'hex' => '#404040'],
                ['name' => '800', 'hex' => '#262626'],
                ['name' => '900', 'hex' => '#171717'],
                ['name' => '950', 'hex' => '#0a0a0a'],
            ];

        return [
            'activeThemeName' => $active?->name ?? 'Default',
            'shades' => $shades,
            'spacing' => ['0', '1', '2', '3', '4', '6', '8', '12', '16'],
            'radius' => ['rounded-none', 'rounded-md', 'rounded-lg', 'rounded-xl', 'rounded-full'],
            'typeScale' => [
                ['class' => 'text-xs', 'label' => 'Extra small', 'sample' => 'The quick brown fox'],
                ['class' => 'text-sm', 'label' => 'Small', 'sample' => 'The quick brown fox'],
                ['class' => 'text-base', 'label' => 'Base', 'sample' => 'The quick brown fox'],
                ['class' => 'text-lg', 'label' => 'Large', 'sample' => 'The quick brown fox'],
                ['class' => 'text-2xl', 'label' => 'Extra large', 'sample' => 'The quick brown fox'],
                ['class' => 'text-4xl', 'label' => 'Display', 'sample' => 'The quick brown fox'],
            ],
        ];
    }
}; ?>

    <div class="flex w-full flex-1 flex-col gap-8">
        <flux:callout variant="info" icon="paint-brush" class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <flux:callout.heading>Theme Generator</flux:callout.heading>
                <flux:callout.text>
                    Create, preview, and globally activate custom color palettes, neutral tones, corner radiuses, and fonts across all project pages.
                </flux:callout.text>
            </div>
            <flux:button :href="route('home')" variant="primary" size="sm" icon-trailing="arrow-right" wire:navigate>
                Open Theme Generator
            </flux:button>
        </flux:callout>
        <x-lab.section heading="Appearance"
                      subheading="Drives Flux's appearance across every page of the lab.">
            <x-lab.example src="lab.examples.theme.appearance"
                           title="Light, dark and system"
                           subheading="radio.group bound to $flux.appearance, which writes to local storage and applies the dark class for you." />
        </x-lab.section>

        <x-lab.section heading="Accent colour"
                      subheading="Set the Flux accent used by buttons, links and focus rings.">
            <x-lab.example src="lab.examples.theme.accent-picker"
                           title="The same picker in three formats"
                           subheading="format switches between hex, rgb and oklch; copyable and clearable add their own controls, and swatches gives a preset row." />
            <div class="grid gap-4 md:grid-cols-2">
                <flux:color-picker wire:model="accent" label="Accent" format="hex" copyable />
                <div class="flex items-center gap-2">
                    <span class="size-10 rounded-lg" style="background-color: {{ $accent }}"></span>
                    <flux:text size="sm" class="font-mono">{{ $accent }}</flux:text>
                </div>
            </div>
        </x-lab.section>

        <x-lab.section heading="Palette"
                      subheading="A neutral ramp used as a reference scale for new component colours.">
            <div class="flex flex-wrap gap-2">
                @foreach ($shades as $shade)
                    <div class="flex flex-col items-center gap-1">
                        <div class="size-12 rounded-lg border border-zinc-200 dark:border-zinc-700"
                             style="background-color: {{ $shade['hex'] }}"></div>
                        <flux:text size="xs">{{ $shade['name'] }}</flux:text>
                    </div>
                @endforeach
            </div>
        </x-lab.section>

        <x-lab.section heading="Corner radius"
                      subheading="Live preview of each radius step.">
            <flux:radio.group wire:model="previewRadius" label="Radius" variant="segmented">
                @foreach ($radius as $option)
                    <flux:radio value="{{ $option }}">Str</flux:radio>
                @endforeach
            </flux:radio.group>

            <div class="flex flex-wrap gap-3">
                @foreach ($radius as $option)
                    <div class="flex flex-col items-center gap-1">
                        <div class="size-16 border-2 border-zinc-300 bg-zinc-100 dark:border-zinc-600 dark:bg-zinc-800 {{ $option }}"></div>
                        <flux:text size="xs" class="font-mono">{{ str($option)->after('rounded-') }}</flux:text>
                    </div>
                @endforeach
            </div>
        </x-lab.section>

        <x-lab.section heading="Typography"
                      subheading="The kit loads Instrument Sans via the Bunny font CDN.">
            <div class="flex flex-col gap-3">
                @foreach ($typeScale as $step)
                    <div class="flex items-baseline gap-4 border-b border-zinc-100 pb-2 dark:border-zinc-800">
                        <flux:text size="xs" class="w-24 shrink-0 font-mono text-zinc-500">{{ $step['class'] }}</flux:text>
                        <span class="{{ $step['class'] }}">{{ $step['sample'] }}</span>
                    </div>
                @endforeach
            </div>
        </x-lab.section>

        <x-lab.section heading="Spacing scale"
                      subheading="Tailwind's default rem steps.">
            <div class="flex items-end gap-3">
                @foreach ($spacing as $step)
                    <div class="flex flex-col items-center gap-1">
                        <div class="w-8 bg-accent-500/40" style="height: {{ $step * 0.25 }}rem"></div>
                        <flux:text size="xs">{{ $step }}</flux:text>
                    </div>
                @endforeach
            </div>
        </x-lab.section>

        <x-lab.section heading="Customizing components"
                      subheading="Publish a component's Blade view, then edit the copy in resources/views.">
            <flux:callout variant="secondary" icon="wrench-screwdriver">
                <flux:text size="sm">
                    Run <code class="rounded bg-zinc-100 px-1 dark:bg-white/10">php artisan flux:publish</code> to
                    publish one component, or <code class="rounded bg-zinc-100 px-1 dark:bg-white/10">--all</code> for
                    every component. Published views live in <code class="rounded bg-zinc-100 px-1 dark:bg-white/10">resources/views/flux</code>
                    and take precedence over the package defaults.
                </flux:text>
            </flux:callout>
        </x-lab.section>
    </div>
