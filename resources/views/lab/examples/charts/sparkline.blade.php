<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">a bare array — no field, no label, no index key</flux:text>

        <flux:chart :value="$spark" class="h-20 max-w-xs">
            <flux:chart.svg gutter="0">
                <flux:chart.line class="text-green-500 dark:text-green-400" />
            </flux:chart.svg>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">curve="none" keeps a sparkline honest — a smooth curve invents values between the points</flux:text>

        <flux:chart :value="$spark" class="h-20 max-w-xs">
            <flux:chart.svg gutter="0">
                <flux:chart.line curve="none" class="text-green-500 dark:text-green-400" />
            </flux:chart.svg>
        </flux:chart>
    </div>
</div>
