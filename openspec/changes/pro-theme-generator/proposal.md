# Proposal

## Why

The current theme generator (`/theme/generator`) is parametric: a few dials (accent seed, base hue/chroma, status seeds) derive every CSS variable. Derivation gives coherence but cannot express declarative themes like daisyUI's "silk" — cream surfaces from explicit values, saturated accent-foregrounds (e.g. lime text on near-black buttons), or pill selectors (2rem radius is UI-capped at 1rem). The goal is a pro-grade, Flux-bounded theme generator where every Flux-visible variable is an explicit, editable value.

## What Changes

- **BREAKING**: `ThemeTokens` becomes pure declarative — explicit color values per scheme (accent trio, full 11-step zinc ramp, status seeds) instead of stored derivation params (base hue/chroma, accent light/dark pair). Derivation moves to the UI as one-shot "fill/suggest" affordances; it is no longer part of the stored model.
- **BREAKING**: Theme JSON schema v1 → v2. Saved DB themes are migrated by compiling each v1 theme once and snapshotting the emitted variables as v2 values (100% visual fidelity). v1 share-link codes and v1 JSON imports are upcast through the same migration on decode, so old links keep working.
- Dark mode policy: themes always carry a light and dark scheme; the dark side is **auto-synthesized** by default (hue-preserving lightness inversion, clamped to per-slot bands, contrast-verified), with a per-theme `darkMode: auto|manual` switch for full manual control.
- Radius selector/field/box become free rem values (validator already allows 0–2; UI currently caps at 1) — pill selectors unlocked.
- `accentForeground` pickers are exposed in the UI (the model and compiler already support them; the blade never surfaced them).
- New: **daisyUI theme import** — paste a daisyUI theme-generator share link/code or theme JSON; mapped into the v2 model (`base-*` → zinc overrides/ramp seed, `primary` → accent, `*-content` → accent-foreground/status companions, `neutral*` → dark-side slots, radius/size → radius/spacing). daisyUI-only fields with no Flux equivalent (secondary/accent roles, `--border`, `--depth`, `--noise`) are dropped and listed in an import report.
- Generator UI reorganized into daisyUI-style semantic slots (Accent & text / Surfaces / Text / Statuses / Shape / Type), each slot Auto-derived ⇄ Custom with reset, plus an Advanced all-variables section.

Deliberately out of scope: daisyUI secondary/accent roles (Flux has no hooks for them), border width / depth / noise emulation (no Flux theme var exists), daisyUI export.

## Capabilities

### New Capabilities

- `theme-engine`: declarative theme token model (v2), validation, compilation to Flux/Tailwind CSS variables, dark auto-synthesis, share-link codec, daisyUI import mapping, and v1→v2 migration of saved themes, share links, and presets.
- `theme-generator-ui`: the `/theme/generator` page experience — semantic slot editor with suggest/fill affordances, dark auto/manual toggle, daisyUI/JSON import modal, export modal, scoped live preview, and saved-theme management.

### Modified Capabilities

<!-- No existing specs — this project has no prior capability inventory. -->

## Impact

- **Code**: `app/Support/Theme/ThemeTokens.php` (rewritten), `ThemeCompiler.php` (compiles explicit slots + synthesis, no longer derives ramps from base hue/chroma at runtime), `ThemeCodec.php` (v1 upcast on decode), `Presets.php` (values precompiled to v2), `app/Casts/ThemeTokensCast.php`, `app/Support/Theme/ActiveTheme.php`, new `DaisyThemeMapper` support class, `resources/views/pages/⚡theme-generator.blade.php` (major restructure), `resources/views/lab/examples/theme/preview` partial.
- **Data**: `themes.tokens` JSON column — one-time upcast migration (compile-snapshot) with rollback notes; `database/seeders` preset seeds.
- **Tests**: `tests/Feature/ThemeGeneratorTest.php` rewritten/extended (silk import, dark synthesis, v1 upcast, radius >1rem, accent-foreground slots).
- **No new dependencies**; daisyUI payload transport (`base64url(zlib(json))`) already matches `ThemeCodec`.
