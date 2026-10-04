<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">grid, axis line, tick marks and tick labels — the axis children follow the axis they sit in</flux:text>

        <flux:chart :value="$usage" class="h-64 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.bar field="web" radius="4" class="text-blue-500" />

                <flux:chart.axis axis="x" field="label">
                    <flux:chart.axis.line />
                    <flux:chart.axis.mark />
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

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">width thins the bar inside its slot, and min-height keeps a zero value visible</flux:text>

        <flux:chart :value="$usage" class="h-56 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.bar field="web" width="50%" :min-height="2" radius="4" class="text-blue-500" />

                <flux:chart.axis axis="x" field="label">
                    <flux:chart.axis.tick />
                    <flux:chart.axis.line />
                </flux:chart.axis>

                <flux:chart.axis axis="y">
                    <flux:chart.axis.grid />
                    <flux:chart.axis.tick />
                </flux:chart.axis>
            </flux:chart.svg>
        </flux:chart>
    </div>
</div>
