<div class="grid gap-4 md:grid-cols-2">
    <flux:phone
        wire:model="phone"
        label="Americas only"
        :countries="['us', 'ca', 'mx', 'br']"
        country="us"
    />

    <flux:phone
        wire:model="phone"
        label="Reordered, Europe first"
        :countries="['gb', 'de', 'fr', 'es', 'it']"
        :country-order="['gb', 'de', 'fr', 'es', 'it']"
    />
</div>
