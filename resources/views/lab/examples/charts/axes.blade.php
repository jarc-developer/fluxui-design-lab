<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">grid, axis line, tick marks and tick labels</flux:text>

        <flux:chart :value="$usage" class="h-64 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.area field="web" class="text-blue-200/50 dark:text-blue-400/30" />

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
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">tick-values on the x axis</flux:text>

        <flux:chart :value="$usage" class="h-64 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.bar field="web" class="text-blue-500" />

                <flux:chart.axis axis="x" field="label" :tick-values="['Jan', 'Mar', 'May']" />
                <flux:chart.axis axis="y" />
            </flux:chart.svg>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">horizontal — the axes swap, so the index field moves to the y axis</flux:text>

        <flux:chart :value="$usage" horizontal class="h-72 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.bar field="web" class="text-blue-500" />

                <flux:chart.axis axis="x" />
                <flux:chart.axis axis="y" field="label" />
            </flux:chart.svg>
        </flux:chart>
    </div>
</div>
