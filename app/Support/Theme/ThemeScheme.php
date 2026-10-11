<?php

namespace App\Support\Theme;

/**
 * One colour scheme of a theme: every colour Flux can read, as explicit hex values.
 *
 * The neutral ramp is positional — Flux picks light steps for light surfaces/text
 * and dark steps for dark ones — so a scheme always carries all eleven steps.
 */
final readonly class ThemeScheme
{
    /**
     * @param  array<string, string>  $zinc  step (50–950) => hex
     * @param  array<string, string|null>  $status  info/success/warning/danger seed, null = Tailwind default
     */
    public function __construct(
        public string $accent,
        public string $accentContent,
        public string $accentForeground,
        public array $zinc,
        public array $status,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function normalise(array $data): array
    {
        $zinc = $data['zinc'] ?? [];

        foreach (Palette::STEPS as $step) {
            $zinc[$step] = self::hexOrNull($zinc[$step] ?? null);
        }

        $status = [];

        foreach (ThemeTokens::STATUSES as $name) {
            $status[$name] = self::hexOrNull($data['status'][$name] ?? null);
        }

        return [
            'accent' => self::hexOrNull($data['accent'] ?? null),
            'accentContent' => self::hexOrNull($data['accentContent'] ?? null),
            'accentForeground' => self::hexOrNull($data['accentForeground'] ?? null),
            'zinc' => $zinc,
            'status' => $status,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'accent' => $this->accent,
            'accentContent' => $this->accentContent,
            'accentForeground' => $this->accentForeground,
            'zinc' => $this->zinc,
            'status' => $this->status,
        ];
    }

    private static function hexOrNull(mixed $value): ?string
    {
        return is_string($value) && $value !== '' ? strtolower($value) : null;
    }
}
