@php
    $appName = config('app.name');
    $brandName = in_array(strtolower((string) $appName), ['', 'laravel', 'design-lab', 'design lab'], true) ? 'Flux Themes' : $appName;
@endphp

<flux:brand :name="$brandName" {{ $attributes }}>
    <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-lg bg-(--color-accent) text-(--color-accent-foreground) shadow-xs">
        <x-app-logo-icon class="size-5 fill-current" />
    </x-slot>
</flux:brand>
