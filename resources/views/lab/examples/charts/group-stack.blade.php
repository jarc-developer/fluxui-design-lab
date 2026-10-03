<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">group — two fields overlaid on one set of axes</flux:text>

        <flux:chart :value="$usage" class="h-64 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.group>
                    <flux:chart.line field="web" class="text-blue-500" />
                    <flux:chart.line field="mobile" class="text-amber-400" />
                </flux:chart.group>

                <flux:chart.axis axis="x" field="label" />
                <flux:chart.axis axis="y" />
            </flux:chart.svg>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">stack — the same fields, accumulated</flux:text>

        <flux:chart :value="$usage" class="h-64 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.stack>
                    <flux:chart.bar field="web" class="text-blue-500" />
                    <flux:chart.bar field="mobile" class="text-blue-200 dark:text-blue-400/30" />
                </flux:chart.stack>

                <flux:chart.axis axis="x" field="label" />
                <flux:chart.axis axis="y" />
            </flux:chart.svg>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">legend — hand-composed, so each indicator mirrors the colour of its bar</flux:text>

        <flux:chart :value="$usage" class="max-w-2xl">
            <flux:chart.viewport class="aspect-[3/1]">
                <flux:chart.svg>
                    <flux:chart.stack>
                        <flux:chart.bar field="web" class="text-blue-500" />
                        <flux:chart.bar field="mobile" class="text-blue-200 dark:text-blue-400/30" />
                    </flux:chart.stack>

                    <flux:chart.axis axis="x" field="label" />
                    <flux:chart.axis axis="y" />
                </flux:chart.svg>
            </flux:chart.viewport>

            <div class="flex justify-center gap-4 pt-4">
                <flux:chart.legend label="Web">
                    <flux:chart.legend.indicator class="bg-blue-500 dark:bg-blue-400" />
                </flux:chart.legend>

                <flux:chart.legend label="Mobile">
                    <flux:chart.legend.indicator class="bg-blue-200 dark:bg-blue-400/30" />
                </flux:chart.legend>
            </div>
        </flux:chart>
    </div>
</div>
