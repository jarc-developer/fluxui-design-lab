<flux:composer wire:model="body" label="Release notes" placeholder="Describe the release…">
    <x-slot name="header">
        <flux:badge size="sm" color="amber">Draft</flux:badge>
        <flux:spacer />
        <flux:text size="xs" class="text-zinc-500">Markdown supported</flux:text>
    </x-slot>

    <x-slot name="footer">
        <flux:text size="xs" class="text-zinc-500">Saved automatically</flux:text>
        <flux:spacer />
        <flux:button size="xs" variant="ghost">Discard</flux:button>
    </x-slot>

    <x-slot name="actionsLeading">
        <flux:button size="xs" square variant="subtle" icon="paper-clip" title="Attach" />
    </x-slot>

    <x-slot name="actionsTrailing">
        <flux:button size="xs" icon="paper-airplane" wire:click="send">Send</flux:button>
    </x-slot>
</flux:composer>
