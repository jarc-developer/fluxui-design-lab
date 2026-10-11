@props([
    'viewport' => 'desktop',
])

@php
    $measurements = [
        ['label' => 'Height', 'value' => "6'5\" (196 cm)", 'note' => 'Without shoes: 6\'3.75"', 'icon' => 'user'],
        ['label' => 'Weight', 'value' => '215 lbs (98 kg)', 'note' => 'Playing weight / 6.2% body fat', 'icon' => 'scale'],
        ['label' => 'Wingspan', 'value' => "7'0\" (213 cm)", 'note' => '+7.0" reach differential (Ape Index)', 'icon' => 'arrows-pointing-out'],
        ['label' => 'Standing Reach', 'value' => "8'8\" (264 cm)", 'note' => 'Vertical leap max: 41.5"', 'icon' => 'arrow-up'],
    ];

    $positions = [
        ['name' => 'Point Guard (PG)', 'type' => 'Primary', 'desc' => 'Primary ball-handler, floor general, pick-and-roll maestro'],
        ['name' => 'Shooting Guard (SG)', 'type' => 'Secondary', 'desc' => 'Catch-and-shoot perimeter spacer, secondary shot creator'],
        ['name' => 'Point Forward', 'type' => 'Situational', 'desc' => 'Mismatch playmaker in small-ball closing lineups'],
    ];

    $teams = [
        [
            'name' => 'Eastside Ballers',
            'league' => 'Metropolitan Premier League',
            'years' => '2023 – Present',
            'jersey' => '#23',
            'role' => 'Team Captain & Starting Point Guard',
            'status' => 'Current',
            'accolades' => ['2024 League Finalist', 'All-Metro First Team'],
        ],
        [
            'name' => 'Venice Coast Breakers',
            'league' => 'Drew League & West Pro-Am',
            'years' => '2021 – 2023',
            'jersey' => '#4',
            'role' => 'Starting Combo Guard',
            'status' => 'Former',
            'accolades' => ['2022 Summer All-Star', 'Scoring Title Runner-up'],
        ],
        [
            'name' => 'Brooklyn Elite Pro-Am',
            'league' => 'NY Summer Classic',
            'years' => '2019 – 2021',
            'jersey' => '#11',
            'role' => 'Sixth Man / Microwave Scorer',
            'status' => 'Former',
            'accolades' => ['2020 Championship MVP'],
        ],
        [
            'name' => 'UCLA Bruins',
            'league' => 'NCAA Division I (Pac-12)',
            'years' => '2017 – 2019',
            'jersey' => '#3',
            'role' => 'Starting Point Guard',
            'status' => 'Collegiate',
            'accolades' => ['Pac-12 All-Defensive Team', 'NCAA Sweet 16'],
        ],
    ];

    $photos = [
        [
            'url' => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&w=600&q=80',
            'title' => 'Fastbreak Transition Slam',
            'tag' => 'Game Action',
            'date' => 'Oct 2025',
        ],
        [
            'url' => 'https://images.unsplash.com/photo-1519766304817-4f37bda74a29?auto=format&fit=crop&w=600&q=80',
            'title' => 'Late Night Shooting Drills',
            'tag' => 'Workout',
            'date' => 'Sep 2025',
        ],
        [
            'url' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=600&q=80',
            'title' => 'Step-Back 3-Pointer vs Lions',
            'tag' => 'Highlight',
            'date' => 'Aug 2025',
        ],
        [
            'url' => 'https://images.unsplash.com/photo-1518063319789-7217e6706b04?auto=format&fit=crop&w=600&q=80',
            'title' => 'Championship Trophy Moment',
            'tag' => 'Award',
            'date' => 'Jun 2025',
        ],
    ];

    $courts = [
        [
            'name' => 'The Cage (West 4th St)',
            'city' => 'New York, NY',
            'surface' => 'Outdoor Asphalt',
            'rim' => 'Double Rim',
            'atmosphere' => 'High intensity streetball, legendary trash talk',
            'rating' => '5.0',
        ],
        [
            'name' => 'Venice Beach Courts',
            'city' => 'Venice, CA',
            'surface' => 'Oceanfront Blacktop',
            'rim' => 'Breakaway Glass',
            'atmosphere' => 'Scenic runs, high flyers, energetic crowds',
            'rating' => '4.9',
        ],
        [
            'name' => 'Holcombe Rucker Park',
            'city' => 'Harlem, NY',
            'surface' => 'Historic Hardcourt',
            'rim' => 'Standard Steel',
            'atmosphere' => 'Mecca of streetball, where legends are born',
            'rating' => '5.0',
        ],
        [
            'name' => 'Brooklyn Bridge Park Pier 2',
            'city' => 'Brooklyn, NY',
            'surface' => 'Covered Polyurethane',
            'rim' => 'Spring Loaded',
            'atmosphere' => 'Skyline views, wind-shielded evening runs',
            'rating' => '4.8',
        ],
    ];

    $isMobile = $viewport === 'mobile';
    $isTablet = $viewport === 'tablet';

    $biometricsGrid = match($viewport) {
        'mobile' => 'grid-cols-1 sm:grid-cols-2 gap-3',
        'tablet' => 'grid-cols-2 gap-4',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4',
    };

    $positionsGrid = $isMobile ? 'grid-cols-1 gap-2.5' : 'grid-cols-1 md:grid-cols-3 gap-3';
    $teamsGrid = $isMobile ? 'grid-cols-1 gap-3' : 'grid-cols-1 md:grid-cols-2 gap-4';
    $photosGrid = match($viewport) {
        'mobile' => 'grid-cols-1 sm:grid-cols-2 gap-3',
        'tablet' => 'grid-cols-2 gap-4',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4',
    };
    $courtsGrid = $isMobile ? 'grid-cols-1 gap-3' : 'grid-cols-1 md:grid-cols-2 gap-4';
