@props([
    'viewport' => 'desktop',
])

@php
    $isMobile = $viewport === 'mobile';
    $columnWidth = $isMobile ? 'w-72 shrink-0' : 'w-80 shrink-0';
    $minWidth = $isMobile ? 'min-w-[680px]' : 'min-w-[840px]';
    $gap = $isMobile ? 'gap-4' : 'gap-6';
@endphp

<div class="overflow-x-auto pb-4 -mx-1 px-1">
    <flux:kanban class="{{ $gap }} {{ $minWidth }}">
        {{-- Backlog Column --}}
        <flux:kanban.column class="{{ $columnWidth }}">
            <flux:kanban.column.header heading="Backlog" :count="3" badge="Planning">
                <flux:icon name="inbox-stack" class="text-zinc-400" />
            </flux:kanban.column.header>

            <flux:kanban.column.cards>
                <flux:kanban.card heading="Audit design tokens accessibility">
                    <x-slot name="header">
                        <flux:badge size="sm" color="amber">High Priority</flux:badge>
                    </x-slot>
                    <flux:text size="sm" class="text-zinc-500">Ensure all OKLCH color ramps pass WCAG 2.2 AA contrast ratios.</flux:text>
                    <x-slot name="footer">
                        <flux:avatar name="Elena Rostova" size="xs" />
                        <flux:text size="xs" class="text-zinc-400">Design</flux:text>
                    </x-slot>
                </flux:kanban.card>

                <flux:kanban.card heading="OAuth Google Workspace integration">
                    <flux:text size="sm" class="text-zinc-500">Allow SSO login restricted to verified team domains.</flux:text>
                    <x-slot name="footer">
                        <flux:avatar name="David Kim" size="xs" />
                        <flux:text size="xs" class="text-zinc-400">Auth</flux:text>
                    </x-slot>
                </flux:kanban.card>

                <flux:kanban.card heading="Export theme tokens as Tailwind plugin">
                    <flux:text size="sm" class="text-zinc-500">One-click download of generated CSS variables package.</flux:text>
                </flux:kanban.card>
            </flux:kanban.column.cards>

            <flux:kanban.column.footer>
                <flux:button size="xs" variant="subtle" icon="plus" class="w-full">Add card</flux:button>
            </flux:kanban.column.footer>
        </flux:kanban.column>

        {{-- In Progress Column --}}
        <flux:kanban.column class="{{ $columnWidth }}">
            <flux:kanban.column.header heading="In Progress" :count="2" badge="Sprint 14">
                <flux:icon name="arrow-path" class="text-blue-500" />
            </flux:kanban.column.header>

            <flux:kanban.column.cards>
                <flux:kanban.card heading="Livewire 4 Blaze view switcher">
                    <x-slot name="header">
                        <flux:badge size="sm" color="blue">Active</flux:badge>
                    </x-slot>
                    <flux:text size="sm" class="text-zinc-500">Build the interactive views generator and layout switcher canvas.</flux:text>
                    <x-slot name="footer">
                        <flux:avatar.group>
                            <flux:avatar name="Michael Chen" size="xs" />
                            <flux:avatar name="Sarah Connor" size="xs" />
                        </flux:avatar.group>
                        <flux:text size="xs" class="text-zinc-400">2 assignees</flux:text>
                    </x-slot>
                </flux:kanban.card>

                <flux:kanban.card heading="WebAuthn biometric passkeys">
                    <flux:text size="sm" class="text-zinc-500">Browser navigator.credentials integration for instant authentication.</flux:text>
                    <x-slot name="footer">
                        <flux:avatar name="James Wilson" size="xs" />
                        <flux:text size="xs" class="text-zinc-400">Security</flux:text>
                    </x-slot>
                </flux:kanban.card>
            </flux:kanban.column.cards>

            <flux:kanban.column.footer>
                <flux:button size="xs" variant="subtle" icon="plus" class="w-full">Add card</flux:button>
            </flux:kanban.column.footer>
        </flux:kanban.column>

        {{-- Completed Column --}}
        <flux:kanban.column class="{{ $columnWidth }}">
            <flux:kanban.column.header heading="Completed" :count="2" badge="Shipped">
                <flux:icon name="check-circle" class="text-emerald-500" />
            </flux:kanban.column.header>

            <flux:kanban.column.cards>
                <flux:kanban.card heading="Flux Pro component catalog">
                    <x-slot name="header">
                        <flux:badge size="sm" color="green">v2.20</flux:badge>
                    </x-slot>
                    <flux:text size="sm" class="text-zinc-500">Editor, Composer, Kanban, Carousel, Phone, Calendar, and Charts pages.</flux:text>
                    <x-slot name="footer">
                        <flux:avatar name="Sarah Connor" size="xs" />
                        <flux:text size="xs" class="text-zinc-400">Core</flux:text>
                    </x-slot>
                </flux:kanban.card>

                <flux:kanban.card heading="OKLCH theme generator studio">
                    <flux:text size="sm" class="text-zinc-500">Interactive color ramp slider with live CSS variable preview.</flux:text>
                </flux:kanban.card>
            </flux:kanban.column.cards>

            <flux:kanban.column.footer>
                <flux:button size="xs" variant="subtle" icon="plus" class="w-full">Add card</flux:button>
            </flux:kanban.column.footer>
        </flux:kanban.column>
    </flux:kanban>
</div>
