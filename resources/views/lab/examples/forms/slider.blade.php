<div class="grid gap-4 md:grid-cols-2">
    <flux:slider wire:model="volume" label="Volume">
        <flux:slider.tick value="0">0</flux:slider.tick>
        <flux:slider.tick value="50">50</flux:slider.tick>
        <flux:slider.tick value="100">100</flux:slider.tick>
    </flux:slider>

    <flux:slider wire:model="volume" label="Dots instead of lines" tick:position="inside">
        <flux:slider.tick value="0" variant="dot" />
        <flux:slider.tick value="25" variant="dot" />
        <flux:slider.tick value="50" variant="dot" />
        <flux:slider.tick value="75" variant="dot" />
        <flux:slider.tick value="100" variant="dot" />
    </flux:slider>

    <div class="flex flex-col gap-2">
        <flux:slider wire:model="priceRange" range label="Price range" />
        <flux:text size="sm" class="tabular-nums text-zinc-500">
            {{ is_array($priceRange) ? implode(' – ', $priceRange) : '—' }}
        </flux:text>
    </div>

    <flux:slider wire:model="volume" label="Disabled" disabled />
</div>
