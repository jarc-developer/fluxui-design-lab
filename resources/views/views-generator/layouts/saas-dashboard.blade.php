@props([
    'headerComponent' => 'stats',
    'mainComponent' => 'charts-and-table',
    'secondaryComponent' => 'activity',
    'shellStyle' => 'minimal',
    'viewport' => 'desktop',
])

@php
    $isStacked = in_array($viewport, ['mobile', 'tablet'], true) || $secondaryComponent === 'none';
    $gridCols = $isStacked ? 'grid-cols-1' : 'grid-cols-1 xl:grid-cols-4';
    $mainCols = $isStacked ? 'w-full' : 'xl:col-span-3';
@endphp

<div class="flex flex-col gap-6">
    {{-- Header Slot --}}
    @if ($headerComponent !== 'none')
        <div>
            @includeIf('views-generator.components.headers.' . $headerComponent, ['viewport' => $viewport])
        </div>
    @endif

    {{-- Main & Secondary Grid --}}
    <div class="grid {{ $gridCols }} gap-6 items-start">
        <div class="{{ $mainCols }} space-y-6 min-w-0">
            @includeIf('views-generator.components.mains.' . $mainComponent, ['viewport' => $viewport])
        </div>

        @if ($secondaryComponent !== 'none')
            <div class="{{ $isStacked ? 'w-full' : 'xl:col-span-1' }} space-y-6 min-w-0">
                @includeIf('views-generator.components.secondaries.' . $secondaryComponent, ['viewport' => $viewport])
            </div>
        @endif
    </div>
</div>
