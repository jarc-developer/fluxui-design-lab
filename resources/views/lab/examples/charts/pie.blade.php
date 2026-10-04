<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">the slices take their names from label-field and their colours from the built-in palette</flux:text>

        <flux:chart :value="$share" class="aspect-square max-w-sm">
            <flux:chart.svg>
                <flux:chart.pie field="value" label-field="label" />
            </flux:chart.svg>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">the same pie with a tooltip — a pie gets no axes, a cursor or a zero line</flux:text>

        <flux:chart :value="$share" class="aspect-square max-w-sm">
            <flux:chart.svg>
                <flux:chart.pie field="value" label-field="label" />
            </flux:chart.svg>

            <flux:chart.tooltip>
                <flux:chart.tooltip.heading field="label" />
                <flux:chart.tooltip.value field="value" suffix="%">
                    <flux:chart.tooltip.indicator />
                </flux:chart.tooltip.value>
            </flux:chart.tooltip>
        </flux:chart>
    </div>
</div>
