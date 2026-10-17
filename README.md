# design-lab

A **design system / component playground** for Flux Pro on Livewire 4 and Laravel 13, running
entirely in Docker.

The app catalogues Flux components, theme tokens and layout patterns around a theme
generator at `/`. There is no business domain and no authentication — it exists so
components can be exercised, compared and tweaked in isolation, and every page is
publicly accessible.

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
| Tests | PHPUnit 12, PHPStan, Pint |

## Running it

```bash
docker compose up -d
```

| Service | URL / port |
| --- | --- |
| App (nginx) | http://localhost:8003 |
| Vite dev server | http://localhost:5175 |
| MySQL | `localhost:3310` |

## Common commands

```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app composer test     # pint + phpstan + phpunit
docker compose exec app php artisan flux:publish <component>   # customise one component
```

## Layout of the lab

| Route | Contents |
| --- | --- |
| `/components/forms` | inputs, selection controls, pickers, tags, file upload |
| `/components/actions` | buttons, menus, command palette, popovers, badges, icons |
| `/components/data` | tabs, accordion, live-filtered table, timeline, chart |
| `/components/overlays` | modals, toasts, callouts, tooltips |
| `/components/editor` | `flux:editor` and every toolbar control (Pro) |
| `/components/composer` | `flux:composer` and its slots (Pro) |
| `/components/kanban` | `flux:kanban` columns, headers and cards (Pro) |
| `/components/carousel` | `flux:carousel` slides, arrows, indicators (Pro) |
| `/components/phone` | `flux:phone` country and number input (Pro) |
| `/components/calendar` | `flux:calendar` month view, compared to `flux:date-picker` (Pro) |
| `/components/charts` | the `flux:chart` family as a preset gallery — line, bar and pie presets, plus a reference tab (Pro) |
| `/` | the theme generator — DaisyUI-style theme creator: accents, neutral zinc ramp, radius, density, fonts, presets, share URL, CSS export, and global activation |
| `/theme` | appearance, accent, palette, radius, type scale, spacing |
| `/layouts` | cards, nav lists, separators, shell anatomy |

Pages live in `resources/views/pages/` and are wrapped automatically by
`<x-layouts::app>` through Livewire's `component_layout` config — do **not** add the layout
wrapper yourself or the component will render multiple root elements.

## How examples are written

Every example on a catalog page is a **fragment** under `resources/views/lab/examples/<family>/`,
rendered through the `x-lab.example` helper:

```blade
<x-lab.example src="lab.examples.forms.input-group"
               title="Input groups"
               subheading="Weld several controls into one field." />
```

`x-lab.example` renders the fragment as a live preview **and** reads the same file from disk to
show its source underneath with a copy button. One file, so the snippet can never drift from the
markup that produced it. A couple of rules follow from that:

- **Put the markup in the fragment, not inline in the page.** Inline markup gets no snippet.
- **Anonymous components have their own scope**, so a fragment cannot see the page's properties.
  Anything it needs goes through the `data` prop:
  `<x-lab.example :data="['usage' => $usage]" src="lab.examples.charts.axes" />`.
- Use `snippet="false"` for chrome that is not a component demo (loops, readouts).
- Fragments live under `resources/views/`, which Tailwind's `@source '../views'` already scans.
  If you ever move example markup into PHP under `app/`, add `@source '../../app'` or the utility
  classes will silently disappear.

## The charts page

`/components/charts` is a **preset gallery** rather than a flat list, because that is how the
official page at <https://fluxui.dev/charts> groups its presets. A `flux:tab.group` switches
between four families, and the first three mirror the official grouping exactly — line
(Line, Area, Points, Sparkline), bar (Bars, Grouped, Stacked, Horizontal) and pie
(Pie, Donut, Ring). The fourth, `reference`, holds the attributes that do not belong to a
family. Every preset is its own fragment file under `lab/examples/charts/`, so it gets the
usual file-backed snippet.

`lab/examples/charts/conflicts.blade.php` is prose rather than markup, and renders with
`snippet="false"`. It records the three chart props the engine accepts and then ignores —
`tick-step`, `target-tick-count` and `flux:chart.legend`'s `field`/`format` — plus the pie
rules and the one attribute (`scale`) that throws. A test asserts none of them is used as an
attribute anywhere on the page, so a preset cannot quietly do nothing.

## The theme generator

`/` is the theme generator, a DaisyUI-style theme creator tailored for Flux UI and Tailwind CSS v4.

- **Global activation:** activating a theme marks it in the database and renders an unlayered
  `<style id="lab-theme">` in `resources/views/partials/theme.blade.php`. This overrides Tailwind
  `@theme` values and applies across **every page of the app** — the generator and every
  catalog page.
- **OKLCH palette synthesis:** changing base hue and chroma generates a complete 11-step neutral
  ramp (`--color-zinc-50` through `--color-zinc-950`), following Tailwind v4's lightness curve.
  Accents generate readable foregrounds and text contrast automatically.
- **Export for sibling projects:** copy the Tailwind v4 `@theme` CSS block from the UI or run:
  ```bash
  docker compose exec app php artisan theme:export ocean --path=resources/css/theme-ocean.css
  ```
  Paste the block into `lazonabasketball` or `adcom-sites` right after the Flux CSS import.
- **Shareable state:** theme parameters serialize to compact URL queries (`?theme=...`) via
  compressed base64url payloads.

## Notes

- **Ports are deliberately offset.** The sibling projects `lazonabasketball` and `adcom-sites`
  already use 8000/8002, 5173/5174 and 3306–3309. If you change 8003, 5175 or 3310, update
  `docker-compose.yml` **and** `vite.config.js` together.
- **Vite is pinned with `strictPort`.** The starter kit ships without a port, which would
  default to 5173 and collide with the existing dev server.
- **Flux Pro credentials** come from `FLUX_USERNAME` / `FLUX_PASSWORD` in `.env`. The
  entrypoint re-applies them before every `composer install`, because
  `php artisan flux:activate` stores the license in a *global* `auth.json` that does not
  survive an image rebuild.
- **Lockfiles are committed** (`composer.lock`, `package-lock.json`) so the verified Flux and
  Livewire versions are reproducible. This differs from the sibling projects, which ignore them.
- **Livewire's multi-root-element warning is switched off** in
  `AppServiceProvider::disableLivewireRootElementHeuristic()`. It re-parses the mount-stage HTML
  with libxml2, which mis-nests the markup Flux's Blaze components emit, so any page combining
  `flux:editor` or `flux:phone` with other content is wrongly reported as having two roots. The
  check is debug-only diagnostics and is removed defensively if Livewire's internals change.
- **`flux:editor` and `flux:phone` pages hydrate on the client.** Blaze defers content built
  entirely from Blaze components, so those two routes ship only the shell in the initial HTML.
  The test suite therefore asserts their contents against the rendered Livewire component rather
  than the raw HTTP response.
- **The icon gallery paints a window.** It searches all 321 icons but renders at most 96 per
  request; resolving every icon in one pass exhausts the PHP memory limit.

