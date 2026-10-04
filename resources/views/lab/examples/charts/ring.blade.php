<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">push inner-radius far enough and the donut becomes a ring</flux:text>

        <flux:chart :value="$share" class="aspect-square max-w-sm">
            <flux:chart.svg>
                <flux:chart.pie field="value" label-field="label" inner-radius="85%" radius="4" />
            </flux:chart.svg>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">the child's stroke-width is the slice gap — the stub's default of 3 against a set of 1</flux:text>

        <div class="grid gap-4 md:grid-cols-2">
            <flux:chart :value="$share" class="aspect-square max-w-sm">
                <flux:chart.svg>
                    <flux:chart.pie field="value" label-field="label" inner-radius="85%" radius="4" stroke-width="1" />
                </flux:chart.svg>
            </flux:chart>

            <flux:chart :value="$share" class="aspect-square max-w-sm">
                <flux:chart.svg>
                    <flux:chart.pie field="value" label-field="label" inner-radius="85%" radius="4" stroke-width="8" />
                </flux:chart.svg>
            </flux:chart>
        </div>
    </div>
</div>
