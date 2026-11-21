@props([
    'previewDark' => null,
])

<div class="flex flex-col gap-5">
    {{-- Semantic Status Callouts --}}
    <div class="grid gap-3 sm:grid-cols-2">
        <flux:callout variant="info" icon="information-circle">
            <flux:callout.heading>Design tokens synced</flux:callout.heading>
            <flux:callout.text>Changes automatically propagate to all connected workspaces.</flux:callout.text>
        </flux:callout>

        <flux:callout variant="warning" icon="exclamation-triangle">
            <flux:callout.heading>Contrast check</flux:callout.heading>
            <flux:callout.text>Verify foreground legibility in both light and dark schemes.</flux:callout.text>
        </flux:callout>
    </div>

    {{-- Workspace Preferences Card --}}
    <flux:card class="flex flex-col gap-5">
        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-zinc-200 pb-4 dark:border-zinc-800">
            <div>
                <flux:heading size="base">Workspace Preferences</flux:heading>
                <flux:subheading>Manage organization identity, notifications, and billing cadence.</flux:subheading>
            </div>
            <flux:badge color="green" size="sm" icon="check-circle">Synced</flux:badge>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <flux:field>
                <flux:label>Workspace name</flux:label>
                <flux:input value="Acme Inc." icon="building-office" />
            </flux:field>

            <flux:field>
                <flux:label>Custom domain</flux:label>
                <flux:input value="design.acme.inc" icon="globe-alt" />
            </flux:field>
        </div>

        <div class="flex flex-col gap-3 rounded-xl border border-zinc-200/80 bg-zinc-50/60 p-4 dark:border-zinc-800 dark:bg-zinc-900/50">
            <flux:heading size="sm">Notifications &amp; Appearance</flux:heading>
            <flux:switch checked label="Weekly revenue and usage digest" />
            <flux:switch label="Real-time webhook failure alerts" />

            @if ($previewDark !== null)
                <flux:switch wire:model.live="previewDark" label="Preview window in dark appearance" />
            @else
                <flux:switch label="Preview window in dark appearance" />
            @endif
        </div>

        <div class="flex flex-wrap items-center justify-between gap-4">
            <flux:field>
                <flux:label>Billing cycle</flux:label>
                <flux:radio.group variant="segmented" value="annual">
                    <flux:radio value="monthly">Monthly</flux:radio>
                    <flux:radio value="annual">Annual (Save 20%)</flux:radio>
                </flux:radio.group>
            </flux:field>

            <div class="ms-auto flex items-center gap-2 self-end">
                <flux:button type="button" variant="ghost">Cancel</flux:button>
                <flux:button type="button" variant="primary" icon="check">Save preferences</flux:button>
            </div>
        </div>
    </flux:card>

    {{-- Danger Zone --}}
    <flux:card class="flex flex-col gap-3 border-red-200/80 sm:flex-row sm:items-center sm:justify-between dark:border-red-900/50">
        <div>
            <flux:heading size="sm">Delete workspace</flux:heading>
            <flux:text size="xs" class="text-zinc-500">Permanently remove Acme Inc., its saved tokens, and all team member access.</flux:text>
        </div>
        <flux:button type="button" variant="danger" size="sm" icon="trash" class="shrink-0">
            Delete workspace
        </flux:button>
    </flux:card>
</div>
