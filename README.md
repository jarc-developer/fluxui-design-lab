# Flux Themes

An open-source **theme generator and landing page** for **Flux UI** and **Tailwind CSS v4**, built on Laravel 13 and Livewire 4 and running entirely in Docker.

Everything lives on a single unified route (`/`): a theme-reactive landing page paired with an interactive token editor and live multi-tab Flux UI component preview.

## Features

- **Declarative Flux UI Token Model:** Edit `--color-accent`, `--color-accent-content`, `--color-accent-foreground`, all 11 `--color-zinc-*` neutral steps, semantic status seeds (`info`, `success`, `warning`, `danger`), corner radii (`selector`, `field`, `box` up to `2rem` pill), spacing density, and Bunny Fonts.
- **Auto & Manual Dark Mode:** Dark schemes are synthesized automatically via contrast-checked OKLCH lightness inversion, or switched to `Manual` for per-slot dark overrides.
- **OKLCH Neutral Ramp Synthesis:** Tint all 11 `zinc` steps (`50`–`950`) in one click from hue and chroma sliders following Tailwind CSS v4's lightness curve.
- **daisyUI Theme Importer:** Paste a daisyUI theme-generator URL, share code, or JSON object; tokens map cleanly to Flux equivalents with a transparent drop report for unsupported fields.
- **Shareable URLs:** Every token state encodes into a compact `?theme=...` compressed query parameter.
- **Presets, Database Storage & Global Activation:** Load built-in presets, save named themes in the database, and activate themes globally across the app.
- **Tailwind CSS v4 & CLI Export:** Copy `@theme` + `@layer theme` CSS or JSON tokens from the UI, or export saved themes directly from the CLI.

## Stack

| Piece | Version |
| --- | --- |
| PHP | 8.4 (FPM) |
| Laravel | 13 |
| Livewire | 4 |
| Flux / Flux Pro | 2.20 |
| MySQL | 8.0 |
| Node | 20 |
| Vite | 8 (via `vite-plus`) |
| Tailwind CSS | 4 |
| Quality | PHPUnit 12, PHPStan (Level 7), Pint |

## Running Locally

```bash
docker compose up -d
```

| Service | URL / Port |
| --- | --- |
| App (nginx) | http://localhost:8003 |
| Vite dev server | http://localhost:5175 |
| MySQL | `localhost:3310` |

## Common Commands

```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app composer test     # pint + phpstan + phpunit
docker compose exec node npm run build
```

## Exporting Themes to Other Flux UI Projects

Copy the generated `@theme` CSS directly from the **Quick Start** section or **Export** modal at `http://localhost:8003`, or export a saved database theme via Artisan:

```bash
docker compose exec app php artisan theme:export ocean --path=resources/css/theme-ocean.css
```

Paste the exported `@theme` and `@layer theme` blocks into your target project's `resources/css/app.css` right after `@import '../../vendor/livewire/flux/dist/flux.css';`.
