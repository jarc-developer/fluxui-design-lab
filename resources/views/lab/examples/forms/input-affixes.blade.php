<div class="grid gap-4 md:grid-cols-2">
    <flux:input wire:model="text" label="Clearable" clearable placeholder="Type then clear" />

    <flux:input wire:model="text" label="Copyable" copyable value="https://fluxui.dev" readonly />

    <flux:input wire:model="description" label="Viewable" type="password" viewable placeholder="Password or token" />

    <flux:input wire:model="text" label="Expandable" expandable rows="3"
                placeholder="Grows to fit the content" />

    <flux:input wire:model="released" label="Masked with a template" mask="99/99/9999" placeholder="dd/mm/yyyy" />

    <flux:input wire:model="amount" label="Masked with a function" mask:function="(input) => '$' + input" />

    <flux:input type="file" label="Upload an avatar" />

    <flux:input label="Checking availability" loading placeholder="Checking..." />
</div>
