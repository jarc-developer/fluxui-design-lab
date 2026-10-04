<div class="flex flex-wrap items-center gap-4">
    <flux:dropdown position="bottom" align="start">
        <flux:button icon-trailing="chevron-down">Click for popover</flux:button>

        <flux:popover class="flex flex-col gap-3 min-w-64">
            <flux:heading size="sm">Filter settings</flux:heading>
            <flux:text size="sm" class="text-zinc-500">
                flux:popover displays rich floating content, hosted inside a flux:dropdown container.
            </flux:text>
            <flux:separator variant="subtle" />
            <div class="flex justify-end gap-2">
                <flux:button size="sm" variant="primary">Apply</flux:button>
            </div>
        </flux:popover>
    </flux:dropdown>

    <flux:dropdown hover position="bottom" align="start">
        <flux:button variant="subtle" icon="information-circle">Hover for info</flux:button>

        <flux:popover class="flex flex-col gap-1 max-w-64">
            <flux:heading size="sm">Hover trigger</flux:heading>
            <flux:text size="sm" class="text-zinc-500">
                Use the hover prop on flux:dropdown to open the popover on mouseover.
            </flux:text>
        </flux:popover>
    </flux:dropdown>

    <flux:context>
        <flux:card class="border-dashed border-2 px-6 py-3 cursor-context-menu">
            <flux:text size="sm" class="text-zinc-500">Right click here for context menu</flux:text>
        </flux:card>

        <flux:menu>
            <flux:menu.item icon="arrow-path">Reload</flux:menu.item>
            <flux:menu.separator />
            <flux:menu.item variant="danger" icon="trash">Delete</flux:menu.item>
        </flux:menu>
    </flux:context>
</div>
