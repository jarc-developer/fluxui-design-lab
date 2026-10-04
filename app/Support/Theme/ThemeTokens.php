<?php

namespace App\Support\Theme;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Everything a theme can change, as validated, typed values.
 *
 * This is the only input ThemeCompiler accepts, and the compiled CSS is printed
 * raw into <head>. Every field is therefore either a strict hex colour, a bounded
 * number or an allow-listed slug, so no user input can smuggle CSS into the page.
 */
final readonly class ThemeTokens
{
    public const STATUSES = ['info', 'success', 'warning', 'danger'];

    public const RADIUS_STEPS = [0, 0.25, 0.5, 1, 2];

    public const SPACING_STEPS = ['0.2' => 'Compact', '0.25' => 'Default', '0.3' => 'Comfortable'];

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

    /**
     * @param  array<string, string|null>  $status  info/success/warning/danger seed, null = Tailwind default
     */
    public function __construct(
        public string $accentLight,
        public string $accentDark,
        public ?string $accentForegroundLight,
        public ?string $accentForegroundDark,
        public float $baseHue,
        public float $baseChroma,
        public array $status,
        public float $radiusSelector,
        public float $radiusField,
        public float $radiusBox,
        public float $spacing,
        public string $font,
    ) {}

    /**
     * The look the app ships with: neutral greys and a near-black / white accent.
     *
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'accent' => ['light' => '#262626', 'dark' => '#ffffff'],
            'accentForeground' => ['light' => '#ffffff', 'dark' => '#262626'],
            'base' => ['hue' => 0, 'chroma' => 0],
            'status' => ['info' => null, 'success' => null, 'warning' => null, 'danger' => null],
            'radius' => ['selector' => 0.25, 'field' => 0.5, 'box' => 0.75],
            'spacing' => 0.25,
            'font' => 'instrument-sans',
        ];
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
            'accent' => ['required', 'array'],
            'accent.light' => $hex,
            'accent.dark' => $hex,
            'accentForeground' => ['nullable', 'array'],
            'accentForeground.light' => $optionalHex,
            'accentForeground.dark' => $optionalHex,
            'base' => ['required', 'array'],
            'base.hue' => ['required', 'numeric', 'between:0,360'],
            'base.chroma' => ['required', 'numeric', 'between:0,0.08'],
            'status' => ['nullable', 'array'],
            'radius' => ['required', 'array'],
            'radius.selector' => ['required', 'numeric', 'between:0,2'],
            'radius.field' => ['required', 'numeric', 'between:0,2'],
            'radius.box' => ['required', 'numeric', 'between:0,2'],
            'spacing' => ['required', 'numeric', 'between:0.15,0.35'],
            'font' => ['required', 'string', Rule::in(array_keys(self::FONTS))],
        ];

        foreach (self::STATUSES as $status) {
            $rules["status.{$status}"] = $optionalHex;
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

        return new self(
            accentLight: strtolower($data['accent']['light']),
            accentDark: strtolower($data['accent']['dark']),
            accentForegroundLight: self::hexOrNull($data['accentForeground']['light'] ?? null),
            accentForegroundDark: self::hexOrNull($data['accentForeground']['dark'] ?? null),
            baseHue: (float) $data['base']['hue'],
            baseChroma: (float) $data['base']['chroma'],
            status: array_combine(
                self::STATUSES,
                array_map(fn (string $s): ?string => self::hexOrNull($data['status'][$s] ?? null), self::STATUSES),
            ),
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
     * Fill missing keys from the defaults and turn empty strings into nulls, which
     * is what cleared colour pickers and partial JSON imports produce.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function normalise(array $data): array
    {
        $merged = array_replace_recursive(self::defaults(), $data);

        array_walk_recursive($merged, function (mixed &$value): void {
            if ($value === '') {
                $value = null;
            }
        });

        return $merged;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'accent' => ['light' => $this->accentLight, 'dark' => $this->accentDark],
            'accentForeground' => ['light' => $this->accentForegroundLight, 'dark' => $this->accentForegroundDark],
            'base' => ['hue' => $this->baseHue, 'chroma' => $this->baseChroma],
            'status' => $this->status,
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

    private static function hexOrNull(mixed $value): ?string
    {
        return is_string($value) && $value !== '' ? strtolower($value) : null;
    }
}
