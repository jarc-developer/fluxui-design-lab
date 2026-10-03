# design-lab

A **design system / component playground** for Flux Pro on Livewire 4 and Laravel 13, running
entirely in Docker.

The app catalogues Flux components, theme tokens and layout patterns. There is no business
domain — it exists so components can be exercised, compared and tweaked in isolation.

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

Seeded accounts (after `php artisan db:seed`):

- `admin@design-lab.test` — role `admin`
- `viewer@design-lab.test` — role `viewer`

Roles are `admin`, `editor` and `viewer`; permissions are `view catalog`, `edit catalog` and
`manage theme`.

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
| `/theme` | appearance, accent, palette, radius, type scale, spacing |
| `/layouts` | cards, nav lists, separators, auth shells, shell anatomy |

Pages live in `resources/views/pages/` and are wrapped automatically by
`<x-layouts::app>` through Livewire's `component_layout` config — do **not** add the layout
wrapper yourself or the component will render multiple root elements.

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
