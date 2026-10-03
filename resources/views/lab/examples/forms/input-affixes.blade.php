<div class="grid gap-4 md:grid-cols-2">
    <flux:input wire:model="text" label="Clearable" clearable placeholder="Type then clear" />

    <flux:input wire:model="text" label="Copyable" copyable value="https://fluxui.dev" readonly />

    <flux:input wire:model="description" label="Viewable" viewable placeholder="Password or token" />

    <flux:input wire:model="text" label="Expandable" expandable rows="3"
                placeholder="Grows to fit the content" />

    <flux:input wire:model="released" label="Masked with a template" mask="99/99/9999" placeholder="dd/mm/yyyy" />

    <flux:input wire:model="amount" label="Masked with a function" mask:function="(input) => '$' + input" />

    <div class="flex flex-col gap-2">
        <flux:label>File input</flux:label>
        <flux:input type="file" label="Upload an avatar" />
    </div>

    <div class="flex flex-col gap-2">
        <flux:label>Loading</flux:label>
        <flux:input label="Checking availability" loading />
    </div>
</div>
