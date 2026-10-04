<?php

use Flux\Flux;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Actions & Navigation')] class extends Component {
    /**
     * How many icons to paint at once. The full set is a few hundred SVGs, and
     * resolving every one of them in a single request exhausts the memory limit,
     * so the gallery paints a window and the filter searches the whole set.
     */
    public int $iconLimit = 96;

    public string $iconSearch = '';

    public function notify(): void
    {
        Flux::toast(text: 'The lab state was updated.', heading: 'Saved', variant: 'success');
    }

    public function simulateAsyncAction(): void
    {
        usleep(1000000);
    }

    /**
     * Every icon that ships with Flux, plus any this app adds, discovered from disk
     * so the gallery cannot drift from the installed packages.
     *
     * @return array<int, string>
     */
    public function getIconNamesProperty(): array
    {
        $directories = [
            base_path('vendor/livewire/flux/stubs/resources/views/flux/icon'),
            resource_path('views/flux/icon'),
        ];

        $names = [];

        foreach ($directories as $directory) {
            if (! File::isDirectory($directory)) {
                continue;
            }

            foreach (File::glob($directory.'/*.blade.php') as $path) {
                $name = basename($path, '.blade.php');

                if ($name !== 'index') {
                    $names[] = $name;
                }
            }
        }

        $names = array_values(array_unique($names));
        sort($names);

        return $names;
    }

    /**
     * @return array<int, string>
     */
    public function getFilteredIconsProperty(): array
    {
        if ($this->iconSearch === '') {
            return $this->iconNames;
        }

        return array_values(array_filter(
            $this->iconNames,
            fn (string $name): bool => str_contains($name, strtolower($this->iconSearch)),
        ));
    }
}; ?>

    <div class="flex w-full flex-1 flex-col gap-8">
        <x-lab.section heading="Buttons"
                      subheading="Every variant, size, colour and icon pairing.">
            <div class="flex flex-wrap items-center gap-2">
                <flux:button variant="primary">Primary</flux:button>
                <flux:button variant="filled">Filled</flux:button>
                <flux:button>Outline (default)</flux:button>
                <flux:button variant="danger">Danger</flux:button>
                <flux:button variant="ghost">Ghost</flux:button>
                <flux:button variant="subtle">Subtle</flux:button>
                <flux:button color="green">Success</flux:button>
                <flux:button color="zinc">Neutral</flux:button>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <flux:button size="xs">Extra small</flux:button>
                <flux:button size="sm">Small</flux:button>
                <flux:button size="base">Base</flux:button>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <flux:button icon="plus">New component</flux:button>
                <flux:button icon="trash" color="red" title="Delete" />
                <flux:button wire:click="notify" icon="check" variant="subtle">Toast</flux:button>
                <flux:button wire:click="simulateAsyncAction">Interactive loading</flux:button>
                <flux:button loading>Static loading</flux:button>
                <flux:button disabled>Disabled</flux:button>
                <flux:button href="{{ route('layouts') }}" wire:navigate>As a link</flux:button>
            </div>

            <x-lab.example src="lab.examples.actions.button-group"
                           title="Button groups"
                           subheading="Weld related controls into a single segmented control, and nest one group inside another." />
        </x-lab.section>

        <x-lab.section heading="Menus & dropdowns"
                      subheading="Dropdown triggers, nested menus and command palettes.">
            <div class="flex flex-wrap items-center gap-2">
                <flux:dropdown position="bottom" align="start">
                    <flux:button icon="bars-3" icon:trailing="chevron-down">Options</flux:button>
                    <flux:menu>
                        <flux:menu.item icon="pencil-square" kbd="⌘E">Edit</flux:menu.item>
                        <flux:menu.item icon="document-duplicate" kbd="⌘D">Duplicate</flux:menu.item>

                        <flux:menu.separator />

                        <flux:menu.submenu heading="Sort by">
                            <flux:menu.radio.group>
                                <flux:menu.radio checked>Name</flux:menu.radio>
                                <flux:menu.radio>Date modified</flux:menu.radio>
                            </flux:menu.radio.group>
                        </flux:menu.submenu>

                        <flux:menu.submenu heading="Filter">
                            <flux:menu.checkbox checked>Active</flux:menu.checkbox>
                            <flux:menu.checkbox>Archived</flux:menu.checkbox>
                        </flux:menu.submenu>

                        <flux:menu.separator />

                        <flux:menu.item icon="trash" variant="danger" kbd="⌘⌫">Delete</flux:menu.item>
                    </flux:menu>
                </flux:dropdown>

                <flux:dropdown align="end">
                    <flux:button icon="cog" icon:trailing="chevron-down" variant="subtle" />
                    <flux:menu>
                        <flux:menu.item href="#">Account</flux:menu.item>
                        <flux:menu.item href="#">Billing</flux:menu.item>
                        <flux:menu.separator />
                        <flux:menu.item href="#" variant="danger">Sign out</flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </div>

            <x-lab.example src="lab.examples.actions.command"
                           title="Command palette"
                           subheading="The default composition, then the same palette with a hand-composed command.input, grouped items and a command.empty state." />
        </x-lab.section>

        <x-lab.section heading="Navigation"
                      subheading="navbar for horizontal navigation, navmenu for a vertical menu surface, and breadcrumbs for a trail.">
            <x-lab.example src="lab.examples.actions.navbar"
                           title="navbar"
                           subheading="Items with current state, icons, badges and a scrollable variant for narrow viewports." />

            <x-lab.example src="lab.examples.actions.navmenu"
                           title="navmenu"
                           subheading="A menu surface for dropdown-like panels, with separators, a primary variant, kbd hints, disabled and indented items." />

            <x-lab.example src="lab.examples.actions.breadcrumbs"
                           title="breadcrumbs"
                           subheading="A trail of ancestors, with icons and a custom separator." />
        </x-lab.section>

        <x-lab.section heading="Popovers & tooltips"
                      subheading="Lightweight contextual surfaces.">
            <x-lab.example src="lab.examples.actions.popover"
                           title="flux:popover & flux:context"
                           subheading="Popovers are rich floating panels hosted inside flux:dropdown. Right-click context menus are wrapped by flux:context." />

            <x-lab.example src="lab.examples.actions.tooltips"
                           title="Tooltip variants"
                           subheading="Position, kbd hints, toggleable pinning and interactive hover targets." />
        </x-lab.section>

        <x-lab.section heading="Badges, flags, avatars & callouts"
                      subheading="Compact status and identity primitives.">
            <x-lab.example src="lab.examples.actions.avatars-badges"
                           title="Avatars, badges and profiles"
                           subheading="Avatars derive initials from a name, avatar.group stacks them, and profile pairs an avatar with a name." />

            <x-lab.example src="lab.examples.actions.callouts"
                           title="Callout parts"
                           subheading="heading, text, link and a free action area, on top of the success, danger and secondary variants." />

            <div class="flex flex-wrap items-center gap-2">
                <flux:badge square color="zinc">DL</flux:badge>
                <flux:badge color="zinc">Laravel</flux:badge>
                <flux:badge color="red" variant="outline">Pro</flux:badge>
            </div>
        </x-lab.section>

        <x-lab.section heading="Icons"
                      subheading="Every icon that ships with Flux, discovered from the installed package plus this app's own additions.">
            <div class="flex items-center gap-2">
                <flux:input wire:model.live="iconSearch" label="Filter icons"
                            icon="magnifying-glass" placeholder="calendar, user, arrow…"
                            class="max-w-sm" />
            </div>

            <div class="flex items-center gap-2">
                <flux:text size="sm" class="text-zinc-500">
                    {{ count($this->filteredIcons) }} match{{ count($this->filteredIcons) === 1 ? 'es' : '' }} —
                    painting the first {{ min($iconLimit, count($this->filteredIcons)) }} of {{ count($this->iconNames) }} icons
                </flux:text>
            </div>

            <div class="grid grid-cols-4 gap-2 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10">
                @foreach (array_slice($this->filteredIcons, 0, $iconLimit) as $icon)
                    <div class="flex flex-col items-center gap-1 rounded-lg p-2 hover:bg-zinc-50 dark:hover:bg-white/5"
                         title="{{ $icon }}">
                        <flux:icon :name="$icon" class="size-5 text-zinc-600 dark:text-zinc-300" />

                        <flux:text size="xs" class="w-full truncate text-center text-zinc-400">{{ $icon }}</flux:text>
                    </div>
                @endforeach
            </div>

            @if ($this->filteredIcons === [])
                <flux:text size="sm" class="text-zinc-500">No icon matches “{{ $iconSearch }}”.</flux:text>
            @endif
        </x-lab.section>
    </div>
