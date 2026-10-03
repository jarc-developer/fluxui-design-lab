<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">line and area</flux:text>

        <div class="grid gap-4 md:grid-cols-2">
            <flux:chart :value="$usage" class="h-56">
                <flux:chart.svg>
                    <flux:chart.line field="web" class="text-blue-500" />
                    <flux:chart.axis axis="x" field="label" />
                    <flux:chart.axis axis="y" />
                </flux:chart.svg>
            </flux:chart>

            <flux:chart :value="$usage" class="h-56">
                <flux:chart.svg>
                    <flux:chart.area field="web" class="text-blue-200/50 dark:text-blue-400/30" />
                    <flux:chart.axis axis="x" field="label" />
                    <flux:chart.axis axis="y" />
                </flux:chart.svg>
            </flux:chart>
        </div>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">bar</flux:text>

        <flux:chart :value="$usage" class="h-56 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.bar field="web" :min-height="2" radius="4" class="text-blue-500" />
                <flux:chart.axis axis="x" field="label" />
                <flux:chart.axis axis="y" />
            </flux:chart.svg>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">point — the same series drawn as discrete markers</flux:text>

        <flux:chart :value="$usage" class="h-56 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.point field="web" class="text-blue-500" />
                <flux:chart.axis axis="x" field="label" />
                <flux:chart.axis axis="y" />
            </flux:chart.svg>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">pie — uses label-field for the slices</flux:text>

        <flux:chart :value="$share" class="aspect-square max-w-sm">
            <flux:chart.svg>
                <flux:chart.pie field="value" label-field="label" />
            </flux:chart.svg>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">pie with an inner radius — a donut, and the one place the tooltip indicator takes its colour</flux:text>

        <flux:chart :value="$share" class="aspect-square max-w-sm">
            <flux:chart.svg>
                <flux:chart.pie field="value" label-field="label" inner-radius="60%" radius="4" />
            </flux:chart.svg>

            <flux:chart.tooltip>
                <flux:chart.tooltip.value label-field="label" field="value" suffix="%">
                    <flux:chart.tooltip.indicator />
                </flux:chart.tooltip.value>
            </flux:chart.tooltip>
        </flux:chart>
    </div>
</div>
