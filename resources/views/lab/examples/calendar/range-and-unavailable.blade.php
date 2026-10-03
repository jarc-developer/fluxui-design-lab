<div class="flex flex-col gap-6">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">mode=&quot;range&quot; with a two-month view</flux:text>

        <flux:calendar wire:model="range" mode="range" with-today />
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">unavailable dates</flux:text>

        <flux:calendar
            wire:model="day"
            with-today
            :unavailable="['2026-10-05', '2026-10-06', '2026-10-07', '2026-10-20', '2026-10-21']"
        />
    </div>
</div>
