@props([
    'viewport' => 'desktop',
])

@php
    $player = [
        'name' => "Marcus 'Viper' Vance",
        'username' => '@marcusvance',
        'jersey_number' => '23',
        'current_team' => 'Eastside Ballers',
        'positions' => ['Point Guard (PG)', 'Shooting Guard (SG)'],
        'height' => "6'5\" (196 cm)",
        'weight' => '215 lbs (98 kg)',
        'wingspan' => "7'0\" (213 cm)",
        'status' => 'Active Roster',
        'avatar_url' => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&w=400&q=80',
    ];

    $isMobile = $viewport === 'mobile';
@endphp

<flux:card class="{{ $isMobile ? 'p-4' : 'p-6' }} relative overflow-hidden">
    {{-- Background Accent Accentuation --}}
    <div class="absolute -right-16 -top-16 size-64 rounded-full bg-orange-500/10 dark:bg-orange-500/15 blur-3xl pointer-events-none"></div>

    <div class="flex flex-col {{ $isMobile ? 'gap-5' : 'lg:flex-row lg:items-center justify-between gap-6' }} relative z-10">
        {{-- Left: Player Image & Info --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
            {{-- Player Avatar with Jersey Badge --}}
            <div class="relative shrink-0">
                <div class="size-24 sm:size-28 rounded-2xl ring-4 ring-orange-500/20 dark:ring-orange-400/20 overflow-hidden shadow-md bg-zinc-100 dark:bg-zinc-800">
                    <img
                        src="{{ $player['avatar_url'] }}"
                        alt="{{ $player['name'] }}"
                        class="size-full object-cover object-top"
                    />
                </div>
                {{-- Jersey Number Overlay --}}
                <div class="absolute -bottom-2 -right-2 flex size-8 items-center justify-center rounded-lg bg-orange-600 text-white font-mono font-bold text-xs shadow-md border-2 border-white dark:border-zinc-900">
                    #{{ $player['jersey_number'] }}
                </div>
            </div>

            {{-- Player Details --}}
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-2">
                    <flux:heading size="xl" class="font-bold tracking-tight text-zinc-900 dark:text-white">
                        {{ $player['name'] }}
                    </flux:heading>
                    <span class="inline-flex items-center text-xs font-semibold px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 dark:bg-orange-950/60 dark:text-orange-400 border border-orange-200 dark:border-orange-800/60">
                        {{ $player['status'] }}
                    </span>
                </div>

                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-zinc-500 dark:text-zinc-400">
                    <span class="font-medium text-orange-600 dark:text-orange-400">{{ $player['username'] }}</span>
                    <span>&bull;</span>
                    <span class="font-medium text-zinc-800 dark:text-zinc-200 flex items-center gap-1">
                        <flux:icon name="trophy" class="size-3.5 text-amber-500" />
                        {{ $player['current_team'] }}
                    </span>
                    <span>&bull;</span>
                    <span>Jersey #{{ $player['jersey_number'] }}</span>
                </div>

                {{-- Positions Badges --}}
                <div class="flex flex-wrap items-center gap-1.5 pt-1">
                    @foreach ($player['positions'] as $pos)
                        <flux:badge size="sm" color="zinc" class="font-medium">
                            {{ $pos }}
                        </flux:badge>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right: Athletic Measurements Quick Chips & Actions --}}
        <div class="flex flex-col {{ $isMobile ? 'gap-3 w-full border-t pt-4 border-zinc-200/80 dark:border-zinc-700/80' : 'sm:flex-row lg:flex-col xl:flex-row items-stretch sm:items-center gap-4 border-t lg:border-t-0 lg:border-s border-zinc-200/80 dark:border-zinc-700/80 pt-4 lg:pt-0 lg:ps-6' }}">
            <div class="grid grid-cols-3 gap-2 sm:gap-3 text-center {{ $isMobile ? 'w-full' : '' }}">
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700/70 p-2 sm:p-2.5 bg-zinc-50/60 dark:bg-zinc-800/40 {{ $isMobile ? '' : 'min-w-20' }}">
                    <flux:text size="xs" class="text-zinc-400 dark:text-zinc-500 uppercase tracking-wider font-semibold">Height</flux:text>
                    <div class="font-bold text-xs sm:text-sm text-zinc-900 dark:text-white mt-0.5">{{ $player['height'] }}</div>
                </div>

                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700/70 p-2 sm:p-2.5 bg-zinc-50/60 dark:bg-zinc-800/40 {{ $isMobile ? '' : 'min-w-20' }}">
                    <flux:text size="xs" class="text-zinc-400 dark:text-zinc-500 uppercase tracking-wider font-semibold">Weight</flux:text>
                    <div class="font-bold text-xs sm:text-sm text-zinc-900 dark:text-white mt-0.5">{{ $player['weight'] }}</div>
                </div>

                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700/70 p-2 sm:p-2.5 bg-zinc-50/60 dark:bg-zinc-800/40 {{ $isMobile ? '' : 'min-w-20' }}">
                    <flux:text size="xs" class="text-zinc-400 dark:text-zinc-500 uppercase tracking-wider font-semibold">Wingspan</flux:text>
                    <div class="font-bold text-xs sm:text-sm text-orange-600 dark:text-orange-400 mt-0.5">{{ $player['wingspan'] }}</div>
                </div>
            </div>

            <div class="flex items-center gap-2 {{ $isMobile ? 'w-full [&>button]:flex-1' : '' }}">
                <flux:button variant="subtle" size="sm" icon="share">Share</flux:button>
                <flux:button variant="primary" size="sm" icon="arrow-down-tray">Export Bio</flux:button>
            </div>
        </div>
    </div>
</flux:card>
