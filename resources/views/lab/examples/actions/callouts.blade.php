<div class="flex flex-col gap-4">
    <flux:callout variant="secondary" icon="rocket-launch" heading="New in Flux Pro">
        <x-slot name="text">
            The Pro bundle adds the editor, composer, kanban, carousel, phone, calendar and the
            full chart family on top of everything in the free package.
        </x-slot>

        <div class="flex items-center gap-3">
            <flux:button size="xs">Read the docs</flux:button>
            <flux:callout.link href="https://fluxui.dev" external>fluxui.dev</flux:callout.link>
        </div>
    </flux:callout>

    <flux:callout variant="success" icon="check-circle" heading="Deployed">
        <x-slot name="text">
            The lab is served on port 8003 and the Vite dev server on 5175.
        </x-slot>
    </flux:callout>

    <flux:callout variant="danger" icon="exclamation-circle" heading="License required">
        <x-slot name="text">
            Every Pro component on these pages needs an active license.
        </x-slot>
    </flux:callout>
</div>
