@php
    $isViewsGenerator = request()->routeIs('views.generator*');
@endphp

<div {{ $attributes->class('px-3 py-2') }}>
    <flux:dropdown position="bottom" align="start" class="w-full">
        <flux:button
            variant="subtle"
            icon-trailing="chevrons-up-down"
            class="w-full !justify-between !px-2.5 !py-2 border border-zinc-200/80 dark:border-zinc-700/80 bg-white/70 dark:bg-zinc-800/70 hover:bg-zinc-100 dark:hover:bg-zinc-700/60 shadow-xs transition"
            data-test="workspace-switcher-trigger"
        >
            <div class="flex items-center gap-2.5 text-start overflow-hidden">
                <div class="flex aspect-square size-7 shrink-0 items-center justify-center rounded-md {{ $isViewsGenerator ? 'bg-indigo-600 text-white shadow-xs' : 'bg-accent-content text-accent-foreground shadow-xs' }}">
                    <flux:icon :name="$isViewsGenerator ? 'sparkles' : 'beaker'" class="size-4 text-white" />
                </div>
                <div class="grid flex-1 leading-tight text-start min-w-0">
                    <span class="text-xs font-semibold text-zinc-900 dark:text-zinc-100 truncate">
                        {{ $isViewsGenerator ? __('Views Generator') : __('Design Lab') }}
                    </span>
                    <span class="text-[10px] uppercase tracking-wider text-zinc-500 dark:text-zinc-400 font-medium truncate">
                        {{ $isViewsGenerator ? __('Layouts Studio') : __('Component Catalog') }}
                    </span>
                </div>
            </div>
        </flux:button>

        <flux:menu class="w-72 p-1.5 shadow-lg">
            <flux:menu.heading class="px-2 pt-1 pb-1.5 text-[11px] uppercase tracking-wider font-semibold text-zinc-400 dark:text-zinc-500">
                {{ __('Select Mode') }}
            </flux:menu.heading>

            <flux:menu.item
                :href="route('dashboard')"
                wire:navigate
                class="flex items-start gap-2.5 p-2 rounded-lg cursor-pointer {{ ! $isViewsGenerator ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white' : '' }}"
                data-test="switch-to-design-lab"
            >
                <div class="flex aspect-square size-6 shrink-0 items-center justify-center rounded-md bg-accent-content text-white mt-0.5">
                    <flux:icon name="beaker" class="size-3.5 text-white" />
                </div>
                <div class="grid flex-1 leading-tight text-start">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold">{{ __('Design Lab') }}</span>
                        @if(! $isViewsGenerator)
                            <flux:icon name="check" class="size-3.5 text-accent-500" />
                        @endif
                    </div>
                    <span class="text-[11px] text-zinc-500 dark:text-zinc-400">{{ __('Component & token catalog') }}</span>
                </div>
            </flux:menu.item>

            <flux:menu.separator class="my-1" />

            <flux:menu.item
                :href="route('views.generator')"
                wire:navigate
                class="flex items-start gap-2.5 p-2 rounded-lg cursor-pointer {{ $isViewsGenerator ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white' : '' }}"
                data-test="switch-to-views-generator"
            >
                <div class="flex aspect-square size-6 shrink-0 items-center justify-center rounded-md bg-indigo-600 text-white mt-0.5">
                    <flux:icon name="sparkles" class="size-3.5 text-white" />
                </div>
                <div class="grid flex-1 leading-tight text-start">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold">{{ __('Views Generator') }}</span>
                        @if($isViewsGenerator)
                            <flux:icon name="check" class="size-3.5 text-indigo-500" />
                        @endif
                    </div>
                    <span class="text-[11px] text-zinc-500 dark:text-zinc-400">{{ __('Layouts & generated components') }}</span>
                </div>
            </flux:menu.item>
        </flux:menu>
    </flux:dropdown>
</div>
