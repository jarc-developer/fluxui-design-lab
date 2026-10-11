<?php

namespace App\Support\Theme;

use App\Support\Theme\Legacy\LegacyThemeCompiler;
use App\Support\Theme\Legacy\LegacyThemeTokens;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Everything a theme can change, as validated, typed values.
 *
 * v2 is declarative: every colour Flux reads is stored explicitly per scheme
 * (accent trio, the full 50–950 neutral ramp, status seeds). Derivation lives
 * in the UI as one-shot fill actions, not in the stored model. The compiled
 * CSS is printed raw into <head>, so every colour is a strict hex value and
 * no user input can smuggle CSS into the page.
 *
 * Legacy v1 payloads (parametric: accent light/dark, base hue/chroma) are
 * upcast by compile-snapshot in normalise(), so old share links, imports and
 * database rows keep working.
 */
final readonly class ThemeTokens
{
    public const STATUSES = ['info', 'success', 'warning', 'danger'];

    /**
     * Fonts the generator offers. `bunny` is the fonts.bunny.net family spec;
     * Instrument Sans has none because Vite already self-hosts it.
     */
    public const FONTS = [
        'instrument-sans' => ['family' => 'Instrument Sans', 'bunny' => null, 'fallback' => 'sans'],
        'inter' => ['family' => 'Inter', 'bunny' => 'inter:400,500,600', 'fallback' => 'sans'],
        'figtree' => ['family' => 'Figtree', 'bunny' => 'figtree:400,500,600', 'fallback' => 'sans'],
        'manrope' => ['family' => 'Manrope', 'bunny' => 'manrope:400,500,600', 'fallback' => 'sans'],
        'dm-sans' => ['family' => 'DM Sans', 'bunny' => 'dm-sans:400,500,600', 'fallback' => 'sans'],
        'plus-jakarta-sans' => ['family' => 'Plus Jakarta Sans', 'bunny' => 'plus-jakarta-sans:400,500,600', 'fallback' => 'sans'],
        'ibm-plex-sans' => ['family' => 'IBM Plex Sans', 'bunny' => 'ibm-plex-sans:400,500,600', 'fallback' => 'sans'],
        'nunito' => ['family' => 'Nunito', 'bunny' => 'nunito:400,500,600', 'fallback' => 'sans'],
        'space-grotesk' => ['family' => 'Space Grotesk', 'bunny' => 'space-grotesk:400,500,600', 'fallback' => 'sans'],
        'lora' => ['family' => 'Lora', 'bunny' => 'lora:400,500,600', 'fallback' => 'serif'],
    ];

    public function __construct(
        public ThemeScheme $light,
        public ?ThemeScheme $dark,
        public string $darkMode,
        public float $radiusSelector,
        public float $radiusField,
        public float $radiusBox,
        public float $spacing,
        public string $font,
    ) {}

    /**
     * The look the app ships with: neutral greys and a near-black / white accent.
     * Both schemes explicit (manual) so the default is a complete static snapshot.
     *
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        $zinc = [
            '50' => '#fafafa', '100' => '#f5f5f5', '200' => '#e5e5e5', '300' => '#d4d4d4',
            '400' => '#a3a3a3', '500' => '#737373', '600' => '#525252', '700' => '#404040',
            '800' => '#262626', '900' => '#171717', '950' => '#0a0a0a',
        ];

        $status = array_fill_keys(self::STATUSES, null);

        return [
            'scheme' => [
                'light' => [
                    'accent' => '#262626',
                    'accentContent' => '#262626',
                    'accentForeground' => '#ffffff',
                    'zinc' => $zinc,
                    'status' => $status,
                ],
                'dark' => [
                    'accent' => '#ffffff',
                    'accentContent' => '#ffffff',
                    'accentForeground' => '#262626',
                    'zinc' => $zinc,
                    'status' => $status,
                ],
            ],
            'darkMode' => 'manual',
            'radius' => ['selector' => 0.25, 'field' => 0.5, 'box' => 0.75],
            'spacing' => 0.25,
            'font' => 'instrument-sans',
        ];
    }

    /**
     * True for the v1 parametric shape (accent light/dark pair, base hue/chroma).
     *
     * @param  array<string, mixed>  $data
     */
    public static function isLegacy(array $data): bool
    {
        return isset($data['base']) || isset($data['accent']['light']);
    }

    /**
     * Validation rules, optionally prefixed for nested use (e.g. `tokens.`).
     *
     * @return array<string, mixed>
     */
    public static function rules(string $prefix = ''): array
    {
        $hex = ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'];
        $optionalHex = ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'];

        $rules = [
            'scheme' => ['required', 'array'],
            'darkMode' => ['required', 'string', Rule::in(['auto', 'manual'])],
            'scheme.dark' => ['nullable', 'array', 'required_if:'.$prefix.'darkMode,manual'],
            'radius' => ['required', 'array'],
            'radius.selector' => ['required', 'numeric', 'between:0,2'],
            'radius.field' => ['required', 'numeric', 'between:0,2'],
            'radius.box' => ['required', 'numeric', 'between:0,2'],
            'spacing' => ['required', 'numeric', 'between:0.15,0.35'],
            'font' => ['required', 'string', Rule::in(array_keys(self::FONTS))],
        ];

        foreach (['light', 'dark'] as $scheme) {
            foreach (['accent', 'accentContent', 'accentForeground'] as $slot) {
                $rules["scheme.{$scheme}.{$slot}"] = $scheme === 'light' ? $hex : ['sometimes', ...$hex];
            }

            foreach (Palette::STEPS as $step) {
                $rules["scheme.{$scheme}.zinc.{$step}"] = $scheme === 'light' ? $hex : ['sometimes', ...$hex];
            }

            foreach (self::STATUSES as $status) {
                $rules["scheme.{$scheme}.status.{$status}"] = $scheme === 'light' ? $optionalHex : ['sometimes', ...$optionalHex];
            }
        }

        $prefixed = [];

        foreach ($rules as $key => $rule) {
            $prefixed[$prefix.$key] = $rule;
        }

        return $prefixed;
    }

    /**
     * @param  array<string, mixed>  $data
     *
     * @throws ValidationException
     */
    public static function fromArray(array $data): self
    {
        $data = self::normalise($data);

        Validator::make($data, self::rules())->validate();

        $darkMode = $data['darkMode'];

        return new self(
            light: self::scheme($data['scheme']['light']),
            dark: $darkMode === 'manual' ? self::scheme($data['scheme']['dark']) : null,
            darkMode: $darkMode,
            radiusSelector: (float) $data['radius']['selector'],
            radiusField: (float) $data['radius']['field'],
            radiusBox: (float) $data['radius']['box'],
            spacing: (float) $data['spacing'],
            font: $data['font'],
        );
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function tryFrom(array $data): ?self
    {
        try {
            return self::fromArray($data);
        } catch (ValidationException) {
            return null;
        }
    }

    /**
     * The dark scheme as it should be compiled: explicit under the manual
     * policy, synthesized from the light scheme under auto.
     */
    public function darkScheme(): ThemeScheme
    {
        return $this->dark ?? DarkScheme::synthesize($this->light);
    }

    /**
     * Fill missing keys from the defaults, upcast legacy v1 payloads, and turn
     * empty strings into nulls, which is what cleared colour pickers and partial
     * JSON imports produce.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function normalise(array $data): array
    {
        if (self::isLegacy($data)) {
            $data = self::upcastLegacy($data);
        }

        $merged = array_replace_recursive(self::defaults(), $data);

        if (($merged['darkMode'] ?? 'manual') === 'auto') {
            $merged['scheme']['dark'] = null;
        }

        array_walk_recursive($merged, function (mixed &$value): void {
            if ($value === '') {
                $value = null;
            }
        });

        $merged['scheme']['light'] = ThemeScheme::normalise($merged['scheme']['light'] ?? []);
        $merged['scheme']['dark'] = is_array($merged['scheme']['dark'] ?? null)
            ? ThemeScheme::normalise($merged['scheme']['dark'])
            : null;

        return $merged;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'scheme' => [
                'light' => $this->light->toArray(),
                'dark' => $this->dark?->toArray(),
            ],
            'darkMode' => $this->darkMode,
            'radius' => ['selector' => $this->radiusSelector, 'field' => $this->radiusField, 'box' => $this->radiusBox],
            'spacing' => $this->spacing,
            'font' => $this->font,
        ];
    }

    /**
     * @return array{family: string, bunny: string|null, fallback: string}
     */
    public function fontDefinition(): array
    {
        return self::FONTS[$this->font];
    }

    public function fontUrl(): ?string
    {
        $bunny = $this->fontDefinition()['bunny'];

        return $bunny === null ? null : 'https://fonts.bunny.net/css?family='.$bunny.'&display=swap';
    }

    /**
     * Compile-snapshot a v1 payload: run the frozen legacy compiler and capture
     * the emitted values as explicit v2 slots, so the migrated theme renders
     * exactly what the v1 theme rendered (design.md D3).
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function upcastLegacy(array $data): array
    {
        $legacy = LegacyThemeTokens::fromArray($data);

        ['light' => $light, 'dark' => $dark] = (new LegacyThemeCompiler)->variables($legacy);

        $zinc = [];

        foreach (Palette::STEPS as $step) {
            $zinc[$step] = $light["--color-zinc-{$step}"];
        }

        return [
            'scheme' => [
                'light' => [
                    'accent' => $light['--color-accent'],
                    'accentContent' => $light['--color-accent-content'],
                    'accentForeground' => $light['--color-accent-foreground'],
                    'zinc' => $zinc,
                    'status' => $legacy->status,
                ],
                'dark' => [
                    'accent' => $dark['--color-accent'],
                    'accentContent' => $dark['--color-accent-content'],
                    'accentForeground' => $dark['--color-accent-foreground'],
                    'zinc' => $zinc,
                    'status' => $legacy->status,
                ],
            ],
            'darkMode' => 'manual',
            'radius' => [
                'selector' => $legacy->radiusSelector,
                'field' => $legacy->radiusField,
                'box' => $legacy->radiusBox,
            ],
            'spacing' => $legacy->spacing,
            'font' => $legacy->font,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private static function scheme(array $data): ThemeScheme
    {
        return new ThemeScheme(
            accent: $data['accent'],
            accentContent: $data['accentContent'],
            accentForeground: $data['accentForeground'],
            zinc: $data['zinc'],
            status: $data['status'],
        );
    }
}
