@props([
    'ramps' => [],
])

<div class="flex flex-col gap-6">
    {{-- Swatches overview --}}
    @if (! empty($ramps))
        <div class="flex flex-col gap-3">
            <flux:heading size="sm">Generated ramps</flux:heading>

            @if (isset($ramps['base']))
                <div class="flex flex-col gap-1">
                    <flux:text size="xs" class="font-mono text-zinc-500">zinc (base)</flux:text>
                    <div class="grid grid-cols-11 gap-1">
                        @foreach ($ramps['base'] as $step => $hex)
                            <div class="group relative flex flex-col items-center">
                                <div class="h-6 w-full rounded border border-black/10 dark:border-white/10" style="background-color: {{ $hex }}"></div>
                                <span class="mt-0.5 text-[9px] font-mono text-zinc-400 opacity-80">{{ $step }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (isset($ramps['accent']))
                <div class="flex flex-col gap-1">
                    <flux:text size="xs" class="font-mono text-zinc-500">accent</flux:text>
                    <div class="grid grid-cols-11 gap-1">
                        @foreach ($ramps['accent'] as $step => $hex)
                            <div class="group relative flex flex-col items-center">
                                <div class="h-6 w-full rounded border border-black/10 dark:border-white/10" style="background-color: {{ $hex }}"></div>
                                <span class="mt-0.5 text-[9px] font-mono text-zinc-400 opacity-80">{{ $step }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- Interactive buttons --}}
    <div class="flex flex-col gap-2">
        <flux:heading size="sm">Buttons</flux:heading>
        <div class="flex flex-wrap items-center gap-2">
            <flux:button variant="primary" icon="sparkles">Primary (Accent)</flux:button>
            <flux:button variant="filled">Filled</flux:button>
            <flux:button variant="outline">Outline</flux:button>
            <flux:button variant="subtle">Subtle</flux:button>
            <flux:button variant="danger" icon="trash">Danger</flux:button>
            <flux:button variant="ghost">Ghost</flux:button>
        </div>
    </div>

    {{-- Form controls --}}
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="flex flex-col gap-3">
            <flux:field>
                <flux:label>Sample Input</flux:label>
                <flux:input placeholder="Type something..." icon="envelope" value="alex@example.com" />
                <flux:description>Notice the focus ring and corner radius.</flux:description>
            </flux:field>

            <flux:field>
                <flux:label>Select dropdown</flux:label>
                <flux:select placeholder="Choose an option">
                    <flux:select.option selected>Production environment</flux:select.option>
                    <flux:select.option>Staging environment</flux:select.option>
                    <flux:select.option>Local environment</flux:select.option>
                </flux:select>
            </flux:field>
        </div>

        <div class="flex flex-col justify-between gap-4 rounded-xl border border-zinc-200 bg-zinc-50/50 p-4 dark:border-zinc-700/60 dark:bg-zinc-900/40">
            <div class="flex flex-col gap-3">
                <flux:heading size="sm">Selectors & Toggles</flux:heading>
                <flux:checkbox label="Enable real-time synchronization" checked />
                <flux:checkbox label="Send push notifications" />

                <div class="pt-1">
                    <flux:switch label="Dark mode preview helper" checked />
                </div>
            </div>

            <flux:radio.group variant="segmented" value="month">
                <flux:radio value="day">Day</flux:radio>
                <flux:radio value="week">Week</flux:radio>
                <flux:radio value="month">Month</flux:radio>
                <flux:radio value="year">Year</flux:radio>
            </flux:radio.group>
        </div>
    </div>

    {{-- Badges --}}
    <div class="flex flex-col gap-2">
        <flux:heading size="sm">Badges & Tags</flux:heading>
        <div class="flex flex-wrap items-center gap-2">
            <flux:badge color="zinc">Zinc</flux:badge>
            <flux:badge color="blue">Info</flux:badge>
            <flux:badge color="green">Success</flux:badge>
            <flux:badge color="amber">Warning</flux:badge>
            <flux:badge color="red">Danger</flux:badge>
            <flux:badge color="purple">Purple</flux:badge>
            <flux:badge size="sm" color="zinc" inset="top bottom">Small</flux:badge>
        </div>
    </div>

    {{-- Callouts --}}
    <div class="grid gap-3 sm:grid-cols-2">
        <flux:callout variant="info" icon="information-circle">
            <flux:callout.heading>Information</flux:callout.heading>
            <flux:callout.text>A new version of the design system is ready.</flux:callout.text>
        </flux:callout>

        <flux:callout variant="success" icon="check-circle">
            <flux:callout.heading>Published</flux:callout.heading>
            <flux:callout.text>Theme changes saved and distributed globally.</flux:callout.text>
        </flux:callout>

        <flux:callout variant="warning" icon="exclamation-triangle">
            <flux:callout.heading>Attention</flux:callout.heading>
            <flux:callout.text>Base contrast is close to minimum accessibility threshold.</flux:callout.text>
        </flux:callout>

        <flux:callout variant="danger" icon="x-circle">
            <flux:callout.heading>Error</flux:callout.heading>
            <flux:callout.text>Could not connect to external asset CDN.</flux:callout.text>
        </flux:callout>
    </div>

    {{-- Card preview --}}
    <flux:card class="flex flex-col gap-4">
        <div class="flex items-center justify-between border-b border-zinc-200 pb-3 dark:border-zinc-700">
            <div>
                <flux:heading size="base">Recent Activity</flux:heading>
                <flux:subheading>Metrics showing card border, spacing, and font rendering.</flux:subheading>
            </div>
            <flux:badge color="green" size="sm">Active</flux:badge>
        </div>

        <div class="grid grid-cols-3 gap-3">
            <div class="rounded-lg bg-zinc-100 p-3 dark:bg-zinc-800">
                <flux:text size="xs" class="text-zinc-500">Components</flux:text>
                <flux:heading size="xl">38</flux:heading>
            </div>
            <div class="rounded-lg bg-zinc-100 p-3 dark:bg-zinc-800">
                <flux:text size="xs" class="text-zinc-500">Contrast Ratio</flux:text>
                <flux:heading size="xl">14.2</flux:heading>
            </div>
            <div class="rounded-lg bg-zinc-100 p-3 dark:bg-zinc-800">
                <flux:text size="xs" class="text-zinc-500">Tokens</flux:text>
                <flux:heading size="xl">72</flux:heading>
            </div>
        </div>
    </flux:card>
</div>
