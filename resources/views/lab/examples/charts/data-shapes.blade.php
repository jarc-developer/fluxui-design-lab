<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">an ISO-8601 index key makes the index a time axis, and interval picks the tick unit</flux:text>

        <flux:chart :value="$revenue" class="h-64 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.area field="revenue" class="text-blue-500/20" />
                <flux:chart.line field="revenue" class="text-blue-500" />
                <flux:chart.line field="expenses" class="text-amber-400" />

                <flux:chart.axis axis="x" field="x" interval="month" :format="['month' => 'short']">
                    <flux:chart.axis.tick />
                    <flux:chart.axis.line />
                </flux:chart.axis>

                <flux:chart.axis axis="y" :format="['useGrouping' => true]">
                    <flux:chart.axis.grid />
                    <flux:chart.axis.tick />
                </flux:chart.axis>
            </flux:chart.svg>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">tick-count thins a time axis the same way it thins a categorical one</flux:text>

        <flux:chart :value="$revenue" class="h-64 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.line field="revenue" class="text-blue-500" />

                <flux:chart.axis axis="x" field="x" interval="month" tick-count="3" :format="['month' => 'short']">
                    <flux:chart.axis.tick />
                    <flux:chart.axis.line />
                </flux:chart.axis>

                <flux:chart.axis axis="y" :format="['useGrouping' => true]">
                    <flux:chart.axis.grid />
                    <flux:chart.axis.tick />
                </flux:chart.axis>
            </flux:chart.svg>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">locale on the root decides how every number and date in that one chart is written</flux:text>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <flux:text size="xs" class="mb-1 font-medium text-zinc-500">default</flux:text>

                <flux:chart :value="$revenue" class="h-56">
                    <flux:chart.svg>
                        <flux:chart.line field="revenue" class="text-blue-500" />

                        <flux:chart.axis axis="x" field="x" interval="month" :format="['month' => 'short']">
                            <flux:chart.axis.tick />
                            <flux:chart.axis.line />
                        </flux:chart.axis>

                        <flux:chart.axis axis="y" :format="['useGrouping' => true]">
                            <flux:chart.axis.grid />
                            <flux:chart.axis.tick />
                        </flux:chart.axis>
                    </flux:chart.svg>
                </flux:chart>
            </div>

            <div>
                <flux:text size="xs" class="mb-1 font-medium text-zinc-500">locale="de-DE"</flux:text>

                <flux:chart :value="$revenue" locale="de-DE" class="h-56">
                    <flux:chart.svg>
                        <flux:chart.line field="revenue" class="text-blue-500" />

                        <flux:chart.axis axis="x" field="x" interval="month" :format="['month' => 'short']">
                            <flux:chart.axis.tick />
                            <flux:chart.axis.line />
                        </flux:chart.axis>

                        <flux:chart.axis axis="y" :format="['useGrouping' => true]">
                            <flux:chart.axis.grid />
                            <flux:chart.axis.tick />
                        </flux:chart.axis>
                    </flux:chart.svg>
                </flux:chart>
            </div>
        </div>
    </div>
</div>
