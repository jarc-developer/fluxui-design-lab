<?php

use App\Models\Theme;
use App\Support\Theme\ActiveTheme;
use App\Support\Theme\Color;
use App\Support\Theme\DaisyThemeMapper;
use App\Support\Theme\DarkScheme;
use App\Support\Theme\Palette;
use App\Support\Theme\Presets;
use App\Support\Theme\ThemeCodec;
use App\Support\Theme\ThemeCompiler;
use App\Support\Theme\ThemeScheme;
use App\Support\Theme\ThemeTokens;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
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

    public string $editorScheme = 'light';

    public string $previewTab = 'dashboard';

    public string $dashboardSubTab = 'overview';

    public string $exportFormat = 'css';

    public string $importPayload = '';

    /** daisyUI fields dropped by the last import, shown as a report. */
    public array $importReport = [];

    /** Import option: seed the dark scheme from daisyUI's neutral colours. */
    public bool $importNeutralDark = false;

    public float $fillHue = 0;

    public float $fillChroma = 0;

    public function mount(): void
    {
        // 1. Share URL code (?theme=...) takes priority and applies platform-wide
        if ($this->themeCode !== '') {
            $decoded = ThemeCodec::decode($this->themeCode);
            if ($decoded !== null) {
                $this->selectedThemeId = null;
                $this->tokens = $decoded->toArray();
                $this->name = 'Shared Theme';
                $this->applyPlatformTheme();

                return;
            }
        }

        // 2. Working live theme across the platform
        $working = app(ActiveTheme::class)->working();
        if ($working !== null) {
            $this->selectedThemeId = $working['selectedThemeId'];
            $this->name = $working['name'];
            $this->tokens = $working['tokens']->toArray();
            $this->syncThemeCode();

            return;
        }

        // 3. Active theme in DB
        $active = Theme::query()->active()->first();
        if ($active !== null) {
            $this->selectedThemeId = $active->id;
            $this->name = $active->name;
            $this->tokens = $active->tokens->toArray();
            $this->syncThemeCode();

            return;
        }

        // 4. Fallback defaults
        $this->tokens = ThemeTokens::defaults();
        $this->syncThemeCode();
    }

    public function updatedTokens(mixed $value, ?string $key = null): void
    {
        // Editing a dark slot under the auto policy promotes the whole dark
        // scheme to manual, materializing the synthesized values around the edit.
        if ($key !== null && str_starts_with($key, 'scheme.dark.') && ($this->tokens['darkMode'] ?? 'auto') === 'auto') {
            $this->materializeDark();
        }

        $this->applyPlatformTheme();
    }

    public function updatedName(): void
    {
        if (app(ActiveTheme::class)->hasWorking()) {
            $this->applyPlatformTheme();
        }
    }

    public function syncThemeCode(): void
    {
        $tokens = ThemeTokens::tryFrom($this->tokens);
        if ($tokens !== null) {
            $this->themeCode = ThemeCodec::encode($tokens);
        }
    }

    public function applyPlatformTheme(): void
    {
        $tokens = ThemeTokens::tryFrom($this->tokens);
        if ($tokens !== null) {
            $this->themeCode = ThemeCodec::encode($tokens);
            app(ActiveTheme::class)->setWorking($tokens, $this->name, $this->selectedThemeId);
        }
    }

    public function setScheme(string $scheme): void
    {
        $normalised = $scheme === 'dark' ? 'dark' : 'light';
        $this->editorScheme = $normalised;
        $this->previewDark = $normalised === 'dark';
    }

    public function toggleScheme(): void
    {
        $this->setScheme($this->editorScheme === 'dark' ? 'light' : 'dark');
    }

    public function updatedEditorScheme(string $value): void
    {
        $this->setScheme($value);
    }

    public function updatedPreviewDark(bool $value): void
    {
        $this->setScheme($value ? 'dark' : 'light');
    }

    #[Computed]
    public function previewTokens(): ThemeTokens
    {
        return ThemeTokens::tryFrom($this->tokens) ?? ThemeTokens::fromArray(ThemeTokens::defaults());
    }

    #[Computed]
    public function previewCss(): string
    {
        $compiler = app(ThemeCompiler::class);

        return $compiler->runtime($this->previewTokens)
            .$compiler->runtime($this->previewTokens, '[data-theme-preview]');
    }

    /**
     * Every compiled variable in both scopes, for the Advanced audit section.
     *
     * @return array{light: array<string, string>, dark: array<string, string>}
     */
    #[Computed]
    public function previewVariables(): array
    {
        return app(ThemeCompiler::class)->variables($this->previewTokens);
    }

    /**
     * Per-scheme suggestion values: what a slot falls back to on reset. Light
     * suggestions are derived from the current anchor colours; dark suggestions
     * are the synthesized dark scheme.
     *
     * @return array{light: array<string, mixed>, dark: array<string, mixed>}
     */
    #[Computed]
    public function suggestions(): array
    {
        $tokens = $this->previewTokens;

        return [
            'light' => $this->suggestLight($tokens->light)->toArray(),
            'dark' => $tokens->darkScheme()->toArray(),
        ];
    }

    /**
     * @return array<string, array<int|string, string>>
     */
    #[Computed]
    public function ramps(): array
    {
        return app(ThemeCompiler::class)->ramps($this->previewTokens, $this->editorScheme);
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

    public function setDarkMode(string $mode): void
    {
        if ($mode === 'manual') {
            $this->materializeDark();
        } else {
            $this->tokens['darkMode'] = 'auto';
            $this->tokens['scheme']['dark'] = null;
        }

        $this->applyPlatformTheme();
    }

    /**
     * Set one dark-scheme slot from the (unbound) auto-mode inputs: promotes the
     * dark scheme to manual first so the edit sticks.
     */
    public function setDarkSlot(string $path, ?string $value): void
    {
        if (($this->tokens['darkMode'] ?? 'auto') === 'auto') {
            $this->materializeDark();
        }

        data_set($this->tokens, "scheme.dark.{$path}", $value === '' ? null : $value);
        $this->applyPlatformTheme();
    }

    public function resetSlot(string $scheme, string $path): void
    {
        $suggestion = data_get($this->suggestions[$scheme] ?? [], $path);

        if ($scheme === 'dark') {
            $this->setDarkSlot($path, $suggestion);

            return;
        }

        data_set($this->tokens, "scheme.light.{$path}", $suggestion);
        $this->applyPlatformTheme();
    }

    /**
     * One-shot derivation: fill a scheme's neutral ramp from a hue/chroma tint.
     */
    public function fillRamp(string $scheme): void
    {
        if ($scheme === 'dark' && ($this->tokens['darkMode'] ?? 'auto') === 'auto') {
            Flux::toast(text: 'Switch the dark scheme to Manual before editing its ramp.', variant: 'warning');

            return;
        }

        $ramp = array_map(
            fn (Color $step): string => $step->toHex(),
            Palette::neutral($this->fillHue, max(0, min(0.08, $this->fillChroma))),
        );

        foreach ($ramp as $step => $hex) {
            $this->tokens['scheme'][$scheme]['zinc'][$step] = $hex;
        }

        $this->applyPlatformTheme();
        Flux::toast(text: 'Neutral ramp filled from the tint controls.', variant: 'success');
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

        $this->applyPlatformTheme();
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

        if ($theme->is_active) {
            app(ActiveTheme::class)->clearWorking();
            $this->syncThemeCode();
        } else {
            $this->applyPlatformTheme();
        }

        Flux::toast(text: "Loaded theme [{$theme->name}].", variant: 'success');
    }

    public function randomize(): void
    {
        $this->tokens = Presets::random();
        $this->selectedThemeId = null;
        $this->name = 'Random '.ucfirst((string) $this->tokens['font']);
        $this->applyPlatformTheme();

        Flux::toast(text: 'Generated a random theme palette.', variant: 'success');
    }

    public function resetToDefaults(): void
    {
        $this->tokens = ThemeTokens::defaults();
        $this->name = 'Flux default';
        $this->selectedThemeId = Theme::query()->where('slug', 'flux-default')->value('id');
        $this->applyPlatformTheme();

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

                if (! $theme->is_active) {
                    $this->applyPlatformTheme();
                }

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
        ]);

        $this->selectedThemeId = $theme->id;
        $this->applyPlatformTheme();

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
                $this->syncThemeCode();
                Flux::toast(text: "Theme [{$theme->name}] saved as active project theme.", variant: 'success');

                return;
            }
        }

        $slug = Theme::uniqueSlug($this->name);
        $theme = Theme::create([
            'name' => $this->name,
            'slug' => $slug,
            'tokens' => $tokens,
        ]);

        $this->selectedThemeId = $theme->id;
        $theme->activate();
        $this->syncThemeCode();

        Flux::toast(text: "Theme [{$theme->name}] saved as active project theme.", variant: 'success');
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

        $hadWorking = app(ActiveTheme::class)->hasWorking();
        $name = $theme->name;
        $theme->delete();

        if ($this->selectedThemeId === $id) {
            $this->selectedThemeId = null;
        }

        if ($hadWorking) {
            $this->applyPlatformTheme();
        }

        Flux::toast(text: "Theme [{$name}] deleted.", variant: 'success');
    }

    public function importTheme(): void
    {
        $payload = trim($this->importPayload);
        if ($payload === '') {
            return;
        }

        // 1. daisyUI share URL, code or theme JSON
        $daisy = DaisyThemeMapper::map($payload, $this->importNeutralDark);
        if ($daisy !== null) {
            $tokens = ThemeTokens::tryFrom($daisy['tokens']);
            if ($tokens !== null) {
                $this->tokens = $tokens->toArray();
                $this->selectedThemeId = null;
                $this->name = ucfirst($daisy['name']);
                $this->importReport = $daisy['dropped'];
                $this->importPayload = '';
                $this->applyPlatformTheme();
                $this->modal('import-modal')->close();

                $dropped = count($daisy['dropped']);
                Flux::toast(
                    text: "Imported [{$daisy['name']}].".($dropped > 0 ? " {$dropped} daisyUI-only field(s) were dropped." : ''),
                    variant: 'success',
                );

                return;
            }
        }

        // 2. Own share code (v2 or legacy v1, both upcast)
        $fromCode = ThemeCodec::decode($payload);
        if ($fromCode !== null) {
            $this->tokens = $fromCode->toArray();
            $this->selectedThemeId = null;
            $this->name = 'Imported Theme';
            $this->importReport = [];
            $this->importPayload = '';
            $this->applyPlatformTheme();
            $this->modal('import-modal')->close();
            Flux::toast(text: 'Theme imported successfully from share code.', variant: 'success');

            return;
        }

        // 3. Theme JSON
        $json = json_decode($payload, true);
        if (is_array($json)) {
            $fromArray = ThemeTokens::tryFrom($json);
            if ($fromArray !== null) {
                $this->tokens = $fromArray->toArray();
                $this->selectedThemeId = null;
                $this->name = 'Imported Theme';
                $this->importReport = [];
                $this->importPayload = '';
                $this->applyPlatformTheme();
                $this->modal('import-modal')->close();
                Flux::toast(text: 'Theme imported successfully from JSON.', variant: 'success');

                return;
            }
        }

        Flux::toast(text: 'Invalid theme code, daisyUI theme or JSON.', variant: 'danger');
    }

    public function clearStatus(string $status): void
    {
        $this->tokens['scheme'][$this->editorScheme]['status'][$status] = null;
        $this->applyPlatformTheme();
    }

    /**
     * Materialize the synthesized dark scheme into explicit values and switch
     * to the manual policy, keeping any dark edits already made.
     */
    private function materializeDark(): void
    {
        $tokens = ThemeTokens::tryFrom($this->tokens);
        if ($tokens === null) {
            return;
        }

        $edited = is_array($this->tokens['scheme']['dark'] ?? null) ? $this->tokens['scheme']['dark'] : [];

        $this->tokens['scheme']['dark'] = array_replace_recursive(
            DarkScheme::synthesize($tokens->light)->toArray(),
            $edited,
        );
        $this->tokens['darkMode'] = 'manual';
    }

    /**
     * Light-scheme suggestions: derived from the current accent and the zinc-50
     * anchor, so resetting a slot returns to a coherent derived value.
     */
    private function suggestLight(ThemeScheme $light): ThemeScheme
    {
        $accent = Color::fromHex($light->accent);
        $background = Color::fromHex($light->zinc['50'] ?? '#ffffff');

        if ($accent->c < 0.03) {
            $content = $accent;
        } else {
            $content = $accent->withLightness($accent->l - 0.06);

            for ($i = 0; $i < 40 && $content->contrastWith($background) < 4.5; $i++) {
                $content = $content->withLightness($content->l - 0.02);
            }
        }

        $anchor = Color::fromHex($light->zinc['50'] ?? '#fafafa');

        $zinc = array_map(
            fn (Color $step): string => $step->toHex(),
            Palette::neutral($anchor->h, min($anchor->c, 0.08)),
        );

        return new ThemeScheme(
            accent: $light->accent,
            accentContent: $content->toGamut()->toHex(),
            accentForeground: $accent->readableForeground()->toHex(),
            zinc: $zinc,
            status: array_fill_keys(ThemeTokens::STATUSES, null),
        );
    }
}; ?>

