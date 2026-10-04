<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Carousel')] class extends Component {
    //
}; ?>

    <div class="flex w-full flex-1 flex-col gap-8">
        <x-lab.section heading="Carousel"
                      subheading="A scroll-snapping track with arrows, indicators and optional autoplay. Flux Pro only.">
            <x-lab.example src="lab.examples.carousel.basic"
                           title="Default with indicators"
                           subheading="Arrows are on by default; indicators are opt-in. Slides are flux:carousel.slide." />

            <x-lab.example src="lab.examples.carousel.variants"
                           title="Behaviour flags"
                           subheading="fade with bleed, autoplay with autoplay:interval, wrap=&quot;rewind&quot;, advance=&quot;page&quot;, overlap arrows and disabled." />

            <x-lab.example src="lab.examples.carousel.external-controls"
                           title="External controls"
                           subheading="Give the carousel a name, disable built-in arrows with :arrows=&quot;false&quot;, and point flux:carousel.controls at it." />
        </x-lab.section>

        <x-lab.section heading="Props reference"
                      subheading="What the root component accepts.">
            <div class="grid gap-4 md:grid-cols-2">
                <flux:card class="flex flex-col gap-2">
                    <flux:card.heading>Behaviour</flux:card.heading>

                    <ul class="flex flex-col gap-1 text-sm text-zinc-600 dark:text-zinc-400">
                        <li><code class="font-mono text-xs">arrows</code> — on by default</li>
                        <li><code class="font-mono text-xs">indicators</code> — dot row, opt-in</li>
                        <li><code class="font-mono text-xs">autoplay</code> + <code class="font-mono text-xs">autoplay:interval</code> — default 5000ms</li>
                        <li><code class="font-mono text-xs">wrap</code> — <code class="font-mono text-xs">none</code> or <code class="font-mono text-xs">rewind</code></li>
                        <li><code class="font-mono text-xs">advance</code> — <code class="font-mono text-xs">slide</code> or <code class="font-mono text-xs">page</code></li>
                        <li><code class="font-mono text-xs">snap</code> — <code class="font-mono text-xs">proximity</code> or <code class="font-mono text-xs">mandatory</code></li>
                        <li><code class="font-mono text-xs">scroll</code> — <code class="font-mono text-xs">smooth</code> or <code class="font-mono text-xs">instant</code></li>
                    </ul>
                </flux:card>

                <flux:card class="flex flex-col gap-2">
                    <flux:card.heading>Appearance</flux:card.heading>

                    <ul class="flex flex-col gap-1 text-sm text-zinc-600 dark:text-zinc-400">
                        <li><code class="font-mono text-xs">fade</code> — edge gradient mask on overflowed track</li>
                        <li><code class="font-mono text-xs">bleed</code> — let slides run to the container edge</li>
                        <li><code class="font-mono text-xs">arrows:position</code> — <code class="font-mono text-xs">inside</code>, <code class="font-mono text-xs">overlap</code> or <code class="font-mono text-xs">outside</code></li>
                        <li><code class="font-mono text-xs">track:class</code> — restyle the scrolling track</li>
                        <li><code class="font-mono text-xs">disabled</code> — inert controls</li>
                        <li><code class="font-mono text-xs">name</code> — target it from carousel.controls</li>
                    </ul>
                </flux:card>
            </div>
        </x-lab.section>
    </div>
