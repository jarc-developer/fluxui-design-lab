<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">tick-count asks for a number of labels; tick-start and tick-end pin the ends of the range</flux:text>

        <flux:chart :value="$usage" class="h-64 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.line field="web" class="text-blue-500" />

                <flux:chart.axis axis="x" field="label" tick-count="3">
                    <flux:chart.axis.tick />
                    <flux:chart.axis.line />
                </flux:chart.axis>

                <flux:chart.axis axis="y" tick-count="5" tick-start="0" tick-end="450">
                    <flux:chart.axis.grid />
                    <flux:chart.axis.tick />
                </flux:chart.axis>
            </flux:chart.svg>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">interval walks a value axis in fixed steps, and tick-prefix and tick-suffix label each one</flux:text>

        <flux:chart :value="$usage" class="h-64 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.bar field="web" radius="4" class="text-blue-500" />

                <flux:chart.axis axis="x" field="label">
                    <flux:chart.axis.tick />
                    <flux:chart.axis.line />
                </flux:chart.axis>

                <flux:chart.axis axis="y" interval="50" tick-prefix="~$" tick-suffix="k">
                    <flux:chart.axis.grid />
                    <flux:chart.axis.tick />
                </flux:chart.axis>
            </flux:chart.svg>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">tick-values thins the index axis to just the labels you name</flux:text>

        <flux:chart :value="$usage" class="h-64 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.bar field="web" radius="4" class="text-blue-500" />

                <flux:chart.axis axis="x" field="label" :tick-values="['Jan', 'Mar', 'May']">
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

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">position moves the index axis to the top and the value axis to the right</flux:text>

        <flux:chart :value="$usage" class="h-64 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.line field="web" class="text-blue-500" />

                <flux:chart.axis axis="x" field="label" position="top">
                    <flux:chart.axis.tick />
                    <flux:chart.axis.line />
                </flux:chart.axis>

                <flux:chart.axis axis="y" position="right">
                    <flux:chart.axis.grid />
                    <flux:chart.axis.tick />
                </flux:chart.axis>
            </flux:chart.svg>
        </flux:chart>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">format is a plain Intl option bag, so anything Intl accepts works — but it has to be an array, never a JSON string</flux:text>

        <flux:chart :value="$usage" class="h-64 max-w-2xl">
            <flux:chart.svg>
                <flux:chart.bar field="web" radius="4" class="text-blue-500" />

                <flux:chart.axis axis="x" field="label">
                    <flux:chart.axis.tick />
                    <flux:chart.axis.line />
                </flux:chart.axis>

                <flux:chart.axis axis="y" :format="['useGrouping' => true, 'maximumFractionDigits' => 0]">
                    <flux:chart.axis.grid />
                    <flux:chart.axis.tick />
                </flux:chart.axis>
            </flux:chart.svg>
        </flux:chart>
    </div>
</div>
