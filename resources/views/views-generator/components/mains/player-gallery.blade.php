@props([
    'viewport' => 'desktop',
])

@php
    $gallery = [
        ['url' => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&w=800&q=80', 'title' => 'Fastbreak Transition Dunk', 'category' => 'In-Game', 'badge' => 'Highlights'],
        ['url' => 'https://images.unsplash.com/photo-1519766304817-4f37bda74a29?auto=format&fit=crop&w=800&q=80', 'title' => 'Late Night Shooting Drills', 'category' => 'Training', 'badge' => 'Prep'],
        ['url' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=800&q=80', 'title' => 'Step-Back Perimeter Jumper', 'category' => 'In-Game', 'badge' => 'Clutch'],
        ['url' => 'https://images.unsplash.com/photo-1518063319789-7217e6706b04?auto=format&fit=crop&w=800&q=80', 'title' => 'Championship Trophy Moment', 'category' => 'Awards', 'badge' => 'Champion'],
        ['url' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?auto=format&fit=crop&w=800&q=80', 'title' => 'Summer League Street Run', 'category' => 'Outdoor', 'badge' => 'Rucker'],
        ['url' => 'https://images.unsplash.com/photo-1505666287802-931dc83948e9?auto=format&fit=crop&w=800&q=80', 'title' => 'Defensive Lock-Down Stance', 'category' => 'Defense', 'badge' => 'Intensity'],
    ];

    $courts = [
        [
            'name' => 'The Cage (West 4th St)',
            'city' => 'New York, NY',
            'surface' => 'Outdoor Asphalt',
            'hoops' => 'Double Rim Steel',
            'lights' => 'Night Floodlights',
            'vibe' => 'Legendary streetball proving ground where NBA stars and street ballers clash.',
            'rating' => '5.0',
        ],
        [
            'name' => 'Venice Beach Courts',
            'city' => 'Venice, CA',
            'surface' => 'Oceanfront Blacktop',
            'hoops' => 'Breakaway Glass Backboards',
            'lights' => 'Sunset / Daylit',
            'vibe' => 'High-flying pickup games with ocean breeze and energetic crowds.',
            'rating' => '4.9',
        ],
        [
            'name' => 'Holcombe Rucker Park',
            'city' => 'Harlem, NY',
            'surface' => 'Historic Hardcourt',
            'hoops' => 'Standard Steel Rims',
            'lights' => 'Night Floodlights',
            'vibe' => 'The holy mecca of basketball. Streetball heritage and legendary announcers.',
            'rating' => '5.0',
        ],
        [
            'name' => 'Brooklyn Bridge Park Pier 2',
            'city' => 'Brooklyn, NY',
            'surface' => 'Covered Polyurethane',
            'hoops' => 'Glass Backboard / Spring Rims',
            'lights' => 'Full Indoor-Grade Lights',
            'vibe' => 'Covered outdoor courts with panoramic Manhattan skyline backdrop.',
            'rating' => '4.8',
        ],
    ];

    $isMobile = $viewport === 'mobile';
    $galleryGrid = match($viewport) {
        'mobile' => 'grid-cols-1 sm:grid-cols-2 gap-3',
        'tablet' => 'grid-cols-2 gap-4',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4',
    };
    $courtsGrid = $isMobile ? 'grid-cols-1 gap-3' : 'grid-cols-1 md:grid-cols-2 gap-4';
@endphp

<div class="space-y-6">
    {{-- Section 1: Expanded Photo Reel --}}
    <flux:card class="{{ $isMobile ? 'p-4' : 'p-5' }} space-y-4">
        <div class="flex items-center justify-between border-b border-zinc-200/80 dark:border-zinc-700/80 pb-3">
            <div class="flex items-center gap-2.5">
                <div class="flex size-7 items-center justify-center rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400">
                    <flux:icon name="photo" class="size-4" />
                </div>
                <flux:heading size="lg">Player Action Gallery & Media Reel</flux:heading>
            </div>
            <div class="flex flex-wrap items-center gap-1.5">
                <flux:badge size="xs" color="orange">All Photos</flux:badge>
                <flux:badge size="xs" color="zinc">In-Game</flux:badge>
                <flux:badge size="xs" color="zinc">Workouts</flux:badge>
            </div>
        </div>

        <div class="grid {{ $galleryGrid }}">
            @foreach ($gallery as $item)
                <div class="group relative rounded-xl overflow-hidden border border-zinc-200/80 dark:border-zinc-700/70 bg-zinc-950 aspect-4/3 shadow-sm">
                    <img
                        src="{{ $item['url'] }}"
                        alt="{{ $item['title'] }}"
                        class="size-full object-cover group-hover:scale-105 transition-transform duration-300"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent p-3.5 flex flex-col justify-end">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-orange-400">{{ $item['badge'] }}</span>
                            <span class="text-[10px] text-zinc-300">{{ $item['category'] }}</span>
                        </div>
                        <span class="text-xs font-semibold text-white truncate mt-0.5">{{ $item['title'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </flux:card>

    {{-- Section 2: Favourite Courts Directory --}}
    <flux:card class="{{ $isMobile ? 'p-4' : 'p-5' }} space-y-4">
        <div class="flex items-center justify-between border-b border-zinc-200/80 dark:border-zinc-700/80 pb-3">
            <div class="flex items-center gap-2.5">
                <div class="flex size-7 items-center justify-center rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400">
                    <flux:icon name="map-pin" class="size-4" />
                </div>
                <flux:heading size="lg">Favourite Courts Directory</flux:heading>
            </div>
            <flux:text size="xs" class="text-zinc-400">Hand-Picked Playgrounds</flux:text>
        </div>

        <div class="grid {{ $courtsGrid }}">
            @foreach ($courts as $court)
                <div class="p-4 rounded-xl border border-zinc-200/80 dark:border-zinc-700/70 bg-zinc-50/50 dark:bg-zinc-800/40 flex flex-col justify-between gap-3">
                    <div class="space-y-2">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <flux:heading size="base" class="font-bold">{{ $court['name'] }}</flux:heading>
                                <div class="flex items-center gap-1.5 text-xs text-zinc-500 mt-0.5">
                                    <flux:icon name="map-pin" class="size-3.5 text-red-500" />
                                    <span>{{ $court['city'] }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 text-xs font-bold text-amber-500 bg-amber-500/10 px-2 py-0.5 rounded-md">
                                <flux:icon name="sparkles" class="size-3" />
                                {{ $court['rating'] }}
                            </div>
                        </div>

                        <p class="text-xs text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $court['vibe'] }}</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-zinc-200/60 dark:border-zinc-700/50 text-xs">
                        <flux:badge size="xs" color="zinc">{{ $court['surface'] }}</flux:badge>
                        <flux:badge size="xs" color="orange">{{ $court['hoops'] }}</flux:badge>
                        <flux:badge size="xs" color="blue">{{ $court['lights'] }}</flux:badge>
                    </div>
                </div>
            @endforeach
        </div>
    </flux:card>
</div>
