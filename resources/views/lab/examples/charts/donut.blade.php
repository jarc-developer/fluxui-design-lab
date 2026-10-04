<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">inner-radius takes a percentage of the outer radius</flux:text>

        <flux:chart :value="$share" class="aspect-square max-w-sm">
            <flux:chart.svg>
                <flux:chart.pie field="value" label-field="label" inner-radius="60%" radius="4" />
            </flux:chart.svg>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">the indicator inside the tooltip value is the one place it picks up the slice colour</flux:text>

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
