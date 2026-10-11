@props([
    'viewport' => 'desktop',
])

@php
    $projects = [
        ['title' => 'Design System Tokens v3', 'desc' => 'Unified styling architecture and OKLCH color generator palette.', 'status' => 'In Review', 'tag' => 'Frontend', 'members' => ['Sarah Connor', 'Michael Chen'], 'updated' => '12m ago'],
        ['title' => 'Mobile Passkey Authentication', 'desc' => 'FIDO2 WebAuthn passkey registration and biometric challenge flows.', 'status' => 'Active', 'tag' => 'Security', 'members' => ['David Kim'], 'updated' => '2h ago'],
        ['title' => 'PostgreSQL Analytics Pipeline', 'desc' => 'High-throughput event streaming with real-time aggregate tables.', 'status' => 'Active', 'tag' => 'Data', 'members' => ['James Wilson', 'Zoe Washington'], 'updated' => '1d ago'],
        ['title' => 'Customer Portal Redesign', 'desc' => 'Self-service billing, invoice downloads, and usage meter displays.', 'status' => 'Pending', 'tag' => 'Product', 'members' => ['Elena Rostova'], 'updated' => '3d ago'],
        ['title' => 'Webhooks Notification Center', 'desc' => 'Outgoing HMAC-signed event dispatch with automated retry backoff.', 'status' => 'Active', 'tag' => 'API', 'members' => ['Michael Chen'], 'updated' => '4d ago'],
        ['title' => 'Audit Log Export Service', 'desc' => 'Automated CSV/JSON log exports to customer S3 buckets.', 'status' => 'Archived', 'tag' => 'Compliance', 'members' => ['Sarah Connor'], 'updated' => '1w ago'],
    ];

    $gridClass = match($viewport) {
        'mobile' => 'grid-cols-1 gap-4',
        'tablet' => 'grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6',
        default => 'grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6',
    };
@endphp

<div class="grid {{ $gridClass }}">
    @foreach ($projects as $project)
        <flux:card class="flex flex-col justify-between gap-4 p-5 hover:border-zinc-300 dark:hover:border-zinc-600 transition">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <flux:badge size="sm" :color="$project['status'] === 'Active' ? 'green' : ($project['status'] === 'In Review' ? 'blue' : ($project['status'] === 'Pending' ? 'amber' : 'zinc'))">
                        {{ $project['status'] }}
                    </flux:badge>
                    <span class="text-xs font-medium text-zinc-400 dark:text-zinc-500">{{ $project['tag'] }}</span>
                </div>

                <div>
                    <flux:heading size="base" class="font-semibold">{{ $project['title'] }}</flux:heading>
                    <flux:text size="sm" class="text-zinc-500 mt-1 line-clamp-2">{{ $project['desc'] }}</flux:text>
                </div>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-zinc-200/60 dark:border-zinc-700/60 text-xs text-zinc-400">
                <div class="flex items-center gap-1.5">
                    <flux:avatar.group>
                        @foreach ($project['members'] as $m)
                            <flux:avatar :name="$m" size="xs" />
                        @endforeach
                    </flux:avatar.group>
                </div>
                <span>{{ $project['updated'] }}</span>
            </div>
        </flux:card>
    @endforeach
</div>