@endphp

<div class="space-y-6">
    {{-- Section 1: Athletic Measurements & Positions --}}
    <flux:card class="{{ $isMobile ? 'p-4' : 'p-5' }} space-y-5">
        <div class="flex items-center justify-between border-b border-zinc-200/80 dark:border-zinc-700/80 pb-3">
            <div class="flex items-center gap-2.5">
                <div class="flex size-7 items-center justify-center rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400">
                    <flux:icon name="user" class="size-4" />
                </div>
                <flux:heading size="lg">Athletic Measurements & Positions</flux:heading>
            </div>
            <flux:badge size="sm" color="orange" class="font-mono">Wingspan +7"</flux:badge>
        </div>

        {{-- Biometrics Grid --}}
        <div class="grid {{ $biometricsGrid }}">
            @foreach ($measurements as $m)
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700/70 p-4 bg-zinc-50/50 dark:bg-zinc-800/30 flex flex-col justify-between gap-2">
                    <div class="flex items-center justify-between text-zinc-400">
                        <span class="text-xs uppercase font-semibold tracking-wider">{{ $m['label'] }}</span>
                        <flux:icon :name="$m['icon']" class="size-4 text-orange-500" />
                    </div>
                    <div>
                        <div class="text-lg font-bold text-zinc-900 dark:text-white">{{ $m['value'] }}</div>
                        <div class="text-[11px] text-zinc-500 dark:text-zinc-400 mt-0.5">{{ $m['note'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Positions & Playing Style --}}
        <div class="space-y-3 pt-2">
            <flux:text size="sm" class="font-semibold text-zinc-700 dark:text-zinc-300">Positions & Tactical Roles</flux:text>
            <div class="grid {{ $positionsGrid }}">
                @foreach ($positions as $pos)
                    <div class="p-3.5 rounded-xl border border-zinc-200/80 dark:border-zinc-700/70 bg-white dark:bg-zinc-800/60 space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-xs text-zinc-900 dark:text-zinc-100">{{ $pos['name'] }}</span>
                            <flux:badge size="xs" :color="$pos['type'] === 'Primary' ? 'orange' : ($pos['type'] === 'Secondary' ? 'blue' : 'zinc')">
                                {{ $pos['type'] }}
                            </flux:badge>
                        </div>
                        <p class="text-[11px] text-zinc-500 dark:text-zinc-400 leading-snug">{{ $pos['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </flux:card>

    {{-- Section 2: Teams & Career Journey --}}
    <flux:card class="{{ $isMobile ? 'p-4' : 'p-5' }} space-y-5">
        <div class="flex items-center justify-between border-b border-zinc-200/80 dark:border-zinc-700/80 pb-3">
            <div class="flex items-center gap-2.5">
                <div class="flex size-7 items-center justify-center rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400">
                    <flux:icon name="trophy" class="size-4" />
                </div>
                <flux:heading size="lg">Teams & Career History</flux:heading>
            </div>
            <flux:text size="xs" class="text-zinc-400">4 Affiliated Teams</flux:text>
        </div>

        <div class="grid {{ $teamsGrid }}">
            @foreach ($teams as $team)
                <div class="p-4 rounded-xl border {{ $team['status'] === 'Current' ? 'border-orange-300 dark:border-orange-800/80 bg-orange-50/20 dark:bg-orange-950/20' : 'border-zinc-200/80 dark:border-zinc-700/70 bg-white dark:bg-zinc-800/40' }} flex flex-col justify-between gap-3">
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm text-zinc-900 dark:text-white">{{ $team['name'] }}</span>
                                <span class="font-mono text-xs font-bold text-orange-600 dark:text-orange-400">{{ $team['jersey'] }}</span>
                            </div>
                            <flux:badge size="sm" :color="$team['status'] === 'Current' ? 'green' : 'zinc'">
                                {{ $team['status'] }}
                            </flux:badge>
                        </div>

                        <div class="flex items-center gap-2 text-xs text-zinc-500">
                            <span>{{ $team['league'] }}</span>
                            <span>&bull;</span>
                            <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ $team['years'] }}</span>
                        </div>

                        <p class="text-xs text-zinc-600 dark:text-zinc-400 font-medium">{{ $team['role'] }}</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-1.5 pt-2 border-t border-zinc-200/60 dark:border-zinc-700/50">
                        @foreach ($team['accolades'] as $acc)
                            <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-md bg-amber-500/10 text-amber-700 dark:text-amber-300">
                                <flux:icon name="sparkles" class="size-3 text-amber-500" />
                                {{ $acc }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </flux:card>

    {{-- Section 3: Photo Reel / Action Gallery --}}
    <flux:card class="{{ $isMobile ? 'p-4' : 'p-5' }} space-y-4">
        <div class="flex items-center justify-between border-b border-zinc-200/80 dark:border-zinc-700/80 pb-3">
            <div class="flex items-center gap-2.5">
                <div class="flex size-7 items-center justify-center rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400">
                    <flux:icon name="photo" class="size-4" />
                </div>
                <flux:heading size="lg">Photo Reel & Media</flux:heading>
            </div>
            <flux:text size="xs" class="text-zinc-400">{{ count($photos) }} Featured Photos</flux:text>
        </div>

        <div class="grid {{ $photosGrid }}">
            @foreach ($photos as $photo)
                <div class="group relative rounded-xl overflow-hidden border border-zinc-200/80 dark:border-zinc-700/70 bg-zinc-900 shadow-sm aspect-4/3">
                    <img
                        src="{{ $photo['url'] }}"
                        alt="{{ $photo['title'] }}"
                        class="size-full object-cover group-hover:scale-105 transition-transform duration-300"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent p-3 flex flex-col justify-end">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-orange-400">{{ $photo['tag'] }}</span>
                            <span class="text-[10px] text-zinc-300">{{ $photo['date'] }}</span>
                        </div>
                        <span class="text-xs font-semibold text-white truncate mt-0.5">{{ $photo['title'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </flux:card>

    {{-- Section 4: Favourite Courts --}}
    <flux:card class="{{ $isMobile ? 'p-4' : 'p-5' }} space-y-4">
        <div class="flex items-center justify-between border-b border-zinc-200/80 dark:border-zinc-700/80 pb-3">
            <div class="flex items-center gap-2.5">
                <div class="flex size-7 items-center justify-center rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400">
                    <flux:icon name="map-pin" class="size-4" />
                </div>
                <flux:heading size="lg">Favourite Courts & Playgrounds</flux:heading>
            </div>
            <flux:badge size="sm" color="zinc">Top 4 Spots</flux:badge>
        </div>

        <div class="grid {{ $courtsGrid }}">
            @foreach ($courts as $court)
                <div class="p-4 rounded-xl border border-zinc-200/80 dark:border-zinc-700/70 bg-zinc-50/40 dark:bg-zinc-800/30 flex flex-col justify-between gap-3">
                    <div class="space-y-1.5">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <flux:heading size="base" class="font-bold">{{ $court['name'] }}</flux:heading>
                                <div class="flex items-center gap-1.5 text-xs text-zinc-500 mt-0.5">
                                    <flux:icon name="map-pin" class="size-3 text-red-500" />
                                    <span>{{ $court['city'] }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 text-xs font-bold text-amber-500">
                                <flux:icon name="sparkles" class="size-3" />
                                {{ $court['rating'] }}
                            </div>
                        </div>

                        <p class="text-xs text-zinc-600 dark:text-zinc-400">{{ $court['atmosphere'] }}</p>
                    </div>

                    <div class="flex items-center gap-2 pt-2 border-t border-zinc-200/60 dark:border-zinc-700/50">
                        <flux:badge size="xs" color="zinc">{{ $court['surface'] }}</flux:badge>
                        <flux:badge size="xs" color="orange">{{ $court['rim'] }}</flux:badge>
                    </div>
                </div>
            @endforeach
        </div>
    </flux:card>
</div>
