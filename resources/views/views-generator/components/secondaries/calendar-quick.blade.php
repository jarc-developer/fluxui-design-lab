@php
    $events = [
        ['title' => 'Design System Sprint Review', 'time' => '11:00 AM - 12:00 PM', 'room' => 'Google Meet', 'color' => 'blue'],
        ['title' => 'Accessibility Contrast Triage', 'time' => '2:30 PM - 3:15 PM', 'room' => 'Huddle #3', 'color' => 'purple'],
        ['title' => 'Q4 Product Roadmap Sync', 'time' => '4:00 PM - 5:00 PM', 'room' => 'Main Boardroom', 'color' => 'emerald'],
    ];
@endphp

<flux:card class="flex flex-col gap-4">
    <div class="flex items-center justify-between">
        <flux:heading size="base">Today's Schedule</flux:heading>
        <flux:badge size="sm" color="blue">{{ now()->format('M d') }}</flux:badge>
    </div>

    <div class="space-y-3">
        @foreach ($events as $event)
            <div class="p-3 rounded-lg border border-zinc-200/60 dark:border-zinc-700/60 bg-zinc-50 dark:bg-zinc-800/40 space-y-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-zinc-900 dark:text-zinc-100">{{ $event['title'] }}</span>
                </div>
                <div class="flex items-center justify-between text-[11px] text-zinc-500">
                    <span class="flex items-center gap-1">
                        <flux:icon name="clock" class="size-3" />
                        {{ $event['time'] }}
                    </span>
                    <span class="flex items-center gap-1 text-zinc-400">
                        <flux:icon name="map-pin" class="size-3" />
                        {{ $event['room'] }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <flux:button variant="subtle" size="xs" icon="plus" class="w-full mt-1">Schedule Event</flux:button>
</flux:card>
