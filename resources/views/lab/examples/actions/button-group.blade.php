<div class="flex flex-col gap-6">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">Default</flux:text>

        <flux:button.group>
            <flux:button>Day</flux:button>
            <flux:button>Week</flux:button>
            <flux:button>Month</flux:button>
        </flux:button.group>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">With icons and a nested group</flux:text>

        <flux:button.group>
            <flux:button icon="arrow-left" />

            <flux:button.group>
                <flux:button>1</flux:button>
                <flux:button>2</flux:button>
                <flux:button>3</flux:button>
            </flux:button.group>

            <flux:button icon="arrow-right" />
        </flux:button.group>
    </div>
</div>
