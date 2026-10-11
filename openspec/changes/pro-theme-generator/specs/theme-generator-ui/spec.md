# Spec Delta

## Purpose

Defines the `/theme/generator` page experience: a daisyUI-style semantic slot editor over the declarative theme model, with suggest/fill affordances, a dark auto/manual toggle, import and export flows, a scoped live preview, and saved-theme management.

## ADDED Requirements

### Requirement: Semantic slot editor

The generator SHALL present theme values as semantic slots grouped as Accent & text, Surfaces, Text, Statuses, Shape (radius), and Type (spacing & font). Each color slot SHALL offer a derived suggestion as its default and SHALL allow overriding it with an explicit custom value, with a per-slot reset back to the suggestion. Every edit SHALL update the live preview. The accent-foreground slot SHALL be directly editable in both schemes (it is not limited to auto-computed readable colors).

#### Scenario: Custom accent foreground

- **WHEN** the user sets the light accent-foreground slot to `#e2fd00` while the accent is near-black
- **THEN** the preview's primary buttons render lime text on the dark accent and the value is stored on the theme

#### Scenario: Reset to suggestion

- **WHEN** the user resets a customized surface slot
- **THEN** the slot returns to the suggested value and the override is removed from the theme

#### Scenario: Ramp fill affordance

- **WHEN** the user asks to fill the neutral ramp from a hue and chroma
- **THEN** all 11 neutral steps are populated with the generated values as explicit slot values, each remaining individually editable afterwards

### Requirement: Dark scheme editing

The generator SHALL expose a dark-mode policy toggle (`auto` / `manual`). Under `auto`, dark slots SHALL display their synthesized values and be promoted to explicit custom values on first edit. Under `manual`, all dark slots SHALL be directly editable and stored.

#### Scenario: Auto dark shows synthesized values

- **WHEN** a theme uses the auto policy and the user inspects the dark surface slots
- **THEN** the slots display the synthesized dark colors and the preview's dark mode reflects them

#### Scenario: Manual dark edit stored

- **WHEN** the user switches to manual and sets a dark surface to a custom color
- **THEN** that exact value is stored and emitted for the dark scheme

### Requirement: Free-form radius controls

Radius controls SHALL accept any value from 0 to 2rem, with quick presets including a pill option (2rem) for selectors.

#### Scenario: Pill selectors

- **WHEN** the user sets the selector radius to 2rem
- **THEN** checkboxes and selectors in the preview render as pills

### Requirement: Import modal

The import flow SHALL accept a daisyUI theme-generator share code or URL, a daisyUI theme JSON, a v2 theme JSON, or a share code (current or legacy v1). A successful import SHALL show which daisyUI-only fields were dropped (secondary, accent roles, border, depth, noise). A failed import SHALL report an error and leave the current theme unchanged.

#### Scenario: Paste a daisyUI link

- **WHEN** the user pastes a full daisyUI theme-generator URL into the import field
- **THEN** the theme is extracted, mapped, loaded into the editor, and a report lists the dropped fields

#### Scenario: Invalid payload

- **WHEN** the user submits an unrecognized payload
- **THEN** an error is shown and the current editor state is unchanged

### Requirement: Export modal

The export flow SHALL produce Tailwind CSS v4 output (an `@theme` block plus a `.dark` layer, ready to paste after the Flux import) and the theme's JSON token form, either copyable to the clipboard. Exported JSON SHALL re-import to an identical theme.

#### Scenario: JSON round-trip through export/import

- **WHEN** the user exports JSON and imports that same JSON
- **THEN** the resulting theme matches the exported theme exactly

### Requirement: Scoped live preview

The preview SHALL compile the current editor state scoped to the preview container only, SHALL support toggling between the light and dark scheme inside the preview, and SHALL load the selected font for accurate rendering.

#### Scenario: Dark preview is scoped

- **WHEN** the user toggles the preview to the dark scheme
- **THEN** only the preview container switches to dark; the surrounding app keeps the visitor's scheme

### Requirement: Saved themes and activation

The generator SHALL list saved themes, load one into the editor, save or save-as, activate a theme so it applies to every page, and delete any theme that is not active. Exactly one theme SHALL be active at a time.

#### Scenario: Activation applies site-wide

- **WHEN** the user activates a saved theme
- **THEN** that theme's compiled CSS is applied to all pages and it is the only active theme

#### Scenario: Active theme protected

- **WHEN** the user attempts to delete the active theme
- **THEN** the deletion is refused with an explanatory message
