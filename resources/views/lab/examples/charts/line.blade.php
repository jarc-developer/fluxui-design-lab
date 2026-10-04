<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">smooth is the default; none joins the points straight</flux:text>

        <div class="grid gap-4 md:grid-cols-2">
            <flux:chart :value="$usage" class="h-56">
                <flux:chart.svg>
                    <flux:chart.line field="web" class="text-blue-500" />

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
                    <flux:chart.line field="web" curve="none" class="text-blue-500" />

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
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">stroke-width and stroke-dasharray reach the path directly</flux:text>

        <flux:chart :value="$usage" class="h-64 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.line field="web" stroke-width="5" class="text-blue-500" />
                <flux:chart.line field="mobile" stroke-dasharray="6 4" stroke-width="2" class="text-amber-400" />

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
