<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Overlays & Feedback')] class extends Component {
    public bool $showModal = false;
    public bool $showConfirm = false;
    public bool $showForm = false;
    public string $formName = '';

    public function save(): void
    {
        $this->showForm = false;

        $this->dispatch(
            'toast',
            type: 'success',
            title: 'Saved',
            description: $this->formName !== '' ? "Created “{$this->formName}”." : 'Draft saved.',
        );
    }

    public function destroy(): void
    {
        $this->showConfirm = false;

        $this->dispatch('toast', type: 'success', title: 'Deleted', description: 'The demo record was removed.');
    }
}; ?>

    <div class="flex w-full flex-1 flex-col gap-8">
        <x-lab.section heading="Modal"
                      subheading="Bound with wire:model, with a footer action area.">
            <div class="flex flex-wrap items-center gap-2">
                <flux:button wire:click="$set('showModal', true)">Open modal</flux:button>
                <flux:button wire:click="$set('showForm', true)" variant="subtle">Open with a form</flux:button>
                <flux:button wire:click="$set('showConfirm', true)" color="red" variant="subtle">Open confirmation</flux:button>
            </div>

            <flux:modal wire:model="showModal" name="demo">
                <div class="space-y-6">
                    <div>
                        <flux:heading>Modal dialog</flux:heading>
                        <flux:subheading>Scrollable body with a pinned footer.</flux:subheading>
                    </div>

                    <flux:text size="sm">
                        Modals trap focus, close on escape, and lock background scroll. Keep the body short
                        so the footer stays reachable.
                    </flux:text>

                    <x-slot name="footer">
                        <div class="flex justify-end gap-2">
                            <flux:button variant="ghost" wire:click="$set('showModal', false)">Cancel</flux:button>
                            <flux:button wire:click="$set('showModal', false)">Confirm</flux:button>
                        </div>
                    </x-slot>
                </div>
            </flux:modal>

            <flux:modal wire:model="showForm" name="form">
                <form wire:submit="save" class="space-y-6">
                    <div>
                        <flux:heading>Create component</flux:heading>
                        <flux:subheading>Saving closes the modal and raises a toast.</flux:subheading>
                    </div>

                    <flux:input wire:model="formName" label="Component name" placeholder="flux:button" />

                    <x-slot name="footer">
                        <div class="flex justify-end gap-2">
                            <flux:button type="button" variant="ghost" wire:click="$set('showForm', false)">Cancel</flux:button>
                            <flux:button type="submit">Save</flux:button>
                        </div>
                    </x-slot>
                </form>
            </flux:modal>

            <flux:modal wire:model="showConfirm" name="confirm">
                <div class="space-y-6">
                    <div>
                        <flux:heading>Delete this component?</flux:heading>
                        <flux:subheading>This cannot be undone.</flux:subheading>
                    </div>

                    <x-slot name="footer">
                        <div class="flex justify-end gap-2">
                            <flux:button variant="ghost" wire:click="$set('showConfirm', false)">Cancel</flux:button>
                            <flux:button color="red" wire:click="destroy">Delete</flux:button>
                        </div>
                    </x-slot>
                </div>
            </flux:modal>
        </x-lab.section>

        <x-lab.section heading="Toasts"
                      subheading="Dispatch a toast from any Livewire action.">
            <div class="flex flex-wrap items-center gap-2">
                <flux:button wire:click="$dispatch('toast', type: 'success', title: 'Success', description: 'Everything worked.')"
                             color="green" variant="subtle">Success</flux:button>
                <flux:button wire:click="$dispatch('toast', type: 'danger', title: 'Error', description: 'Something failed.')"
                             color="red" variant="subtle">Error</flux:button>
                <flux:button wire:click="$dispatch('toast', type: 'warning', title: 'Warning', description: 'Check the input.')"
                             color="amber" variant="subtle">Warning</flux:button>
                <flux:button wire:click="$dispatch('toast', type: 'info', title: 'Info', description: 'FYI only.')"
                             variant="subtle">Info</flux:button>
            </div>
        </x-lab.section>

        <x-lab.section heading="Callouts"
                      subheading="Inline, non-blocking messages.">
            <flux:callout variant="success" icon="check-circle" heading="All checks passed">
                <flux:text size="sm">The lab builds and serves on port 8003.</flux:text>
            </flux:callout>

            <flux:callout variant="warning" icon="exclamation-triangle" heading="Vite port is pinned">
                <flux:text size="sm">Changing the port means editing <code class="rounded bg-white/20 px-1">vite.config.js</code> and <code class="rounded bg-white/20 px-1">docker-compose.yml</code> together.</flux:text>
            </flux:callout>

            <flux:callout variant="danger" icon="exclamation-circle" heading="Pro components">
                <flux:text size="sm">Advanced components require a Flux Pro license.</flux:text>
            </flux:callout>
        </x-lab.section>

        <x-lab.section heading="Tooltip & popover"
                      subheading="Anchored contextual surfaces.">
            <div class="flex flex-wrap items-center gap-2">
                <flux:tooltip content="Shortcut: ⌘K" kbd position="top">
                    <flux:button variant="subtle">Hover for a shortcut hint</flux:button>
                </flux:tooltip>

                <flux:button class="relative" x-data="{ show: false }" x-on:click="show = ! show"
                             variant="subtle" icon-trailing="chevron-up">
                    Popover
                    <flux:popover x-show="show" x-cloak x-transition class="absolute z-10 w-64">
                        <flux:heading size="sm">Anchored panel</flux:heading>
                        <flux:text size="sm" class="mt-1">For richer content than a tooltip can hold.</flux:text>
                    </flux:popover>
                </flux:button>
            </div>
        </x-lab.section>
    </div>
