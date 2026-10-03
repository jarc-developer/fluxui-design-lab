<div class="flex flex-col gap-6">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">with-inputs</flux:text>

        <flux:calendar wire:model="day" with-inputs with-today />
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">week-numbers</flux:text>

        <flux:calendar wire:model="day" week-numbers with-today />
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">size=&quot;xs&quot; and size=&quot;lg&quot;</flux:text>

        <div class="flex flex-wrap items-start gap-6">
            <flux:calendar wire:model="day" size="xs" with-today />
            <flux:calendar wire:model="day" size="lg" with-today />
        </div>
    </div>
</div>
