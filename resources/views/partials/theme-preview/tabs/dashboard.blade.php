@props([
    'dashboardSubTab' => 'overview',
])

<div class="flex flex-col gap-6">
    {{-- KPI Stat Cards --}}
    <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2 2xl:grid-cols-4">
        <flux:card class="flex flex-col justify-between gap-2 p-4 sm:p-5">
            <flux:text size="xs" class="font-medium text-zinc-500 dark:text-zinc-400">Total Revenue</flux:text>
            <div class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">$45,231</div>
            <div class="flex items-center gap-1.5 pt-0.5">
                <flux:badge color="green" size="sm">+12.5%</flux:badge>
                <span class="text-xs text-zinc-400 dark:text-zinc-500">vs last month</span>
            </div>
        </flux:card>

        <flux:card class="flex flex-col justify-between gap-2 p-4 sm:p-5">
            <flux:text size="xs" class="font-medium text-zinc-500 dark:text-zinc-400">New Customers</flux:text>
            <div class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">+2,350</div>
            <div class="flex items-center gap-1.5 pt-0.5">
                <flux:badge color="green" size="sm">+18.2%</flux:badge>
                <span class="text-xs text-zinc-400 dark:text-zinc-500">vs last month</span>
            </div>
        </flux:card>

        <flux:card class="flex flex-col justify-between gap-2 p-4 sm:p-5">
            <flux:text size="xs" class="font-medium text-zinc-500 dark:text-zinc-400">Active Users</flux:text>
            <div class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">12,789</div>
            <div class="flex items-center gap-1.5 pt-0.5">
                <flux:badge color="green" size="sm">+2.1%</flux:badge>
                <span class="text-xs text-zinc-400 dark:text-zinc-500">vs last month</span>
            </div>
        </flux:card>

        <flux:card class="flex flex-col justify-between gap-2 p-4 sm:p-5">
            <flux:text size="xs" class="font-medium text-zinc-500 dark:text-zinc-400">Growth Rate</flux:text>
            <div class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">4.5%</div>
            <div class="flex items-center gap-1.5 pt-0.5">
                <flux:badge color="amber" size="sm">+0.3%</flux:badge>
                <span class="text-xs text-zinc-400 dark:text-zinc-500">vs last month</span>
            </div>
        </flux:card>
    </div>

    {{-- Underline Sub-Tabs & Data View --}}
    <flux:tab.group>
        <flux:tabs wire:model="dashboardSubTab">
            <flux:tab name="overview" :selected="$dashboardSubTab === 'overview'">Overview</flux:tab>
            <flux:tab name="analytics" :selected="$dashboardSubTab === 'analytics'">Analytics</flux:tab>
            <flux:tab name="reports" :selected="$dashboardSubTab === 'reports'">Reports</flux:tab>
        </flux:tabs>

        <flux:tab.panel name="overview" :selected="$dashboardSubTab === 'overview'" class="pt-4">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Email</flux:table.column>
                    <flux:table.column>Role</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column align="end">Revenue</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    <flux:table.row>
                        <flux:table.cell variant="strong">Alice Johnson</flux:table.cell>
                        <flux:table.cell class="text-zinc-500 dark:text-zinc-400">alice@example.com</flux:table.cell>
                        <flux:table.cell class="text-zinc-500 dark:text-zinc-400">Admin</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge color="green" size="sm">Active</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end" class="tabular-nums font-medium">$12,450</flux:table.cell>
                    </flux:table.row>

                    <flux:table.row>
                        <flux:table.cell variant="strong">Bob Smith</flux:table.cell>
                        <flux:table.cell class="text-zinc-500 dark:text-zinc-400">bob@example.com</flux:table.cell>
                        <flux:table.cell class="text-zinc-500 dark:text-zinc-400">Editor</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge color="green" size="sm">Active</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end" class="tabular-nums font-medium">$8,320</flux:table.cell>
                    </flux:table.row>

                    <flux:table.row>
                        <flux:table.cell variant="strong">Clara Davis</flux:table.cell>
                        <flux:table.cell class="text-zinc-500 dark:text-zinc-400">clara@example.com</flux:table.cell>
                        <flux:table.cell class="text-zinc-500 dark:text-zinc-400">Designer</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge color="amber" size="sm">Pending</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end" class="tabular-nums font-medium">$6,190</flux:table.cell>
                    </flux:table.row>

                    <flux:table.row>
                        <flux:table.cell variant="strong">David Kim</flux:table.cell>
                        <flux:table.cell class="text-zinc-500 dark:text-zinc-400">david@example.com</flux:table.cell>
                        <flux:table.cell class="text-zinc-500 dark:text-zinc-400">Viewer</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge color="blue" size="sm">Invited</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end" class="tabular-nums font-medium">$4,080</flux:table.cell>
                    </flux:table.row>
                </flux:table.rows>
            </flux:table>
        </flux:tab.panel>

        <flux:tab.panel name="analytics" :selected="$dashboardSubTab === 'analytics'" class="pt-4">
            <flux:card class="flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:heading size="sm">Acquisition Channels</flux:heading>
                        <flux:subheading>Share of monthly active signups by traffic source.</flux:subheading>
                    </div>
                    <flux:badge color="blue" size="sm">Last 30 days</flux:badge>
                </div>

                <div class="flex flex-col gap-3">
                    @foreach ([
                        ['label' => 'Organic Search', 'share' => '48%', 'visitors' => '6,138'],
                        ['label' => 'Direct & Brand', 'share' => '29%', 'visitors' => '3,709'],
                        ['label' => 'Product Referrals', 'share' => '16%', 'visitors' => '2,046'],
                        ['label' => 'Social Campaigns', 'share' => '7%', 'visitors' => '896'],
                    ] as $channel)
                        <div class="flex flex-col gap-1.5">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-medium text-zinc-700 dark:text-zinc-200">{{ $channel['label'] }}</span>
                                <span class="tabular-nums text-zinc-500">{{ $channel['visitors'] }} ({{ $channel['share'] }})</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">
                                <div class="h-full rounded-full bg-(--color-accent)" style="width: {{ $channel['share'] }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </flux:card>
        </flux:tab.panel>

        <flux:tab.panel name="reports" :selected="$dashboardSubTab === 'reports'" class="pt-4">
            <div class="flex flex-col gap-2.5">
                @foreach ([
                    ['title' => 'Q4 Revenue & Retention Summary', 'date' => 'Updated 2 hours ago', 'badge' => 'Ready', 'color' => 'green'],
                    ['title' => 'Enterprise Cohort Conversion Audit', 'date' => 'Updated yesterday', 'badge' => 'Ready', 'color' => 'green'],
                    ['title' => 'Annual Tax & Payout Reconciliation', 'date' => 'Scheduled tomorrow', 'badge' => 'Queued', 'color' => 'amber'],
                ] as $report)
                    <flux:card class="flex flex-wrap items-center justify-between gap-3 p-4">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $report['title'] }}</span>
                            <span class="text-xs text-zinc-500">{{ $report['date'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <flux:badge :color="$report['color']" size="sm">{{ $report['badge'] }}</flux:badge>
                            <flux:button type="button" variant="subtle" size="xs" icon="arrow-down-tray">Export</flux:button>
                        </div>
                    </flux:card>
                @endforeach
            </div>
        </flux:tab.panel>
    </flux:tab.group>
</div>
