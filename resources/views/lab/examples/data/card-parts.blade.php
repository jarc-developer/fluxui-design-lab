<div class="grid gap-4 md:grid-cols-2">
    <flux:card>
        <flux:card.header>
            <flux:card.heading>With actions</flux:card.heading>
            <flux:card.actions>
                <flux:button size="xs" variant="subtle" square icon="cog" title="Settings" />
            </flux:card.actions>
        </flux:card.header>

        <flux:card.body>
            <flux:text size="sm">card.actions sits at the end of the header row.</flux:text>
        </flux:card.body>
    </flux:card>

    <flux:card class="gap-2">
        <flux:card.heading>Standalone parts</flux:card.heading>
        <flux:card.subheading>Without a header or body band</flux:card.subheading>

        <flux:text size="sm">
            card.heading, card.subheading and card.body can be used directly on a bare card.
        </flux:text>
    </flux:card>

    <flux:card body="inset">
        <flux:card.body>
            <flux:card.bleed>
                <div class="grid h-24 place-items-center bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-t-[calc(var(--flux-card-radius)-1px)]">
                    <flux:text class="font-semibold text-white">flux:card.bleed</flux:text>
                </div>
            </flux:card.bleed>

            <flux:text size="sm" class="pt-3">Bleed extends media to the card edges automatically.</flux:text>
        </flux:card.body>
    </flux:card>

    <flux:card size="sm" :highlight="false">
        <flux:card.header>
            <flux:card.heading>Small, no highlight</flux:card.heading>
            <flux:card.subheading>size="sm" :highlight="false"</flux:card.subheading>
        </flux:card.header>

        <flux:card.body>
            <flux:text size="sm">size scales padding and highlight controls edge shine.</flux:text>
        </flux:card.body>
    </flux:card>
</div>

