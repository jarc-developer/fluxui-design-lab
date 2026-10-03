<div class="flex flex-col gap-6">
    <div>
        <flux:label>Checkbox group — default</flux:label>
        <flux:checkbox.group wire:model="channels" label="Channels">
            <flux:checkbox value="email" label="Email" />
            <flux:checkbox value="sms" label="SMS" />
            <flux:checkbox value="push" label="Push" />
            <flux:checkbox.all label="Select all" />
        </flux:checkbox.group>
    </div>

    <div>
        <flux:label>variant=&quot;pills&quot;</flux:label>
        <flux:checkbox.group wire:model="channels" variant="pills">
            <flux:checkbox value="email" label="Email" />
            <flux:checkbox value="sms" label="SMS" />
            <flux:checkbox value="push" label="Push" />
        </flux:checkbox.group>
    </div>

    <div>
        <flux:label>variant=&quot;buttons&quot;</flux:label>
        <flux:checkbox.group wire:model="channels" variant="buttons">
            <flux:checkbox value="email" label="Email" icon="envelope" />
            <flux:checkbox value="sms" label="SMS" icon="chat-bubble-left" />
            <flux:checkbox value="push" label="Push" icon="bell" />
        </flux:checkbox.group>
    </div>

    <div>
        <flux:label>variant=&quot;cards&quot;</flux:label>
        <flux:checkbox.group wire:model="channels" variant="cards">
            <flux:checkbox value="email" label="Email" description="Daily digest" icon="envelope" />
            <flux:checkbox value="sms" label="SMS" description="Only critical alerts" icon="chat-bubble-left" />
            <flux:checkbox value="push" label="Push" description="Real-time" icon="bell" />
        </flux:checkbox.group>
    </div>
</div>
