<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">tabs variants</flux:text>

        <div class="flex flex-col gap-4">
            <flux:tab.group wire:model="tabVariant">
                <flux:tabs>
                    <flux:tab name="one" icon="squares-2x2">One</flux:tab>
                    <flux:tab name="two" icon="bolt">Two</flux:tab>
                    <flux:tab name="three" icon="cog">Three</flux:tab>
                </flux:tabs>

                <div class="mt-4">
                    <flux:tab.panel name="one"><flux:text size="sm">Default tabs.</flux:text></flux:tab.panel>
                    <flux:tab.panel name="two"><flux:text size="sm">Second panel.</flux:text></flux:tab.panel>
                    <flux:tab.panel name="three"><flux:text size="sm">Third panel.</flux:text></flux:tab.panel>
                </div>
            </flux:tab.group>
        </div>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            findable — type to jump between tabs with the keyboard
        </flux:text>

        <flux:tab.group wire:model="tabFindable" findable>
            <flux:tabs variant="pills" size="sm">
                <flux:tab name="overview">Overview</flux:tab>
                <flux:tab name="activity">Activity</flux:tab>
                <flux:tab name="settings">Settings</flux:tab>
            </flux:tabs>

            <div class="mt-4">
                <flux:tab.panel name="overview"><flux:text size="sm">Overview panel.</flux:text></flux:tab.panel>
                <flux:tab.panel name="activity"><flux:text size="sm">Activity panel.</flux:text></flux:tab.panel>
                <flux:tab.panel name="settings"><flux:text size="sm">Settings panel.</flux:text></flux:tab.panel>
            </div>
        </flux:tab.group>
    </div>
</div>
