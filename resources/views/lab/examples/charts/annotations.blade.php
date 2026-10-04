<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">summary — follows the cursor, and fallback shows only once the pointer leaves the plot</flux:text>

        <flux:chart :value="$usage" class="grid gap-4">
            <flux:chart.summary>
                <flux:heading size="lg" class="tabular-nums">
                    <flux:chart.summary.value field="web" :format="['useGrouping' => true]" fallback="No value" />
                </flux:heading>

                <flux:chart.summary.value field="mobile" :format="['useGrouping' => true]" fallback="No value" />
            </flux:chart.summary>

            <flux:chart.viewport class="h-56">
                <flux:chart.svg>
                    <flux:chart.line field="web" class="text-blue-500" />
                    <flux:chart.cursor />

                    <flux:chart.axis axis="x" field="label">
                        <flux:chart.axis.tick />
                        <flux:chart.axis.line />
                    </flux:chart.axis>

                    <flux:chart.axis axis="y">
                        <flux:chart.axis.grid />
                        <flux:chart.axis.tick />
                    </flux:chart.axis>
                </flux:chart.svg>
            </flux:chart.viewport>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">tooltip with a heading and one row per series</flux:text>

        <flux:chart :value="$usage" class="h-64 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.line field="web" class="text-blue-500" />
                <flux:chart.line field="mobile" class="text-amber-400" />

                <flux:chart.axis axis="x" field="label">
                    <flux:chart.axis.tick />
                    <flux:chart.axis.line />
                </flux:chart.axis>

                <flux:chart.axis axis="y">
                    <flux:chart.axis.grid />
                    <flux:chart.axis.tick />
                </flux:chart.axis>

                <flux:chart.cursor />
            </flux:chart.svg>

            <flux:chart.tooltip>
                <flux:chart.tooltip.heading field="label" />
                <flux:chart.tooltip.value field="web" label="Web" :format="['useGrouping' => true]" />
                <flux:chart.tooltip.value field="mobile" label="Mobile" :format="['useGrouping' => true]" />
            </flux:chart.tooltip>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">the zero line only draws once the scale goes negative — hence a series that crosses it</flux:text>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <flux:text size="xs" class="mb-1 font-medium text-zinc-500">cursor type="line" (the default)</flux:text>

                <flux:chart :value="$delta" class="h-56">
                    <flux:chart.svg gutter="12 0 12 8">
                        <flux:chart.area field="web" class="text-blue-200/50 dark:text-blue-400/30" />
                        <flux:chart.zero-line />
                        <flux:chart.cursor />

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

            <div>
                <flux:text size="xs" class="mb-1 font-medium text-zinc-500">cursor type="area" — the engine sets its own opacity</flux:text>

                <flux:chart :value="$delta" class="h-56">
                    <flux:chart.svg gutter="12 0 12 8">
                        <flux:chart.area field="web" class="text-blue-200/50 dark:text-blue-400/30" />
                        <flux:chart.zero-line />
                        <flux:chart.cursor type="area" />

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
    </div>
</div>
