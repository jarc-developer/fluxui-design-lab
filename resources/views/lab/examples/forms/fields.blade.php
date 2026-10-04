<div class="flex flex-col gap-6">
    <flux:fieldset legend="Billing address" description="Used for the invoice.">
        <div class="grid gap-4 md:grid-cols-2">
            <flux:input wire:model="text" label="Street" placeholder="1 Design Lab Way" />
            <flux:input wire:model="text" label="City" placeholder="Brighton" />
        </div>
    </flux:fieldset>

    <flux:fieldset>
        <flux:legend>Delivery</flux:legend>

        <div class="flex flex-col gap-3">
            <flux:checkbox wire:model="agree" label="Leave with a neighbour" />
            <flux:checkbox wire:model="agree" label="Leave in a safe place" />
        </div>
    </flux:fieldset>

    <div class="flex flex-col gap-2">
        <flux:heading size="sm">Explicit parts</flux:heading>

        <flux:field variant="block">
            <flux:label>Project slug</flux:label>
            <flux:input wire:model="text" placeholder="design-lab" />
            <flux:description>Lowercase, no spaces.</flux:description>
        </flux:field>

        <flux:field variant="inline">
            <flux:label>Slug</flux:label>
            <flux:input wire:model="text" />
            <flux:description>Inline label and control.</flux:description>
        </flux:field>

        <flux:error name="text" message="The project name is required." />
    </div>
</div>
