<div class="grid gap-4 md:grid-cols-2">
    <flux:color-picker wire:model="accent" label="hex (default)" />

    <flux:color-picker wire:model="accent" label="rgb" format="rgb" />

    <flux:color-picker wire:model="accent" label="hsl" format="hsl" />

    <flux:color-picker wire:model="accent" label="oklch" format="oklch" />

    <flux:color-picker wire:model="accent" label="Button trigger" type="button" swatches />

    <flux:color-picker wire:model="accent" label="Copyable and clearable" copyable clearable swatches />

    <flux:color-picker wire:model="accent" label="With confirmation" with-confirmation swatches />

    <flux:color-picker wire:model="accent" label="With the screen dropper" dropper swatches />

    <flux:color-picker wire:model="accent" label="Invalid" invalid value="not-a-color" />

    <div class="flex flex-col gap-2">
        <flux:color-picker
            wire:model="accent"
            label="Custom swatches"
            :swatches="['#ef4444', '#f97316', '#eab308', '#22c55e', '#3b82f6', '#6366f1', '#a855f7']"
        />
    </div>

    <div class="flex flex-col gap-2">
        <flux:color-picker wire:model="accent" label="Composed from parts">
            <flux:color-picker.area />
            <flux:color-picker.slider channel="hue" />
            <flux:color-picker.slider channel="alpha" />
            <flux:color-picker.input class="flex-1" placeholder="#000000" />
            <flux:color-picker.dropper />
            <flux:color-picker.swatches :colors="['#ef4444', '#22c55e', '#3b82f6', '#a855f7']" />
        </flux:color-picker>
    </div>
</div>
