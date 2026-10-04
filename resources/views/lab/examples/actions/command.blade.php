<div class="flex flex-col gap-6">
    <div class="max-w-md">
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">Standalone command palette</flux:text>

        <flux:command>
            <flux:command.input placeholder="Search components…" clearable />

            <flux:command.items>
                <flux:command.item icon="cursor-arrow-ripple" kbd="⌘1">Input</flux:command.item>
                <flux:command.item icon="squares-2x2" kbd="⌘2">Select</flux:command.item>
                <flux:command.item icon="calendar-days">Date picker</flux:command.item>
                <flux:command.item icon="table-cells">Table</flux:command.item>
                <flux:command.item icon="chart-bar">Chart</flux:command.item>
            </flux:command.items>
        </flux:command>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">Modal command palette (with ⌘K shortcut)</flux:text>

        <flux:modal.trigger name="command-search" shortcut="cmd.k">
            <flux:input as="button" placeholder="Search commands..." icon="magnifying-glass" kbd="⌘K" class="max-w-xs" />
        </flux:modal.trigger>

        <flux:modal name="command-search" variant="bare" class="w-full max-w-[30rem] my-[12vh] max-h-screen overflow-y-hidden">
            <flux:command class="border-none shadow-lg inline-flex flex-col max-h-[76vh]">
                <flux:command.input placeholder="Type a command or search..." closable />

                <flux:command.items>
                    <flux:command.item icon="plus" kbd="⌘N">New component</flux:command.item>
                    <flux:command.item icon="document-duplicate" kbd="⌘D">Duplicate</flux:command.item>
                    <flux:command.item icon="arrow-path" kbd="⌘R">Reload assets</flux:command.item>
                    <flux:command.item icon="cog-6-tooth" kbd="⌘,">Settings</flux:command.item>
                </flux:command.items>
            </flux:command>
        </flux:modal>
    </div>
</div>
