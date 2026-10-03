<div class="grid gap-4 md:grid-cols-2">
    <flux:time-picker wire:model="meeting" label="Button trigger" />

    <flux:time-picker wire:model="meeting" type="input" label="Input trigger" />

    <flux:time-picker wire:model="meeting" label="With a 5 minute step" :unavailable="['12:00', '12:30', '13:00']" />

    <flux:time-picker wire:model="meeting" label="Inline (dropdown)" dropdown />
</div>
