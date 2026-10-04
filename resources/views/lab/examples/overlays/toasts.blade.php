<div class="flex flex-wrap items-center gap-2">
    <flux:button
        x-on:click="$flux.toast({ heading: 'Success', text: 'Everything worked.', variant: 'success' })"
        color="green"
        variant="subtle"
    >Success</flux:button>

    <flux:button
        x-on:click="$flux.toast({ heading: 'Error', text: 'Something failed.', variant: 'danger' })"
        color="red"
        variant="subtle"
    >Error</flux:button>

    <flux:button
        x-on:click="$flux.toast({ heading: 'Warning', text: 'Check the input.', variant: 'warning' })"
        color="amber"
        variant="subtle"
    >Warning</flux:button>

    <flux:button
        x-on:click="$flux.toast({ heading: 'Info', text: 'FYI only.', variant: 'info' })"
        variant="subtle"
    >Info</flux:button>

    <flux:button
        x-on:click="$flux.toast({ heading: 'With action', text: 'A toast can carry an interactive action.', variant: 'success', action: { label: 'Undo' } })"
        variant="subtle"
    >With action</flux:button>
</div>
