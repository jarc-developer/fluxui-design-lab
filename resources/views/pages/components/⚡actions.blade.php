<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Actions & Navigation')] class extends Component {
    public function notify(): void
    {
        $this->dispatch('toast', type: 'success', title: 'Saved', description: 'The lab state was updated.');
    }
}; ?>

    <div class="flex w-full flex-1 flex-col gap-8">
        <x-lab.section heading="Buttons"
                      subheading="Every variant, size, colour and icon pairing.">
            <div class="flex flex-wrap items-center gap-2">
                <flux:button>Primary</flux:button>
                <flux:button color="red">Destructive</flux:button>
                <flux:button color="green">Success</flux:button>
                <flux:button color="zinc">Neutral</flux:button>
                <flux:button variant="ghost">Ghost</flux:button>
                <flux:button variant="primary">Filled</flux:button>
                <flux:button variant="subtle">Soft</flux:button>
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
                <flux:button loading wire:click="$sleep(3000)">Loading</flux:button>
                <flux:button disabled>Disabled</flux:button>
            </div>
        </x-lab.section>

        <x-lab.section heading="Menus & dropdowns"
                      subheading="Dropdown triggers, nested menus and command palettes.">
            <div class="flex flex-wrap items-center gap-2">
                <flux:dropdown placement="bottom" align="start">
                    <flux:button icon="bars-3" icon:trailing="chevron-down">Options</flux:button>
                    <flux:menu>
                        <flux:menu.item icon="pencil-square">Edit</flux:menu.item>
                        <flux:menu.item icon="document-duplicate">Duplicate</flux:menu.item>
                        <flux:menu.item icon="trash" variant="danger">Delete</flux:menu.item>
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

                <flux:command icon="magnifying-glass" placeholder="Search components…">
                    <flux:command.items>
                        <flux:command.item icon="rectangle-group">Buttons</flux:command.item>
                        <flux:command.item icon="rectangle-group">Selects</flux:command.item>
                        <flux:command.item icon="rectangle-group">Modals</flux:command.item>
                        <flux:command.item icon="rectangle-group">Tables</flux:command.item>
                    </flux:command.items>
                </flux:command>
            </div>
        </x-lab.section>

        <x-lab.section heading="Popovers & tooltips"
                      subheading="Lightweight contextual surfaces.">
            <div class="flex flex-wrap items-center gap-2">
                <flux:tooltip>
                    <flux:button variant="subtle">Hover me</flux:button>
                    <flux:tooltip.content>Explains the button.</flux:tooltip.content>
                </flux:tooltip>

                <flux:button class="relative" x-data="{ show: false }" x-on:click="show = ! show"
                             icon-trailing="chevron-up">
                    Popover
                    <flux:popover x-show="show" x-cloak x-transition class="absolute z-10 w-64">
                        <flux:heading size="sm">Popover content</flux:heading>
                        <flux:text size="sm" class="mt-1">Anchored panel for richer content than a tooltip.</flux:text>
                    </flux:popover>
                </flux:button>
            </div>
        </x-lab.section>

        <x-lab.section heading="Badges, flags & icons"
                      subheading="Compact status and identity primitives.">
            <div class="flex flex-wrap items-center gap-2">
                <flux:badge color="green">Shipped</flux:badge>
                <flux:badge color="red">Deprecated</flux:badge>
                <flux:badge color="amber" variant="solid" size="sm">Beta</flux:badge>
                <flux:flag square color="zinc">DL</flux:flag>
                <flux:flag color="zinc">Laravel</flux:flag>
            </div>

            <div class="flex flex-wrap items-center gap-2 text-zinc-500 dark:text-zinc-400">
                <flux:icon name="home" class="size-5" />
                <flux:icon name="user" class="size-5" />
                <flux:icon name="cog" class="size-5" />
                <flux:icon name="star" class="size-5" />
                <flux:icon name="heart" class="size-5" />
                <flux:icon name="bell" class="size-5" />
                <flux:icon name="calendar" class="size-5" />
                <flux:icon name="envelope" class="size-5" />
            </div>
        </x-lab.section>
    </div>
