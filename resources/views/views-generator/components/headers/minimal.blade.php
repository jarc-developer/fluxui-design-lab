@props([
    'viewport' => 'desktop',
])

@php
    $isMobile = $viewport === 'mobile';
@endphp

<div class="flex flex-col {{ $isMobile ? 'gap-3' : 'sm:flex-row sm:items-center justify-between gap-3' }}">
    <div class="space-y-1">
        <flux:breadcrumbs class="text-xs">
            <flux:breadcrumbs.item href="#">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#">Workspace</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Current View</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        <flux:heading size="xl" class="font-bold tracking-tight">Active View Overview</flux:heading>
    </div>

    <div class="flex items-center gap-2">
        <flux:button variant="subtle" size="sm" icon="share">Share</flux:button>
        <flux:button variant="primary" size="sm" icon="plus">Action</flux:button>
    </div>
</div>
