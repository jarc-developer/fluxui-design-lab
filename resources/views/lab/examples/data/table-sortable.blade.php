<div class="flex flex-col gap-4">
    <div class="max-w-md">
        <flux:input wire:model.live="tableSearch" label="Search" icon="magnifying-glass"
                    placeholder="flux:button" />
    </div>

    <div class="overflow-x-auto">
        <flux:table>
            <flux:table.columns>
                <flux:table.column sortable :sorted="$sortField === 'name'" :direction="$sortDirection" wire:click="sortBy('name')">Component</flux:table.column>
                <flux:table.column>Group</flux:table.column>
                <flux:table.column sortable :sorted="$sortField === 'usage'" :direction="$sortDirection" wire:click="sortBy('usage')" align="end">Usages</flux:table.column>
                <flux:table.column align="end">Tier</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->sortedRows as $row)
                    <flux:table.row :key="$row['name']">
                        <flux:table.cell variant="strong">{{ $row['name'] }}</flux:table.cell>
                        <flux:table.cell>{{ $row['group'] }}</flux:table.cell>
                        <flux:table.cell align="end" class="tabular-nums">{{ $row['usage'] }}</flux:table.cell>
                        <flux:table.cell align="end">
                            <flux:badge size="sm" :color="$row['tier'] === 'Pro' ? 'amber' : 'green'">
                                {{ $row['tier'] }}
                            </flux:badge>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="4" class="text-center text-zinc-500">No components match.</flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    <flux:text size="xs" class="text-zinc-500">
        Sorted by {{ $sortField }} {{ $sortDirection === 'asc' ? 'ascending' : 'descending' }}
    </flux:text>
</div>

