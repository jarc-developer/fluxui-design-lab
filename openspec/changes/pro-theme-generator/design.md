# Design

## Context

The current model (`app/Support/Theme/ThemeTokens.php`) stores derivation *parameters*: an accent hex pair, base hue/chroma, optional status seeds, stepped radius values, spacing, font. `ThemeCompiler` derives everything from them at runtime — `Palette::neutral()` builds the zinc ramp, `Palette::fromSeed()` builds status ramps, `readableForeground()` derives foregrounds. Flux's entire themeable surface is `--color-accent{,-content,-foreground}`, `--color-zinc-50..950`, status palettes (blue/sky, green/emerald, amber/yellow, red/rose), `--radius-*`, `--spacing`, `--font-sans`. Themes persist in `themes.tokens` (JSON, via `ThemeTokensCast`) and travel as `base64url(zlib(json))` share codes — the same transport daisyUI uses (daisyUI wraps deflate in a zlib header; we use raw `gzdeflate`).

## Goals / Non-Goals

**Goals:**
- Every Flux-visible variable is an explicit, stored, editable value (pure declarative model).
- Light-only sources (daisyUI themes) become full dual-scheme themes via auto-synthesis.
- daisyUI share links/JSON import with a dropped-fields report.
- Migration with zero visual regression for saved themes, presets, and existing share links.

**Non-Goals:**
- daisyUI secondary/accent roles, border width, depth, noise (no Flux hooks).
- daisyUI-format export.
- A params+overrides hybrid model (considered; rejected in favor of pure declarative).
- Per-scheme fonts or spacing.

## Decisions

### D1: Pure declarative token shape (v2)

```json
{
  "scheme": { "light": <scheme>, "dark": <scheme|null> },
  "darkMode": "auto" | "manual",
  "radius": { "selector": 2.0, "field": 0.5, "box": 1.0 },
  "spacing": 0.25,
  "font": "instrument-sans"
}
```

where `<scheme>` is:

```json
{
  "accent": "#1c1c29", "accentContent": "#1c1c29", "accentForeground": "#e2fd00",
  "zinc": { "50": "#f7f5f3", "100": "...", "...": "...", "950": "#0a0a0a" },
  "status": { "info": "#7cc8ff", "success": null, "warning": null, "danger": null }
}
```

`dark: null` + `darkMode: auto` means "synthesize". Rationale: explicit values are the only way to represent themes like silk (saturated foregrounds, cream surfaces); hybrid override maps were rejected because they keep two mental models and a second code path forever. The cost — derivation leaves the stored model — is accepted; derivation becomes UI-side one-shot **fill actions** (see D5).

**Status stays a seed, not 11 explicit steps.** Flux consumes status *ramps* (badges use 50–800 across a palette), so the declared value is the seed and `Palette::fromSeed()` ramp generation is compilation mechanics, not model derivation. Storing 44 explicit status colors per scheme was considered and rejected as hostile UX for zero expressive gain.

### D2: Dark auto-synthesis algorithm

For each dark slot without an explicit value, synthesize from the light counterpart:

1. Keep oklch hue; scale chroma ×0.8.
2. Invert lightness `L' = 1 − L`, then clamp into the slot band: surfaces `0.14–0.30`, text `0.85–0.97`, accent trio `0.65–0.80`.
3. Where a synthesized foreground pairs with a synthesized background (accent-foreground on accent, body text on surfaces), nudge `L'` in 0.02 steps until contrast ≥ 4.5:1 (max 40 iterations) — same loop shape as the current `accentContent()` helper.

Everything needed exists in `Color` (oklch `l/c/h`, `withLightness()`, `contrastWith()`). Bands/chroma-scale are tuned during implementation against the silk reference; the algorithm contract (bands + contrast floor) is what's specified.

Alternative considered: fixed dark defaults per slot — rejected, it discards the theme's hue identity.

### D3: Migration by compile-snapshot

v1 detection: presence of `accent.light` / `base.hue` keys. Upcast = run the **legacy v1 compiler** on the v1 tokens and snapshot the emitted variables into v2 slots (accent trio, all 11 zinc steps from the ramp, status seeds, radius, spacing, font). Because v1 rendering was fully derived, the snapshot is exactly what users saw — zero visual regression by construction.

