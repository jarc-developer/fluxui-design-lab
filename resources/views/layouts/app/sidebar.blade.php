<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <x-workspace-switcher />

            @if (request()->routeIs('views.generator*'))
                <flux:sidebar.nav>
                    <flux:sidebar.group :heading="__('Generator')">
                        <flux:sidebar.item icon="sparkles" :href="route('views.generator')" :current="request()->routeIs('views.generator') && ! request()->has('layout')" wire:navigate>
                            {{ __('Canvas Studio') }}
                        </flux:sidebar.item>
                    </flux:sidebar.group>

                    <flux:sidebar.group :heading="__('Suggested Layouts')">
                        <flux:sidebar.item icon="chart-bar" :href="route('views.generator', ['layout' => 'saas-dashboard'])" :current="request()->get('layout') === 'saas-dashboard' || (! request()->has('layout') && request()->routeIs('views.generator'))" wire:navigate>
                            {{ __('SaaS Dashboard') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="table-cells" :href="route('views.generator', ['layout' => 'data-crud'])" :current="request()->get('layout') === 'data-crud'" wire:navigate>
                            {{ __('Data CRUD & Table') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="rectangle-stack" :href="route('views.generator', ['layout' => 'kanban'])" :current="request()->get('layout') === 'kanban'" wire:navigate>
                            {{ __('Project Kanban') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="cog-6-tooth" :href="route('views.generator', ['layout' => 'settings'])" :current="request()->get('layout') === 'settings'" wire:navigate>
                            {{ __('Account & Settings') }}
                        </flux:sidebar.item>
                    </flux:sidebar.group>

                    <flux:sidebar.group :heading="__('Navigation')">
                        <flux:sidebar.item icon="paint-brush" :href="route('theme.generator')" wire:navigate>
                            {{ __('Theme Generator') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="beaker" :href="route('dashboard')" wire:navigate>
                            {{ __('Back to Design Lab') }}
                        </flux:sidebar.item>
                    </flux:sidebar.group>
                </flux:sidebar.nav>
            @else
                <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
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
                    <flux:sidebar.item icon="paint-brush" :href="route('theme.generator')" :current="request()->routeIs('theme.generator')" wire:navigate>
                        {{ __('Theme Generator') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="rectangle-group" :href="route('layouts')" :current="request()->routeIs('layouts')" wire:navigate>
                        {{ __('Layout Patterns') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>
            @endif

            <flux:sidebar.spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <x-desktop-user-menu />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="bottom" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                        <flux:avatar
                            :name="auth()->user()->name"
                            :initials="auth()->user()->initials()"
                        />

                        <div class="grid flex-1 text-start text-sm leading-tight">
                            <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                            <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                        </div>
                    </div>

                    <flux:menu.separator />

                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
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
