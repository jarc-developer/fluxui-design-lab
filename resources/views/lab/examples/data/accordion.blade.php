<div class="flex flex-col gap-6">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">transition + exclusive (single open at a time)</flux:text>

        <flux:accordion transition exclusive>
            <flux:accordion.item heading="What does transition do?" expanded>
                <flux:text size="sm">Animates the open and close rather than snapping.</flux:text>
            </flux:accordion.item>
            <flux:accordion.item heading="What does exclusive do?">
                <flux:text size="sm">Ensures only a single accordion item is expanded at a time.</flux:text>
            </flux:accordion.item>
            <flux:accordion.item heading="Disabled items" disabled>
                <flux:text size="sm">A disabled item cannot be toggled open or closed.</flux:text>
            </flux:accordion.item>
        </flux:accordion>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">variant="reverse" (leading icon) and composed parts</flux:text>

        <flux:accordion variant="reverse">
            <flux:accordion.item>
                <flux:accordion.heading>
                    What are composed parts?
                </flux:accordion.heading>

                <flux:accordion.content>
                    <flux:text size="sm">
                        Use flux:accordion.heading and flux:accordion.content directly when you need
                        rich content in the header row or complex disclosures.
                    </flux:text>
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading>
                    <span class="inline-flex items-center gap-2">
                        <flux:icon.beaker variant="micro" class="text-zinc-500" />
                        Custom heading with an icon prefix
                    </span>
                </flux:accordion.heading>

                <flux:accordion.content>
                    <flux:text size="sm">
                        flux:accordion.heading automatically renders the animated chevron.
                        variant="reverse" positions it at the leading edge.
                    </flux:text>
                </flux:accordion.content>
            </flux:accordion.item>
        </flux:accordion>
    </div>
</div>

