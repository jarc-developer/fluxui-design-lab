<div class="flex flex-col gap-6">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            variant=&quot;combobox&quot; — an input you type into, with the list filtering as you go.
            A combobox is searchable by definition and single-select by definition, so it rejects
            both searchable and multiple.
        </flux:text>

        <flux:select wire:model="framework" variant="combobox" label="Framework"
                    placeholder="Search frameworks…">
            <flux:select.option value="livewire">Livewire</flux:select.option>
            <flux:select.option value="inertia">Inertia</flux:select.option>
            <flux:select.option value="volt">Volt</flux:select.option>

            <flux:select.group label="Others">
                <flux:select.option value="alpine">Alpine</flux:select.option>
                <flux:select.option value="htmx">HTMX</flux:select.option>
            </flux:select.group>
        </flux:select>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            variant=&quot;combobox&quot; clearable, with option.create
        </flux:text>

        <flux:select wire:model="framework" variant="combobox" label="Stack" clearable
                    placeholder="Add a stack…">
            <flux:select.option value="php">PHP</flux:select.option>
            <flux:select.option value="js">JavaScript</flux:select.option>
            <flux:select.option value="go">Go</flux:select.option>

            <flux:select.option.create>Add a new stack</flux:select.option.create>
        </flux:select>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            variant=&quot;listbox&quot; — a button trigger plus a popover list
        </flux:text>

        <flux:select wire:model="status" variant="listbox" label="Status" placeholder="Choose a status…">
            <flux:select.option value="backlog">Backlog</flux:select.option>
            <flux:select.option value="active">Active</flux:select.option>
            <flux:select.option value="paused">Paused</flux:select.option>
            <flux:select.option value="done">Done</flux:select.option>
        </flux:select>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            variant=&quot;listbox&quot; searchable, with a custom empty message
        </flux:text>

        <flux:select wire:model="status" variant="listbox" label="Status" searchable
                    empty="No status matches that search.">
            <flux:select.option value="backlog">Backlog</flux:select.option>
            <flux:select.option value="active">Active</flux:select.option>
        </flux:select>
    </div>
</div>
