<div class="flex flex-wrap items-center gap-2">
    <flux:tooltip content="A plain tooltip">
        <flux:button variant="subtle">Hover</flux:button>
    </flux:tooltip>

    <flux:tooltip content="Open palette" kbd="⌘K" position="top">
        <flux:button variant="subtle">With a kbd hint</flux:button>
    </flux:tooltip>

    <flux:tooltip toggleable content="Click to pin, click again to release">
        <flux:button variant="subtle">Toggleable</flux:button>
    </flux:tooltip>

    <flux:tooltip interactive content="You can hover into this one">
        <flux:button variant="subtle">Interactive</flux:button>
    </flux:tooltip>

    <flux:tooltip position="right" content="Positioned right">
        <flux:button variant="subtle" size="sm">Right</flux:button>
    </flux:tooltip>
</div>
