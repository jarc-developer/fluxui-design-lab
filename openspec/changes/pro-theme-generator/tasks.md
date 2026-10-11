# Tasks

## 1. Color primitives

- [x] 1.1 Add `Color::fromOklch(string)` parsing `oklch(L C H)` (percent or 0–1 lightness, with/without alpha rejected) and verify a unit test round-trips silk's values (`oklch(23.27% 0.0249 284.3)` → `#1c1c29`)
- [x] 1.2 Add `Color` helpers needed for synthesis if missing (`withChroma`, band clamping) and verify unit tests cover hue preservation and gamut clamping

## 2. Declarative token model (v2)

- [x] 2.1 Rewrite `App\Support\Theme\ThemeTokens` as the v2 shape (per-scheme accent trio + explicit zinc 50–950 + status seeds, `darkMode`, free-rem radius 0–2, spacing, font) and verify validation rejects non-hex colors, out-of-range radius, and missing zinc steps
- [x] 2.2 Implement v1 detection (`accent.light`/`base.hue` keys) and the compile-snapshot upcast inside `ThemeTokens::normalise`/`fromArray` using a frozen copy of the v1 compiler, and verify a unit test upcasts each current preset array into valid v2 tokens
- [x] 2.3 Update `ThemeTokensCast`, `ActiveTheme`, and `Presets` (presets as precompiled v2 values) and verify `php artisan tinker` loads every seeded preset as valid v2 `ThemeTokens`

## 3. Compiler v2 + dark synthesis

- [x] 3.1 Rewrite `ThemeCompiler::variables()` to emit explicit slot values (no runtime ramp derivation except status seeds via `Palette::fromSeed`) for `:root`/`.dark`/scoped output and verify a test compiles a full v2 theme with every Flux variable present in both scopes
- [x] 3.2 Implement dark auto-synthesis (hue kept, chroma ×0.8, lightness inverted and clamped to slot bands, contrast-nudged to ≥4.5:1) behind `darkMode: auto` and verify synthesized dark pairs meet 4.5:1 in a test using silk's light values
- [x] 3.3 Verify `export()` emits the Tailwind v4 `@theme` + `.dark` layer snippet from v2 tokens and the exported CSS contains the explicit values (test asserts substrings for accent, zinc-50, radius-selector)

## 4. Codec and daisyUI import

- [x] 4.1 Extend `ThemeCodec::decode` to upcast v1 codes and verify round-trip tests for both a v1 code (generated from the legacy codec) and a v2 code, plus rejection of oversized/garbage payloads
- [x] 4.2 Add `App\Support\Theme\DaisyThemeMapper` (accepts full URL, bare code, or JSON; tries `gzuncompress` then `gzinflate`; `Color::fromOklch` for values) implementing the design.md D4 mapping table and returning tokens + a dropped-fields list
- [x] 4.3 Verify a test imports the real silk share code and asserts cream zinc 50–200, accent `#1c1c29`, accent-foreground `#e2fd00`, selector radius 2rem, pastel status seeds, and a report listing secondary/accent/border/depth/noise as dropped

## 5. Database migration

- [x] 5.1 Write the migration (or idempotent `theme:migrate-v2` command invoked by it) that compile-snapshots every legacy `themes.tokens` row, logging before/after JSON, and verify on a seeded legacy DB that migrated themes compile to identical CSS variables as before migration

## 6. Generator UI

- [x] 6.1 Restructure `⚡theme-generator.blade.php` into slot groups (Accent & text / Surfaces / Text / Statuses / Shape / Type) with per-slot suggestion display, custom-value promotion, and reset, and verify editing the accent-foreground slot updates the preview without a full reload
- [x] 6.2 Add the dark `auto|manual` toggle with synthesized-value display and promote-on-edit, plus the "fill ramp from hue/chroma" one-shot action, and verify manual dark edits persist through save/reload
- [x] 6.3 Replace stepped radius controls with free rem inputs (0–2) plus quick presets including pill (2rem), and verify 2rem renders pill checkboxes in the preview
- [x] 6.4 Wire the import modal to `DaisyThemeMapper` (URL/code/JSON autodetect) with the dropped-fields report, and verify importing a daisyUI URL shows the report and a bad payload leaves state unchanged
- [x] 6.5 Add the Advanced all-variables audit section and verify it lists every compiled variable for the current editor state in both schemes

## 7. Test suite and verification

- [x] 7.1 Rewrite `tests/Feature/ThemeGeneratorTest.php` for the v2 flow (save/load/activate/delete, share link round-trip, v1 upcast, silk import, synthesis contrast, pill radius) and verify `docker exec design_lab_app php artisan test` is green
- [x] 7.2 Manually import the daisyUI silk link at `/theme/generator`, activate it, and verify cream surfaces, lime-on-ink buttons, pill selectors, pastel statuses, and a coherent auto dark scheme on real pages

## 8. Post-review fixes (import dark scheme)

- [x] 8.1 Import defaults to `darkMode: auto` (neutral no longer stamped onto dark zinc 900/950 — it flattened dark surfaces when neutral equals base-content, e.g. daisyUI emerald) and verify the silk unit test asserts auto + neutral in the dropped report
- [x] 8.2 Add opt-in neutral path: `DaisyThemeMapper::map($payload, useNeutralForDark: true)` seeds a manual dark ramp from neutral's tint, pinning exact neutral at its nearest step with darker steps derived, and verify a unit test asserts no flat 800/900/950 run
- [x] 8.3 Add the "Use daisyUI neutral for the dark scheme" checkbox to the import modal, wired to the mapper option, and verify the feature suite stays green
