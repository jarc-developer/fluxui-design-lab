<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">the fill is currentColor, so opacity on the text colour is the whole gradient story</flux:text>

        <div class="grid gap-4 md:grid-cols-2">
            <flux:chart :value="$usage" class="h-56">
                <flux:chart.svg>
                    <flux:chart.area field="web" class="text-blue-500/20" />

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

            <flux:chart :value="$usage" class="h-56">
                <flux:chart.svg>
                    <flux:chart.area field="web" curve="none" class="text-blue-500/40 dark:text-blue-400/30" />

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

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">two areas on shared axes read as a band rather than a shape</flux:text>

        <flux:chart :value="$usage" class="h-64 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.area field="mobile" class="text-amber-400/20" />
                <flux:chart.area field="web" class="text-blue-500/20" />

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
