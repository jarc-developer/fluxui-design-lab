<div class="grid gap-4 md:grid-cols-2">
    <flux:input.group>
        <flux:input.group.prefix>
            <flux:icon.magnifying-glass variant="micro" />
        </flux:input.group.prefix>

        <flux:input placeholder="Grouped input" />

        <flux:input.group.affix>
            <flux:badge size="sm" color="zinc">new</flux:badge>
        </flux:input.group.affix>

        <flux:input.group.suffix>
            <flux:icon.x-mark variant="micro" />
        </flux:input.group.suffix>
    </flux:input.group>

    <flux:input.group>
        <flux:input.group.prefix>
            <flux:text size="sm" class="text-zinc-500">https://</flux:text>
        </flux:input.group.prefix>

        <flux:input placeholder="design-lab.test" />

        <flux:input.group.suffix>
            <flux:button size="sm" square variant="subtle" icon="arrow-top-right-on-square" title="Open" />
        </flux:input.group.suffix>
    </flux:input.group>
</div>
