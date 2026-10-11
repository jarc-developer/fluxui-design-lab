@props([
    'viewport' => 'desktop',
])

@php
    $isMobile = $viewport === 'mobile';
@endphp

<div class="flex flex-col {{ $isMobile ? 'gap-3' : 'sm:flex-row sm:items-center justify-between gap-4' }} border-b border-zinc-200 dark:border-zinc-700/80 pb-3">
    <div class="flex items-center gap-1 overflow-x-auto w-full pb-1 sm:pb-0">
        <flux:button variant="subtle" size="sm" class="bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white font-medium">
            Overview
        </flux:button>
        <flux:button variant="subtle" size="sm" class="text-zinc-600 dark:text-zinc-400">
            Analytics
            <flux:badge size="sm" color="zinc" class="ml-1.5">3</flux:badge>
        </flux:button>
        <flux:button variant="subtle" size="sm" class="text-zinc-600 dark:text-zinc-400">
            Reports
        </flux:button>
        <flux:button variant="subtle" size="sm" class="text-zinc-600 dark:text-zinc-400">
            Activity Log
        </flux:button>
        <flux:button variant="subtle" size="sm" class="text-zinc-600 dark:text-zinc-400">
            Settings
        </flux:button>
    </div>

    <div class="flex items-center gap-2">
        <flux:button variant="subtle" size="sm" icon="clock">Last 30 Days</flux:button>
        <flux:button variant="subtle" size="sm" icon="arrow-down-tray">Download</flux:button>
    </div>
</div>