- **DB**: a migration (or idempotent `theme:migrate-v2` artisan command run by it) rewrites `themes.tokens` row by row.
- **Share links / JSON import**: the same upcast runs inside decode/normalise, so v1 codes keep working forever.
- The v1 compiler code is kept (frozen, marked legacy) until the upcast ships; it is the fidelity oracle for migration tests.

Alternative considered: lazy upcast on read in `ThemeTokensCast` — rejected; two live shapes in the DB means every future feature handles both.

### D4: daisyUI import as a mapper, not a second codec

Transport is shared (`base64url(zlib(json))`), but daisyUI wraps deflate with a zlib header while `ThemeCodec` uses raw deflate — the mapper tries both `gzuncompress()` and `gzinflate()`. Full daisyUI URLs are accepted; the `#theme=` fragment is extracted.

Parsing needs an oklch string reader: add `Color::fromOklch(string)` (the class already thinks in oklch; today only `fromHex()` exists). Mapping table:

| daisyUI | v2 target |
|---|---|
| `base-100/200/300` | light `zinc.50/100/200`; whole ramp pre-filled from base-100 hue/chroma, then those 3 steps overwritten with exact values |
| `base-content` | light `zinc.800` |
| `primary` / `primary-content` | light `accent` / `accentForeground` |
| `info/success/warning/error` | status seeds |
| `radius-selector/field/box` | radius (rem, free) |
| `size-field` | spacing |
| `neutral` / `neutral-content` | **opt-in only** (import checkbox): seeds a manual dark scheme — a fresh ramp from neutral's tint, exact neutral pinned at its nearest step, darker steps derived. Off by default: stamping neutral onto dark zinc 900/950 collided with the `base-content` pin at 800 (both are often the same colour, e.g. daisyUI emerald), flattening all dark surfaces to one value |
| `secondary`, `accent`, `*-content` for them, `--border`, `--depth`, `--noise` | **dropped → import report** |

Unmapped dark slots fall through to synthesis (D2), which is the default dark mode for imports.

### D5: UI — slots with suggestion engine

The Livewire component computes **suggestions on demand** using the existing derivations (`Palette::neutral`, `readableForeground`): each slot shows the suggestion as its default; editing promotes it to an explicit stored value; reset returns to the suggestion. "Fill ramp from hue/chroma" is a one-shot action writing 11 explicit steps. This keeps the guided feel of the old dials without derivation in the model.

Page restructure: slot groups (Accent & text / Surfaces / Text / Statuses / Shape / Type), a light/dark scheme switcher per group, the `auto|manual` dark toggle, import modal extended with the daisyUI report, and an Advanced section listing every compiled variable read-only (audit view).

## Risks / Trade-offs

- **Migration fidelity drift** (snapshot ≠ old render) → migration test compiles each preset and a fixture theme through v1 and asserts the migrated v2 theme emits identical variables.
- **v1 share links in the wild break** → upcast on decode (D3) + round-trip tests for both versions.
- **Synthesis produces muddy dark themes** for high-chroma imports → chroma scale + band clamps; preview encourages manual promotion; worst case is aesthetic, not broken.
- **Bigger validated input surface** (11 zinc steps × 2 schemes ≈ more user-controlled strings in compiled CSS) → same strict hex regex per slot as today; validation stays the injection firewall.
- **Rollback** is git-revert + DB backup restore; the tokens column is not downcastable losslessly, so the migration logs before/after JSON per row.

## Migration Plan

1. Land v2 model + compiler + upcast reader (v1 inputs still accepted everywhere).
2. Run the DB migration re-writing `themes.tokens`; verify with the fidelity test.
3. Swap the generator UI to the slot editor; presets ship precompiled v2.
4. Delete v1-shaped writes; keep the frozen v1 compiler only as the migration/test oracle.

## Open Questions

- Exact synthesis constants (chroma scale, band edges) — tuned against silk and presets during implementation; contract in D2 is unaffected either way.
