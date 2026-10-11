@php
    $courts = [
        ['name' => 'The Cage (West 4th St)', 'city' => 'New York, NY', 'surface' => 'Asphalt', 'rating' => '5.0'],
        ['name' => 'Venice Beach Courts', 'city' => 'Venice, CA', 'surface' => 'Blacktop', 'rating' => '4.9'],
        ['name' => 'Holcombe Rucker Park', 'city' => 'Harlem, NY', 'surface' => 'Hardcourt', 'rating' => '5.0'],
        ['name' => 'Brooklyn Bridge Pier 2', 'city' => 'Brooklyn, NY', 'surface' => 'Covered', 'rating' => '4.8'],
    ];

    $teams = [
        ['name' => 'Eastside Ballers', 'jersey' => '#23', 'status' => 'Current', 'color' => 'green'],
        ['name' => 'Venice Coast Breakers', 'jersey' => '#4', 'status' => '2021-23', 'color' => 'zinc'],
        ['name' => 'Brooklyn Elite Pro-Am', 'jersey' => '#11', 'status' => '2019-21', 'color' => 'zinc'],
    ];
@endphp

<div class="space-y-6">
    {{-- Favourite Courts Quick Widget --}}
    <flux:card class="p-4 space-y-3">
        <div class="flex items-center justify-between border-b border-zinc-200/80 dark:border-zinc-700/80 pb-2.5">
            <div class="flex items-center gap-2">
                <flux:icon name="map-pin" class="size-4 text-orange-500" />
                <flux:heading size="sm" class="font-bold">Favourite Courts</flux:heading>
            </div>
            <flux:badge size="xs" color="orange">Top 4</flux:badge>
        </div>

        <div class="space-y-2.5">
            @foreach ($courts as $court)
                <div class="flex items-center justify-between p-2 rounded-lg bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200/60 dark:border-zinc-700/60">
                    <div class="min-w-0 pr-2">
                        <div class="font-semibold text-xs text-zinc-900 dark:text-white truncate">{{ $court['name'] }}</div>
                        <div class="text-[10px] text-zinc-500 truncate">{{ $court['city'] }} &bull; {{ $court['surface'] }}</div>
                    </div>
                    <div class="flex items-center gap-1 text-[11px] font-bold text-amber-500 shrink-0">
                        <flux:icon name="sparkles" class="size-3" />
                        {{ $court['rating'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </flux:card>

    {{-- Teams Affiliation Widget --}}
    <flux:card class="p-4 space-y-3">
        <div class="flex items-center justify-between border-b border-zinc-200/80 dark:border-zinc-700/80 pb-2.5">
            <div class="flex items-center gap-2">
                <flux:icon name="trophy" class="size-4 text-orange-500" />
                <flux:heading size="sm" class="font-bold">Teams Affiliation</flux:heading>
            </div>
            <flux:badge size="xs" color="zinc">Roster</flux:badge>
        </div>

        <div class="space-y-2">
            @foreach ($teams as $t)
                <div class="flex items-center justify-between p-2 rounded-lg bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200/60 dark:border-zinc-700/60">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="font-mono text-xs font-bold text-orange-600 dark:text-orange-400 shrink-0">{{ $t['jersey'] }}</span>
                        <span class="text-xs font-medium text-zinc-800 dark:text-zinc-200 truncate">{{ $t['name'] }}</span>
                    </div>
                    <flux:badge size="xs" :color="$t['color']" class="shrink-0">
                        {{ $t['status'] }}
                    </flux:badge>
                </div>
            @endforeach
        </div>
    </flux:card>

    {{-- Athletic Vitals Summary --}}
    <flux:card class="p-4 space-y-3 bg-gradient-to-br from-orange-500/5 to-transparent border-orange-200 dark:border-orange-900/50">
        <div class="flex items-center justify-between">
            <flux:heading size="sm" class="font-bold text-zinc-900 dark:text-white">Player Vitals</flux:heading>
            <flux:icon name="scale" class="size-4 text-orange-500" />
        </div>

        <div class="grid grid-cols-3 gap-2 text-center pt-1">
            <div class="p-2 rounded-lg bg-white dark:bg-zinc-800/80 border border-zinc-200/80 dark:border-zinc-700/80">
                <div class="text-[10px] text-zinc-400 font-semibold uppercase">Height</div>
                <div class="text-xs font-bold text-zinc-900 dark:text-white mt-0.5">6'5"</div>
            </div>
            <div class="p-2 rounded-lg bg-white dark:bg-zinc-800/80 border border-zinc-200/80 dark:border-zinc-700/80">
                <div class="text-[10px] text-zinc-400 font-semibold uppercase">Weight</div>
                <div class="text-xs font-bold text-zinc-900 dark:text-white mt-0.5">215 lbs</div>
            </div>
            <div class="p-2 rounded-lg bg-white dark:bg-zinc-800/80 border border-zinc-200/80 dark:border-zinc-700/80">
                <div class="text-[10px] text-orange-500 font-semibold uppercase">Wingspan</div>
                <div class="text-xs font-bold text-orange-600 dark:text-orange-400 mt-0.5">7'0"</div>
            </div>
        </div>
    </flux:card>
</div>
