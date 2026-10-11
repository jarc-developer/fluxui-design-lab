@php
    $activities = [
        ['user' => 'Sarah Connor', 'action' => 'deployed release', 'target' => 'v2.20.1 to production', 'time' => '10m ago', 'initials' => 'SC', 'color' => 'emerald'],
        ['user' => 'Michael Chen', 'action' => 'merged pull request', 'target' => '#142 feat(views-generator)', 'time' => '42m ago', 'initials' => 'MC', 'color' => 'blue'],
        ['user' => 'Elena Rostova', 'action' => 'updated theme tokens', 'target' => 'Dark mode OKLCH ramps', 'time' => '2h ago', 'initials' => 'ER', 'color' => 'purple'],
        ['user' => 'David Kim', 'action' => 'invited new member', 'target' => 'jwilson@stackdev.org', 'time' => '4h ago', 'initials' => 'DK', 'color' => 'amber'],
        ['user' => 'System Bot', 'action' => 'completed daily backup', 'target' => '2.4 GB snapshot saved', 'time' => '8h ago', 'initials' => 'SB', 'color' => 'zinc'],
    ];
@endphp

<flux:card class="flex flex-col gap-4">
    <div class="flex items-center justify-between">
        <flux:heading size="base">Recent Activity</flux:heading>
        <flux:badge size="sm" color="zinc">Live Feed</flux:badge>
    </div>

    <div class="space-y-4">
        @foreach ($activities as $act)
            <div class="flex items-start gap-3">
                <flux:avatar :name="$act['user']" :initials="$act['initials']" size="xs" class="mt-0.5" />
                <div class="flex-1 min-w-0 text-xs">
                    <p class="text-zinc-900 dark:text-zinc-100 font-medium">
                        {{ $act['user'] }} <span class="font-normal text-zinc-500">{{ $act['action'] }}</span>
                    </p>
                    <p class="font-medium text-zinc-700 dark:text-zinc-300 truncate">{{ $act['target'] }}</p>
                    <span class="text-[11px] text-zinc-400">{{ $act['time'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <flux:button variant="subtle" size="xs" class="w-full mt-2">View Complete Audit Log</flux:button>
</flux:card>
