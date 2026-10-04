<div class="flex flex-wrap items-center gap-2">
    <flux:modal.trigger name="confirm-modal">
        <flux:button color="red" variant="subtle">Open with a trigger button</flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-modal" class="max-w-sm">
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

    <flux:modal.trigger name="titled-modal">
        <flux:button variant="subtle">With a close button</flux:button>
    </flux:modal.trigger>

    <flux:modal name="titled-modal" class="max-w-sm">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Custom title</flux:heading>
                <flux:subheading>
                    modal.close places a dismiss control wherever you want it.
                </flux:subheading>
            </div>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="subtle" size="sm">Close</flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>

    <flux:modal.trigger name="flyout-modal">
        <flux:button variant="subtle">Flyout panel</flux:button>
    </flux:modal.trigger>

    <flux:modal name="flyout-modal" flyout class="space-y-6">
        <div>
            <flux:heading size="lg">Flyout panel</flux:heading>
            <flux:subheading>Slides in anchored to the edge of the viewport.</flux:subheading>
        </div>

        <flux:text size="sm">
            Flyouts are ideal for longer workflows, drawer navigation, or inspector sheets.
        </flux:text>

        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">Close</flux:button>
            </flux:modal.close>
        </div>
    </flux:modal>
</div>
