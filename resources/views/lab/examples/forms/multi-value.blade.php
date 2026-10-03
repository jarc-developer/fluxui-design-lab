<div class="grid gap-4 md:grid-cols-2">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            pillbox — default variant, options as children
        </flux:text>

        <flux:pillbox wire:model="tags" label="Tags" placeholder="Add a tag…">
            <flux:pillbox.option value="livewire">Livewire</flux:pillbox.option>
            <flux:pillbox.option value="flux">Flux</flux:pillbox.option>
            <flux:pillbox.option value="laravel">Laravel</flux:pillbox.option>
            <flux:pillbox.option value="tailwind">Tailwind</flux:pillbox.option>
        </flux:pillbox>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            variant=&quot;combobox&quot; with option.create and option.empty. Like the select
            combobox it filters as you type, so it rejects the searchable prop.
        </flux:text>

        <flux:pillbox wire:model="tags" variant="combobox" label="Tags"
                       placeholder="Search or create…">
            <flux:pillbox.option value="livewire">Livewire</flux:pillbox.option>
            <flux:pillbox.option value="flux">Flux</flux:pillbox.option>
            <flux:pillbox.option.create>Create a new tag</flux:pillbox.option.create>
            <flux:pillbox.option.empty>No tags match that search.</flux:pillbox.option.empty>
        </flux:pillbox>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            autocomplete — filter and disabled
        </flux:text>

        <flux:autocomplete wire:model="skus" label="Component SKU" placeholder="flux-acme…">
            <flux:autocomplete.item value="flux-acme-001">flux-acme-001</flux:autocomplete.item>
            <flux:autocomplete.item value="flux-acme-002">flux-acme-002</flux:autocomplete.item>
            <flux:autocomplete.item value="flux-beta-010">flux-beta-010</flux:autocomplete.item>
        </flux:autocomplete>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            autocomplete with filter=&quot;false&quot; — options are shown unfiltered
        </flux:text>

        <flux:autocomplete wire:model="skus" label="SKU" :filter="false" placeholder="Choose a SKU…">
            <flux:autocomplete.item value="flux-acme-001">flux-acme-001</flux:autocomplete.item>
            <flux:autocomplete.item value="flux-acme-002">flux-acme-002</flux:autocomplete.item>
        </flux:autocomplete>
    </div>
</div>
