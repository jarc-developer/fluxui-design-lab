@props([
    'viewport' => 'desktop',
])

@php
    $isMobile = $viewport === 'mobile';
@endphp

<div class="relative overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700/80 bg-gradient-to-r from-zinc-50 via-white to-zinc-50 dark:from-zinc-900 dark:via-zinc-800/80 dark:to-zinc-900 {{ $isMobile ? 'p-4' : 'p-6' }} shadow-xs">
    <div class="flex flex-col {{ $isMobile ? 'gap-4' : 'sm:flex-row items-start sm:items-center justify-between gap-4' }}">
        <div class="flex items-start gap-4">
            <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-accent-content text-accent-foreground shadow-sm">
                <flux:icon name="sparkles" class="size-6 text-white" />
            </div>
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <flux:heading size="lg" class="font-semibold">Welcome back, Alex!</flux:heading>
                    <flux:badge size="sm" color="indigo" class="font-mono">v2.4 Live</flux:badge>
                </div>
                <flux:text size="sm" class="text-zinc-600 dark:text-zinc-400">
                    Your platform metrics are pacing 18% above target this quarter. Check out your latest report or customize your layout below.
                </flux:text>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0 self-end sm:self-auto">
            <flux:button variant="subtle" size="sm">Dismiss</flux:button>
            <flux:button variant="primary" size="sm" icon-trailing="arrow-right">View Analytics</flux:button>
        </div>
    </div>
</div>
