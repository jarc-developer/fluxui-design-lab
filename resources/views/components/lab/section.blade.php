@props([
    'heading' => null,
    'subheading' => null,
])

<div {{ $attributes->class('flex flex-col gap-4') }}>
    @if ($heading)
        <div>
            <flux:heading>{{ $heading }}</flux:heading>

            @if ($subheading)
                <flux:subheading>{{ $subheading }}</flux:subheading>
            @endif
        </div>
    @endif

    <div class="flex flex-col gap-4">
        {{ $slot }}
    </div>
</div>
