<div class="flex flex-col gap-6">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            variant=&quot;custom&quot; — a bare ui-select you assemble from the parts
        </flux:text>

        <flux:select wire:model="region" variant="custom" label="Region">
            <flux:select.button placeholder="Pick a region…" clearable />
            <flux:select.search icon="magnifying-glass" />
            <flux:select.options>
                <flux:select.group label="Europe">
                    <flux:select.option value="eu-west">EU West</flux:select.option>
                    <flux:select.option value="eu-central">EU Central</flux:select.option>
                </flux:select.group>

                <flux:select.group label="Americas">
                    <flux:select.option value="us-east">US East</flux:select.option>
                    <flux:select.option value="us-west">US West</flux:select.option>
                </flux:select.group>

                <flux:select.option.empty>No regions found.</flux:select.option.empty>
            </flux:select.options>
        </flux:select>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            The same, but with an input trigger instead of a button trigger
        </flux:text>

        <flux:select wire:model="region" variant="custom" label="Region">
            <flux:select.input placeholder="Filter regions…" clearable />
            <flux:select.options>
                <flux:select.option value="eu-west">EU West</flux:select.option>
                <flux:select.option value="eu-central">EU Central</flux:select.option>
                <flux:select.option value="us-east">US East</flux:select.option>
            </flux:select.options>
        </flux:select>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            select.indicator — check (default), checkbox and radio
        </flux:text>

        <div class="grid gap-4 md:grid-cols-3">
            <flux:select wire:model="licenses" variant="custom" label="check">
                <flux:select.button placeholder="Single…" />
                <flux:select.options>
                    <flux:select.option value="mit">MIT</flux:select.option>
                    <flux:select.option value="apache">Apache-2.0</flux:select.option>
                </flux:select.options>
            </flux:select>

            <flux:select wire:model="licenses" variant="custom" label="checkbox">
                <flux:select.button placeholder="Multiple…" />
                <flux:select.indicator variant="checkbox" />
                <flux:select.options>
                    <flux:select.option value="mit">MIT</flux:select.option>
                    <flux:select.option value="apache">Apache-2.0</flux:select.option>
                </flux:select.options>
            </flux:select>

            <flux:select wire:model="licenses" variant="custom" label="radio">
                <flux:select.button placeholder="One only…" />
                <flux:select.indicator variant="radio" />
                <flux:select.options>
                    <flux:select.option value="mit">MIT</flux:select.option>
                    <flux:select.option value="apache">Apache-2.0</flux:select.option>
                </flux:select.options>
            </flux:select>
        </div>
    </div>
</div>
