<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Layout Patterns')] class extends Component {
    //
}; ?>

    <div class="flex w-full flex-1 flex-col gap-8">
        <x-lab.section heading="Cards"
                      subheading="The workhorse container for grouping related content.">
            <div class="grid gap-4 md:grid-cols-3">
                <flux:card class="flex flex-col gap-3">
                    <flux:icon name="rocket-launch" class="size-6 text-accent-500" />
                    <flux:heading size="sm">Scaffold</flux:heading>
                    <flux:text size="sm">Generate the app, then layer the design system on top.</flux:text>
                </flux:card>

                <flux:card class="flex flex-col gap-3">
                    <flux:icon name="puzzle-piece" class="size-6 text-accent-500" />
                    <flux:heading size="sm">Compose</flux:heading>
                    <flux:text size="sm">Combine primitives into the patterns you actually ship.</flux:text>
                </flux:card>

                <flux:card class="flex flex-col gap-3">
                    <flux:icon name="beaker" class="size-6 text-accent-500" />
                    <flux:heading size="sm">Verify</flux:heading>
                    <flux:text size="sm">Check each state in light, dark and at every breakpoint.</flux:text>
                </flux:card>
            </div>

            <x-lab.example src="lab.examples.data.cards"
                           title="Card variants"
                           subheading="The full band treatment set, shared with the data page." />
        </x-lab.section>

        <x-lab.section heading="Shell primitives"
                      subheading="The building blocks the app shell itself is made of.">
            <x-lab.example src="lab.examples.layouts.shell"
                           title="container, aside, header, footer and brand"
                           subheading="Each one is a thin semantic wrapper that exists so the layout is consistent, not to carry styles." />

            <x-lab.example src="lab.examples.layouts.accent"
                           title="Accent"
                           subheading="flux:accent scopes the accent custom properties to one subtree, so a single region of a page can use a different accent from the rest." />
        </x-lab.section>

        <x-lab.section heading="Nav lists"
                      subheading="Vertical navigation, as used in settings and the lab sidebar.">
            <div class="max-w-xs">
                <flux:navlist aria-label="Example navlist">
                    <flux:navlist.group heading="Pages">
                        <flux:navlist.item :href="route('dashboard')" wire:navigate icon="home">Dashboard</flux:navlist.item>
                        <flux:navlist.item :href="route('components.forms')" wire:navigate icon="cursor-arrow-rays">
                            Forms &amp; Inputs
                            <flux:navlist.badge size="sm">8</flux:navlist.badge>
                        </flux:navlist.item>
                        <flux:navlist.item :href="route('components.overlays')" wire:navigate icon="arrows-pointing-out">
                            Overlays
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <flux:navlist.group heading="Pro" expandable>
                        <flux:navlist.item :href="route('components.kanban')" wire:navigate icon="rectangle-stack">
                            Kanban
                            <flux:navlist.badge size="sm" color="amber">Pro</flux:navlist.badge>
                        </flux:navlist.item>
                        <flux:navlist.item :href="route('components.carousel')" wire:navigate icon="photo">
                            Carousel
                        </flux:navlist.item>
                    </flux:navlist.group>
                </flux:navlist>
            </div>
        </x-lab.section>

        <x-lab.section heading="Navbar & navmenu"
                      subheading="Horizontal navigation and a vertical menu surface.">
            <x-lab.example src="lab.examples.actions.navbar"
                           title="navbar"
                           subheading="Horizontal navigation with current state, icons, badges and a scrollable variant." />

            <x-lab.example src="lab.examples.actions.navmenu"
                           title="navmenu"
                           subheading="A menu surface for dropdown-like panels." />
        </x-lab.section>

        <x-lab.section heading="Separators & spacers"
                      subheading="Rhythm helpers for separating content.">
            <flux:text size="sm">Above the separator.</flux:text>
            <flux:separator />
            <flux:separator orientation="vertical" class="h-6" />
            <flux:text size="sm">Below the separator.</flux:text>
            <flux:spacer />
            <flux:text size="sm" class="text-zinc-500">Below the spacer.</flux:text>
        </x-lab.section>

        <x-lab.section heading="Auth layouts"
                      subheading="The kit ships three authentication shells. These are rendered live.">
            <div class="grid gap-4 md:grid-cols-3">
                <flux:card class="flex flex-col gap-2">
                    <flux:badge size="sm">card</flux:badge>
                    <flux:text size="sm">
                        A centered card on a plain background. The default for login and registration.
                    </flux:text>
                    <flux:button size="sm" variant="subtle" href="{{ route('login') }}" wire:navigate class="self-start">
                        View login
                    </flux:button>
                </flux:card>

                <flux:card class="flex flex-col gap-2">
                    <flux:badge size="sm">simple</flux:badge>
                    <flux:text size="sm">
                        Minimal chrome, useful for embedded flows and passwordless entry.
                    </flux:text>
                </flux:card>

                <flux:card class="flex flex-col gap-2">
                    <flux:badge size="sm">split</flux:badge>
                    <flux:text size="sm">
                        Two-pane marketing shell with a branded side panel.
                    </flux:text>
                </flux:card>
            </div>
        </x-lab.section>

        <x-lab.section heading="Shell anatomy"
                      subheading="The sidebar layout used by every page in this lab.">
            <x-lab.example src="lab.examples.layouts.sidebar"
                           title="Sidebar navigation shell"
                           subheading="Collapsible navigation with search, brand, expandable groups, badges, and user profile." />

            <div class="flex flex-col gap-3 text-sm">
                <flux:callout icon="rectangle-group">
                    <code>layouts/app.blade.php</code> wraps the sidebar shell and yields a
                    <code>flux:main</code> region.
                </flux:callout>
                <flux:callout icon="bars-3" variant="secondary">
                    <code>layouts/app/sidebar.blade.php</code> renders the Flux sidebar, its groups and the
                    user menu.
                </flux:callout>
                <flux:callout icon="bars-3" variant="secondary">
                    <code>layouts/app/header.blade.php</code> provides the mobile header and sidebar toggle.
                </flux:callout>
                <flux:callout icon="swatch" variant="secondary">
                    Every full-page component wraps itself in <code>&lt;x-layouts::app&gt;</code>.
                </flux:callout>
            </div>
        </x-lab.section>
    </div>

