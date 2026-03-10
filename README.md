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

## MinIO mc Runner

Open the MinIO client runner shell from `app/`:

```bash
docker compose run --rm mc
```

The `local` alias is configured automatically for the runner.

## Routes Snapshot

- `/` public welcome page
- `/dashboard` auth + verified
- `/profile` auth only (edit/update/delete)
- auth routes from Breeze: login/register/password reset/email verification
- `/api/user` protected by `auth:sanctum`

## Small Docs

- `docs/PROJECT_OVERVIEW.md`
- `docs/DEV_NOTES.md`

## Project Capabilities (EN)

- Public web area + Breeze authentication flow (register/login/logout/reset/verify).
- Profile management for authenticated users.
- News CRUD in the web app with Livewire-based action flow.
- Dedicated Filament admin panel at `/admin`.
- Admin resources for News and Users (list/create/edit), filters, and table actions.
- User blocking via `is_blocked` for login/admin access restriction.
- RBAC foundation via user tags (`user_tags` + `user_user_tag`).
- News permissions via policy:
  - `admin` / `news_admin`: full news management.
  - `author`: update own news only.

## Возможности Проекта (RU)

- Публичная часть сайта + аутентификация Breeze (register/login/logout/reset/verify).
- Управление профилем для авторизованных пользователей.
- News CRUD в web-части с Livewire action-flow.
- Отдельная Filament-админка по адресу `/admin`.
- Админ-ресурсы для News и Users (list/create/edit), фильтры и табличные действия.
- Блокировка пользователя через `is_blocked` (ограничение входа и доступа в админку).
- База для RBAC через теги пользователей (`user_tags` + `user_user_tag`).
- Права на News через policy:
  - `admin` / `news_admin`: полный доступ к управлению новостями.
  - `author`: редактирование только своих новостей.
 

## Project screenshots 
 
<img width="1421" height="606" alt="image" src="https://github.com/user-attachments/assets/6fd85466-799c-4804-adc3-57ea4296b7da" />
<img width="1251" height="409" alt="image" src="https://github.com/user-attachments/assets/7567e58a-3b32-451b-84ba-6757ed4a44b2" />
<img width="1280" height="610" alt="image" src="https://github.com/user-attachments/assets/3724af89-8b61-4172-8d28-0d964a271ca8" />


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

- `TASK-005` Full News CRUD migration to Livewire handlers:
  - moved News CRUD mutation/UI/validation logic into dedicated services
  - added page-level Livewire update flow with redirect + success flash after save
  - removed legacy JS news modal action handlers and unified Blade/Livewire modal flow
  - details: [TASK-005-news-full-livewire-migration.md](docs/tasks/TASK-005-news-full-livewire-migration.md)
  - MR: [#4](https://github.com/ivanserg0692/laravel-admin-api-platform/pull/4)

- `TASK-006` Filament admin panel for News and Users:
  - installed and configured Filament with dedicated /admin panel provider
  - added News/User resources (list/create/edit), table actions, and News filters
  - added user tags (`user_tags` + `user_user_tag`) as RBAC base and admin tag seeding for user `id=1`
  - added News policy-based permissions (`admin`/`news_admin`/`author`) with backend authorization + policy-aware UI
  - implemented user blocking via is_blocked with login/panel access restriction
  - details: [TASK-006-filament-admin-panel-news-users.md](docs/tasks/TASK-006-filament-admin-panel-news-users.md)
  - MR: [#5](https://github.com/ivanserg0692/laravel-admin-api-platform/pull/5)

- `TASK-007` News events and notifications pipeline:
  - formalized backend flow `NewsCreatedEvent -> SendNewsCreatedNotification -> NewsCreated`
  - ensured queued async delivery with `ShouldQueue` and `mail + database` channels
  - stabilized database payload contract for consumers (`news_id`, `message`, `url`)
  - documented queue runtime expectations (`queue:work`, `queue:restart`, `queue:failed`)
  - details: [TASK-007-news-events-and-notifications-pipeline.md](docs/tasks/TASK-007-news-events-and-notifications-pipeline.md)
  - MR: [#6](https://github.com/ivanserg0692/laravel-admin-api-platform/pull/6)

- `TASK-008` News export pipeline with Filament, queue batches, and MinIO versioning:
  - added dedicated Filament exports screen and export launch action for News CSV generation
  - implemented async chunked export pipeline with `GenerateNewsExportFileJob` and `FinalizeNewsExportFileJob`
  - added `job_batches` and `news_exports` persistence with progress/status tracking in admin UI
  - integrated MinIO/S3 storage, object version-aware downloads, and `mc` runner support
  - details: [TASK-008-news-export-pipeline-and-minio-versioning.md](docs/tasks/TASK-008-news-export-pipeline-and-minio-versioning.md)
  - MR: [#7](https://github.com/ivanserg0692/laravel-admin-api-platform/pull/7)
