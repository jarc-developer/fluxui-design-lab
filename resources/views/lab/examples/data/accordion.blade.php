<div class="flex flex-col gap-6">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">transition + expanded</flux:text>

        <flux:accordion wire:model="accordionOne" transition>
            <flux:accordion.item heading="What does transition do?">
                <flux:text size="sm">Animates the open and close rather than snapping.</flux:text>
            </flux:accordion.item>
            <flux:accordion.item heading="Can an item start open?">
                <flux:text size="sm">Yes — pass expanded to the item.</flux:text>
            </flux:accordion.item>
            <flux:accordion.item heading="Disabled items">
                <flux:text size="sm">A disabled item cannot be toggled.</flux:text>
            </flux:accordion.item>
            <flux:accordion.item heading="I am disabled" disabled>
                <flux:text size="sm">You cannot open me.</flux:text>
            </flux:accordion.item>
        </flux:accordion>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">Composed parts — heading, icon and content</flux:text>

        <flux:accordion variant="separated">
            <flux:accordion.item>
                <flux:accordion.heading>
                    <flux:accordion.icon />
                    A custom heading row
                </flux:accordion.heading>

                <flux:accordion.content>
                    <flux:text size="sm">
                        Replace the heading slot to control the row yourself, then drop the
                        content inside accordion.content.
                    </flux:text>
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading>
                    <flux:icon.beaker variant="micro" />
                    With an icon instead
                </flux:accordion.heading>

                <flux:accordion.content>
                    <flux:text size="sm">accordion.icon already includes the chevron affordance.</flux:text>
                </flux:accordion.content>
            </flux:accordion.item>
        </flux:accordion>
    </div>
</div>
