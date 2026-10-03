<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Data Display')] class extends Component {
    public array $rows = [
        ['name' => 'flux:button', 'group' => 'Actions', 'status' => 'Stable', 'usage' => '482'],
        ['name' => 'flux:select', 'group' => 'Forms', 'status' => 'Stable', 'usage' => '317'],
        ['name' => 'flux:date-picker', 'group' => 'Forms', 'status' => 'Pro', 'usage' => '96'],
        ['name' => 'flux:modal', 'group' => 'Overlays', 'status' => 'Stable', 'usage' => '254'],
        ['name' => 'flux:chart', 'group' => 'Data', 'status' => 'Pro', 'usage' => '41'],
        ['name' => 'flux:kanban', 'group' => 'Data', 'status' => 'Pro', 'usage' => '18'],
    ];

    public string $search = '';
    public string $statusFilter = 'all';
    public array $chartSeries = [
        ['label' => 'Jan', 'usage' => 120],
        ['label' => 'Feb', 'usage' => 180],
        ['label' => 'Mar', 'usage' => 240],
        ['label' => 'Apr', 'usage' => 310],
        ['label' => 'May', 'usage' => 280],
        ['label' => 'Jun', 'usage' => 420],
    ];

    public string $tab = 'overview';
    public string $accordion = 'one';
    public int $slide = 0;

    public function getFilteredRowsProperty(): array
    {
        return array_values(array_filter($this->rows, function (array $row): bool {
            $matchesSearch = $this->search === ''
                || str_contains(strtolower($row['name']), strtolower($this->search));

            $matchesStatus = $this->statusFilter === 'all'
                || $row['status'] === $this->statusFilter;

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
}; ?>

    <div class="flex w-full flex-1 flex-col gap-8">
        <x-lab.section heading="Tabs"
                      subheading="Tab group with panels, from Flux Pro.">
            <flux:tab.group wire:model="tab">
                <flux:tabs variant="pills">
                    <flux:tab name="overview" icon="squares-2x2">Overview</flux:tab>
                    <flux:tab name="activity" icon="bolt">Activity</flux:tab>
                    <flux:tab name="settings" icon="cog">Settings</flux:tab>
                </flux:tabs>

                <div class="mt-4">
                    <flux:tab.panel name="overview">
                        <flux:text>Overview panel — component counts, usage and ownership at a glance.</flux:text>
                    </flux:tab.panel>
                    <flux:tab.panel name="activity">
                        <flux:text>Activity panel — recent changes and publish events.</flux:text>
                    </flux:tab.panel>
                    <flux:tab.panel name="settings">
                        <flux:text>Settings panel — per-component configuration.</flux:text>
                    </flux:tab.panel>
                </div>
            </flux:tab.group>
        </x-lab.section>

        <x-lab.section heading="Accordion"
                      subheading="Disclosure group, from Flux Pro.">
            <flux:accordion wire:model="accordion">
                <flux:accordion.item heading="What is Flux?">
                    <flux:text size="sm">The official UI component library for Livewire.</flux:text>
                </flux:accordion.item>
                <flux:accordion.item heading="What is Flux Pro?">
                    <flux:text size="sm">The commercial tier with advanced components such as the chart, date picker, editor and kanban.</flux:text>
                </flux:accordion.item>
                <flux:accordion.item heading="How do I customize a component?">
                    <flux:text size="sm">Publish its Blade view with <code class="rounded bg-zinc-100 px-1 dark:bg-white/10">php artisan flux:publish &lt;component&gt;</code> and edit the copy.</flux:text>
                </flux:accordion.item>
            </flux:accordion>
        </x-lab.section>

        <x-lab.section heading="Table"
                      subheading="Live filtering bound to the table body.">
            <div class="grid gap-4 md:grid-cols-2">
                <flux:input wire:model.live="search" label="Search" icon="magnifying-glass"
                            placeholder="flux:button" />
                <flux:select wire:model.live="statusFilter" label="Status">
                    <flux:select.option value="all">All</flux:select.option>
                    <flux:select.option value="Stable">Stable</flux:select.option>
                    <flux:select.option value="Pro">Pro</flux:select.option>
                </flux:select>
            </div>

            <div class="overflow-x-auto">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Component</flux:table.column>
                        <flux:table.column>Group</flux:table.column>
                        <flux:table.column>Status</flux:table.column>
                        <flux:table.column align="end">Usages</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse ($this->filteredRows as $row)
                            <flux:table.row>
                                <flux:table.cell class="font-medium">{{ $row['name'] }}</flux:table.cell>
                                <flux:table.cell>{{ $row['group'] }}</flux:table.cell>
                                <flux:table.cell>
                                    <flux:badge size="sm" :color="$row['status'] === 'Pro' ? 'amber' : 'green'">
                                        {{ $row['status'] }}
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

        <x-lab.section heading="Pagination, progress & skeleton"
                      subheading="Compact feedback primitives.">
            <flux:progress value="65" class="max-w-md">65% of the library documented</flux:progress>

            <div class="flex max-w-md flex-col gap-2">
                <flux:skeleton class="h-4 w-3/4" />
                <flux:skeleton class="h-4 w-1/2" />
                <flux:skeleton class="h-4 w-2/3" />
            </div>

            <div class="max-w-md">
                <flux:pagination :paginator="$this->paginator" />
            </div>
        </x-lab.section>

        <x-lab.section heading="Timeline"
                      subheading="Chronological status feed, from Flux Pro.">
            <flux:timeline>
                <flux:timeline.item status="complete" title="Project scaffolded">
                    <flux:text size="sm">Laravel 13 with the Livewire Starter Kit.</flux:text>
                </flux:timeline.item>
                <flux:timeline.item status="complete" title="Flux Pro activated">
                    <flux:text size="sm">License applied via <code class="rounded bg-zinc-100 px-1 dark:bg-white/10">flux:activate</code>.</flux:text>
                </flux:timeline.item>
                <flux:timeline.item title="Catalog pages in progress">
                    <flux:text size="sm">Iterating on component coverage.</flux:text>
                </flux:timeline.item>
            </flux:timeline>
        </x-lab.section>

        <x-lab.section heading="Chart"
                      subheading="Pro charting component rendering a static series.">
            <flux:chart :value="$chartSeries" class="h-64 max-w-2xl">
                <flux:chart.viewport>
                    <flux:chart.line field="usage" label="Component usage" />
                    <flux:chart.axis axis="x" field="label" />
                    <flux:chart.axis axis="y" field="usage" />
                    <flux:chart.tooltip>
                        <flux:chart.tooltip.heading field="label" />
                        <flux:chart.tooltip.value field="usage" label="Usages" />
                    </flux:chart.tooltip>
                </flux:chart.viewport>
            </flux:chart>
        </x-lab.section>
    </div>
