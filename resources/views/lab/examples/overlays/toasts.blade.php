<div class="flex flex-wrap items-center gap-2">
    <flux:button
        wire:click="$dispatch('toast', type: 'success', title: 'Success', description: 'Everything worked.')"
        color="green"
        variant="subtle"
    >Success</flux:button>

    <flux:button
        wire:click="$dispatch('toast', type: 'danger', title: 'Error', description: 'Something failed.')"
        color="red"
        variant="subtle"
    >Error</flux:button>

    <flux:button
        wire:click="$dispatch('toast', type: 'warning', title: 'Warning', description: 'Check the input.')"
        color="amber"
        variant="subtle"
    >Warning</flux:button>

    <flux:button
        wire:click="$dispatch('toast', type: 'info', title: 'Info', description: 'FYI only.')"
        variant="subtle"
    >Info</flux:button>

    <flux:button
        wire:click="$dispatch('toast', type: 'success', title: 'With actions', description: 'A toast can carry its own buttons.')"
        variant="subtle"
    >With actions</flux:button>
</div>
