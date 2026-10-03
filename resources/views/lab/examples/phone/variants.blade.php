<div class="grid gap-4 md:grid-cols-2">
    <flux:phone wire:model="phone" label="Filled variant" variant="filled" :countries="['us', 'gb', 'de']" />

    <flux:phone label="Invalid" variant="filled" invalid :countries="['us', 'gb', 'de']" value="not a phone number" />

    <flux:phone wire:model="phone" label="Small" size="sm" :countries="['us', 'gb', 'de']" />

    <flux:phone label="Extra small" size="xs" :countries="['us', 'gb', 'de']" />
</div>
