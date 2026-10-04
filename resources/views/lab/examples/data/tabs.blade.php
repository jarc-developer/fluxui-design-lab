<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">Tab variants — default, segmented and pills</flux:text>

        <div class="flex flex-col gap-6">
            {{-- Default underline tabs with wire:model --}}
            <flux:tab.group>
                <flux:tabs wire:model="tabVariant">
                    <flux:tab name="one" icon="squares-2x2">Default (one)</flux:tab>
                    <flux:tab name="two" icon="bolt">Two</flux:tab>
                    <flux:tab name="three" icon="cog">Three</flux:tab>
                </flux:tabs>

                <div class="mt-3">
                    <flux:tab.panel name="one"><flux:text size="sm">Default underline tabs with wire:model binding.</flux:text></flux:tab.panel>
                    <flux:tab.panel name="two"><flux:text size="sm">Second panel content.</flux:text></flux:tab.panel>
                    <flux:tab.panel name="three"><flux:text size="sm">Third panel content.</flux:text></flux:tab.panel>
                </div>
            </flux:tab.group>

            {{-- Segmented tabs --}}
            <flux:tab.group>
                <flux:tabs variant="segmented" size="sm">
                    <flux:tab name="list" icon="list-bullet">List</flux:tab>
                    <flux:tab name="board" icon="squares-2x2">Board</flux:tab>
                    <flux:tab name="timeline" icon="calendar-days">Timeline</flux:tab>
                </flux:tabs>

                <div class="mt-3">
                    <flux:tab.panel name="list"><flux:text size="sm">Segmented tab list view.</flux:text></flux:tab.panel>
                    <flux:tab.panel name="board"><flux:text size="sm">Segmented tab board view.</flux:text></flux:tab.panel>
                    <flux:tab.panel name="timeline"><flux:text size="sm">Segmented tab timeline view.</flux:text></flux:tab.panel>
                </div>
            </flux:tab.group>
        </div>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            Pill tabs with findable — ⌘F search automatically switches active tab
        </flux:text>

        <flux:tab.group findable>
            <flux:tabs variant="pills" wire:model="tabFindable">
                <flux:tab name="overview">Overview</flux:tab>
                <flux:tab name="activity">Activity</flux:tab>
                <flux:tab name="settings">Settings</flux:tab>
            </flux:tabs>

            <div class="mt-4">
                <flux:tab.panel name="overview"><flux:text size="sm">Findable overview panel details.</flux:text></flux:tab.panel>
                <flux:tab.panel name="activity"><flux:text size="sm">Findable user activity logs and records.</flux:text></flux:tab.panel>
                <flux:tab.panel name="settings"><flux:text size="sm">Findable application preferences and configuration.</flux:text></flux:tab.panel>
            </div>
        </flux:tab.group>
    </div>
</div>

