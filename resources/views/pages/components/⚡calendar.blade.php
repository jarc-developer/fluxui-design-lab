<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Calendar')] class extends Component {
    public string $day = '2026-10-03';

    public array $range = [];
}; ?>

    <div class="flex w-full flex-1 flex-col gap-8">
        <x-lab.section heading="Calendar"
                      subheading="The standalone month view. Unlike flux:date-picker it renders inline instead of inside a popover. Flux Pro only.">
            <x-lab.example src="lab.examples.calendar.default"
                           title="Default"
                           subheading="A single month with a today marker and a bound date." />

            <x-lab.example src="lab.examples.calendar.options"
                           title="Display options"
                           subheading="with-inputs adds typed day/month/year fields, week-numbers adds the ISO week column, and size steps the cell size." />

            <x-lab.example src="lab.examples.calendar.range-and-unavailable"
                           title="Range mode and blocked dates"
                           subheading="mode=&quot;range&quot; binds an array with start and end keys and widens the view; unavailable greys out individual days." />

            <div class="grid gap-4 md:grid-cols-2">
                <div class="flex items-center gap-2">
                    <flux:text size="sm" class="text-zinc-500">$day</flux:text>
                    <flux:text size="sm" class="font-mono">{{ $day === '' ? '—' : $day }}</flux:text>
                </div>

                <div class="flex items-center gap-2">
                    <flux:text size="sm" class="text-zinc-500">$range</flux:text>
                    <flux:text size="sm" class="font-mono">{{ empty($range) ? '—' : json_encode($range) }}</flux:text>
                </div>
            </div>
        </x-lab.section>

        <x-lab.section heading="Calendar or date-picker?"
                      subheading="The two Pro date surfaces, side by side.">
            <div class="grid gap-4 md:grid-cols-2">
                <flux:card class="flex flex-col gap-2">
                    <flux:card.heading>flux:calendar</flux:card.heading>

                    <flux:text size="sm">
                        Renders inline, always visible. Use it for a scheduling screen, a month overview, or
                        anything embedded in a page flow. It is a surface, not a control — there is no trigger.
                    </flux:text>
                </flux:card>

                <flux:card class="flex flex-col gap-2">
                    <flux:card.heading>flux:date-picker</flux:card.heading>

                    <flux:text size="sm">
                        A trigger plus a popover. Use it inside a form where the date is one field among many.
                        It wraps this same calendar and adds presets, confirmation and input variants.
                    </flux:text>

                    <flux:button size="sm" variant="subtle" href="{{ route('components.forms') }}" wire:navigate class="self-start">
                        See the date-picker on the forms page
                    </flux:button>
                </flux:card>
            </div>
        </x-lab.section>

        <x-lab.section heading="Props reference"
                      subheading="What the calendar accepts.">
            <div class="grid gap-4 md:grid-cols-2">
                <flux:card class="flex flex-col gap-2">
                    <flux:card.heading>Data</flux:card.heading>

                    <ul class="flex flex-col gap-1 text-sm text-zinc-600 dark:text-zinc-400">
                        <li><code class="font-mono text-xs">mode</code> — <code class="font-mono text-xs">single</code> (default) or <code class="font-mono text-xs">range</code></li>
                        <li><code class="font-mono text-xs">months</code> — how many months to show</li>
                        <li><code class="font-mono text-xs">unavailable</code> — array of Y-m-d strings to block</li>
                        <li><code class="font-mono text-xs">wire:model</code> — a date string, or start/end in range mode</li>
                    </ul>
                </flux:card>

                <flux:card class="flex flex-col gap-2">
                    <flux:card.heading>Display</flux:card.heading>

                    <ul class="flex flex-col gap-1 text-sm text-zinc-600 dark:text-zinc-400">
                        <li><code class="font-mono text-xs">size</code> — <code class="font-mono text-xs">xs</code>, <code class="font-mono text-xs">sm</code>, <code class="font-mono text-xs">lg</code>, <code class="font-mono text-xs">xl</code>, <code class="font-mono text-xs">2xl</code></li>
                        <li><code class="font-mono text-xs">with-inputs</code> — typed date fields above the grid</li>
                        <li><code class="font-mono text-xs">with-today</code> — highlight today</li>
                        <li><code class="font-mono text-xs">week-numbers</code> — ISO week column</li>
                        <li><code class="font-mono text-xs">navigation</code> / <code class="font-mono text-xs">selectable-header</code></li>
                    </ul>
                </flux:card>
            </div>
        </x-lab.section>
    </div>
