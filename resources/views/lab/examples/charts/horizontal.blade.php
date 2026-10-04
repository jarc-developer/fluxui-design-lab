<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">horizontal swaps the axes, so the index field moves to the y axis</flux:text>

        <flux:chart :value="$usage" horizontal class="h-72 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.bar field="web" radius="4" class="text-blue-500" />

                <flux:chart.axis axis="x" :format="['useGrouping' => true]">
                    <flux:chart.axis.grid />
                    <flux:chart.axis.tick />
                </flux:chart.axis>

                <flux:chart.axis axis="y" field="label">
                    <flux:chart.axis.tick />
                    <flux:chart.axis.line />
                </flux:chart.axis>
            </flux:chart.svg>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">position moves the value axis to the top of a horizontal chart</flux:text>

        <flux:chart :value="$usage" horizontal class="h-72 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.stack width="80%" gap="2">
                    <flux:chart.bar field="web" class="text-blue-500" />
                    <flux:chart.bar field="mobile" class="text-blue-200 dark:text-blue-400/30" />
                </flux:chart.stack>

                <flux:chart.axis axis="x" position="top" :format="['useGrouping' => true]">
                    <flux:chart.axis.grid />
                    <flux:chart.axis.tick />
                </flux:chart.axis>

                <flux:chart.axis axis="y" field="label" position="left">
                    <flux:chart.axis.tick />
                    <flux:chart.axis.line />
                </flux:chart.axis>
            </flux:chart.svg>
        </flux:chart>
    </div>
</div>
