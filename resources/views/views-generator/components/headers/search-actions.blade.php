@props([
    'viewport' => 'desktop',
])

@php
    $isMobile = $viewport === 'mobile';
@endphp

<div class="flex flex-col {{ $isMobile ? 'gap-3' : 'sm:flex-row items-stretch sm:items-center justify-between gap-3' }} p-1">
    <div class="flex flex-1 items-center gap-2 {{ $isMobile ? 'w-full' : 'max-w-md' }}">
        <flux:input icon="magnifying-glass" placeholder="Search records, users, transactions..." class="w-full" />
    </div>

    <div class="flex items-center gap-2 flex-wrap {{ $isMobile ? 'w-full' : '' }}">
        <flux:dropdown position="bottom" align="end">
            <flux:button variant="subtle" icon="funnel" class="border border-zinc-200 dark:border-zinc-700">
                Filters
            </flux:button>
            <flux:menu class="w-56">
                <flux:menu.heading>Filter by Status</flux:menu.heading>
                <flux:menu.checkbox checked>Active</flux:menu.checkbox>
                <flux:menu.checkbox checked>Pending</flux:menu.checkbox>
                <flux:menu.checkbox>Archived</flux:menu.checkbox>
                <flux:menu.separator />
                <flux:menu.item icon="arrow-path">Reset Filters</flux:menu.item>
            </flux:menu>
        </flux:dropdown>

        <flux:button variant="subtle" icon="arrow-down-tray" class="border border-zinc-200 dark:border-zinc-700">
            Export
        </flux:button>

        <flux:button variant="primary" icon="plus">
            Create Record
        </flux:button>
    </div>
</div>
