<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">a group puts its bars side by side; width and gap take a share of each slot</flux:text>

        <flux:chart :value="$grouped" class="max-w-2xl">
            <flux:chart.viewport class="aspect-[3/1]">
                <flux:chart.svg>
                    <flux:chart.group width="80%" gap="10%">
                        <flux:chart.bar field="web" class="text-blue-500" />
                        <flux:chart.bar field="mobile" class="text-blue-400" />
                        <flux:chart.bar field="tablet" class="text-blue-200 dark:text-blue-400/30" />
                    </flux:chart.group>

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

            <div class="flex flex-wrap justify-center gap-4 pt-4">
                <flux:chart.legend label="Web">
                    <flux:chart.legend.indicator class="bg-blue-500 dark:bg-blue-400" />
                </flux:chart.legend>

                <flux:chart.legend label="Mobile">
                    <flux:chart.legend.indicator class="bg-blue-400 dark:bg-blue-300" />
                </flux:chart.legend>

                <flux:chart.legend label="Tablet">
                    <flux:chart.legend.indicator class="bg-blue-200 dark:bg-blue-400/30" />
                </flux:chart.legend>
            </div>
        </flux:chart>
    </div>
</div>
