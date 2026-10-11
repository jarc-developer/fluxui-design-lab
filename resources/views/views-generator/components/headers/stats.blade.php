@props([
    'viewport' => 'desktop',
])

@php
    $gridClass = match($viewport) {
        'mobile' => 'grid-cols-1 sm:grid-cols-2 gap-3',
        'tablet' => 'grid-cols-2 gap-4',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4',
    };
@endphp

<div class="grid {{ $gridClass }}">
    <flux:card class="flex flex-col gap-2 p-4">
        <div class="flex items-center justify-between text-zinc-500 dark:text-zinc-400">
            <flux:text size="sm" class="font-medium">Total Revenue</flux:text>
            <div class="flex size-7 items-center justify-center rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                <flux:icon name="banknotes" class="size-4" />
            </div>
        </div>
        <div class="flex items-baseline justify-between mt-1">
            <flux:heading size="xl" class="font-semibold tracking-tight">$45,231.89</flux:heading>
            <flux:badge size="sm" color="green" class="gap-1">
                <flux:icon name="arrow-trending-up" class="size-3" />
                +20.1%
            </flux:badge>
        </div>
        <flux:text size="xs" class="text-zinc-500">+4.5k from last month</flux:text>
    </flux:card>

    <flux:card class="flex flex-col gap-2 p-4">
        <div class="flex items-center justify-between text-zinc-500 dark:text-zinc-400">
            <flux:text size="sm" class="font-medium">Active Subscriptions</flux:text>
            <div class="flex size-7 items-center justify-center rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400">
                <flux:icon name="users" class="size-4" />
            </div>
        </div>
        <div class="flex items-baseline justify-between mt-1">
            <flux:heading size="xl" class="font-semibold tracking-tight">+2,350</flux:heading>
            <flux:badge size="sm" color="green" class="gap-1">
                <flux:icon name="arrow-trending-up" class="size-3" />
                +12.4%
            </flux:badge>
        </div>
        <flux:text size="xs" class="text-zinc-500">+180 this week</flux:text>
    </flux:card>

    <flux:card class="flex flex-col gap-2 p-4">
        <div class="flex items-center justify-between text-zinc-500 dark:text-zinc-400">
            <flux:text size="sm" class="font-medium">Conversion Rate</flux:text>
            <div class="flex size-7 items-center justify-center rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400">
                <flux:icon name="arrow-path-rounded-square" class="size-4" />
            </div>
        </div>
        <div class="flex items-baseline justify-between mt-1">
            <flux:heading size="xl" class="font-semibold tracking-tight">3.24%</flux:heading>
            <flux:badge size="sm" color="red" class="gap-1">
                <flux:icon name="arrow-trending-down" class="size-3" />
                -1.2%
            </flux:badge>
        </div>
        <flux:text size="xs" class="text-zinc-500">Target was 3.50%</flux:text>
    </flux:card>

    <flux:card class="flex flex-col gap-2 p-4">
        <div class="flex items-center justify-between text-zinc-500 dark:text-zinc-400">
            <flux:text size="sm" class="font-medium">Avg. Response Time</flux:text>
            <div class="flex size-7 items-center justify-center rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400">
                <flux:icon name="bolt" class="size-4" />
            </div>
        </div>
        <div class="flex items-baseline justify-between mt-1">
            <flux:heading size="xl" class="font-semibold tracking-tight">142ms</flux:heading>
            <flux:badge size="sm" color="green" class="gap-1">
                <flux:icon name="arrow-trending-up" class="size-3" />
                -28ms
            </flux:badge>
        </div>
        <flux:text size="xs" class="text-zinc-500">99.9% uptime SLA</flux:text>
    </flux:card>
</div>
