<?php

use App\Models\Theme;
use App\Support\Theme\Color;
use App\Support\Theme\Presets;
use App\Support\Theme\ThemeCodec;
use App\Support\Theme\ThemeCompiler;
use App\Support\Theme\ThemeTokens;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

new #[Title('Theme Generator')] class extends Component {
    #[Url(as: 'theme')]
    public string $themeCode = '';

    /** @var array<string, mixed> */
    public array $tokens = [];

    public string $name = 'Custom Theme';

    public ?int $selectedThemeId = null;

    public bool $previewDark = false;

    public string $exportFormat = 'css';

    public string $importPayload = '';

    public function mount(): void
    {
        // 1. Share URL code (?theme=...) takes priority
        if ($this->themeCode !== '') {
            $decoded = ThemeCodec::decode($this->themeCode);
            if ($decoded !== null) {
                $this->tokens = $decoded->toArray();
                $this->name = 'Shared Theme';
                $this->syncThemeCode();

                return;
            }
        }

        // 2. Active theme in DB
        $active = Theme::query()->active()->first();
        if ($active !== null) {
            $this->selectedThemeId = $active->id;
            $this->name = $active->name;
            $this->tokens = $active->tokens->toArray();
            $this->syncThemeCode();

            return;
        }

        // 3. Fallback defaults
        $this->tokens = ThemeTokens::defaults();
        $this->syncThemeCode();
    }

    public function updatedTokens(): void
    {
        $this->syncThemeCode();
    }

    public function syncThemeCode(): void
    {
        $tokens = ThemeTokens::tryFrom($this->tokens);
        if ($tokens !== null) {
            $this->themeCode = ThemeCodec::encode($tokens);
        }
    }

    #[Computed]
    public function previewTokens(): ThemeTokens
    {
        return ThemeTokens::tryFrom($this->tokens) ?? ThemeTokens::fromArray(ThemeTokens::defaults());
    }

    #[Computed]
    public function previewCss(): string
    {
        return app(ThemeCompiler::class)->runtime($this->previewTokens, '[data-theme-preview]');
    }

    /**
     * @return array<string, array<string, string>>
     */
    #[Computed]
    public function ramps(): array
    {
        return app(ThemeCompiler::class)->ramps($this->previewTokens);
    }

    #[Computed]
    public function exportCss(): string
    {
        return app(ThemeCompiler::class)->export($this->previewTokens, $this->name);
    }

    #[Computed]
    public function exportJson(): string
    {
        return json_encode($this->tokens, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @return Collection<int, Theme>
     */
    #[Computed]
    public function savedThemes(): Collection
    {
        return Theme::query()->orderBy('name')->get();
    }

    public function loadPreset(string $key): void
    {
        $preset = Presets::all()[$key] ?? null;
        if (! $preset) {
            return;
        }

        $this->tokens = $preset['tokens'];
        $this->name = $preset['name'];

        $existing = Theme::query()->where('slug', $key)->first();
        $this->selectedThemeId = $existing?->id;

        $this->syncThemeCode();
        Flux::toast(text: "Loaded {$preset['name']} preset.", variant: 'success');
    }

    public function loadTheme(int $id): void
    {
        $theme = Theme::find($id);
        if (! $theme) {
            return;
        }

        $this->selectedThemeId = $theme->id;
        $this->name = $theme->name;
        $this->tokens = $theme->tokens->toArray();
        $this->syncThemeCode();

        Flux::toast(text: "Loaded theme [{$theme->name}].", variant: 'success');
    }

    public function randomize(): void
    {
        $this->tokens = Presets::random();
        $this->selectedThemeId = null;
        $this->name = 'Random '.ucfirst((string) $this->tokens['font']);
        $this->syncThemeCode();

        Flux::toast(text: 'Generated a random theme palette.', variant: 'success');
    }

    public function resetToDefaults(): void
    {
        $this->tokens = ThemeTokens::defaults();
        $this->name = 'Flux default';
        $this->selectedThemeId = Theme::query()->where('slug', 'flux-default')->value('id');
        $this->syncThemeCode();

        Flux::toast(text: 'Reset to default theme values.', variant: 'success');
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:50',
        ]);

        $tokens = ThemeTokens::fromArray($this->tokens);

        if ($this->selectedThemeId !== null) {
            $theme = Theme::find($this->selectedThemeId);
            if ($theme) {
                $theme->update([
                    'name' => $this->name,
                    'tokens' => $tokens,
                ]);

                Flux::toast(text: "Theme [{$theme->name}] updated.", variant: 'success');

                return;
            }
        }

        $this->saveAs();
    }

    public function saveAs(): void
    {
        $this->validate([
            'name' => 'required|string|max:50',
        ]);

        $tokens = ThemeTokens::fromArray($this->tokens);
        $slug = Theme::uniqueSlug($this->name);

        $theme = Theme::create([
            'name' => $this->name,
            'slug' => $slug,
            'tokens' => $tokens,
            'user_id' => Auth::id(),
        ]);

        $this->selectedThemeId = $theme->id;

        Flux::toast(text: "Theme [{$theme->name}] saved as new theme.", variant: 'success');
    }

    public function activate(): void
    {
        $this->validate([
            'name' => 'required|string|max:50',
        ]);

        $tokens = ThemeTokens::fromArray($this->tokens);

        if ($this->selectedThemeId !== null) {
            $theme = Theme::find($this->selectedThemeId);
            if ($theme) {
                $theme->update(['name' => $this->name, 'tokens' => $tokens]);
                $theme->activate();
                $this->redirect(route('theme.generator', ['theme' => $this->themeCode]), navigate: false);

                return;
            }
        }

        $slug = Theme::uniqueSlug($this->name);
        $theme = Theme::create([
            'name' => $this->name,
            'slug' => $slug,
            'tokens' => $tokens,
            'user_id' => Auth::id(),
        ]);

        $this->selectedThemeId = $theme->id;
        $theme->activate();

        $this->redirect(route('theme.generator', ['theme' => $this->themeCode]), navigate: false);
    }

    public function deleteTheme(int $id): void
    {
        $theme = Theme::find($id);
        if (! $theme) {
            return;
        }

        if ($theme->is_active) {
            Flux::toast(text: 'The active theme cannot be deleted.', variant: 'danger');

            return;
        }

        $name = $theme->name;
        $theme->delete();

        if ($this->selectedThemeId === $id) {
            $this->selectedThemeId = null;
        }

        Flux::toast(text: "Theme [{$name}] deleted.", variant: 'success');
    }

    public function importTheme(): void
    {
        $payload = trim($this->importPayload);
        if ($payload === '') {
            return;
        }

        // Try share code
        $fromCode = ThemeCodec::decode($payload);
        if ($fromCode !== null) {
            $this->tokens = $fromCode->toArray();
            $this->selectedThemeId = null;
            $this->name = 'Imported Theme';
            $this->importPayload = '';
            $this->syncThemeCode();
            $this->modal('import-modal')->close();
            Flux::toast(text: 'Theme imported successfully from share code.', variant: 'success');

            return;
        }

        // Try JSON
        $json = json_decode($payload, true);
        if (is_array($json)) {
            $fromArray = ThemeTokens::tryFrom($json);
            if ($fromArray !== null) {
                $this->tokens = $fromArray->toArray();
                $this->selectedThemeId = null;
                $this->name = 'Imported Theme';
                $this->importPayload = '';
                $this->syncThemeCode();
                $this->modal('import-modal')->close();
                Flux::toast(text: 'Theme imported successfully from JSON.', variant: 'success');

                return;
            }
        }

        Flux::toast(text: 'Invalid theme code or JSON.', variant: 'danger');
    }

    public function clearStatus(string $status): void
    {
        $this->tokens['status'][$status] = null;
        $this->syncThemeCode();
    }
}; ?>

