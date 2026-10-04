<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Data Display')] class extends Component {
    public array $rows = [
        ['name' => 'flux:button', 'group' => 'Actions', 'tier' => 'Free', 'usage' => 482],
        ['name' => 'flux:select', 'group' => 'Forms', 'tier' => 'Free', 'usage' => 317],
        ['name' => 'flux:date-picker', 'group' => 'Forms', 'tier' => 'Pro', 'usage' => 96],
        ['name' => 'flux:modal', 'group' => 'Overlays', 'tier' => 'Free', 'usage' => 254],
        ['name' => 'flux:chart', 'group' => 'Data', 'tier' => 'Pro', 'usage' => 41],
        ['name' => 'flux:kanban', 'group' => 'Data', 'tier' => 'Pro', 'usage' => 18],
        ['name' => 'flux:editor', 'group' => 'Forms', 'tier' => 'Pro', 'usage' => 64],
        ['name' => 'flux:phone', 'group' => 'Forms', 'tier' => 'Pro', 'usage' => 27],
    ];

    public string $search = '';
    public string $statusFilter = 'all';
    public string $tableSearch = '';
    public string $sortField = 'usage';
    public string $sortDirection = 'desc';

    public array $chartSeries = [
        ['label' => 'Jan', 'usage' => 120],
        ['label' => 'Feb', 'usage' => 180],
        ['label' => 'Mar', 'usage' => 240],
        ['label' => 'Apr', 'usage' => 310],
        ['label' => 'May', 'usage' => 280],
        ['label' => 'Jun', 'usage' => 420],
    ];

    public string $tab = 'overview';
    public string $tabVariant = 'one';
    public string $tabFindable = 'overview';
    public string $accordion = 'one';
    public string $accordionOne = '';

    public function getFilteredRowsProperty(): array
    {
        return array_values(array_filter($this->rows, function (array $row): bool {
            $matchesSearch = $this->search === ''
                || str_contains(strtolower($row['name']), strtolower($this->search));

            $matchesStatus = $this->statusFilter === 'all'
                || $row['tier'] === $this->statusFilter;

            return $matchesSearch && $matchesStatus;
        }));
    }

    public function getPaginatorProperty(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $this->filteredRows,
            count($this->filteredRows),
            10,
            1,
            ['path' => request()->url()],
        );
    }

    public function getSortedRowsProperty(): array
    {
        // The page-level search narrows both tables; tableSearch narrows this one further.
        $rows = array_values(array_filter($this->rows, function (array $row): bool {
            return str_contains(strtolower($row['name']), strtolower($this->search))
                && ($this->tableSearch === ''
                    || str_contains(strtolower($row['name']), strtolower($this->tableSearch)));
        }));

        usort($rows, function (array $a, array $b): int {
            $result = $this->sortField === 'name'
                ? strcasecmp($a['name'], $b['name'])
                : $a['usage'] <=> $b['usage'];

            return $this->sortDirection === 'asc' ? $result : -$result;
        });

        return $rows;
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';

            return;
        }

        $this->sortField = $field;
        $this->sortDirection = 'asc';
    }
}; ?>

    <div class="flex w-full flex-1 flex-col gap-8">
        <x-lab.section heading="Cards"
                      subheading="The workhorse container, in every band treatment.">
            <x-lab.example src="lab.examples.data.cards"
                           title="Card variants"
                           subheading="default, separated, soft and filled — with card.header, card.body and card.footer." />

            <x-lab.example src="lab.examples.data.card-parts"
                           title="Card parts"
                           subheading="heading, subheading, actions, bleed and body used standalone, plus size and highlight." />
        </x-lab.section>

        <x-lab.section heading="Tabs"
                      subheading="Tab group with panels, from Flux Pro.">
            <x-lab.example src="lab.examples.data.tabs"
                           title="Tab variants and findable"
                           subheading="tabs takes size, variant and scrollable; tab.group findable adds type-to-jump navigation." />
        </x-lab.section>

        <x-lab.section heading="Accordion"
                      subheading="Disclosure group, from Flux Pro.">
            <x-lab.example src="lab.examples.data.accordion"
                           title="Variants and composed parts"
                           subheading="transition, expanded and disabled, plus accordion.heading, accordion.icon and accordion.content." />
        </x-lab.section>

        <x-lab.section heading="Table"
                      subheading="Live filtering bound to the table body.">
            <div class="grid gap-4 md:grid-cols-2">
                <flux:input wire:model.live="search" label="Search" icon="magnifying-glass"
                            placeholder="flux:button" />
                <flux:select wire:model.live="statusFilter" label="Tier">
                    <flux:select.option value="all">All</flux:select.option>
                    <flux:select.option value="Free">Free</flux:select.option>
                    <flux:select.option value="Pro">Pro</flux:select.option>
                </flux:select>
            </div>

            <div class="overflow-x-auto">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Component</flux:table.column>
                        <flux:table.column>Group</flux:table.column>
                        <flux:table.column>Tier</flux:table.column>
                        <flux:table.column align="end">Usages</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse ($this->filteredRows as $row)
                            <flux:table.row>
                                <flux:table.cell class="font-medium">{{ $row['name'] }}</flux:table.cell>
                                <flux:table.cell>{{ $row['group'] }}</flux:table.cell>
                                <flux:table.cell>
                                    <flux:badge size="sm" :color="$row['tier'] === 'Pro' ? 'amber' : 'green'">
                                        {{ $row['tier'] }}
                                    </flux:badge>
                                </flux:table.cell>
                                <flux:table.cell class="tabular-nums text-end">{{ $row['usage'] }}</flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="4" class="text-center text-zinc-500">No components match.</flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </div>
        </x-lab.section>

        <x-lab.section heading="Sortable table"
                      subheading="table.column sortable wired to a real sort, with its own search.">
            <x-lab.example :data="['sortField' => $sortField, 'sortDirection' => $sortDirection]"
                           src="lab.examples.data.table-sortable"
                           title="Sortable columns"
                           subheading="Click a sortable column header to flip the direction; the sorted order is reported underneath." />
        </x-lab.section>

        <x-lab.section heading="Pagination, progress & skeleton"
                      subheading="Compact feedback primitives.">
            <flux:progress value="65" class="max-w-md">65% of the library documented</flux:progress>

            <div class="flex max-w-md flex-col gap-2">
                <flux:skeleton.group class="flex flex-col gap-2">
                    <flux:skeleton.line class="h-4 w-3/4" />
                    <flux:skeleton.line class="h-4 w-1/2" />
                    <flux:skeleton.line class="h-4 w-2/3" />
                </flux:skeleton.group>
            </div>

            <div class="max-w-md">
                <flux:pagination :paginator="$this->paginator" />
            </div>
        </x-lab.section>

        <x-lab.section heading="Timeline"
                      subheading="Chronological status feed, from Flux Pro.">
            <x-lab.example src="lab.examples.data.timeline"
                           title="Orientation and parts"
                           subheading="vertical and horizontal, the four status values, indicator variants, and the block + subgrid shape for full-width children." />
        </x-lab.section>

        <x-lab.section heading="Nav lists"
                      subheading="Vertical navigation, as used in settings and the lab sidebar.">
            <div class="max-w-xs">
                <flux:navlist aria-label="Example navlist">
                    <flux:navlist.group heading="Components">
                        <flux:navlist.item :href="route('components.forms')" wire:navigate icon="cursor-arrow-rays">
                            Forms &amp; Inputs
                            <flux:navlist.badge size="sm">12</flux:navlist.badge>
                        </flux:navlist.item>
                        <flux:navlist.item :href="route('components.data')" wire:navigate icon="table-cells">
                            Data Display
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <flux:navlist.group heading="Pro" expandable>
                        <flux:navlist.item :href="route('components.editor')" wire:navigate icon="chat-bubble-oval-left">
                            Editor
                            <flux:navlist.badge size="sm" color="amber">Pro</flux:navlist.badge>
                        </flux:navlist.item>
                        <flux:navlist.item :href="route('components.charts')" wire:navigate icon="chart-bar">
                            Charts
                        </flux:navlist.item>
                    </flux:navlist.group>
                </flux:navlist>
            </div>
        </x-lab.section>

        <x-lab.section heading="Chart"
                      subheading="A first taste — the charts page covers the whole family.">
            <flux:chart :value="$chartSeries" class="h-64 max-w-2xl">
                <flux:chart.svg>
                    <flux:chart.line field="usage" class="text-blue-500" />

                    <flux:chart.axis axis="x" field="label">
                        <flux:chart.axis.tick />
                        <flux:chart.axis.line />
                    </flux:chart.axis>

                    <flux:chart.axis axis="y">
                        <flux:chart.axis.grid />
                        <flux:chart.axis.tick />
                    </flux:chart.axis>

                    <flux:chart.cursor />
                </flux:chart.svg>

                <flux:chart.tooltip>
                    <flux:chart.tooltip.heading field="label" />
                    <flux:chart.tooltip.value field="usage" label="Usages" />
                </flux:chart.tooltip>
            </flux:chart>

            <flux:button size="sm" variant="subtle" href="{{ route('components.charts') }}" wire:navigate class="self-start">
                Open the charts page
            </flux:button>
        </x-lab.section>
    </div>
