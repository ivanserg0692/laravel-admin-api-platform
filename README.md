# Learning Laravel in 2026

Short project based on Laravel 12 + Breeze (Blade + Tailwind) with Sail for local Docker development.

## Stack

- PHP `^8.3`
- Laravel `^12.10`
- Laravel Breeze `^2.0`
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
