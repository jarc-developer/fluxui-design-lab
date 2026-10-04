<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">a stack accumulates instead. Its gap is pixels — a percentage would parse as NaN</flux:text>

        <flux:chart :value="$grouped" class="max-w-2xl">
            <flux:chart.viewport class="aspect-[3/1]">
                <flux:chart.svg>
                    <flux:chart.stack width="70%" gap="2" radius="4">
                        <flux:chart.bar field="web" class="text-blue-500" />
                        <flux:chart.bar field="mobile" class="text-blue-400" />
                        <flux:chart.bar field="tablet" class="text-blue-200 dark:text-blue-400/30" />
                    </flux:chart.stack>

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
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">a stack inside a group combines with it — the group lays the stack out, the stack fills it</flux:text>

        <flux:chart :value="$grouped" class="max-w-2xl">
            <flux:chart.viewport class="aspect-[3/1]">
                <flux:chart.svg>
                    <flux:chart.group width="40%" gap="30%">
                        <flux:chart.stack gap="0" radius="4">
                            <flux:chart.bar field="web" class="text-blue-500" />
                            <flux:chart.bar field="mobile" class="text-blue-400" />
                        </flux:chart.stack>

                        <flux:chart.stack gap="0" radius="4">
                            <flux:chart.bar field="web" class="text-emerald-500" />
                            <flux:chart.bar field="mobile" class="text-emerald-400" />
                        </flux:chart.stack>
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
        </flux:chart>
    </div>
</div>