<div class="flex w-full flex-1 flex-col gap-6">
    {{-- Head Font link for live preview font rendering if external font selected --}}
    @if ($this->previewTokens->fontUrl())
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="stylesheet" href="{{ $this->previewTokens->fontUrl() }}">
    @endif

    {{-- Header & Primary Action Bar --}}
    <div class="flex flex-col gap-4 rounded-xl border border-zinc-200 bg-white p-5 shadow-xs sm:flex-row sm:items-center sm:justify-between dark:border-zinc-800 dark:bg-zinc-900">
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-2">
                <flux:heading size="xl">Theme Generator</flux:heading>
                @if ($selectedThemeId && ($activeTheme = $this->savedThemes->firstWhere('id', $selectedThemeId)) && $activeTheme->is_active)
                    <flux:badge color="green" size="sm" icon="check">Active Project Theme</flux:badge>
                @endif
            </div>
            <flux:subheading>
                Customize colors, neutral tones, corner radiuses, and fonts. Changes can be applied globally to all pages or exported.
            </flux:subheading>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <flux:button wire:click="activate" variant="primary" icon="bolt" class="cursor-pointer">
                Activate Globally
            </flux:button>

            <flux:dropdown>
                <flux:button variant="filled" icon-trailing="chevron-down">Save...</flux:button>
                <flux:menu>
                    <flux:menu.item wire:click="save" icon="arrow-down-tray">Save Theme</flux:menu.item>
                    <flux:menu.item wire:click="saveAs" icon="document-plus">Save As New Theme</flux:menu.item>
                </flux:menu>
            </flux:dropdown>

            <flux:button wire:click="randomize" variant="subtle" icon="arrow-path" title="Generate coherent random theme">
                Random
            </flux:button>

            <flux:button
                variant="subtle"
                icon="share"
                x-data
                x-on:click="navigator.clipboard.writeText(window.location.origin + window.location.pathname + '?theme={{ $themeCode }}'); $flux.toast({ text: 'Shareable theme link copied to clipboard!', variant: 'success' })"
                title="Copy share link"
            >
                Share
            </flux:button>

            <flux:modal.trigger name="export-modal">
                <flux:button variant="subtle" icon="arrow-up-tray" title="Export CSS / JSON">Export</flux:button>
            </flux:modal.trigger>

            <flux:modal.trigger name="import-modal">
                <flux:button variant="subtle" icon="arrow-down-tray" title="Import Theme">Import</flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    {{-- Main 3-Column Layout --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        {{-- Left Column: Themes & Presets (3 cols) --}}
        <div class="flex flex-col gap-6 lg:col-span-3">
            {{-- Theme Name Input --}}
            <flux:card class="flex flex-col gap-3">
                <flux:field>
                    <flux:label>Theme Name</flux:label>
                    <flux:input wire:model.defer="name" placeholder="e.g. Ocean Blue" />
                </flux:field>
            </flux:card>

            {{-- Built-in Presets --}}
            <flux:card class="flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <flux:heading size="sm">Presets</flux:heading>
                    <flux:button wire:click="resetToDefaults" variant="ghost" size="xs">Reset</flux:button>
                </div>
                <div class="flex flex-col gap-1.5">
                    @foreach (\App\Support\Theme\Presets::all() as $slug => $preset)
                        <button
                            type="button"
                            wire:click="loadPreset('{{ $slug }}')"
                            class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-start text-sm transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-800"
                        >
                            <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $preset['name'] }}</span>
                            <div class="flex items-center gap-1.5">
                                <span class="size-4 rounded-full border border-black/10" style="background-color: {{ $preset['tokens']['accent']['light'] }}"></span>
                                <span class="size-4 rounded-full border border-black/10" style="background-color: {{ $preset['tokens']['accent']['dark'] }}"></span>
                            </div>
                        </button>
                    @endforeach
                </div>
            </flux:card>

            {{-- Saved Themes in Database --}}
            <flux:card class="flex flex-col gap-3">
                <flux:heading size="sm">Saved Themes</flux:heading>
                <div class="flex max-h-72 flex-col gap-1 overflow-y-auto">
                    @forelse ($this->savedThemes as $savedTheme)
                        <div class="flex items-center justify-between rounded-lg px-2.5 py-1.5 transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-800 {{ $selectedThemeId === $savedTheme->id ? 'bg-zinc-100 dark:bg-zinc-800 ring-1 ring-zinc-300 dark:ring-zinc-600' : '' }}">
                            <button
                                type="button"
                                wire:click="loadTheme({{ $savedTheme->id }})"
                                class="flex flex-1 items-center gap-2 text-start text-sm text-zinc-800 dark:text-zinc-200"
                            >
                                <span class="size-3.5 shrink-0 rounded-full" style="background-color: {{ $savedTheme->tokens->accentLight }}"></span>
                                <span class="truncate">{{ $savedTheme->name }}</span>
                                @if ($savedTheme->is_active)
                                    <flux:badge color="green" size="sm">active</flux:badge>
                                @endif
                            </button>

                            @if (! $savedTheme->is_active)
                                <flux:button
                                    wire:click="deleteTheme({{ $savedTheme->id }})"
                                    wire:confirm="Delete theme '{{ $savedTheme->name }}'?"
                                    variant="ghost"
                                    size="xs"
                                    icon="trash"
                                    class="text-zinc-400 hover:text-red-600"
                                />
                            @endif
                        </div>
                    @empty
                        <flux:text size="sm" class="text-zinc-500">No saved themes yet.</flux:text>
                    @endforelse
                </div>
            </flux:card>
        </div>

        {{-- Middle Column: Token Controls (4 cols) --}}
        <div class="flex flex-col gap-6 lg:col-span-4">
            {{-- Accent Colors --}}
            <flux:card class="flex flex-col gap-4">
                <div>
                    <flux:heading size="base">1. Accent Color</flux:heading>
                    <flux:subheading>The primary brand accent for buttons, badges, links & focus rings.</flux:subheading>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <flux:field>
                        <flux:label>Light Mode Accent</flux:label>
                        <div class="flex items-center gap-2">
                            <input
                                type="color"
                                wire:model.live.debounce.100ms="tokens.accent.light"
                                class="size-10 cursor-pointer rounded-lg border border-zinc-300 dark:border-zinc-700 bg-transparent p-0.5"
                            />
                            <flux:input wire:model.live.debounce.150ms="tokens.accent.light" class="font-mono text-xs uppercase" />
                        </div>
                    </flux:field>

                    <flux:field>
                        <flux:label>Dark Mode Accent</flux:label>
                        <div class="flex items-center gap-2">
                            <input
                                type="color"
                                wire:model.live.debounce.100ms="tokens.accent.dark"
                                class="size-10 cursor-pointer rounded-lg border border-zinc-300 dark:border-zinc-700 bg-transparent p-0.5"
                            />
                            <flux:input wire:model.live.debounce.150ms="tokens.accent.dark" class="font-mono text-xs uppercase" />
                        </div>
                    </flux:field>
                </div>
            </flux:card>

            {{-- Base Neutral Ramp --}}
            <flux:card class="flex flex-col gap-4">
                <div>
                    <flux:heading size="base">2. Base Neutrals (Zinc)</flux:heading>
                    <flux:subheading>Tint the entire 50–950 grey scale used by backgrounds, borders & text.</flux:subheading>
                </div>

                <div class="flex flex-col gap-3">
                    <flux:field>
                        <div class="flex items-center justify-between">
                            <flux:label>Base Hue (Tint)</flux:label>
                            <span class="font-mono text-xs text-zinc-500">{{ round($tokens['base']['hue'] ?? 0) }}°</span>
                        </div>
                        <input
                            type="range"
                            min="0"
                            max="360"
                            step="1"
                            wire:model.live.debounce.50ms="tokens.base.hue"
                            class="h-2 w-full cursor-pointer rounded-lg appearance-none bg-gradient-to-r from-red-500 via-green-500 via-blue-500 to-red-500"
                        />
                    </flux:field>

                    <flux:field>
                        <div class="flex items-center justify-between">
                            <flux:label>Tint Saturation (Chroma)</flux:label>
                            <span class="font-mono text-xs text-zinc-500">{{ number_format($tokens['base']['chroma'] ?? 0, 3) }}</span>
                        </div>
                        <input
                            type="range"
                            min="0"
                            max="0.06"
                            step="0.002"
                            wire:model.live.debounce.50ms="tokens.base.chroma"
                            class="h-2 w-full cursor-pointer rounded-lg appearance-none bg-zinc-200 dark:bg-zinc-700 accent-zinc-800 dark:accent-zinc-200"
                        />
                        <flux:description>0 is pure neutral grey. Higher values add a subtle tint (slate, zinc, stone).</flux:description>
                    </flux:field>
                </div>
            </flux:card>

            {{-- Status Semantic Overrides --}}
            <flux:card class="flex flex-col gap-4">
                <div>
                    <flux:heading size="base">3. Status Colors (Optional)</flux:heading>
                    <flux:subheading>Override default semantic tones for alerts, callouts, and badges.</flux:subheading>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    @foreach (['info' => 'Info', 'success' => 'Success', 'warning' => 'Warning', 'danger' => 'Danger'] as $key => $label)
                        <div class="flex flex-col gap-1.5 rounded-lg border border-zinc-200 p-2.5 dark:border-zinc-800">
                            <div class="flex items-center justify-between">
                                <flux:label class="text-xs font-semibold">{{ $label }}</flux:label>
                                @if (! empty($tokens['status'][$key]))
                                    <button type="button" wire:click="clearStatus('{{ $key }}')" class="text-[10px] text-zinc-400 hover:text-red-500">reset</button>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <input
                                    type="color"
                                    wire:model.live.debounce.100ms="tokens.status.{{ $key }}"
                                    class="size-8 cursor-pointer rounded border border-zinc-300 dark:border-zinc-700 bg-transparent p-0.5"
                                />
                                <span class="font-mono text-xs text-zinc-600 dark:text-zinc-400">
                                    {{ $tokens['status'][$key] ?? 'default' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </flux:card>

            {{-- Corner Radius --}}
            <flux:card class="flex flex-col gap-4">
                <div>
                    <flux:heading size="base">4. Corner Radius</flux:heading>
                    <flux:subheading>Control the sharpness of selectors, input fields, and surface containers.</flux:subheading>
                </div>

                <div class="flex flex-col gap-3">
                    <flux:field>
                        <flux:label>Selectors (Checkboxes, small tags)</flux:label>
                        <flux:radio.group wire:model.live="tokens.radius.selector" variant="segmented">
                            <flux:radio value="0">0</flux:radio>
                            <flux:radio value="0.25">0.25</flux:radio>
                            <flux:radio value="0.5">0.5</flux:radio>
                            <flux:radio value="1">1</flux:radio>
                        </flux:radio.group>
                    </flux:field>

                    <flux:field>
                        <flux:label>Fields (Buttons, Inputs, Selects)</flux:label>
                        <flux:radio.group wire:model.live="tokens.radius.field" variant="segmented">
                            <flux:radio value="0">0</flux:radio>
                            <flux:radio value="0.25">0.25</flux:radio>
                            <flux:radio value="0.5">0.5</flux:radio>
                            <flux:radio value="1">1</flux:radio>
                        </flux:radio.group>
                    </flux:field>

                    <flux:field>
                        <flux:label>Boxes (Cards, Modals, Callouts)</flux:label>
                        <flux:radio.group wire:model.live="tokens.radius.box" variant="segmented">
                            <flux:radio value="0">0</flux:radio>
                            <flux:radio value="0.5">0.5</flux:radio>
                            <flux:radio value="0.75">0.75</flux:radio>
                            <flux:radio value="1">1</flux:radio>
                            <flux:radio value="2">2</flux:radio>
                        </flux:radio.group>
                    </flux:field>
                </div>
            </flux:card>

            {{-- Spacing & Typography --}}
            <flux:card class="flex flex-col gap-4">
                <div>
                    <flux:heading size="base">5. Density & Typography</flux:heading>
                    <flux:subheading>Adjust spacing density and font family.</flux:subheading>
                </div>

                <div class="flex flex-col gap-3">
                    <flux:field>
                        <flux:label>Spacing Density</flux:label>
                        <flux:radio.group wire:model.live="tokens.spacing" variant="segmented">
                            <flux:radio value="0.2">Compact</flux:radio>
                            <flux:radio value="0.25">Default</flux:radio>
                            <flux:radio value="0.3">Comfortable</flux:radio>
                        </flux:radio.group>
                    </flux:field>

                    <flux:field>
                        <flux:label>Font Family</flux:label>
                        <flux:select wire:model.live="tokens.font">
                            @foreach (\App\Support\Theme\ThemeTokens::FONTS as $key => $fontDef)
                                <flux:select.option value="{{ $key }}">{{ $fontDef['family'] }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:field>
                </div>
            </flux:card>
        </div>

        {{-- Right Column: Scoped Live Preview (5 cols) --}}
        <div class="flex flex-col gap-4 lg:col-span-5">
            <div class="flex items-center justify-between">
                <flux:heading size="lg">Live Component Preview</flux:heading>
                <div class="flex items-center gap-2">
                    <flux:button
                        wire:click="$toggle('previewDark')"
                        size="sm"
                        variant="subtle"
                        icon="{{ $previewDark ? 'sun' : 'moon' }}"
                    >
                        {{ $previewDark ? 'Dark Preview' : 'Light Preview' }}
                    </flux:button>
                </div>
            </div>

            {{-- Scoped dynamic theme style block --}}
            <style>
                {!! $this->previewCss !!}
            </style>

            {{-- Preview Wrapper --}}
            <div
                data-theme-preview
                class="flex flex-col gap-6 rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition-colors duration-150 {{ $previewDark ? 'dark bg-zinc-950 text-white' : 'text-zinc-900' }}"
            >
                @include('lab.examples.theme.preview', ['ramps' => $this->ramps])
            </div>
        </div>
    </div>

    {{-- Export Modal --}}
    <flux:modal name="export-modal" class="max-w-2xl">
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between border-b border-zinc-200 pb-3 dark:border-zinc-800">
                <div>
                    <flux:heading size="lg">Export Theme</flux:heading>
                    <flux:subheading>Export for Tailwind CSS v4 or raw JSON tokens.</flux:subheading>
                </div>
                <flux:radio.group wire:model.live="exportFormat" variant="segmented">
                    <flux:radio value="css">Tailwind CSS</flux:radio>
                    <flux:radio value="json">JSON Tokens</flux:radio>
                </flux:radio.group>
            </div>

            <div class="relative">
                <pre class="max-h-96 overflow-x-auto rounded-lg bg-zinc-900 p-4 font-mono text-xs text-zinc-100"><code>{{ $exportFormat === 'css' ? $this->exportCss : $this->exportJson }}</code></pre>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <flux:button
                    variant="primary"
                    icon="document-duplicate"
                    x-data
                    x-on:click="navigator.clipboard.writeText($el.closest('[data-flux-modal]').querySelector('pre code').innerText); $flux.toast({ text: 'Copied to clipboard!', variant: 'success' })"
                >
                    Copy to Clipboard
                </flux:button>
                <flux:modal.close>
                    <flux:button variant="ghost">Close</flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>

    {{-- Import Modal --}}
    <flux:modal name="import-modal" class="max-w-md">
        <div class="flex flex-col gap-4">
            <div>
                <flux:heading size="lg">Import Theme</flux:heading>
                <flux:subheading>Paste a share code (?theme=...) or JSON theme definition.</flux:subheading>
            </div>

            <flux:field>
                <flux:textarea wire:model.defer="importPayload" rows="6" placeholder="Paste code or JSON here..." />
            </flux:field>

            <div class="flex items-center justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="importTheme" variant="primary">Import</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
