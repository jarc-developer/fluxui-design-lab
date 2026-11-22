# Flux Themes

An open-source, **zero-database theme generator and landing page** for **Flux UI** and **Tailwind CSS v4**, built on Laravel 13 and Livewire 4.

Everything runs on a single unified route (`/`): a theme-reactive landing page paired with an interactive token editor, private browser `localStorage` saved themes, and a live multi-tab Flux UI component preview.

## Features

- **Declarative Flux UI Token Model:** Edit `--color-accent`, `--color-accent-content`, `--color-accent-foreground`, all 11 `--color-zinc-*` neutral steps, semantic status seeds (`info`, `success`, `warning`, `danger`), corner radii (`selector`, `field`, `box` up to `2rem` pill), spacing density, and Bunny Fonts.
- **Auto & Manual Dark Mode:** Dark schemes are synthesized automatically via contrast-checked OKLCH lightness inversion, or switched to `Manual` for per-slot dark overrides.
- **OKLCH Neutral Ramp Synthesis:** Tint all 11 `zinc` steps (`50`–`950`) in one click from hue and chroma sliders calibrated to Tailwind CSS v4's lightness curve.
- **daisyUI Theme Importer:** Paste a daisyUI theme-generator URL, share code, or JSON object; tokens map cleanly to Flux equivalents with a transparent drop report for unsupported fields.
- **Private Browser Storage & Shareable URLs:** Bookmark custom palettes privately in browser `localStorage`, or share any state via a compact `?theme=...` compressed query parameter — no database required.
- **Tailwind CSS v4 & CLI Export:** Copy `@theme` + `@layer theme` CSS or JSON tokens from the UI, or export presets and share codes directly from the CLI.

## Stack

| Piece | Version |
| --- | --- |
| PHP | 8.4 (FPM) |
| Laravel | 13 |
| Livewire | 4 |
| Flux / Flux Pro | 2.20 |
| Node | 20 |
| Vite | 8 (via `vite-plus`) |
| Tailwind CSS | 4 |
| Quality | PHPUnit 12, PHPStan (Level 7), Pint |

## Local Development (Docker)

```bash
docker compose up -d
docker compose exec app composer test     # pint + phpstan + phpunit
docker compose exec node npm run build
```

## Exporting Themes via CLI

Copy the generated `@theme` CSS directly from the **Quick Start** section or **Export** modal, or export a preset / share code via Artisan:

```bash
php artisan theme:export ocean --path=resources/css/theme-ocean.css
php artisan theme:export --code="<share-code>" --name="Brand Theme" --path=resources/css/theme.css
```

Paste the exported `@theme` and `@layer theme` blocks into your target project's `resources/css/app.css` right after `@import '../../vendor/livewire/flux/dist/flux.css';`.
