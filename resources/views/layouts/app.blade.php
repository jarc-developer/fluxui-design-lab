<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-950 flex flex-col text-zinc-900 antialiased dark:text-zinc-100">
        <flux:header sticky class="border-b border-zinc-200/80 bg-white/90 backdrop-blur-md dark:border-zinc-800/80 dark:bg-zinc-950/90">
            <div class="mx-auto flex w-full max-w-[1440px] items-center gap-4">
                <x-app-logo href="{{ route('home') }}" />

                <flux:navbar class="-my-px ms-2 max-md:hidden">
                    <flux:navbar.item href="#generator">Theme Generator</flux:navbar.item>
                    <flux:navbar.item href="#features">Features</flux:navbar.item>
                    <flux:navbar.item href="#quickstart">Quick Start</flux:navbar.item>
                </flux:navbar>

                <flux:spacer />

                <div class="flex items-center gap-2.5">
                    <flux:radio.group x-data variant="segmented" size="sm" x-model="$flux.appearance" aria-label="{{ __('Appearance') }}">
                        <flux:radio value="light" icon="sun" title="{{ __('Light') }}" />
                        <flux:radio value="dark" icon="moon" title="{{ __('Dark') }}" />
                        <flux:radio value="system" icon="computer-desktop" title="{{ __('System') }}" />
                    </flux:radio.group>

                    <flux:button
                        href="https://fluxui.dev"
                        target="_blank"
                        rel="noopener"
                        variant="ghost"
                        size="sm"
                        icon="book-open-text"
                        class="max-sm:hidden"
                    >
                        Flux Docs
                    </flux:button>

                    <flux:button
                        href="https://github.com/jarc-developer/fluxui-design-lab"
                        target="_blank"
                        rel="noopener"
                        variant="subtle"
                        size="sm"
                        icon="folder-git-2"
                    >
                        <span class="max-sm:hidden">GitHub</span>
                    </flux:button>
                </div>
            </div>
        </flux:header>

        <main class="flex flex-1 flex-col">
            {{ $slot }}
        </main>

        <footer class="border-t border-zinc-200/80 bg-zinc-50/60 py-10 dark:border-zinc-800/80 dark:bg-zinc-900/40">
            <div class="mx-auto flex w-full max-w-[1440px] flex-col items-center justify-between gap-4 px-4 sm:flex-row sm:px-6 lg:px-8">
                <div class="flex flex-col items-center gap-1 sm:items-start">
                    <x-app-logo href="{{ route('home') }}" />
                    <flux:text size="xs" class="text-zinc-500 dark:text-zinc-400">
                        Open-source OKLCH theme generator built for Flux UI and Tailwind CSS v4.
                    </flux:text>
                </div>

                <div class="flex flex-wrap items-center gap-5 text-xs font-medium text-zinc-500 dark:text-zinc-400">
                    <a href="#generator" class="transition-colors hover:text-zinc-900 dark:hover:text-white">Generator</a>
                    <a href="#features" class="transition-colors hover:text-zinc-900 dark:hover:text-white">Features</a>
                    <a href="#quickstart" class="transition-colors hover:text-zinc-900 dark:hover:text-white">Quick Start</a>
                    <a href="https://fluxui.dev" target="_blank" rel="noopener" class="transition-colors hover:text-zinc-900 dark:hover:text-white">Flux UI</a>
                    <a href="https://github.com/jarc-developer/fluxui-design-lab" target="_blank" rel="noopener" class="transition-colors hover:text-zinc-900 dark:hover:text-white">GitHub</a>
                </div>
            </div>
        </footer>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
