<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('home') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Generator')">
                    <flux:sidebar.item icon="paint-brush" :href="route('home')" :current="request()->routeIs('home')" wire:navigate>
                        {{ __('Theme Generator') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Components')">
                    <flux:sidebar.item icon="cursor-arrow-rays" :href="route('components.forms')" :current="request()->routeIs('components.forms')" wire:navigate>
                        {{ __('Forms & Inputs') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="cursor-arrow-ripple" :href="route('components.actions')" :current="request()->routeIs('components.actions')" wire:navigate>
                        {{ __('Actions') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="table-cells" :href="route('components.data')" :current="request()->routeIs('components.data')" wire:navigate>
                        {{ __('Data Display') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="arrows-pointing-out" :href="route('components.overlays')" :current="request()->routeIs('components.overlays')" wire:navigate>
                        {{ __('Overlays') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Pro Components')">
                    <flux:sidebar.item icon="chat-bubble-oval-left" :href="route('components.editor')" :current="request()->routeIs('components.editor')" wire:navigate>
                        {{ __('Editor') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="beaker" :href="route('components.composer')" :current="request()->routeIs('components.composer')" wire:navigate>
                        {{ __('Composer') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="rectangle-stack" :href="route('components.kanban')" :current="request()->routeIs('components.kanban')" wire:navigate>
                        {{ __('Kanban') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="photo" :href="route('components.carousel')" :current="request()->routeIs('components.carousel')" wire:navigate>
                        {{ __('Carousel') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="device-phone-mobile" :href="route('components.phone')" :current="request()->routeIs('components.phone')" wire:navigate>
                        {{ __('Phone') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="calendar-days" :href="route('components.calendar')" :current="request()->routeIs('components.calendar')" wire:navigate>
                        {{ __('Calendar') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="chart-bar" :href="route('components.charts')" :current="request()->routeIs('components.charts')" wire:navigate>
                        {{ __('Charts') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Foundations')">
                    <flux:sidebar.item icon="swatch" :href="route('theme')" :current="request()->routeIs('theme')" wire:navigate>
                        {{ __('Theme & Tokens') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="rectangle-group" :href="route('layouts')" :current="request()->routeIs('layouts')" wire:navigate>
                        {{ __('Layout Patterns') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:sidebar.spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>
        </flux:sidebar>

        <!-- Mobile header -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
