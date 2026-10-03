<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Phone')] class extends Component {
    public string $phone = '';
}; ?>

    <div class="flex w-full flex-1 flex-col gap-8">
        <x-lab.section heading="Phone"
                      subheading="An international number input pairing a country select with a number field. Flux Pro only.">
            <x-lab.example src="lab.examples.phone.default"
                           title="Default"
                           subheading="The outline variant with the full country list and a bound value." />

            <x-lab.example src="lab.examples.phone.variants"
                           title="Variants and sizes"
                           subheading="variant=&quot;filled&quot; removes the seam between the country select and the number input; size follows the input scale." />

            <x-lab.example src="lab.examples.phone.countries"
                           title="Restricting and reordering countries"
                           subheading="countries limits the list to ISO2 codes; country pins the initial selection; country-order reorders what is left." />

            <div class="flex items-center gap-2">
                <flux:text size="sm" class="text-zinc-500">Bound value:</flux:text>
                <flux:text size="sm" class="font-mono">{{ $phone === '' ? '—' : $phone }}</flux:text>
            </div>
        </x-lab.section>

        <x-lab.section heading="Props reference"
                      subheading="What the phone component accepts.">
            <div class="grid gap-4 md:grid-cols-2">
                <flux:card class="flex flex-col gap-2">
                    <flux:card.heading>Selection</flux:card.heading>

                    <ul class="flex flex-col gap-1 text-sm text-zinc-600 dark:text-zinc-400">
                        <li><code class="font-mono text-xs">countries</code> — array of ISO2 codes to keep</li>
                        <li><code class="font-mono text-xs">country</code> — the initially selected code</li>
                        <li><code class="font-mono text-xs">country-order</code> — reorder the list</li>
                    </ul>
                </flux:card>

                <flux:card class="flex flex-col gap-2">
                    <flux:card.heading>Appearance</flux:card.heading>

                    <ul class="flex flex-col gap-1 text-sm text-zinc-600 dark:text-zinc-400">
                        <li><code class="font-mono text-xs">variant</code> — <code class="font-mono text-xs">outline</code> (default) or <code class="font-mono text-xs">filled</code></li>
                        <li><code class="font-mono text-xs">size</code> — <code class="font-mono text-xs">xs</code>, <code class="font-mono text-xs">sm</code> or base</li>
                        <li><code class="font-mono text-xs">invalid</code> — red border</li>
                        <li>Plus the usual <code class="font-mono text-xs">label</code>, <code class="font-mono text-xs">description</code> and <code class="font-mono text-xs">wire:model</code></li>
                    </ul>
                </flux:card>
            </div>

            <flux:callout icon="wrench-screwdriver" variant="secondary">
                <flux:text size="sm">
                    The country dataset ships as <code class="rounded bg-white/20 px-1">dist/phone-countries.json</code>
                    and the behaviour as <code class="rounded bg-white/20 px-1">dist/phone.js</code>. Both are
                    injected by the component's own <code class="rounded bg-white/20 px-1">@assets</code> block, and
                    the filtered dataset is inlined as a JSON island so the browser never fetches the full list.
                </flux:text>
            </flux:callout>
        </x-lab.section>
    </div>
