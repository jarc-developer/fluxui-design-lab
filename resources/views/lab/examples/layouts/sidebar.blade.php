<div class="flex flex-col gap-6">
    <flux:text size="sm" class="text-zinc-500">
        The application shell sidebar layout, demonstrating brand, search, expandable groups with icons, badges, and profile triggers.
    </flux:text>

    <div class="relative overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
        <div class="flex min-h-[420px]">
            <flux:sidebar class="w-64 border-e border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:sidebar.header>
                    <flux:sidebar.brand
                        href="#"
                        name="Design Lab"
                    >
                        <div class="flex aspect-square size-6 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
                            <flux:icon name="bolt" variant="micro" class="text-white dark:text-black" />
                        </div>
                    </flux:sidebar.brand>

                    <flux:sidebar.collapse />
                </flux:sidebar.header>

                <flux:sidebar.search placeholder="Search..." />

                <flux:sidebar.nav>
                    <flux:sidebar.item icon="home" href="#" current>Dashboard</flux:sidebar.item>
                    <flux:sidebar.item icon="inbox" badge="5" href="#">Inbox</flux:sidebar.item>
                    <flux:sidebar.item icon="document-text" href="#">Documents</flux:sidebar.item>

                    <flux:sidebar.group expandable icon="star" heading="Favorites" class="grid">
                        <flux:sidebar.item href="#">Design System</flux:sidebar.item>
                        <flux:sidebar.item href="#">Component Catalog</flux:sidebar.item>
                    </flux:sidebar.group>
                </flux:sidebar.nav>

                <flux:sidebar.spacer />

                <flux:sidebar.nav>
                    <flux:sidebar.item icon="cog-6-tooth" href="#">Settings</flux:sidebar.item>
                    <flux:sidebar.item icon="question-mark-circle" href="#">Support</flux:sidebar.item>
                </flux:sidebar.nav>

                <flux:dropdown position="top" align="start">
                    <flux:sidebar.profile name="Alex Morgan" initials="AM" icon:trailing="chevrons-up-down" />

                    <flux:menu>
                        <flux:menu.item icon="user">Account</flux:menu.item>
                        <flux:menu.separator />
                        <flux:menu.item icon="arrow-right-start-on-rectangle">Log out</flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </flux:sidebar>

            <div class="flex-1 p-6">
                <flux:heading size="lg">Dashboard Overview</flux:heading>
                <flux:text size="sm" class="mt-1 text-zinc-500">
                    Main content area alongside the sidebar navigation shell.
                </flux:text>
            </div>
        </div>
    </div>
</div>
