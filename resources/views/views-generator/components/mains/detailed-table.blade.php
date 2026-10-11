@props([
    'viewport' => 'desktop',
])

@php
    $members = [
        ['name' => 'Sarah Connor', 'email' => 'sarah@skynet-resistance.io', 'role' => 'Administrator', 'teams' => 'Operations, Security', 'status' => 'Active', 'joined' => 'Jan 12, 2025', 'initials' => 'SC'],
        ['name' => 'Michael Chen', 'email' => 'mchen@innovate.co', 'role' => 'Lead Engineer', 'teams' => 'Engineering', 'status' => 'Active', 'joined' => 'Mar 04, 2025', 'initials' => 'MC'],
        ['name' => 'Elena Rostova', 'email' => 'elena@designcraft.studio', 'role' => 'Product Designer', 'teams' => 'Design System', 'status' => 'Pending', 'joined' => 'Yesterday', 'initials' => 'ER'],
        ['name' => 'David Kim', 'email' => 'david.k@enterprise.net', 'role' => 'Billing Manager', 'teams' => 'Finance', 'status' => 'Active', 'joined' => 'Aug 19, 2024', 'initials' => 'DK'],
        ['name' => 'Zoe Washington', 'email' => 'zoe@growthhub.agency', 'role' => 'Marketing Lead', 'teams' => 'Growth', 'status' => 'Inactive', 'joined' => 'Feb 15, 2024', 'initials' => 'ZW'],
        ['name' => 'James Wilson', 'email' => 'jwilson@stackdev.org', 'role' => 'DevOps Specialist', 'teams' => 'Infrastructure', 'status' => 'Active', 'joined' => 'May 28, 2025', 'initials' => 'JW'],
    ];

    $isMobile = $viewport === 'mobile';
@endphp

<flux:card class="{{ $isMobile ? 'p-4' : 'p-6' }} flex flex-col gap-4">
    <div class="flex flex-col {{ $isMobile ? 'gap-3' : 'sm:flex-row sm:items-center justify-between gap-3' }}">
        <div>
            <flux:heading size="lg">Organization Members</flux:heading>
            <flux:text size="sm" class="text-zinc-500">Manage member access, permissions, and team assignments.</flux:text>
        </div>
        <div class="flex items-center gap-2 {{ $isMobile ? 'w-full [&>button]:shrink-0' : '' }}">
            <flux:input icon="magnifying-glass" placeholder="Search members..." size="sm" class="{{ $isMobile ? 'flex-1' : 'w-64' }}" />
            <flux:button variant="primary" size="sm" icon="plus">Invite Member</flux:button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Member</flux:table.column>
                <flux:table.column>Role</flux:table.column>
                <flux:table.column>Teams</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Joined</flux:table.column>
                <flux:table.column align="end">Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($members as $member)
                    <flux:table.row>
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                <flux:avatar :name="$member['name']" :initials="$member['initials']" size="sm" />
                                <div class="flex flex-col leading-tight">
                                    <span class="font-medium text-sm text-zinc-900 dark:text-zinc-100">{{ $member['name'] }}</span>
                                    <span class="text-xs text-zinc-500">{{ $member['email'] }}</span>
                                </div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" :color="$member['role'] === 'Administrator' ? 'purple' : 'zinc'">
                                {{ $member['role'] }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $member['teams'] }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" :color="$member['status'] === 'Active' ? 'green' : ($member['status'] === 'Pending' ? 'amber' : 'zinc')">
                                {{ $member['status'] }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="text-sm text-zinc-500">
                            {{ $member['joined'] }}
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <flux:dropdown position="bottom" align="end">
                                <flux:button variant="subtle" size="xs" icon="ellipsis-horizontal" />
                                <flux:menu>
                                    <flux:menu.item icon="pencil-square">Edit details</flux:menu.item>
                                    <flux:menu.item icon="key">Manage permissions</flux:menu.item>
                                    <flux:menu.separator />
                                    <flux:menu.item icon="trash" variant="danger">Remove member</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-zinc-200 dark:border-zinc-700/60">
        <flux:text size="sm" class="text-zinc-500">Showing 1 to 6 of 48 members</flux:text>
        <div class="flex items-center gap-1.5">
            <flux:button variant="subtle" size="xs" disabled icon="chevron-left">Previous</flux:button>
            <flux:button variant="subtle" size="xs" class="bg-zinc-100 dark:bg-zinc-800 font-semibold">1</flux:button>
            <flux:button variant="subtle" size="xs">2</flux:button>
            <flux:button variant="subtle" size="xs">3</flux:button>
            <flux:button variant="subtle" size="xs" icon-trailing="chevron-right">Next</flux:button>
        </div>
    </div>
</flux:card>
