<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">summary — a readout row above the plot, filled as the cursor moves</flux:text>

        <flux:chart :value="$usage" class="grid gap-4">
            <flux:chart.summary>
                <flux:heading size="lg" class="tabular-nums">
                    <flux:chart.value field="web" :format="['useGrouping' => true]" />
                </flux:heading>

                <flux:chart.value field="mobile" :format="['useGrouping' => true]" />
            </flux:chart.summary>

            <flux:chart.viewport class="h-56">
                <flux:chart.svg>
                    <flux:chart.line field="web" class="text-blue-500" />
                    <flux:chart.axis axis="x" field="label" />
                    <flux:chart.axis axis="y" />
                </flux:chart.svg>
            </flux:chart.viewport>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">tooltip with a heading and one row per series</flux:text>

        <flux:chart :value="$usage" class="h-64 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.group>
                    <flux:chart.line field="web" class="text-blue-500" />
                    <flux:chart.line field="mobile" class="text-amber-400" />
                </flux:chart.group>

                <flux:chart.axis axis="x" field="label" />
                <flux:chart.axis axis="y" />
            </flux:chart.svg>

            <flux:chart.tooltip>
                <flux:chart.tooltip.heading field="label" />
                <flux:chart.tooltip.value field="web" label="Web" />
                <flux:chart.tooltip.value field="mobile" label="Mobile" />
            </flux:chart.tooltip>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">cursor, and a zero line — which only shows once the scale goes negative</flux:text>

        <flux:chart :value="$delta" class="h-64 max-w-2xl">
            <flux:chart.svg gutter="12 0 12 8">
                <flux:chart.area field="web" class="text-blue-200/50 dark:text-blue-400/30" />
                <flux:chart.zero-line />
                <flux:chart.cursor />

                <flux:chart.axis axis="x" field="label" />
                <flux:chart.axis axis="y" />
            </flux:chart.svg>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">sparkline — a bare array of values, with the 8px gutter turned off</flux:text>

        <flux:chart :value="[15, 18, 16, 19, 22, 25, 28, 25, 29, 28, 32, 35]" class="h-20 max-w-xs">
            <flux:chart.svg gutter="0">
                <flux:chart.line class="text-green-500 dark:text-green-400" />
            </flux:chart.svg>
        </flux:chart>
    </div>
</div>
