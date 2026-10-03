<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Editor')] class extends Component {
    public string $body = '<p>Type in any editor below — the HTML lands in <code>$body</code>.</p>';
}; ?>

    <div class="flex w-full flex-1 flex-col gap-8">
        <x-lab.section heading="Editor"
                      subheading="A rich text surface backed by its own JS bundle. Flux Pro only.">
            <x-lab.example src="lab.examples.editor.default"
                           title="Default"
                           subheading="An empty slot renders the stock toolbar and an empty content area automatically." />

            <x-lab.example src="lab.examples.editor.items"
                           title="Custom toolbar via items"
                           subheading="A space-separated string of editor.* component names." />

            <x-lab.example src="lab.examples.editor.items-spacer"
                           title="Separator and spacer tokens"
                           subheading="Mixed into the items string: | becomes a separator and ~ becomes a spacer." />

            <x-lab.example src="lab.examples.editor.composed"
                           title="Composed by hand"
                           subheading="Pass a slot to keep full control. editor.toolbar, editor.content and every individual control are yours to place." />

            <x-lab.example src="lab.examples.editor.borderless"
                           title="Borderless variant"
                           subheading="Drops the frame and tints the toolbar instead." />

            <x-lab.example src="lab.examples.editor.invalid"
                           title="Invalid state"
                           subheading="The border turns red and editor.content is seeded with the failure message." />
        </x-lab.section>

        <x-lab.section heading="Bound value"
                      subheading="The editor round-trips HTML through wire:model. Type in any editor above and watch this update.">
            <div class="flex flex-col gap-2">
                <flux:heading size="sm">Current $body</flux:heading>

                <pre class="overflow-x-auto rounded-lg bg-zinc-50 p-3 text-xs whitespace-pre-wrap text-zinc-700 dark:bg-zinc-900 dark:text-zinc-300">{{ $body }}</pre>
            </div>

            <flux:callout variant="secondary" icon="code-bracket">
                <flux:text size="sm">
                    The editor ships <code class="rounded bg-zinc-100 px-1 dark:bg-white/10">dist/editor.js</code> and
                    <code class="rounded bg-zinc-100 px-1 dark:bg-white/10">dist/editor.css</code>, injected by the
                    component's own <code class="rounded bg-zinc-100 px-1 dark:bg-white/10">@assets</code> block. No
                    script tag is needed in the layout.
                </flux:text>
            </flux:callout>
        </x-lab.section>
    </div>
