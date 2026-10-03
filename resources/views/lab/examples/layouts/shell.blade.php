<div class="flex flex-col gap-6">
    <flux:card>
        <flux:card.header>
            <flux:card.heading>Shell primitives</flux:card.heading>
        </flux:card.header>

        <flux:card.body class="flex flex-col gap-4">
            <flux:header class="flex items-center gap-2 rounded-lg border border-zinc-200 px-3 py-2 dark:border-white/10">
                <flux:icon.bell class="size-4 text-zinc-500" variant="micro" />
                <flux:text size="sm">flux:header — a page or panel header</flux:text>
            </flux:header>

            <flux:container class="rounded-lg border border-dashed border-zinc-300 px-4 py-6 text-center dark:border-zinc-700">
                <flux:text size="sm" class="text-zinc-500">flux:container — centres and constrains its content</flux:text>
            </flux:container>

            <div class="flex gap-3">
                <flux:aside class="w-40 rounded-lg border border-zinc-200 p-3 dark:border-white/10">
                    <flux:text size="xs" class="text-zinc-500">flux:aside</flux:text>
                </flux:aside>

                <flux:aside sticky class="flex-1 rounded-lg border border-zinc-200 p-3 dark:border-white/10">
                    <flux:text size="xs" class="text-zinc-500">flux:aside sticky</flux:text>
                </flux:aside>
            </div>

            <flux:footer class="rounded-lg border border-zinc-200 px-3 py-2 dark:border-white/10">
                <flux:text size="sm" class="text-zinc-500">flux:footer</flux:text>
            </flux:footer>

            <div class="flex items-center gap-3">
                <flux:brand name="Design Lab" />
                <flux:spacer />
                <flux:text size="sm" class="text-zinc-500">flux:brand</flux:text>
            </div>
        </flux:card.body>
    </flux:card>
</div>
