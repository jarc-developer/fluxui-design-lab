<div class="grid gap-4 md:grid-cols-2">
    <flux:card>
        <flux:card.header>
            <flux:card.heading>Divided card</flux:card.heading>
            <flux:card.heading size="sm" class="text-zinc-500">default</flux:card.heading>
        </flux:card.header>

        <flux:card.body>
            <flux:text size="sm">
                The default card divides its bands with a hairline. header, body and footer
                line up automatically.
            </flux:text>
        </flux:card.body>

        <flux:card.footer>
            <flux:button size="xs" variant="subtle">Action</flux:button>
        </flux:card.footer>
    </flux:card>

    <flux:card variant="separated">
        <flux:card.header>
            <flux:card.heading>Separated</flux:card.heading>
        </flux:card.header>

        <flux:card.body>
            <flux:text size="sm">Bands sit apart instead of sharing a rule.</flux:text>
        </flux:card.body>
    </flux:card>

    <flux:card variant="soft">
        <flux:card.header>
            <flux:card.heading>Soft</flux:card.heading>
        </flux:card.header>

        <flux:card.body>
            <flux:text size="sm">A muted background behind the bands.</flux:text>
        </flux:card.body>
    </flux:card>

    <flux:card variant="filled">
        <flux:card.header>
            <flux:card.heading>Filled</flux:card.heading>
        </flux:card.header>

        <flux:card.body>
            <flux:text size="sm">The strongest band treatment.</flux:text>
        </flux:card.body>
    </flux:card>
</div>
