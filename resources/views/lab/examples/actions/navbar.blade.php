<div class="flex flex-col gap-6">
    <flux:navbar>
        <flux:navbar.item href="#" :current="true" icon="squares-2x2">Overview</flux:navbar.item>
        <flux:navbar.item href="#" icon="cube">Components</flux:navbar.item>
        <flux:navbar.item href="#" icon="swatch" badge="New">Theme</flux:navbar.item>
        <flux:navbar.item href="#" icon="cog">Settings</flux:navbar.item>
    </flux:navbar>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">scrollable</flux:text>

        <div class="max-w-sm">
            <flux:navbar scrollable>
                <flux:navbar.item href="#" :current="true" icon="home">Home</flux:navbar.item>
                <flux:navbar.item href="#" icon="document">Docs</flux:navbar.item>
                <flux:navbar.item href="#" icon="cube">Components</flux:navbar.item>
                <flux:navbar.item href="#" icon="swatch">Theme</flux:navbar.item>
                <flux:navbar.item href="#" icon="user-group">Team</flux:navbar.item>
                <flux:navbar.item href="#" icon="cog">Settings</flux:navbar.item>
            </flux:navbar>
        </div>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">navbar.badge with its own colour</flux:text>

        <flux:navbar>
            <flux:navbar.item href="#" icon="bell" badge="3" badge-color="red">Alerts</flux:navbar.item>
            <flux:navbar.item href="#" icon="inbox" badge="12">Inbox</flux:navbar.item>
            <flux:navbar.item href="#" icon="sparkles" badge="Beta" badge-color="amber">Labs</flux:navbar.item>
        </flux:navbar>
    </div>
</div>
