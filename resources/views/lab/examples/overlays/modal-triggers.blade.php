<div class="flex flex-wrap items-center gap-2">
    <flux:modal.trigger name="confirm-modal">
        <flux:button color="red" variant="subtle">Open with a trigger button</flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-modal" class="max-w-sm">
        <flux:heading>Delete this component?</flux:heading>
        <flux:subheading>This cannot be undone.</flux:subheading>

        <div class="flex justify-end gap-2">
            <flux:button variant="ghost" x-on:click="$el.closest('[data-flux-modal]').close()">Cancel</flux:button>
            <flux:button color="red" wire:click="destroy">Delete</flux:button>
        </div>
    </flux:modal>

    <flux:modal.trigger name="titled-modal">
        <flux:button variant="subtle">With a close button</flux:button>
    </flux:modal.trigger>

    <flux:modal name="titled-modal" class="max-w-sm">
        <flux:heading>Custom title</flux:heading>
        <flux:text size="sm" class="text-zinc-500">
            modal.close places a dismiss control wherever you want it.
        </flux:text>

        <x-slot name="footer">
            <flux:modal.close>
                <flux:button variant="subtle" size="sm">Close</flux:button>
            </flux:modal.close>
        </x-slot>
    </flux:modal>
</div>
