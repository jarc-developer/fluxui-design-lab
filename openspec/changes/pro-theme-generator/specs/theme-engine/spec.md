# Spec Delta

## Purpose

Defines the declarative theme model and its engine: explicit per-scheme color values, compilation into the CSS variables Flux and Tailwind read, dark-scheme auto-synthesis, share-link encoding, daisyUI theme import, and migration of legacy theme data.

## ADDED Requirements

### Requirement: Declarative theme token model

A theme SHALL be stored as explicit values rather than derivation parameters: for each of the light and dark schemes, an accent color, accent-content color, accent-foreground color, an explicit 11-step neutral ramp (steps 50–950), and optional status seeds for info/success/warning/danger; plus theme-wide radius values (selector, field, box as rem values between 0 and 2), a spacing density value, and a font choice. Every stored color SHALL be a validated 6-digit hex value. Derivation (e.g. generating a ramp from hue/chroma) SHALL be a UI affordance only and SHALL NOT be part of the stored model.

#### Scenario: Explicit values are stored verbatim

- **WHEN** a theme declares accent `#1c1c29` and accent-foreground `#e2fd00` in the light scheme
- **THEN** those exact values are stored and re-emitted unchanged, and no readable-foreground derivation may replace the declared foreground

#### Scenario: Pill radius accepted

- **WHEN** a theme declares a selector radius of `2rem`
- **THEN** validation accepts it (radius range is 0–2rem)

#### Scenario: Invalid color rejected

- **WHEN** a theme contains a color value that is not a 6-digit hex string
- **THEN** validation rejects the theme and no CSS is produced from it

### Requirement: Compilation to Flux CSS variables

The theme engine SHALL compile a theme into CSS custom properties consumed by Flux and Tailwind v4: `--color-accent`, `--color-accent-content`, `--color-accent-foreground`, `--color-zinc-50` through `--color-zinc-950`, status palette ramps for blue/sky/green/emerald/amber/yellow/red/rose derived from declared status seeds, `--radius-*`, `--spacing`, and `--font-sans`, emitted for `:root` and `.dark` scopes or scoped to a preview container. Compiled CSS SHALL be built only from validated theme values so no user input can inject arbitrary CSS.

#### Scenario: Full variable set emitted

- **WHEN** a complete theme is compiled
- **THEN** the output defines the accent trio, all 11 zinc steps, radius scale, spacing, and font for both the light and dark scopes

#### Scenario: Status seed produces a full ramp

- **WHEN** a theme declares an info seed of `#7cc8ff`
- **THEN** the compiled output re-declares the blue and sky palettes as full 50–950 ramps containing that seed at its nearest lightness step

#### Scenario: Scoped preview compilation

- **WHEN** a theme is compiled with a container scope for live preview
- **THEN** the variables are emitted under that selector (with a matching `.dark` variant) instead of `:root` and apply only within the container

### Requirement: Dark scheme auto-synthesis

A theme SHALL carry a dark-mode policy of `auto` or `manual`. Under `auto`, any dark-scheme value not explicitly set SHALL be synthesized from its light-scheme counterpart by preserving oklch hue, reducing chroma, and inverting lightness, then clamping into the band appropriate for the slot (surfaces land in the dark band, text in the light band, accent in the dark-mode accent band); synthesized foreground/background pairs SHALL be nudged until they meet a contrast ratio of at least 4.5:1. Under `manual`, all dark-scheme values SHALL be stored and emitted exactly as declared.

#### Scenario: Light-only theme gains a dark side

- **WHEN** a theme declares only light-scheme values under the auto policy
- **THEN** compiled dark-scheme output contains synthesized surfaces, text colors, and accent trio, and every synthesized text-on-surface pairing meets at least 4.5:1 contrast

#### Scenario: Manual dark values preserved

- **WHEN** a theme under the manual policy declares a dark accent of `#e2fd00`
- **THEN** the compiled dark scope contains exactly `#e2fd00` for the accent, with no synthesis applied

### Requirement: Share-link codec with legacy upcast

A theme SHALL encode to a URL-safe share code as base64url(zlib(JSON)) and decode back losslessly. Legacy v1 codes (parametric token shape) SHALL be accepted on decode and upcast to the v2 model by compiling the legacy theme once and snapshotting the emitted values. Codes exceeding the length cap, containing invalid characters, or failing decompression or validation SHALL be rejected.

#### Scenario: v2 round-trip

- **WHEN** a v2 theme is encoded and the code is decoded
- **THEN** the resulting theme equals the original, including explicit ramp steps and foreground colors

#### Scenario: Legacy code upcast

- **WHEN** a v1 share code created by the previous generator is decoded
- **THEN** it yields a v2 theme whose compiled CSS matches what the v1 theme rendered

#### Scenario: Malformed code rejected

- **WHEN** a code is oversized, has invalid characters, or does not inflate to valid theme JSON
- **THEN** decode returns no theme and no error is raised to the user beyond import failure handling

### Requirement: daisyUI theme import

The engine SHALL accept a daisyUI theme-generator share code or daisyUI theme JSON and map it into the declarative model: `base-100/200/300` to light neutral steps 50/100/200 (with the ramp seeded from base-100's tint), `base-content` to the light body-text step, `neutral` and `neutral-content` to dark-scheme surface and text slots, `primary` to the light accent, `primary-content` to the light accent-foreground, `info/success/warning/error` to status seeds, `radius-selector/field/box` to radius values, and `size-field` to spacing. daisyUI fields with no Flux equivalent (secondary, accent roles, border width, depth, noise) SHALL be dropped, and the import SHALL report which fields were dropped. Dark-scheme values not derivable from `neutral`/`neutral-content` SHALL be auto-synthesized.

#### Scenario: Silk theme import

- **WHEN** the daisyUI "silk" share code is imported
- **THEN** the theme has warm cream surfaces (zinc 50–200), a near-black indigo accent, an explicit lime accent-foreground, a 2rem selector radius, pastel status seeds, and a synthesized dark scheme, and the import report lists secondary, accent, border, depth, and noise as dropped

#### Scenario: Invalid daisyUI payload rejected

- **WHEN** an import payload is neither a valid daisyUI code/JSON nor a valid v2 theme
- **THEN** the import fails without changing the current theme

### Requirement: Legacy data migration

All saved themes stored in the legacy parametric format SHALL be migrated to the declarative model by compile-snapshot: each legacy theme is compiled with the legacy compiler and the emitted values are stored as explicit v2 values, so migrated themes render identically. Built-in presets SHALL ship as precompiled v2 values.

#### Scenario: Saved theme survives migration

- **WHEN** the migration runs over a database containing legacy themes
- **THEN** every migrated theme compiles to the same CSS variable values it produced before migration

#### Scenario: Presets load as v2

- **WHEN** any built-in preset is loaded after migration
- **THEN** it is a valid v2 theme and previews identically to its pre-migration appearance
