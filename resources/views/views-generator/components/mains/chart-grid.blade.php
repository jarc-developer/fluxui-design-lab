@props([
    'viewport' => 'desktop',
])

@php
    $weeklyTraffic = [
        ['label' => 'Mon', 'traffic' => 3200],
        ['label' => 'Tue', 'traffic' => 4500],
        ['label' => 'Wed', 'traffic' => 4100],
        ['label' => 'Thu', 'traffic' => 5600],
        ['label' => 'Fri', 'traffic' => 6200],
        ['label' => 'Sat', 'traffic' => 2900],
        ['label' => 'Sun', 'traffic' => 3100],
    ];

    $deviceUsage = [
        ['label' => 'Desktop', 'share' => 58],
        ['label' => 'Mobile', 'share' => 34],
        ['label' => 'Tablet', 'share' => 8],
    ];

    $isMobile = $viewport === 'mobile';
@endphp

<div class="grid {{ $isMobile ? 'grid-cols-1' : 'grid-cols-1 md:grid-cols-2' }} gap-6">
    <flux:card class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="lg">Daily Visitors</flux:heading>
                <flux:text size="sm" class="text-zinc-500">Unique visitor sessions across current week.</flux:text>
            </div>
            <flux:badge size="sm" color="green">+14.2%</flux:badge>
        </div>

        <div class="pt-2">
            <flux:chart :value="$weeklyTraffic" class="h-56 w-full">
                <flux:chart.svg>
                    <flux:chart.bar field="traffic" radius="4" class="text-indigo-500" />
                    <flux:chart.axis axis="x" field="label">
                        <flux:chart.axis.line />
                        <flux:chart.axis.tick />
                    </flux:chart.axis>
                    <flux:chart.axis axis="y">
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
            <div>
                <flux:heading size="lg">Device Breakdown</flux:heading>
                <flux:text size="sm" class="text-zinc-500">Traffic segmented by client user agent.</flux:text>
            </div>
            <flux:badge size="sm" color="blue">Live</flux:badge>
        </div>

        <div class="pt-2">
            <flux:chart :value="$deviceUsage" class="h-56 w-full">
                <flux:chart.svg>
                    <flux:chart.bar field="share" radius="4" class="text-emerald-500" />
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
</div>
