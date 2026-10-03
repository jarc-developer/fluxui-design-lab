<div class="flex flex-col gap-8">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            The shape every other example builds on: an item is an indicator plus a content pair,
            placed directly in the timeline. Do not wrap them in timeline.block — that wrapper is for
            extra, full-width children and it makes the indicator land in the wrong column.
        </flux:text>

        <flux:timeline>
            <flux:timeline.item status="complete">
                <flux:timeline.indicator />

                <flux:timeline.content>
                    <flux:heading size="sm">Project scaffolded</flux:heading>
                    <flux:text size="sm" class="text-zinc-500">
                        Laravel 13 with the Livewire Starter Kit.
                    </flux:text>
                </flux:timeline.content>
            </flux:timeline.item>

            <flux:timeline.item status="complete">
                <flux:timeline.indicator />

                <flux:timeline.content>
                    <flux:heading size="sm">Flux Pro activated</flux:heading>
                    <flux:text size="sm" class="text-zinc-500">
                        License applied via <code class="rounded bg-zinc-100 px-1 dark:bg-white/10">flux:activate</code>.
                    </flux:text>
                </flux:timeline.content>
            </flux:timeline.item>

            <flux:timeline.item status="current">
                <flux:timeline.indicator />

                <flux:timeline.content>
                    <flux:heading size="sm">Catalog in progress</flux:heading>
                    <flux:text size="sm" class="text-zinc-500">
                        Iterating on component coverage.
                    </flux:text>
                </flux:timeline.content>
            </flux:timeline.item>

            <flux:timeline.item>
                <flux:timeline.indicator />

                <flux:timeline.content>
                    <flux:heading size="sm">Ship it</flux:heading>
                    <flux:text size="sm" class="text-zinc-500">Not started yet.</flux:text>
                </flux:timeline.content>
            </flux:timeline.item>
        </flux:timeline>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            status drives the indicator — complete, current, incomplete, or nothing at all
        </flux:text>

        <flux:timeline size="sm">
            <flux:timeline.item status="complete">
                <flux:timeline.indicator />
                <flux:timeline.content>
                    <flux:text size="sm">complete</flux:text>
                </flux:timeline.content>
            </flux:timeline.item>

            <flux:timeline.item status="current">
                <flux:timeline.indicator />
                <flux:timeline.content>
                    <flux:text size="sm">current</flux:text>
                </flux:timeline.content>
            </flux:timeline.item>

            <flux:timeline.item status="incomplete">
                <flux:timeline.indicator />
                <flux:timeline.content>
                    <flux:text size="sm">incomplete</flux:text>
                </flux:timeline.content>
            </flux:timeline.item>

            <flux:timeline.item>
                <flux:timeline.indicator />
                <flux:timeline.content>
                    <flux:text size="sm">no status</flux:text>
                </flux:timeline.content>
            </flux:timeline.item>
        </flux:timeline>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            Indicator variants — bare, a colour, or a glyph in the middle
        </flux:text>

        <flux:timeline size="sm">
            <flux:timeline.item>
                <flux:timeline.indicator variant="bare" />
                <flux:timeline.content>
                    <flux:text size="sm">variant=&quot;bare&quot; drops the circle</flux:text>
                </flux:timeline.content>
            </flux:timeline.item>

            <flux:timeline.item>
                <flux:timeline.indicator color="green" />
                <flux:timeline.content>
                    <flux:text size="sm">color=&quot;green&quot;</flux:text>
                </flux:timeline.content>
            </flux:timeline.item>

            <flux:timeline.item status="complete">
                <flux:timeline.indicator>
                    <flux:icon.check variant="micro" />
                </flux:timeline.indicator>
                <flux:timeline.content>
                    <flux:text size="sm">An icon or a number as the indicator</flux:text>
                </flux:timeline.content>
            </flux:timeline.item>
        </flux:timeline>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            horizontal, with align=&quot;start&quot;
        </flux:text>

        <flux:timeline horizontal size="sm" align="start">
            <flux:timeline.item status="complete">
                <flux:timeline.indicator />
                <flux:timeline.content>
                    <flux:text size="sm" class="font-medium">Scaffold</flux:text>
                </flux:timeline.content>
            </flux:timeline.item>

            <flux:timeline.item status="complete">
                <flux:timeline.indicator />
                <flux:timeline.content>
                    <flux:text size="sm" class="font-medium">License</flux:text>
                </flux:timeline.content>
            </flux:timeline.item>

            <flux:timeline.item status="current">
                <flux:timeline.indicator />
                <flux:timeline.content>
                    <flux:text size="sm" class="font-medium">Catalog</flux:text>
                </flux:timeline.content>
            </flux:timeline.item>

            <flux:timeline.item>
                <flux:timeline.indicator />
                <flux:timeline.content>
                    <flux:text size="sm" class="font-medium">Ship</flux:text>
                </flux:timeline.content>
            </flux:timeline.item>
        </flux:timeline>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            timeline.block + timeline.subgrid — the same indicator/content pair, but with room for
            extra full-width children alongside. The subgrid holds the pair so the block's other
            children can span the whole row.
        </flux:text>

        <flux:timeline>
            <flux:timeline.item status="complete">
                <flux:timeline.block>
                    <flux:timeline.subgrid>
                        <flux:timeline.indicator />

                        <flux:timeline.content>
                            <flux:heading size="sm">Release v2.20</flux:heading>
                            <flux:text size="sm" class="text-zinc-500">
                                The editor, phone and canvas date pickers all landed in this release.
                            </flux:text>
                        </flux:timeline.content>
                    </flux:timeline.subgrid>

                    <flux:card>
                        <flux:card.body>
                            <flux:text size="sm">
                                This card is a sibling of the subgrid, so it spans the full width of
                                the row instead of sitting in the content column.
                            </flux:text>
                        </flux:card.body>
                    </flux:card>
                </flux:timeline.block>
            </flux:timeline.item>
        </flux:timeline>
    </div>
</div>
