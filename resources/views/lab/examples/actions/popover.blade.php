<div class="flex flex-wrap items-center gap-2">
    <flux:context position="bottom start">
        <flux:button icon-trailing="chevron-up">Popover</flux:button>

        <flux:popover>
            <div class="flex flex-col gap-1">
                <flux:heading size="sm">Anchored panel</flux:heading>
                <flux:text size="sm" class="text-zinc-500">
                    flux:context anchors and positions flux:popover. Clicking anywhere in the
                    context toggles it, so keep the content non-interactive — use flux:dropdown
                    with flux:menu when you need buttons inside.
                </flux:text>
            </div>
        </flux:popover>
    </flux:context>

    <flux:context position="right start" gap="12">
        <flux:button variant="subtle" icon="information-circle">Right side</flux:button>

        <flux:popover>
            <flux:text size="sm" class="block max-w-56">
                flux:context also takes position, gap and offset, and accepts wire:model if you
                want to drive the open state from the server.
            </flux:text>
        </flux:popover>
    </flux:context>
</div>
