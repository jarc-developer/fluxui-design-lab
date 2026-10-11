@props([
    'viewport' => 'desktop',
])

@php
    $isMobile = $viewport === 'mobile';
@endphp

<div class="space-y-6 max-w-4xl">
    <flux:card class="{{ $isMobile ? 'p-4' : 'p-6' }} flex flex-col gap-6">
        <div>
            <flux:heading size="lg">Profile Information</flux:heading>
            <flux:text size="sm" class="text-zinc-500">Update your account profile information and email address.</flux:text>
        </div>

        <div class="flex {{ $isMobile ? 'flex-col items-start' : 'items-center' }} gap-4">
            <flux:avatar name="Alex Morgan" initials="AM" size="lg" />
            <div class="flex items-center gap-2">
                <flux:button variant="subtle" size="sm">Change Avatar</flux:button>
                <flux:button variant="subtle" size="sm" class="text-red-600 dark:text-red-400">Remove</flux:button>
            </div>
        </div>

        <div class="grid {{ $isMobile ? 'grid-cols-1' : 'grid-cols-1 sm:grid-cols-2' }} gap-4">
            <flux:input label="Full Name" value="Alex Morgan" />
            <flux:input label="Email Address" type="email" value="alex.morgan@designlab.local" />
            <flux:input label="Job Title" value="Lead UI / UX Engineer" />
            <flux:input label="Organization" value="Design Systems Core" />
        </div>

        <div class="flex {{ $isMobile ? 'flex-col' : 'justify-end' }} gap-2 pt-2 border-t border-zinc-200 dark:border-zinc-700/60">
            <flux:button variant="subtle" class="{{ $isMobile ? 'w-full' : '' }}">Cancel</flux:button>
            <flux:button variant="primary" class="{{ $isMobile ? 'w-full' : '' }}">Save Changes</flux:button>
        </div>
    </flux:card>

    <flux:card class="flex flex-col gap-6">
        <div>
            <flux:heading size="lg">Notification Preferences</flux:heading>
            <flux:text size="sm" class="text-zinc-500">Manage how you receive security alerts and project notifications.</flux:text>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <flux:text size="sm" class="font-medium text-zinc-900 dark:text-zinc-100">Security Alerts</flux:text>
                    <flux:text size="xs" class="text-zinc-500">Receive instant push notifications for new device logins.</flux:text>
                </div>
                <flux:switch checked />
            </div>

            <flux:separator />

            <div class="flex items-center justify-between">
                <div>
                    <flux:text size="sm" class="font-medium text-zinc-900 dark:text-zinc-100">Weekly Digest</flux:text>
                    <flux:text size="xs" class="text-zinc-500">A weekly summary of project activity and team metrics.</flux:text>
                </div>
                <flux:switch checked />
            </div>

            <flux:separator />

            <div class="flex items-center justify-between">
                <div>
                    <flux:text size="sm" class="font-medium text-zinc-900 dark:text-zinc-100">Marketing & Product Updates</flux:text>
                    <flux:text size="xs" class="text-zinc-500">Occasional announcements about new features and Flux components.</flux:text>
                </div>
                <flux:switch />
            </div>
        </div>
    </flux:card>
</div>
