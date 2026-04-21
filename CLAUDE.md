# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Reset migrations and reseed the database
php artisan migrate:fresh --seed

# Run a single test
php artisan test --filter TestName

# Run all tests
php artisan test

# Start dev server with Vite (asset compilation)
npm run dev

# Build assets for production
npm run build

# Code style (Laravel Pint)
./vendor/bin/pint
```

The app runs under Laragon at `http://planeta-boy.test`. There is no built-in `php artisan serve` workflow — use Laragon's virtual host.

## Architecture

**Planeta Boy** is a Laravel 13 adult companion listing platform (similar to an escort directory). Key domain concepts:

- **Profile** (`perfis` table): A companion/escort profile linked to a `User`. Has many images, videos, services, pricing, availability, comments, and reports. Supports geolocation (Haversine-based filtering). Profiles are active/inactive and linked to a subscription plan.
- **Plan / SubscriptionRequest**: Users request a subscription plan; admins approve/reject. `PLAN_PRIORITY_ORDER` constant in `PlanoController` controls display order.
- **City**: Cities have a `featured` flag used to highlight them on the home page. Cities store lat/lng for geo-filtering.
- **SubscriberCategory**: Categorizes subscribers and can restrict access to certain services.
- **Service**: Services offered by profiles, referenced via pivot table.

### Middleware

- `age.gate` (`AgeGateMiddleware`): Applied to all public routes (home, explorar, perfil.ver, planos, faq). Checks for a `age_gate_confirmed` cookie; the cookie is set via `POST /age-gate/confirm` and lasts 30 days.
- `EnsureUserIsAdmin`: Guards all `/admin/*` routes.

### Key Controllers

| Controller | Responsibility |
|---|---|
| `HomeController` | Home page: featured cities with profile counts grouped by city |
| `ExplorarController` | Profile listing with filters (city, state, services, geolocation via Haversine) |
| `PerfilController` | View/create/edit companion profiles; comment; report; shows 4 random similar profiles (same city/state) |
| `PlanoController` | Public plans page, subscribe, cancel, "meu plano" |
| `LocationController` | Updates profile lat/lng from browser geolocation |
| `Admin/*` | Full CRUD for users, profiles, plans, subscriptions, cities, FAQs, subscriber categories |

### Frontend

- **Tailwind CSS v4** via `@tailwindcss/vite` plugin (no `tailwind.config.js` — config is in CSS).
- **Vite** for asset bundling (`vite.config.js`).
- Blade components live in `resources/views/components/`. Layout in `resources/views/layouts/`.
- The explorar page uses `IntersectionObserver`-based infinite scroll with pre-fetching, retry with exponential backoff, and AbortController (15s timeout).

### Design Files

`.pen` files (e.g., `Tony.pen`) are Pencil design files. They are **encrypted** and must only be accessed via the `pencil` MCP tools (`batch_get`, `batch_design`). Never use `Read` or `Grep` on `.pen` files.
