<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Composer')] class extends Component {
    public string $body = '';

    public function send(): void
    {
        $this->dispatch(
            'toast',
            type: 'success',
            title: 'Sent',
            description: $this->body !== '' ? 'The composer body was submitted.' : 'The composer was empty, so nothing was sent.',
        );
    }
}; ?>

    <div class="flex w-full flex-1 flex-col gap-8">
        <x-lab.section heading="Composer"
                      subheading="A multi-line authoring surface with optional header, footer and action slots. Flux Pro only.">
            <x-lab.example src="lab.examples.composer.default"
                           title="Default"
                           subheading="A textarea, bound to Livewire, with a label." />

            <x-lab.example src="lab.examples.composer.variants"
                           title="Variants and states"
                           subheading="variant=&quot;input&quot; squares the corners; invalid swaps the border for red." />

            <x-lab.example src="lab.examples.composer.slots"
                           title="Slots"
                           subheading="header, footer, actionsLeading and actionsTrailing. Slot names are camelCase and match the prop names exactly." />

            <x-lab.example src="lab.examples.composer.in-form"
                           title="Inside a form"
                           subheading="The composer is a plain control, so it submits like any other field." />
        </x-lab.section>

        <x-lab.section heading="Slots reference"
                      subheading="The four named slots and the two variant props.">
            <div class="grid gap-4 md:grid-cols-2">
                <flux:card class="flex flex-col gap-2">
                    <flux:card.heading>Slots</flux:card.heading>

                    <ul class="flex flex-col gap-1 text-sm text-zinc-600 dark:text-zinc-400">
                        <li><code class="font-mono text-xs">header</code> — row above the input</li>
                        <li><code class="font-mono text-xs">footer</code> — row below the actions</li>
                        <li><code class="font-mono text-xs">input</code> — replaces the textarea</li>
                        <li><code class="font-mono text-xs">actionsLeading</code> / <code class="font-mono text-xs">actionsTrailing</code></li>
                    </ul>
                </flux:card>

                <flux:card class="flex flex-col gap-2">
                    <flux:card.heading>Props</flux:card.heading>

                    <ul class="flex flex-col gap-1 text-sm text-zinc-600 dark:text-zinc-400">
                        <li><code class="font-mono text-xs">variant</code> — <code class="font-mono text-xs">input</code> squares the corners</li>
                        <li><code class="font-mono text-xs">invalid</code> — red border, usually derived from the error bag</li>
                        <li><code class="font-mono text-xs">label</code> / <code class="font-mono text-xs">description</code> / <code class="font-mono text-xs">badge</code></li>
                    </ul>
                </flux:card>
            </div>
        </x-lab.section>
    </div>