<div
    class="flex w-full flex-1 flex-col gap-6"
    x-data="{
        init() {
            const initialScheme = this.$flux.dark ? 'dark' : 'light';
            if (this.$wire.editorScheme !== initialScheme) {
                this.$wire.$set('editorScheme', initialScheme);
            }

            this.$watch('$flux.dark', (isDark) => {
                const scheme = isDark ? 'dark' : 'light';
                if (this.$wire.editorScheme !== scheme) {
                    this.$wire.$set('editorScheme', scheme);
                }
            });

            this.$wire.$watch('editorScheme', (scheme) => {
                const isDark = scheme === 'dark';
                if (this.$flux.dark !== isDark) {
                    this.$flux.dark = isDark;
                }
            });
        }
    }"
>
    {{-- Head Font link for live preview font rendering if external font selected --}}
    @if ($this->previewTokens->fontUrl())
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="stylesheet" href="{{ $this->previewTokens->fontUrl() }}">
    @endif

    {{-- Top Control Card: Theme Name, Actions, Presets & Saved Themes --}}
    <flux:card class="flex flex-col gap-5">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="flex flex-wrap items-end gap-3">
                <flux:field class="w-full sm:w-64">
                    <flux:label>Theme Name</flux:label>
                    <flux:input wire:model.live.debounce.300ms="name" icon="swatch" placeholder="e.g. Ocean Blue" />
                </flux:field>

                <div class="flex h-10 items-center">
                    @if (app(\App\Support\Theme\ActiveTheme::class)->hasWorking())
                        <flux:badge color="blue" size="sm" icon="bolt">Live Across Platform</flux:badge>
                    @elseif ($selectedThemeId && ($activeTheme = $this->savedThemes->firstWhere('id', $selectedThemeId)) && $activeTheme->is_active)
                        <flux:badge color="green" size="sm" icon="check">Active Project Theme</flux:badge>
                    @endif
                </div>
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

        <div class="grid grid-cols-1 gap-5 border-t border-zinc-200 pt-4 lg:grid-cols-12 dark:border-zinc-800">
            {{-- Built-in Presets --}}
            <div class="flex flex-col gap-2.5 lg:col-span-5">
                <div class="flex items-center justify-between">
                    <flux:heading size="sm">Presets</flux:heading>
                    <flux:button wire:click="resetToDefaults" variant="ghost" size="xs">Reset</flux:button>
                </div>

                <div class="grid grid-cols-2 gap-1.5 sm:grid-cols-3">
                    @foreach (\App\Support\Theme\Presets::all() as $slug => $preset)
                        @php
                            $presetTokens = \App\Support\Theme\ThemeTokens::fromArray($preset['tokens']);
                            $presetScheme = $editorScheme === 'dark' ? $presetTokens->darkScheme() : $presetTokens->light;
                            $presetSurface = $editorScheme === 'dark'
                                ? ($presetScheme->zinc['900'] ?? '#171717')
                                : ($presetScheme->zinc['50'] ?? '#fafafa');
                        @endphp
                        <button
                            type="button"
                            wire:click="loadPreset('{{ $slug }}')"
                            class="flex items-center justify-between gap-2 rounded-lg border border-zinc-200/80 px-2.5 py-1.5 text-start text-xs transition-colors hover:bg-zinc-100 dark:border-zinc-800 dark:hover:bg-zinc-800"
                        >
                            <span class="truncate font-medium text-zinc-900 dark:text-zinc-100">{{ $preset['name'] }}</span>
                            <span class="flex shrink-0 items-center gap-1">
                                <span class="size-3.5 rounded-full border border-black/10 dark:border-white/15" style="background-color: {{ $presetScheme->accent }}"></span>
                                <span class="size-3.5 rounded-full border border-black/10 dark:border-white/15" style="background-color: {{ $presetSurface }}"></span>
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Saved Themes in Database --}}
            <div class="flex flex-col gap-2.5 lg:col-span-7 lg:border-s lg:border-zinc-200 lg:ps-5 dark:lg:border-zinc-800">
                <div class="flex items-center justify-between">
                    <flux:heading size="sm">Saved Themes</flux:heading>
                    <flux:badge size="sm" color="zinc">{{ $this->savedThemes->count() }}</flux:badge>
                </div>

                <div class="grid max-h-28 grid-cols-1 gap-1.5 overflow-y-auto pr-1 sm:grid-cols-2 xl:grid-cols-3">
                    @forelse ($this->savedThemes as $savedTheme)
                        @php
                            $savedScheme = $editorScheme === 'dark' ? $savedTheme->tokens->darkScheme() : $savedTheme->tokens->light;
                        @endphp
                        <div class="flex items-center justify-between gap-1 rounded-lg border border-zinc-200/80 px-2.5 py-1.5 transition-colors hover:bg-zinc-100 dark:border-zinc-800 dark:hover:bg-zinc-800 {{ $selectedThemeId === $savedTheme->id ? 'bg-zinc-100 ring-1 ring-zinc-300 dark:bg-zinc-800 dark:ring-zinc-600' : '' }}">
                            <button
                                type="button"
                                wire:click="loadTheme({{ $savedTheme->id }})"
                                class="flex min-w-0 flex-1 items-center gap-2 text-start text-xs text-zinc-800 dark:text-zinc-200"
                            >
                                <span class="size-3.5 shrink-0 rounded-full border border-black/10 dark:border-white/15" style="background-color: {{ $savedScheme->accent }}"></span>
                                <span class="truncate font-medium">{{ $savedTheme->name }}</span>
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
                                    class="shrink-0 text-zinc-400 hover:text-red-600"
                                />
                            @endif
                        </div>
                    @empty
                        <flux:text size="sm" class="text-zinc-500">No saved themes yet.</flux:text>
                    @endforelse
                </div>
            </div>
        </div>
    </flux:card>

    {{-- Import report: daisyUI fields that have no Flux equivalent --}}
    @if (! empty($importReport))
        <flux:callout variant="warning" icon="information-circle">
            <flux:callout.heading>Import report</flux:callout.heading>
            <flux:callout.text>
                These daisyUI-only fields were dropped (Flux has no equivalent):
                <code class="font-mono">{{ implode(', ', $importReport) }}</code>
            </flux:callout.text>
            <x-slot:actions>
                <flux:button wire:click="$set('importReport', [])" variant="ghost" size="sm">Dismiss</flux:button>
            </x-slot:actions>
        </flux:callout>
    @endif

    {{-- Main 2-Column Layout: Settings & Live Preview --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12 xl:items-start">
        {{-- Left Column: Token Slots (4 cols) --}}
        <div class="flex flex-col gap-6 xl:col-span-4">
            @php
                $scheme = $editorScheme;
                $isAutoDark = $scheme === 'dark' && ($tokens['darkMode'] ?? 'auto') === 'auto';
                $accentSlots = [
                    ['key' => 'accent', 'label' => 'Accent', 'hint' => 'Buttons, links, focus rings'],
                    ['key' => 'accentContent', 'label' => 'Accent content', 'hint' => 'Accent used as text on the page'],
                    ['key' => 'accentForeground', 'label' => 'Accent foreground', 'hint' => 'Text on accent buttons'],
                ];
                $zincGroups = [
                    'Surfaces & borders' => ['50', '100', '200', '300'],
                    'Mid tones' => ['400', '500', '600'],
                    'Text & deep surfaces' => ['700', '800', '900', '950'],
                ];
            @endphp

            {{-- Scheme switcher + dark policy --}}
            <flux:card class="flex flex-col gap-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <flux:radio.group wire:model.live="editorScheme" variant="segmented">
                        <flux:radio value="light" icon="sun">Light</flux:radio>
                        <flux:radio value="dark" icon="moon">Dark</flux:radio>
                    </flux:radio.group>

                    <div class="flex items-center gap-1">
                        <span class="me-1 text-xs text-zinc-500">Dark scheme:</span>
                        <flux:button wire:click="setDarkMode('auto')" size="xs" :variant="($tokens['darkMode'] ?? 'auto') === 'auto' ? 'filled' : 'ghost'">Auto</flux:button>
                        <flux:button wire:click="setDarkMode('manual')" size="xs" :variant="($tokens['darkMode'] ?? 'auto') === 'manual' ? 'filled' : 'ghost'">Manual</flux:button>
                    </div>
                </div>

                @if ($isAutoDark)
                    <flux:text size="sm" class="text-zinc-500">
                        Dark values are synthesized from the light scheme. Editing any slot switches the theme to a manual dark scheme.
                    </flux:text>
                @endif
            </flux:card>

            {{-- Accent & Text --}}
            <flux:card class="flex flex-col gap-4">
                <div>
                    <flux:heading size="base">Accent &amp; text</flux:heading>
                    <flux:subheading>The brand colour trio for the {{ $scheme }} scheme.</flux:subheading>
                </div>

                <div class="flex flex-col gap-3">
                    @foreach ($accentSlots as $slot)
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex min-w-0 flex-col">
                                <flux:label class="text-xs font-semibold">{{ $slot['label'] }}</flux:label>
                                <span class="truncate text-[11px] text-zinc-400">{{ $slot['hint'] }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    wire:click="resetSlot('{{ $scheme }}', '{{ $slot['key'] }}')"
                                    title="Reset to suggestion {{ $this->suggestions[$scheme][$slot['key']] ?? '' }}"
                                    class="size-6 shrink-0 cursor-pointer rounded-md border border-dashed border-zinc-400 dark:border-zinc-500"
                                    style="background-color: {{ $this->suggestions[$scheme][$slot['key']] ?? 'transparent' }}"
                                ></button>
                                @if ($isAutoDark)
                                    <flux:color-picker
                                        type="button"
                                        size="sm"
                                        class="w-28"
                                        swatches
                                        dropper
                                        value="{{ $this->suggestions['dark'][$slot['key']] }}"
                                        wire:change="setDarkSlot('{{ $slot['key'] }}', $event.target.value)"
                                    />
                                @else
                                    <flux:color-picker
                                        type="button"
                                        size="sm"
                                        class="w-28"
                                        swatches
                                        dropper
                                        wire:model.live.debounce.150ms="tokens.scheme.{{ $scheme }}.{{ $slot['key'] }}"
                                    />
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </flux:card>

            {{-- Surfaces & Text: the full neutral ramp --}}
            <flux:card class="flex flex-col gap-4">
                <div>
                    <flux:heading size="base">Surfaces &amp; text</flux:heading>
                    <flux:subheading>The neutral ramp for the {{ $scheme }} scheme. Light steps are surfaces here, dark steps are text — and vice-versa in the other scheme.</flux:subheading>
                </div>

                @foreach ($zincGroups as $groupLabel => $steps)
                    <div class="flex flex-col gap-2">
                        <flux:label class="text-[10px] font-semibold uppercase tracking-wide text-zinc-400">{{ $groupLabel }}</flux:label>
                        @foreach ($steps as $step)
                            <div class="flex items-center justify-between gap-3">
                                <flux:label class="font-mono text-xs">zinc-{{ $step }}</flux:label>
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        wire:click="resetSlot('{{ $scheme }}', 'zinc.{{ $step }}')"
                                        title="Reset to suggestion {{ $this->suggestions[$scheme]['zinc'][$step] ?? '' }}"
                                        class="size-6 shrink-0 cursor-pointer rounded-md border border-dashed border-zinc-400 dark:border-zinc-500"
                                        style="background-color: {{ $this->suggestions[$scheme]['zinc'][$step] ?? 'transparent' }}"
                                    ></button>
                                    @if ($isAutoDark)
                                        <flux:color-picker
                                            type="button"
                                            size="sm"
                                            class="w-28"
                                            swatches
                                            dropper
                                            value="{{ $this->suggestions['dark']['zinc'][$step] }}"
                                            wire:change="setDarkSlot('zinc.{{ $step }}', $event.target.value)"
                                        />
                                    @else
                                        <flux:color-picker
                                            type="button"
                                            size="sm"
                                            class="w-28"
                                            swatches
                                            dropper
                                            wire:model.live.debounce.150ms="tokens.scheme.{{ $scheme }}.zinc.{{ $step }}"
                                        />
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach

                {{-- One-shot ramp fill --}}
                <div class="flex flex-col gap-3 rounded-lg border border-zinc-200 p-3 dark:border-zinc-800">
                    <flux:label class="text-xs font-semibold">Fill ramp from tint</flux:label>
                    <div class="flex items-center gap-2">
                        <span class="w-12 font-mono text-[10px] text-zinc-500">{{ round($fillHue) }}°</span>
                        <input type="range" min="0" max="360" step="1" wire:model.live.debounce.50ms="fillHue" class="h-2 w-full cursor-pointer rounded-lg appearance-none bg-gradient-to-r from-red-500 via-green-500 via-blue-500 to-red-500" />
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-12 font-mono text-[10px] text-zinc-500">{{ number_format($fillChroma, 3) }}</span>
                        <input type="range" min="0" max="0.06" step="0.002" wire:model.live.debounce.50ms="fillChroma" class="h-2 w-full cursor-pointer rounded-lg appearance-none bg-zinc-200 dark:bg-zinc-700 accent-zinc-800 dark:accent-zinc-200" />
                    </div>
                    <flux:button wire:click="fillRamp('{{ $scheme }}')" variant="subtle" size="sm" icon="paint-brush">
                        Fill {{ $scheme }} ramp (all 11 steps)
                    </flux:button>
                </div>
            </flux:card>

            {{-- Status Semantic Overrides --}}
            <flux:card class="flex flex-col gap-4">
                <div>
                    <flux:heading size="base">Status colours</flux:heading>
                    <flux:subheading>Optional seeds for alerts, callouts and badges. Empty = Tailwind default.</flux:subheading>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    @foreach (['info' => 'Info', 'success' => 'Success', 'warning' => 'Warning', 'danger' => 'Danger'] as $key => $label)
                        <div class="flex flex-col gap-1.5 rounded-lg border border-zinc-200 p-2.5 dark:border-zinc-800">
                            <div class="flex items-center justify-between">
                                <flux:label class="text-xs font-semibold">{{ $label }}</flux:label>
                                @if (! empty($tokens['scheme'][$scheme]['status'][$key]))
                                    <button type="button" wire:click="clearStatus('{{ $key }}')" class="text-[10px] text-zinc-400 hover:text-red-500">reset</button>
                                @endif
                            </div>
                            @if ($isAutoDark)
                                <flux:text size="xs" class="text-zinc-400">same as light (synthesized dark uses light seeds)</flux:text>
                            @else
                                <flux:color-picker
                                    type="button"
                                    size="sm"
                                    swatches
                                    dropper
                                    placeholder="Tailwind default"
                                    wire:model.live.debounce.150ms="tokens.scheme.{{ $scheme }}.status.{{ $key }}"
                                />
                            @endif
                        </div>
                    @endforeach
                </div>
            </flux:card>

            {{-- Shape (theme-wide, scheme-independent) --}}
            <flux:card class="flex flex-col gap-4">
                <div>
                    <flux:heading size="base">Shape</flux:heading>
                    <flux:subheading>Corner radius in rem (0–2). Pill = 2rem.</flux:subheading>
                </div>

                <div class="flex flex-col gap-3">
                    @foreach (['selector' => 'Selectors (checkboxes, small tags)', 'field' => 'Fields (buttons, inputs, selects)', 'box' => 'Boxes (cards, modals, callouts)'] as $key => $label)
                        <flux:field>
                            <flux:label>{{ $label }}</flux:label>
                            <div class="flex items-center gap-2">
                                <flux:input
                                    type="number"
                                    step="0.05"
                                    min="0"
                                    max="2"
                                    wire:model.live.debounce.150ms="tokens.radius.{{ $key }}"
                                    class="w-24 font-mono text-xs"
                                />
                                <div class="flex items-center gap-1">
                                    @foreach ([0, 0.25, 0.5, 1, 2] as $presetValue)
                                        <button
                                            type="button"
                                            wire:click="$set('tokens.radius.{{ $key }}', {{ $presetValue }})"
                                            class="rounded-md border border-zinc-300 px-2 py-1 text-[10px] font-mono transition-colors hover:bg-zinc-100 dark:border-zinc-700 dark:hover:bg-zinc-800 {{ (float) ($tokens['radius'][$key] ?? 0) === (float) $presetValue ? 'bg-zinc-100 dark:bg-zinc-800' : '' }}"
                                        >{{ $presetValue === 2 ? 'pill' : $presetValue }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </flux:field>
                    @endforeach
                </div>
            </flux:card>

            {{-- Type --}}
            <flux:card class="flex flex-col gap-4">
                <div>
                    <flux:heading size="base">Type</flux:heading>
                    <flux:subheading>Spacing density and font family.</flux:subheading>
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

        {{-- Right Column: Scoped Live Preview (8 cols) --}}
        <div class="flex flex-col gap-4 xl:sticky xl:top-20 xl:col-span-8">
            <div class="flex items-center justify-between">
                <flux:heading size="lg">Live Component Preview</flux:heading>
                <div class="flex items-center gap-2">
                    <flux:button
                        wire:click="toggleScheme"
                        size="sm"
                        variant="subtle"
                        icon="{{ $previewDark ? 'moon' : 'sun' }}"
                    >
                        {{ $previewDark ? 'Dark Preview' : 'Light Preview' }}
                    </flux:button>
                </div>
            </div>

            {{-- Scoped dynamic theme style block --}}
            <style>
                {!! $this->previewCss !!}
            </style>

            {{-- Windowed Preview Container --}}
            <div
                data-theme-preview
                class="overflow-hidden rounded-2xl border shadow-sm transition-colors duration-150 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white {{ $previewDark ? 'dark border-zinc-800 bg-zinc-950 text-white' : 'border-zinc-200 bg-white text-zinc-900' }}"
            >
                @include('lab.examples.theme.preview', [
                    'previewDark' => $previewDark,
                    'previewTab' => $previewTab,
                    'dashboardSubTab' => $dashboardSubTab,
                ])
            </div>

            {{-- Collapsible: Generated 11-step color ramps --}}
            @if (! empty($this->ramps))
                <details class="group rounded-xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
                    <summary class="cursor-pointer list-none p-4">
                        <flux:heading size="sm">Generated color ramps</flux:heading>
                        <flux:text size="xs" class="text-zinc-500">11-step neutral and accent swatches for the {{ $editorScheme }} scheme.</flux:text>
                    </summary>
                    <div class="flex flex-col gap-3 border-t border-zinc-200 p-4 dark:border-zinc-800">
                        @foreach (['base' => 'zinc (base)', 'accent' => 'accent'] as $rampKey => $rampLabel)
                            @if (isset($this->ramps[$rampKey]))
                                <div class="flex flex-col gap-1">
                                    <flux:text size="xs" class="font-mono text-zinc-500">{{ $rampLabel }}</flux:text>
                                    <div class="grid grid-cols-11 gap-1">
                                        @foreach ($this->ramps[$rampKey] as $step => $hex)
                                            <div class="flex flex-col items-center">
                                                <div class="h-6 w-full rounded border border-black/10 dark:border-white/10" style="background-color: {{ $hex }}"></div>
                                                <span class="mt-0.5 font-mono text-[9px] text-zinc-400 opacity-80">{{ $step }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </details>
            @endif

            {{-- Advanced: every compiled variable --}}
            <details class="group rounded-xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
                <summary class="cursor-pointer list-none p-4">
                    <flux:heading size="sm">Advanced · all compiled variables</flux:heading>
                    <flux:text size="xs" class="text-zinc-500">Every CSS variable this theme emits, in both scopes.</flux:text>
                </summary>
                <div class="grid gap-4 border-t border-zinc-200 p-4 sm:grid-cols-2 dark:border-zinc-800">
                    @foreach (['light' => ':root (light)', 'dark' => '.dark'] as $scope => $label)
                        <div class="flex flex-col gap-1 rounded-lg p-2 {{ $editorScheme === $scope ? 'bg-zinc-50 ring-1 ring-zinc-200 dark:bg-zinc-800/60 dark:ring-zinc-700' : '' }}">
                            <flux:text size="xs" class="font-mono text-zinc-500">{{ $label }}</flux:text>
                            <div class="flex max-h-80 flex-col gap-0.5 overflow-y-auto">
                                @foreach ($this->previewVariables[$scope] as $var => $value)
                                    <div class="flex items-center justify-between gap-2 rounded px-1 py-0.5 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                        <span class="truncate font-mono text-[10px] text-zinc-500">{{ $var }}</span>
                                        <span class="flex items-center gap-1.5">
                                            @if (preg_match('/^#[0-9a-f]{6}$/i', $value))
                                                <span class="size-3 rounded-sm border border-black/10" style="background-color: {{ $value }}"></span>
                                            @endif
                                            <span class="font-mono text-[10px] text-zinc-700 dark:text-zinc-300">{{ \Illuminate\Support\Str::limit($value, 22) }}</span>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </details>
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
                <flux:subheading>Paste a daisyUI theme-generator link or code, a share code (?theme=...), or theme JSON.</flux:subheading>
            </div>

            <flux:field>
                <flux:textarea wire:model.defer="importPayload" rows="6" placeholder="https://daisyui.com/theme-generator/#theme=..." />
            </flux:field>

            <flux:checkbox
                wire:model="importNeutralDark"
                label="Use daisyUI neutral for the dark scheme"
                description="Off: the dark scheme is synthesized automatically. On: a manual dark scheme is seeded from the theme's neutral colours."
            />

            <div class="flex items-center justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="importTheme" variant="primary">Import</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
