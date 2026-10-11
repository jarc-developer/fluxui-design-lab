@props([
    'viewport' => 'desktop',
])

@php
    $chartData = [
        ['label' => 'Jan', 'revenue' => 18400, 'target' => 15000],
        ['label' => 'Feb', 'revenue' => 22100, 'target' => 18000],
        ['label' => 'Mar', 'revenue' => 26800, 'target' => 22000],
        ['label' => 'Apr', 'revenue' => 31200, 'target' => 25000],
        ['label' => 'May', 'revenue' => 38900, 'target' => 30000],
        ['label' => 'Jun', 'revenue' => 45230, 'target' => 35000],
    ];

    $recentOrders = [
        ['id' => 'ORD-7291', 'customer' => 'Sophia Martinez', 'email' => 'sophia@example.com', 'amount' => '$450.00', 'status' => 'Paid', 'date' => 'Today, 2:45 PM'],
        ['id' => 'ORD-7290', 'customer' => 'Liam Henderson', 'email' => 'liam.h@work.co', 'amount' => '$1,280.00', 'status' => 'Processing', 'date' => 'Today, 1:12 PM'],
        ['id' => 'ORD-7289', 'customer' => 'Emma Watson', 'email' => 'emma.w@acme.org', 'amount' => '$89.00', 'status' => 'Paid', 'date' => 'Yesterday'],
        ['id' => 'ORD-7288', 'customer' => 'Noah Jenkins', 'email' => 'noah@cloudscale.io', 'amount' => '$620.00', 'status' => 'Refunded', 'date' => 'Oct 02, 2026'],
        ['id' => 'ORD-7287', 'customer' => 'Ava Robinson', 'email' => 'ava.r@designers.net', 'amount' => '$2,450.00', 'status' => 'Paid', 'date' => 'Oct 01, 2026'],
    ];

    $isDesktop = $viewport === 'desktop';
@endphp

<div class="grid {{ $isDesktop ? 'grid-cols-1 lg:grid-cols-3' : 'grid-cols-1' }} gap-6">
    <flux:card class="{{ $isDesktop ? 'lg:col-span-2' : 'w-full' }} flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="lg">Revenue Performance</flux:heading>
                <flux:text size="sm" class="text-zinc-500">Monthly gross volume compared against quarter forecast.</flux:text>
            </div>
            <div class="flex items-center gap-2">
                <flux:badge size="sm" color="blue">Actual</flux:badge>
                <flux:badge size="sm" color="zinc">Target</flux:badge>
            </div>
        </div>

        <div class="pt-2">
            <flux:chart :value="$chartData" class="h-64 w-full">
                <flux:chart.svg>
                    <flux:chart.area field="revenue" class="text-blue-500/25" />
                    <flux:chart.line field="revenue" class="text-blue-500" stroke-width="2" />
                    <flux:chart.line field="target" class="text-zinc-400 dark:text-zinc-600" stroke-dasharray="4 4" stroke-width="1.5" />

                    <flux:chart.axis axis="x" field="label">
                        <flux:chart.axis.line />
                        <flux:chart.axis.tick />
                    </flux:chart.axis>

                    <flux:chart.axis axis="y" :format="['useGrouping' => true]">
                        <flux:chart.axis.grid />
                        <flux:chart.axis.line />
                        <flux:chart.axis.tick />
                    </flux:chart.axis>
                </flux:chart.svg>
            </flux:chart>
        </div>
    </flux:card>

    <flux:card class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <flux:heading size="base">Quick Insights</flux:heading>
            <flux:icon name="ellipsis-horizontal" class="size-4 text-zinc-400" />
        </div>

        <div class="flex flex-col gap-4">
            <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200/60 dark:border-zinc-700/60">
                <flux:text size="xs" class="font-medium text-zinc-500">Top Growth Region</flux:text>
                <flux:heading size="sm" class="mt-0.5">North America (+34%)</flux:heading>
                <div class="w-full bg-zinc-200 dark:bg-zinc-700 h-1.5 rounded-full mt-2 overflow-hidden">
                    <div class="bg-blue-500 h-full rounded-full" style="width: 78%"></div>
                </div>
            </div>

            <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200/60 dark:border-zinc-700/60">
                <flux:text size="xs" class="font-medium text-zinc-500">Average Order Value</flux:text>
                <flux:heading size="sm" class="mt-0.5">$318.50</flux:heading>
                <div class="w-full bg-zinc-200 dark:bg-zinc-700 h-1.5 rounded-full mt-2 overflow-hidden">
                    <div class="bg-emerald-500 h-full rounded-full" style="width: 62%"></div>
                </div>
            </div>

            <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200/60 dark:border-zinc-700/60">
                <flux:text size="xs" class="font-medium text-zinc-500">Churn Rate</flux:text>
                <flux:heading size="sm" class="mt-0.5">0.82%</flux:heading>
                <div class="w-full bg-zinc-200 dark:bg-zinc-700 h-1.5 rounded-full mt-2 overflow-hidden">
                    <div class="bg-purple-500 h-full rounded-full" style="width: 25%"></div>
                </div>
            </div>
        </div>
    </flux:card>

    <flux:card class="{{ $isDesktop ? 'lg:col-span-3' : 'w-full' }} flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="lg">Recent Transactions</flux:heading>
                <flux:text size="sm" class="text-zinc-500">Latest customer orders and settlement states.</flux:text>
            </div>
            <flux:button variant="subtle" size="sm" icon-trailing="arrow-right">View All Orders</flux:button>
        </div>

        <div class="overflow-x-auto">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Order</flux:table.column>
                    <flux:table.column>Customer</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column>Date</flux:table.column>
                    <flux:table.column align="end">Amount</flux:table.column>
                    <flux:table.column align="end">Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($recentOrders as $order)
                        <flux:table.row>
                            <flux:table.cell variant="strong" class="font-mono text-xs">{{ $order['id'] }}</flux:table.cell>
                            <flux:table.cell>
                                <div class="flex flex-col leading-tight">
                                    <span class="font-medium text-sm text-zinc-900 dark:text-zinc-100">{{ $order['customer'] }}</span>
                                    <span class="text-xs text-zinc-500">{{ $order['email'] }}</span>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge size="sm" :color="$order['status'] === 'Paid' ? 'green' : ($order['status'] === 'Processing' ? 'blue' : 'zinc')">
                                    {{ $order['status'] }}
                                </flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-500 text-sm">{{ $order['date'] }}</flux:table.cell>
                            <flux:table.cell align="end" class="font-mono text-sm font-semibold tabular-nums">{{ $order['amount'] }}</flux:table.cell>
                            <flux:table.cell align="end">
                                <flux:button variant="subtle" size="xs" icon="ellipsis-horizontal" />
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>
    </flux:card>
</div>
