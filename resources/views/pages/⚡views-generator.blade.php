<?php

use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

new #[Title('Views Generator')] class extends Component {
    #[Url]
    public string $layout = 'saas-dashboard';

    #[Url]
    public string $shellStyle = 'minimal';

    #[Url]
    public string $headerComponent = 'stats';

    #[Url]
    public string $mainComponent = 'charts-and-table';

    #[Url]
    public string $secondaryComponent = 'activity';

    #[Url]
    public string $viewport = 'desktop';

    public bool $previewDark = false;

    public bool $showCodeModal = false;

    /**
     * @return array<string, array{name: string, description: string, icon: string, defaultHeader: string, defaultMain: string, defaultSecondary: string, mains: array<string, string>}>
     */
    #[Computed]
    public function layoutsRegistry(): array
    {
        return [
            'saas-dashboard' => [
                'name' => 'SaaS Dashboard',
                'description' => 'KPI metrics, revenue trend chart, quick insights, and transactions table',
                'icon' => 'chart-bar',
                'defaultHeader' => 'stats',
                'defaultMain' => 'charts-and-table',
                'defaultSecondary' => 'activity',
                'mains' => [
                    'charts-and-table' => 'Split Chart + Transactions Table',
                    'chart-grid' => 'Multi-Metric Chart Grid',
                ],
            ],
            'data-crud' => [
                'name' => 'Data Management & CRUD',
                'description' => 'Search, filter bar, interactive member table, and usage quotas widget',
                'icon' => 'table-cells',
                'defaultHeader' => 'search-actions',
                'defaultMain' => 'detailed-table',
                'defaultSecondary' => 'quick-stats',
                'mains' => [
                    'detailed-table' => 'Member Roster Data Table',
                    'card-grid' => 'Entity Cards Grid',
                ],
            ],
            'kanban' => [
                'name' => 'Project Kanban Board',
                'description' => 'Three-column workflow lanes, draggable-ready task cards, and member avatars',
                'icon' => 'rectangle-stack',
                'defaultHeader' => 'minimal',
                'defaultMain' => 'kanban-board',
                'defaultSecondary' => 'none',
                'mains' => [
                    'kanban-board' => 'Sprint Workflow Columns (3 Lanes)',
                ],
            ],
            'settings' => [
                'name' => 'Account & Settings',
                'description' => 'Profile information form, notification toggles, and schedule widget',
                'icon' => 'cog-6-tooth',
                'defaultHeader' => 'tabs',
                'defaultMain' => 'settings-form',
                'defaultSecondary' => 'calendar-quick',
                'mains' => [
                    'settings-form' => 'Profile & Notification Settings',
                ],
            ],
            'basketball-player' => [
                'name' => 'Basketball Player Profile',
                'description' => 'Player bio, athletic measurements, positions, team history, photo gallery, and favourite courts',
                'icon' => 'user',
                'defaultHeader' => 'player-hero',
                'defaultMain' => 'player-overview',
                'defaultSecondary' => 'player-courts',
                'mains' => [
                    'player-overview' => 'Player Biography & Physical Attributes',
                    'player-gallery' => 'Photo Reel & Favourite Courts Showcase',
                ],
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    #[Computed]
    public function shellStyles(): array
    {
        return [
            'minimal' => 'Minimal Canvas (No Chrome)',
            'header' => 'Top Navbar Chrome',
            'stacked' => 'Stacked Cards Shell',
        ];
    }

    /**
     * @return array<string, string>
     */
    #[Computed]
    public function headerComponents(): array
    {
        return [
            'player-hero' => 'Player Hero Profile Card',
            'stats' => 'KPI Stat Cards',
            'search-actions' => 'Search & Action Bar',
            'announcement' => 'Hero Welcome Banner',
            'tabs' => 'Subnavigation Tabs',
            'minimal' => 'Minimal Breadcrumbs & Title',
            'none' => 'None (Skip Header)',
        ];
    }

    /**
     * @return array<string, string>
     */
    #[Computed]
    public function currentMainComponents(): array
    {
        $registry = $this->layoutsRegistry();
        $active = $registry[$this->layout] ?? $registry['saas-dashboard'];

        return $active['mains'];
    }

    /**
     * @return array<string, string>
     */
    #[Computed]
    public function secondaryComponents(): array
    {
        return [
            'player-courts' => 'Favourite Courts & Teams Card',
            'activity' => 'Recent Activity Stream',
            'calendar-quick' => "Today's Schedule Widget",
            'quick-stats' => 'Usage & Quotas Meter',
            'none' => 'None (Full Width Main)',
        ];
    }

    #[Computed]
    public function activeLayoutName(): string
    {
        return $this->layoutsRegistry()[$this->layout]['name'] ?? 'Custom View';
    }

    public function selectLayout(string $key): void
    {
        $registry = $this->layoutsRegistry();
        if (! isset($registry[$key])) {
            return;
        }

        $this->layout = $key;
        $this->headerComponent = $registry[$key]['defaultHeader'];
        $this->mainComponent = $registry[$key]['defaultMain'];
        $this->secondaryComponent = $registry[$key]['defaultSecondary'];

        Flux::toast(text: "Switched to {$registry[$key]['name']} layout.", variant: 'success');
    }

    public function selectShellStyle(string $style): void
    {
        if (array_key_exists($style, $this->shellStyles())) {
            $this->shellStyle = $style;
        }
    }

    public function selectHeaderComponent(string $comp): void
    {
        if (array_key_exists($comp, $this->headerComponents())) {
            $this->headerComponent = $comp;
        }
    }

    public function selectMainComponent(string $comp): void
    {
        if (array_key_exists($comp, $this->currentMainComponents())) {
            $this->mainComponent = $comp;
        }
    }

    public function selectSecondaryComponent(string $comp): void
    {
        if (array_key_exists($comp, $this->secondaryComponents())) {
            $this->secondaryComponent = $comp;
        }
    }

    public function selectViewport(string $vp): void
    {
        if (in_array($vp, ['desktop', 'tablet', 'mobile'], true)) {
            $this->viewport = $vp;
        }
    }

    public function togglePreviewDark(): void
    {
        $this->previewDark = ! $this->previewDark;
    }

    public function toggleCodeModal(): void
    {
        $this->showCodeModal = ! $this->showCodeModal;
    }

    public function randomize(): void
    {
        $layouts = array_keys($this->layoutsRegistry());
        $randomLayout = $layouts[array_rand($layouts)];
        $this->selectLayout($randomLayout);

        $shells = array_keys($this->shellStyles());
        $this->shellStyle = $shells[array_rand($shells)];

        $headers = array_keys($this->headerComponents());
        $this->headerComponent = $headers[array_rand($headers)];

        $mains = array_keys($this->currentMainComponents());
        $this->mainComponent = $mains[array_rand($mains)];

        $secondaries = array_keys($this->secondaryComponents());
        $this->secondaryComponent = $secondaries[array_rand($secondaries)];

        Flux::toast(text: 'Generated a random view composition.', variant: 'success');
    }

    public function resetToDefaults(): void
    {
        $this->selectLayout('saas-dashboard');
        $this->shellStyle = 'minimal';
        $this->viewport = 'desktop';
        $this->previewDark = false;

        Flux::toast(text: 'Reset views generator to defaults.', variant: 'success');
    }

    #[Computed]
    public function generatedBladeCode(): string
    {
        $headerComment = match ($this->headerComponent) {
            'player-hero' => "    <!-- Header: Basketball Player Profile Hero -->\n    <flux:card class=\"p-6\">...</flux:card>\n",
            'stats' => "    <!-- Header: KPI Stats Cards -->\n    <div class=\"grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4\">...</div>\n",
            'search-actions' => "    <!-- Header: Search & Action Toolbar -->\n    <div class=\"flex items-center justify-between gap-3\">...</div>\n",
            'announcement' => "    <!-- Header: Welcome Announcement Hero -->\n    <div class=\"rounded-xl border p-6 bg-gradient-to-r ...\">...</div>\n",
            'tabs' => "    <!-- Header: Subnavigation Tabs -->\n    <div class=\"flex items-center gap-1 border-b pb-3\">...</div>\n",
            'minimal' => "    <!-- Header: Breadcrumbs & Heading -->\n    <flux:breadcrumbs>...</flux:breadcrumbs>\n",
            default => '',
        };

        $secondarySlot = $this->secondaryComponent !== 'none'
            ? "    <!-- Secondary Widget: {$this->secondaryComponent} -->\n    <div class=\"xl:col-span-1 space-y-6\">\n        <flux:card>...</flux:card>\n    </div>\n"
            : '';

        $colsClass = $this->secondaryComponent !== 'none' ? 'xl:grid-cols-4' : 'grid-cols-1';
        $mainColsClass = $this->secondaryComponent !== 'none' ? 'xl:col-span-3' : 'w-full';

        return <<<BLADE
<x-layouts::app :title="__( '{$this->activeLayoutName}')">
    <div class="flex flex-col gap-6">
{$headerComment}
        <div class="grid grid-cols-1 {$colsClass} gap-6 items-start">
            <!-- Main Content: {$this->mainComponent} -->
            <div class="{$mainColsClass} space-y-6">
                <flux:card>
                    <!-- Generated {$this->mainComponent} markup -->
                </flux:card>
            </div>

{$secondarySlot}        </div>
    </div>
</x-layouts::app>
BLADE;
    }
}; ?>

<div class="flex w-full flex-1 flex-col gap-6">
    {{-- Top Generator Control Bar --}}
    <div class="sticky top-0 z-30 rounded-xl border border-zinc-200/90 dark:border-zinc-700/80 bg-white/90 dark:bg-zinc-800/90 backdrop-blur-md px-3.5 py-2.5 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3">
            {{-- Component Selection Dropdowns --}}
            <div class="flex flex-wrap items-center gap-2">
                {{-- Dropdown 1: Suggested Layout --}}
                <flux:dropdown position="bottom" align="start">
                    <flux:button
                        variant="subtle"
                        size="sm"
                        icon="squares-plus"
                        icon-trailing="chevron-down"
                        class="border border-zinc-200 dark:border-zinc-700 font-medium"
                        data-test="dropdown-layout-trigger"
                    >
                        <span class="text-zinc-400 font-normal mr-1">Layout:</span>
                        <span class="truncate max-w-[130px] sm:max-w-none">{{ $this->activeLayoutName }}</span>
                    </flux:button>

                    <flux:menu class="w-80 max-w-[calc(100vw-2rem)] p-1.5 shadow-xl">
                        <flux:menu.heading class="px-2 pt-1 pb-1.5 text-[11px] uppercase tracking-wider font-semibold text-zinc-400">
                            {{ __('Suggested Layouts') }}
                        </flux:menu.heading>

                        @foreach ($this->layoutsRegistry() as $key => $item)
                            <flux:menu.item
                                wire:click="selectLayout('{{ $key }}')"
                                class="flex items-start gap-2.5 p-2 rounded-lg cursor-pointer {{ $layout === $key ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white font-medium' : '' }}"
                                data-test="select-layout-{{ $key }}"
                            >
                                <div class="flex aspect-square size-6 shrink-0 items-center justify-center rounded-md bg-zinc-200 dark:bg-zinc-700 mt-0.5">
                                    <flux:icon :name="$item['icon']" class="size-3.5 text-zinc-700 dark:text-zinc-300" />
                                </div>
                                <div class="grid flex-1 leading-tight text-start">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold">{{ $item['name'] }}</span>
                                        @if($layout === $key)
                                            <flux:icon name="check" class="size-3.5 text-indigo-500" />
                                        @endif
                                    </div>
                                    <span class="text-[11px] text-zinc-500 line-clamp-1">{{ $item['description'] }}</span>
                                </div>
                            </flux:menu.item>
                        @endforeach
                    </flux:menu>
                </flux:dropdown>

                {{-- Dropdown 2: Shell Style --}}
                <flux:dropdown position="bottom" align="start">
                    <flux:button
                        variant="subtle"
                        size="sm"
                        icon="window"
                        icon-trailing="chevron-down"
                        class="border border-zinc-200 dark:border-zinc-700"
                        data-test="dropdown-shell-trigger"
                    >
                        <span class="text-zinc-400 font-normal mr-1">Shell:</span>
                        <span class="truncate max-w-[110px] sm:max-w-none">{{ $this->shellStyles()[$shellStyle] ?? $shellStyle }}</span>
                    </flux:button>

                    <flux:menu class="w-64 max-w-[calc(100vw-2rem)] p-1.5">
                        <flux:menu.heading class="px-2 pt-1 pb-1.5 text-[11px] uppercase tracking-wider font-semibold text-zinc-400">
                            {{ __('Shell Chrome Style') }}
                        </flux:menu.heading>

                        @foreach ($this->shellStyles() as $key => $label)
                            <flux:menu.item
                                wire:click="selectShellStyle('{{ $key }}')"
                                :icon="$shellStyle === $key ? 'check' : null"
                                class="{{ $shellStyle === $key ? 'bg-zinc-100 dark:bg-zinc-800 font-medium' : '' }}"
                                data-test="select-shell-{{ $key }}"
                            >
                                {{ $label }}
                            </flux:menu.item>
                        @endforeach
                    </flux:menu>
                </flux:dropdown>

                {{-- Dropdown 3: Header Component --}}
                <flux:dropdown position="bottom" align="start">
                    <flux:button
                        variant="subtle"
                        size="sm"
                        icon="view-columns"
                        icon-trailing="chevron-down"
                        class="border border-zinc-200 dark:border-zinc-700"
                        data-test="dropdown-header-trigger"
                    >
                        <span class="text-zinc-400 font-normal mr-1">Header:</span>
                        <span class="truncate max-w-[110px] sm:max-w-none">{{ $this->headerComponents()[$headerComponent] ?? $headerComponent }}</span>
                    </flux:button>

                    <flux:menu class="w-64 max-w-[calc(100vw-2rem)] p-1.5">
                        <flux:menu.heading class="px-2 pt-1 pb-1.5 text-[11px] uppercase tracking-wider font-semibold text-zinc-400">
                            {{ __('Header Section Component') }}
                        </flux:menu.heading>

                        @foreach ($this->headerComponents() as $key => $label)
                            <flux:menu.item
                                wire:click="selectHeaderComponent('{{ $key }}')"
                                :icon="$headerComponent === $key ? 'check' : null"
                                class="{{ $headerComponent === $key ? 'bg-zinc-100 dark:bg-zinc-800 font-medium' : '' }}"
                                data-test="select-header-{{ $key }}"
                            >
                                {{ $label }}
                            </flux:menu.item>
                        @endforeach
                    </flux:menu>
                </flux:dropdown>

                {{-- Dropdown 4: Main Component --}}
                <flux:dropdown position="bottom" align="start">
                    <flux:button
                        variant="subtle"
                        size="sm"
                        icon="table-cells"
                        icon-trailing="chevron-down"
                        class="border border-zinc-200 dark:border-zinc-700"
                        data-test="dropdown-main-trigger"
                    >
                        <span class="text-zinc-400 font-normal mr-1">Main:</span>
                        <span class="truncate max-w-[120px] sm:max-w-none">{{ $this->currentMainComponents()[$mainComponent] ?? $mainComponent }}</span>
                    </flux:button>

                    <flux:menu class="w-72 max-w-[calc(100vw-2rem)] p-1.5">
                        <flux:menu.heading class="px-2 pt-1 pb-1.5 text-[11px] uppercase tracking-wider font-semibold text-zinc-400">
                            {{ __('Main Content Component') }}
                        </flux:menu.heading>

                        @foreach ($this->currentMainComponents() as $key => $label)
                            <flux:menu.item
                                wire:click="selectMainComponent('{{ $key }}')"
                                :icon="$mainComponent === $key ? 'check' : null"
                                class="{{ $mainComponent === $key ? 'bg-zinc-100 dark:bg-zinc-800 font-medium' : '' }}"
                                data-test="select-main-{{ $key }}"
                            >
                                {{ $label }}
                            </flux:menu.item>
                        @endforeach
                    </flux:menu>
                </flux:dropdown>

                {{-- Dropdown 5: Secondary Component --}}
                <flux:dropdown position="bottom" align="start">
                    <flux:button
                        variant="subtle"
                        size="sm"
                        icon="bars-3-bottom-right"
                        icon-trailing="chevron-down"
                        class="border border-zinc-200 dark:border-zinc-700"
                        data-test="dropdown-secondary-trigger"
                    >
                        <span class="text-zinc-400 font-normal mr-1">Widget:</span>
                        <span class="truncate max-w-[110px] sm:max-w-none">{{ $this->secondaryComponents()[$secondaryComponent] ?? $secondaryComponent }}</span>
                    </flux:button>

                    <flux:menu class="w-64 max-w-[calc(100vw-2rem)] p-1.5">
                        <flux:menu.heading class="px-2 pt-1 pb-1.5 text-[11px] uppercase tracking-wider font-semibold text-zinc-400">
                            {{ __('Secondary / Widget Component') }}
                        </flux:menu.heading>

                        @foreach ($this->secondaryComponents() as $key => $label)
                            <flux:menu.item
                                wire:click="selectSecondaryComponent('{{ $key }}')"
                                :icon="$secondaryComponent === $key ? 'check' : null"
                                class="{{ $secondaryComponent === $key ? 'bg-zinc-100 dark:bg-zinc-800 font-medium' : '' }}"
                                data-test="select-secondary-{{ $key }}"
                            >
                                {{ $label }}
                            </flux:menu.item>
                        @endforeach
                    </flux:menu>
                </flux:dropdown>
            </div>

            {{-- Quick Actions & Viewport Controls --}}
            <div class="flex flex-wrap items-center gap-2">
                {{-- Viewport Selector --}}
                <div class="flex items-center rounded-lg border border-zinc-200 dark:border-zinc-700 p-0.5 bg-zinc-50 dark:bg-zinc-800">
                    <flux:button
                        wire:click="selectViewport('desktop')"
                        variant="subtle"
                        size="xs"
                        icon="computer-desktop"
                        class="{{ $viewport === 'desktop' ? 'bg-white dark:bg-zinc-700 shadow-xs' : 'text-zinc-400' }}"
                        title="Desktop (100%)"
                        data-test="viewport-desktop"
                    />
                    <flux:button
                        wire:click="selectViewport('tablet')"
                        variant="subtle"
                        size="xs"
                        icon="device-tablet"
                        class="{{ $viewport === 'tablet' ? 'bg-white dark:bg-zinc-700 shadow-xs' : 'text-zinc-400' }}"
                        title="Tablet (768px)"
                        data-test="viewport-tablet"
                    />
                    <flux:button
                        wire:click="selectViewport('mobile')"
                        variant="subtle"
                        size="xs"
                        icon="device-phone-mobile"
                        class="{{ $viewport === 'mobile' ? 'bg-white dark:bg-zinc-700 shadow-xs' : 'text-zinc-400' }}"
                        title="Mobile (390px)"
                        data-test="viewport-mobile"
                    />
                </div>

                {{-- Dark Mode Preview Toggle --}}
                <flux:button
                    wire:click="togglePreviewDark"
                    variant="subtle"
                    size="sm"
                    :icon="$previewDark ? 'sun' : 'moon'"
                    class="border border-zinc-200 dark:border-zinc-700"
                    title="Toggle Preview Light/Dark"
                    data-test="toggle-dark-mode"
                />

                {{-- Randomize Button --}}
                <flux:button
                    wire:click="randomize"
                    variant="subtle"
                    size="sm"
                    icon="sparkles"
                    class="border border-zinc-200 dark:border-zinc-700"
                    title="Generate Random Combination"
                    data-test="randomize-button"
                >
                    <span class="hidden md:inline">{{ __('Shuffle') }}</span>
                </flux:button>

                {{-- View / Copy Blade Code --}}
                <flux:button
                    wire:click="toggleCodeModal"
                    variant="primary"
                    size="sm"
                    icon="code-bracket"
                    data-test="view-blade-code"
                >
                    {{ __('View Blade Code') }}
                </flux:button>
            </div>
        </div>
    </div>

    {{-- Canvas Preview Viewport Container --}}
    <div class="flex justify-center w-full transition-all duration-300">
        <div class="w-full transition-all duration-300 @container {{ $viewport === 'mobile' ? 'max-w-[400px]' : ($viewport === 'tablet' ? 'max-w-[768px]' : 'w-full') }}">
            {{-- Canvas Frame --}}
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden shadow-lg transition-colors {{ $previewDark ? 'dark bg-zinc-950 text-zinc-100' : 'bg-zinc-50/70 text-zinc-900' }}">

                {{-- Simulated Window Chrome Bar --}}
                <div class="flex items-center justify-between px-4 py-2.5 border-b border-zinc-200/80 dark:border-zinc-800 bg-white/70 dark:bg-zinc-900/80 text-xs text-zinc-400">
                    <div class="flex items-center gap-2">
                        <div class="flex gap-1.5">
                            <div class="size-2.5 rounded-full bg-red-400"></div>
                            <div class="size-2.5 rounded-full bg-amber-400"></div>
                            <div class="size-2.5 rounded-full bg-emerald-400"></div>
                        </div>
                        <span class="ml-2 font-mono text-[11px] text-zinc-400 truncate max-w-[150px] sm:max-w-none">localhost:8000/app/preview</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-[11px] font-mono capitalize">{{ $shellStyle }} shell &bull; {{ $viewport }}</span>
                    </div>
                </div>

                {{-- Simulated Shell Body --}}
                <div class="flex min-h-[600px] w-full flex-col">
                    {{-- Simulated Header Shell Chrome --}}
                    @if ($shellStyle === 'header')
                        <div class="border-b border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 px-4 sm:px-6 py-3 flex items-center justify-between" data-test="simulated-header">
                            <div class="flex items-center gap-4 sm:gap-6">
                                @if ($viewport === 'mobile')
                                    <flux:button variant="subtle" size="xs" icon="bars-2" />
                                @endif
                                <div class="flex items-center gap-2">
                                    <div class="size-6 rounded-md bg-accent-content flex items-center justify-center text-white text-xs font-bold">
                                        DL
                                    </div>
                                    <span class="text-xs font-semibold text-zinc-900 dark:text-white">Workspace App</span>
                                </div>
                                @if ($viewport !== 'mobile')
                                    <div class="hidden sm:flex items-center gap-4 text-xs font-medium text-zinc-500">
                                        <span class="text-zinc-900 dark:text-white font-semibold">Overview</span>
                                        <span>Analytics</span>
                                        <span>Reports</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center gap-3">
                                <flux:avatar name="Alex Morgan" initials="AM" size="xs" />
                            </div>
                        </div>
                    @endif

                    {{-- Layout Content Body --}}
                    <div class="{{ $viewport === 'mobile' ? 'p-3.5 sm:p-4' : ($viewport === 'tablet' ? 'p-4 sm:p-5' : 'p-6') }} flex-1 min-w-0">
                        @include('views-generator.layouts.' . $layout, [
                            'headerComponent' => $headerComponent,
                            'mainComponent' => $mainComponent,
                            'secondaryComponent' => $secondaryComponent,
                            'shellStyle' => $shellStyle,
                            'viewport' => $viewport,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Code Export Modal --}}
    @if ($showCodeModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 animate-in fade-in duration-150">
            <div class="w-full max-w-2xl rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
                <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-200 dark:border-zinc-800">
                    <div>
                        <flux:heading size="lg">{{ __('Generated View Blade Template') }}</flux:heading>
                        <flux:text size="xs" class="text-zinc-500 mt-0.5">
                            {{ __('Clean, presentational Flux & Tailwind markup ready for production templates.') }}
                        </flux:text>
                    </div>
                    <flux:button wire:click="toggleCodeModal" variant="subtle" size="sm" icon="x-mark" />
                </div>

                <div class="p-6 flex-1 overflow-y-auto font-mono text-xs">
                    <pre class="p-4 rounded-xl bg-zinc-950 text-zinc-100 overflow-x-auto leading-relaxed border border-zinc-800"><code>{{ $this->generatedBladeCode }}</code></pre>
                </div>

                <div class="flex items-center justify-between px-6 py-4 border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50">
                    <flux:text size="xs" class="text-zinc-500">
                        {{ $this->activeLayoutName }} &bull; {{ $headerComponent }} + {{ $mainComponent }}
                    </flux:text>

                    <div class="flex items-center gap-2">
                        <flux:button wire:click="toggleCodeModal" variant="subtle" size="sm">
                            {{ __('Close') }}
                        </flux:button>
                        <flux:button
                            variant="primary"
                            size="sm"
                            icon="clipboard-document"
                            x-data="{ copied: false }"
                            x-on:click="navigator.clipboard.writeText($el.closest('.max-w-2xl').querySelector('code').innerText); copied = true; setTimeout(() => copied = false, 2000)"
                        >
                            <span x-show="!copied">{{ __('Copy Blade Code') }}</span>
                            <span x-show="copied" class="text-emerald-300">{{ __('Copied!') }}</span>
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
