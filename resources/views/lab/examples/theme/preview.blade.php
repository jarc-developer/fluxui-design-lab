@props([
    'previewDark' => false,
    'previewTab' => 'dashboard',
    'dashboardSubTab' => 'overview',
])

<div
    x-data="{
        activeTab: @js($previewTab),
        init() {
            if (this.$wire && 'previewTab' in this.$wire) {
                this.$watch('$wire.previewTab', (val) => {
                    if (val) this.activeTab = val;
                });
            }
        },
        selectTab(tab) {
            this.activeTab = tab;
            const tabEl = $el.querySelector(`[data-flux-tab][name='${tab}']`);
            if (tabEl && !tabEl.hasAttribute('data-selected')) {
                tabEl.click();
            }
        },
        get activeHeading() {
            return {
                dashboard: 'Dashboard',
                register: 'Create Account',
                settings: 'Workspace Settings',
            }[this.activeTab] ?? 'Dashboard';
        }
    }"
    class="flex flex-col"
>
    {{-- macOS-Style Window Chrome Bar --}}
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-zinc-200/80 bg-zinc-50/90 px-4 py-2.5 dark:border-zinc-800 dark:bg-zinc-900/90">
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-1.5" aria-hidden="true">
                <span class="size-3 rounded-full bg-red-400/90"></span>
                <span class="size-3 rounded-full bg-amber-400/90"></span>
                <span class="size-3 rounded-full bg-emerald-400/90"></span>
            </div>
            <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Component Preview</span>
        </div>

        <flux:tabs
            wire:model="previewTab"
            variant="segmented"
            size="sm"
            x-on:input="if ($event.target.value) activeTab = $event.target.value"
            x-on:change="if ($event.target.value) activeTab = $event.target.value"
        >
            <flux:tab name="dashboard" :selected="$previewTab === 'dashboard'" x-on:click="activeTab = 'dashboard'">Dashboard</flux:tab>
            <flux:tab name="register" :selected="$previewTab === 'register'" x-on:click="activeTab = 'register'">Register</flux:tab>
            <flux:tab name="settings" :selected="$previewTab === 'settings'" x-on:click="activeTab = 'settings'">Settings</flux:tab>
        </flux:tabs>
    </div>

    {{-- Embedded Application Shell (Sidebar + Header + Tabbed Main Content) --}}
    <div class="flex min-h-[600px]">
        <flux:sidebar class="hidden w-52 shrink-0 border-e border-zinc-200 bg-zinc-50 p-3 md:flex dark:border-zinc-800 dark:bg-zinc-900">
            <flux:sidebar.header>
                <flux:sidebar.brand name="Acme Inc." as="div">
                    <div class="flex aspect-square size-6 items-center justify-center rounded-full bg-(--color-accent) text-(--color-accent-foreground)">
                        <flux:icon name="cube" variant="micro" />
                    </div>
                </flux:sidebar.brand>
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.item
                    icon="home"
                    :current="$previewTab === 'dashboard'"
                    x-bind:data-current="activeTab === 'dashboard' ? '' : null"
                    x-on:click="selectTab('dashboard')"
                >
                    Dashboard
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="chart-bar"
                    x-on:click="selectTab('dashboard')"
                >
                    Analytics
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="folder"
                    badge="4"
                    x-on:click="selectTab('dashboard')"
                >
                    Projects
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="user-plus"
                    :current="$previewTab === 'register'"
                    x-bind:data-current="activeTab === 'register' ? '' : null"
                    x-on:click="selectTab('register')"
                >
                    Register
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <flux:sidebar.spacer />

            <flux:separator variant="subtle" />

            <flux:sidebar.nav>
                <flux:sidebar.item
                    icon="cog-6-tooth"
                    :current="$previewTab === 'settings'"
                    x-bind:data-current="activeTab === 'settings' ? '' : null"
                    x-on:click="selectTab('settings')"
                >
                    Settings
                </flux:sidebar.item>

                <flux:sidebar.item icon="question-mark-circle">
                    Help
                </flux:sidebar.item>
            </flux:sidebar.nav>
        </flux:sidebar>

        <div class="flex min-w-0 flex-1 flex-col bg-white dark:bg-zinc-950">
            <flux:header class="min-h-14 border-b border-zinc-200 bg-white px-5 lg:px-6 dark:border-zinc-800 dark:bg-zinc-900">
                <flux:heading size="base" x-text="activeHeading">
                    {{ match ($previewTab) {
                        'register' => 'Create Account',
                        'settings' => 'Workspace Settings',
                        default => 'Dashboard',
                    } }}
                </flux:heading>

                <flux:spacer />

                <div class="flex items-center gap-2.5">
                    <flux:input
                        size="sm"
                        icon="magnifying-glass"
                        placeholder="Search..."
                        class="hidden w-44 sm:block"
                    />

                    <flux:dropdown position="bottom" align="end">
                        <flux:button type="button" variant="ghost" size="sm" icon:trailing="chevron-down">
                            John
                        </flux:button>

                        <flux:menu>
                            <flux:menu.item icon="home" x-on:click="selectTab('dashboard')">Dashboard</flux:menu.item>
                            <flux:menu.item icon="user-plus" x-on:click="selectTab('register')">Register form</flux:menu.item>
                            <flux:menu.item icon="cog-6-tooth" x-on:click="selectTab('settings')">Settings</flux:menu.item>
                            <flux:menu.separator />
                            <flux:menu.item icon="arrow-right-start-on-rectangle" variant="danger">Sign out</flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </flux:header>

            <div class="flex-1 p-5 sm:p-6">
                <div
                    data-preview-panel="dashboard"
                    x-bind:hidden="activeTab !== 'dashboard'"
                    {{ $previewTab !== 'dashboard' ? 'hidden' : '' }}
                >
                    @include('lab.examples.theme.tabs.dashboard', ['dashboardSubTab' => $dashboardSubTab])
                </div>

                <div
                    data-preview-panel="register"
                    x-bind:hidden="activeTab !== 'register'"
                    {{ $previewTab !== 'register' ? 'hidden' : '' }}
                >
                    @include('lab.examples.theme.tabs.register')
                </div>

                <div
                    data-preview-panel="settings"
                    x-bind:hidden="activeTab !== 'settings'"
                    {{ $previewTab !== 'settings' ? 'hidden' : '' }}
                >
                    @include('lab.examples.theme.tabs.settings', ['previewDark' => $previewDark])
                </div>
            </div>
        </div>
    </div>
</div>
