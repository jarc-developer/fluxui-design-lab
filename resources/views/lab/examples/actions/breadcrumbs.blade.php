<div class="flex flex-col gap-6">
    <flux:breadcrumbs aria-label="Breadcrumb">
        <flux:breadcrumbs.item href="#" icon="home">Home</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">Projects</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">Design lab</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Components</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            separator is an icon name, not a literal — the default renders a chevron pair that flips
            for RTL. Use &quot;slash&quot; for the built-in slash, or the name of any icon.
        </flux:text>

        <flux:breadcrumbs aria-label="With a custom separator">
            <flux:breadcrumbs.item href="#" icon="folder" separator="slash">Flux</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#" separator="chevron-down">Pro</flux:breadcrumbs.item>
            <flux:breadcrumbs.item separator="slash">Editor</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>
</div>
