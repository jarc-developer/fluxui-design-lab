<flux:kanban>
    <flux:kanban.column>
        <flux:kanban.column.header heading="Backlog" :count="2" badge="Soon">
            <flux:icon.inbox class="text-zinc-400" variant="micro" />
        </flux:kanban.column.header>

        <flux:kanban.column.cards>
            <flux:kanban.card heading="Audit the color tokens">
                <flux:text size="sm" class="text-zinc-500">Every accent step needs a contrast check.</flux:text>
            </flux:kanban.card>

            <flux:kanban.card heading="Document the snippet helper">
                <flux:text size="sm" class="text-zinc-500">Explain x-lab.example to the next contributor.</flux:text>
            </flux:kanban.card>
        </flux:kanban.column.cards>

        <flux:kanban.column.footer>
            <flux:button size="xs" variant="subtle" icon="plus" class="w-full">Add a card</flux:button>
        </flux:kanban.column.footer>
    </flux:kanban.column>

    <flux:kanban.column>
        <flux:kanban.column.header heading="In progress" :count="2" />

        <flux:kanban.column.cards>
            <flux:kanban.card heading="Cover the chart family">
                <x-slot name="header">
                    <flux:badge size="sm" color="red">Blocked</flux:badge>
                </x-slot>

                <flux:text size="sm" class="text-zinc-500">Pie, stack and group still need examples.</flux:text>

                <x-slot name="footer">
                    <flux:avatar.group>
                        <flux:avatar name="Ana Ruiz" />
                        <flux:avatar name="Kim Ito" />
                    </flux:avatar.group>

                    <flux:text size="xs" class="text-zinc-500">2 people</flux:text>
                </x-slot>
            </flux:kanban.card>

            <flux:kanban.card heading="Rewrite the lab README" />

            <flux:kanban.card as="button" heading="Pick me up">
                <flux:text size="sm" class="text-zinc-500">as=&quot;button&quot; renders a real button.</flux:text>
            </flux:kanban.card>
        </flux:kanban.column.cards>
    </flux:kanban.column>

    <flux:kanban.column>
        <flux:kanban.column.header heading="Shipped" :count="1" badge="v2.20">
            <flux:icon.check-circle class="text-green-500" variant="micro" />
        </flux:kanban.column.header>

        <flux:kanban.column.cards>
            <flux:kanban.card heading="Add the Pro catalog pages">
                <flux:text size="sm" class="text-zinc-500">Seven new routes and a sidebar group.</flux:text>
            </flux:kanban.card>
        </flux:kanban.column.cards>

        <flux:kanban.column.footer>
            <flux:text size="xs" class="text-zinc-500">Drop a card here to close it.</flux:text>
        </flux:kanban.column.footer>
    </flux:kanban.column>
</flux:kanban>
