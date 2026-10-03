<div class="flex flex-col gap-6">
    <div>
        <flux:label>Radio group — default</flux:label>
        <flux:radio.group wire:model="plan" label="Plan">
            <flux:radio value="free" label="Free" />
            <flux:radio value="pro" label="Pro" />
            <flux:radio value="team" label="Team" />
        </flux:radio.group>
    </div>

    <div>
        <flux:label>variant=&quot;segmented&quot;</flux:label>
        <flux:radio.group wire:model="plan" variant="segmented">
            <flux:radio value="free" icon="user">Free</flux:radio>
            <flux:radio value="pro" icon="bolt">Pro</flux:radio>
            <flux:radio value="team" icon="user-group">Team</flux:radio>
        </flux:radio.group>
    </div>

    <div>
        <flux:label>variant=&quot;pills&quot;</flux:label>
        <flux:radio.group wire:model="plan" variant="pills">
            <flux:radio value="free" label="Free" />
            <flux:radio value="pro" label="Pro" />
            <flux:radio value="team" label="Team" />
        </flux:radio.group>
    </div>

    <div>
        <flux:label>variant=&quot;buttons&quot;</flux:label>
        <flux:radio.group wire:model="plan" variant="buttons">
            <flux:radio value="free" label="Free" icon="user" />
            <flux:radio value="pro" label="Pro" icon="bolt" />
            <flux:radio value="team" label="Team" icon="user-group" />
        </flux:radio.group>
    </div>

    <div>
        <flux:label>variant=&quot;cards&quot;</flux:label>
        <flux:radio.group wire:model="plan" variant="cards">
            <flux:radio value="free" label="Free" description="Up to 3 projects" price="$0" />
            <flux:radio value="pro" label="Pro" description="Unlimited projects" price="$20" />
            <flux:radio value="team" label="Team" description="Shared workspaces" price="$60" />
        </flux:radio.group>
    </div>
</div>
