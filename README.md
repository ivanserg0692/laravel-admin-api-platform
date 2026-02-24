# Learning Laravel in 2026

Short project based on Laravel 12 + Breeze (Blade + Tailwind) with Sail for local Docker development.

## Stack

- PHP `^8.3`
- Laravel `^12.10`
- Laravel Breeze `^2.0`
- Livewire `^4.1`
- Alpine.js `^3.4.2`
- Vite `^5`
- Tailwind CSS `^4`
- MySQL + Redis + Mailpit (via Sail)

## Quick Start

From `app/` run:

```bash
cp .env.example .env
composer install
npm install
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
npm run dev
```

Open:

- App: `http://localhost`
- Mailpit UI: `http://localhost:8025`

## Useful Commands

```bash
./vendor/bin/sail ps
./vendor/bin/sail down
./vendor/bin/sail down -v   # removes volumes (DB data loss)
./vendor/bin/sail artisan test
npm run build
```

## Routes Snapshot

- `/` public welcome page
- `/dashboard` auth + verified
- `/profile` auth only (edit/update/delete)
- auth routes from Breeze: login/register/password reset/email verification
- `/api/user` protected by `auth:sanctum`

## Small Docs

- `docs/PROJECT_OVERVIEW.md`
- `docs/DEV_NOTES.md`

## Recent Task Updates

- `TASK-001` Cloudflare Turnstile for registration:
  - added captcha widget in registration form
  - added backend token validation and UI error handling
  - details: [TASK-001-cloudflare-turnstile-captcha.md](docs/tasks/TASK-001-cloudflare-turnstile-captcha.md)
  - MR: [#1](https://github.com/ivanserg0692/laravel-admin-api-platform/pull/1)

- `TASK-002` Breeze authentication setup for Laravel 12:
  - enabled full auth flow (register/login/logout/reset/verify)
  - protected `/dashboard` and `/profile` routes
  - aligned frontend auth stack (Blade + Tailwind + Alpine)
  - details: [TASK-002-laravel-breeze-auth-installation.md](docs/tasks/TASK-002-laravel-breeze-auth-installation.md)

- `TASK-003` Homepage and dashboard UI alignment:
  - unified visual style between public and authenticated areas
  - improved dark mode consistency and component styling
  - added theme toggle and component documentation (RU/EN)
  - details: [TASK-003-homepage-ui-and-dashboard-style-alignment.md](docs/tasks/TASK-003-homepage-ui-and-dashboard-style-alignment.md)
  - MR: [#2](https://github.com/ivanserg0692/laravel-admin-api-platform/pull/2)

- `TASK-004` News CRUD UI and Livewire integration:
  - converted news index flow to a Livewire root component
  - added reactive search and query-based sorting with page preservation
  - extracted list query logic into a dedicated service and updated component docs/tests
  - details: [TASK-004-ai-crud-layer-news-admin.md](docs/tasks/TASK-004-ai-crud-layer-news-admin.md)
  - MR: [#3](https://github.com/ivanserg0692/laravel-admin-api-platform/pull/3)