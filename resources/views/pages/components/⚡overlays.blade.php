<?php

use Flux\Flux;
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

        Flux::toast(
            text: $this->formName !== '' ? "Created “{$this->formName}”." : 'Draft saved.',
            heading: 'Saved',
            variant: 'success',
        );
    }

    public function destroy(): void
    {
        $this->showConfirm = false;

        Flux::toast(
            text: 'The demo record was removed.',
            heading: 'Deleted',
            variant: 'danger',
        );
    }
}; ?>

    <div class="flex w-full flex-1 flex-col gap-8">
        <x-lab.section heading="Modal"
                      subheading="Bound with wire:model or triggered declaratively, with close controls.">
            <div class="flex flex-wrap items-center gap-2">
                <flux:button wire:click="$set('showModal', true)">Open modal</flux:button>
                <flux:button wire:click="$set('showForm', true)" variant="subtle">Open with a form</flux:button>
                <flux:button wire:click="$set('showConfirm', true)" color="red" variant="subtle">Open confirmation</flux:button>
            </div>

            <flux:modal wire:model="showModal" name="demo">
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">Modal dialog</flux:heading>
                        <flux:subheading>Scrollable body with action buttons.</flux:subheading>
                    </div>

                    <flux:text size="sm">
                        Modals trap focus, close on escape, and lock background scroll. Keep the body short
                        so action controls stay reachable.
                    </flux:text>

                    <div class="flex gap-2">
                        <flux:spacer />
                        <flux:modal.close>
                            <flux:button variant="ghost">Cancel</flux:button>
                        </flux:modal.close>
                        <flux:button wire:click="$set('showModal', false)">Confirm</flux:button>
                    </div>
                </div>
            </flux:modal>

            <flux:modal wire:model="showForm" name="form">
                <form wire:submit="save" class="space-y-6">
                    <div>
                        <flux:heading size="lg">Create component</flux:heading>
                        <flux:subheading>Saving closes the modal and raises a toast.</flux:subheading>
                    </div>

                    <flux:input wire:model="formName" label="Component name" placeholder="flux:button" />

                    <div class="flex gap-2">
                        <flux:spacer />
                        <flux:modal.close>
                            <flux:button type="button" variant="ghost">Cancel</flux:button>
                        </flux:modal.close>
                        <flux:button type="submit" variant="primary">Save</flux:button>
                    </div>
                </form>
            </flux:modal>

            <flux:modal wire:model="showConfirm" name="confirm">
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">Delete this component?</flux:heading>
                        <flux:subheading>This cannot be undone.</flux:subheading>
                    </div>

                    <div class="flex gap-2">
                        <flux:spacer />
                        <flux:modal.close>
                            <flux:button variant="ghost">Cancel</flux:button>
                        </flux:modal.close>
                        <flux:button variant="danger" wire:click="destroy">Delete</flux:button>
                    </div>
                </div>
            </flux:modal>

            <x-lab.example src="lab.examples.overlays.modal-triggers"
                           title="Declarative modals & flyouts"
                           subheading="Drive modals with flux:modal.trigger or wire:model. The flyout prop anchors the dialog to the viewport edge." />
        </x-lab.section>

        <x-lab.section heading="Toasts"
                      subheading="Trigger toasts using Flux::toast() from PHP or $flux.toast() from Alpine.">
            <x-lab.example src="lab.examples.overlays.toasts"
                           title="Toast variants"
                           subheading="success, danger, warning, and info variants, with optional headings and interactive actions." />
        </x-lab.section>

        <x-lab.section heading="Callouts"
                      subheading="Inline, non-blocking messages with heading, text, link and action slots.">
            <div class="flex flex-col gap-4">
                <flux:callout variant="success" icon="check-circle" heading="All checks passed">
                    <flux:callout.text>The lab builds and serves on port 8003.</flux:callout.text>
                </flux:callout>

                <flux:callout variant="warning" icon="exclamation-triangle" heading="Vite port is pinned" inline>
                    <flux:callout.text>Changing the port means editing vite.config.js and docker-compose.yml together.</flux:callout.text>
                    <x-slot name="actions">
                        <flux:button size="xs">View config</flux:button>
                    </x-slot>
                </flux:callout>

                <flux:callout variant="danger" icon="exclamation-circle" heading="Pro components">
                    <flux:callout.text>
                        Advanced components require a Flux Pro license.
                        <flux:callout.link href="https://fluxui.dev/pricing" external>Pricing</flux:callout.link>
                    </flux:callout.text>
                </flux:callout>
            </div>
        </x-lab.section>

        <x-lab.section heading="Tooltip & popover"
                      subheading="Anchored contextual surfaces.">
            <x-lab.example src="lab.examples.actions.tooltips"
                           title="Tooltips"
                           subheading="Hover hints with positions, keyboard shortcuts, toggleable pinning and interactive contents." />

            <x-lab.example src="lab.examples.actions.popover"
                           title="flux:popover & flux:context"
                           subheading="Popovers are rich floating panels hosted inside flux:dropdown. Right-click context menus are wrapped by flux:context." />
        </x-lab.section>
    </div>
