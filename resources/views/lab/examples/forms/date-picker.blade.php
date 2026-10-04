<div class="grid gap-4 md:grid-cols-2">
    <flux:date-picker wire:model="released" label="Button trigger" with-presets />

    <flux:date-picker wire:model="released" type="input" label="Input trigger" with-presets />

    <flux:date-picker wire:model="released" label="With confirmation" with-confirmation />

    <flux:date-picker wire:model="released" label="With typed inputs" with-inputs with-presets />

    <flux:date-picker wire:model="released" label="Week numbers" week-numbers with-presets />

    <flux:date-picker wire:model="released" label="Inline (dropdown)" dropdown with-presets />

    <flux:date-picker wire:model="released" label="Custom presets" :presets="['Next Friday' => '2026-10-09', 'New year' => '2027-01-01']" />

    <flux:date-picker wire:model="dateRange" label="Range mode" mode="range" />
</div>
