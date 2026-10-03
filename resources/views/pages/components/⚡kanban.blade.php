<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Kanban')] class extends Component {
    //
}; ?>

    <div class="flex w-full flex-1 flex-col gap-8">
        <x-lab.section heading="Kanban"
                      subheading="A board of columns and draggable cards. Flux Pro only.">
            <x-lab.example src="lab.examples.kanban.board"
                           title="A full board"
                           subheading="Three columns showing every part: column.header with heading, count and badge, column.cards holding the cards, and column.footer." />

            <flux:callout icon="information-circle" variant="secondary">
                <flux:text size="sm">
                    <code class="rounded bg-white/20 px-1">flux:kanban</code> is a presentational surface: it renders
                    a flex row and the Flux JS handles pointer dragging between
                    <code class="rounded bg-white/20 px-1">column.cards</code> regions. It does not bind
                    <code class="rounded bg-white/20 px-1">wire:model</code>, so the order of cards in this demo is
                    fixed by the markup. Persist the order by listening for the drag event and posting the column ids
                    back to your own state.
                </flux:text>
            </flux:callout>
        </x-lab.section>

        <x-lab.section heading="Parts reference"
                      subheading="Every kanban tag and the props it accepts.">
            <div class="grid gap-4 md:grid-cols-2">
                <flux:card class="flex flex-col gap-2">
                    <flux:card.heading>Column</flux:card.heading>

                    <ul class="flex flex-col gap-1 text-sm text-zinc-600 dark:text-zinc-400">
                        <li><code class="font-mono text-xs">flux:kanban.column</code> — one 80-wide lane</li>
                        <li><code class="font-mono text-xs">column.header</code> — heading, subheading, count, badge, actions</li>
                        <li><code class="font-mono text-xs">column.cards</code> — the drop target</li>
                        <li><code class="font-mono text-xs">column.footer</code> — trailing slot</li>
                    </ul>
                </flux:card>

                <flux:card class="flex flex-col gap-2">
                    <flux:card.heading>Card</flux:card.heading>

                    <ul class="flex flex-col gap-1 text-sm text-zinc-600 dark:text-zinc-400">
                        <li><code class="font-mono text-xs">flux:kanban.card</code> — heading, header, footer</li>
                        <li><code class="font-mono text-xs">as</code> — <code class="font-mono text-xs">div</code> by default, <code class="font-mono text-xs">button</code> for a clickable card</li>
                        <li>An empty card self-closes; header and footer hide when empty</li>
                    </ul>
                </flux:card>
            </div>
        </x-lab.section>
    </div>
