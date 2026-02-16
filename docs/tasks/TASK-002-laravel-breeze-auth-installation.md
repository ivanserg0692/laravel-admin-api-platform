# TASK-002: Laravel Breeze Authentication Setup

## RU

### Цель
Установить и включить стандартную веб-авторизацию Laravel Breeze (Blade) в проекте Laravel 12.

### Постановка задачи
На основе изменений из коммитов:
- `107b431a901b86e0fc2155d78b927bedaa80b6f4` (установка и подключение auth-слоя Breeze)
- `e1e850ce1a0988a958a8b169bed9e1eabc30d544` (обновление до Laravel 12 и актуализация зависимостей и документации)

необходимо формализовать задачу на разворачивание полной аутентификации через Breeze:
- регистрация, логин, логаут
- сброс и подтверждение пароля
- верификация email
- защищенные маршруты `/dashboard` и `/profile`

### Функциональные требования
- Подключен пакет `laravel/breeze` (для Laravel 12 использовать ветку `^2.0`).
- В проекте присутствуют маршруты auth (`routes/auth.php`) и их подключение в `routes/web.php`.
- Добавлены контроллеры и request-классы Breeze для auth-flow.
- После входа пользователь перенаправляется на `/dashboard` (`RouteServiceProvider::HOME`).
- Профиль пользователя доступен только под `auth` middleware (`/profile` edit/update/delete).
- Фронтенд-поддержка Breeze включена:
  - `alpinejs` в `resources/js/app.js`
  - `@tailwindcss/forms` в `tailwind.config.js`
  - обновленный pipeline Vite/Tailwind.

### Конфигурация и шаги установки
Для нового окружения:
- `composer require laravel/breeze --dev`
- `php artisan breeze:install blade`
- `npm install`
- `npm run build` (или `npm run dev` для локальной разработки)
- `php artisan migrate`

Для Laravel 12 использовать совместимые версии:
- PHP `^8.3`
- Laravel `^12.x`
- Breeze `^2.0`

### Критерии готовности (DoD)
- Доступны страницы и сценарии: register, login, forgot/reset password, verify email.
- Логин/логаут работают, сессия корректно регенерируется.
- `/dashboard` открыт только для `auth + verified`.
- `/profile` открыт только для авторизованных пользователей.
- Проходят feature-тесты auth/profile (минимум: `tests/Feature/Auth/*`, `tests/Feature/ProfileTest.php`).
- README и внутренние документы отражают использование Breeze на Laravel 12.

### Технические заметки
- Не изменять вручную логику Breeze, если это не требуется бизнес-логикой.
- Для ограничений логина использовать встроенный `RateLimiter` в `LoginRequest`.
- Проверять, что UI-ссылки ведут на `/dashboard`, а не на legacy `/home`.

## EN

### Goal
Install and enable standard Laravel Breeze web authentication (Blade) in a Laravel 12 project.

### Task Description
Based on changes in:
- `107b431a901b86e0fc2155d78b927bedaa80b6f4` (Breeze auth layer installation and wiring)
- `e1e850ce1a0988a958a8b169bed9e1eabc30d544` (Laravel 12 upgrade and dependency/docs alignment)

define and complete full Breeze authentication setup:
- register, login, logout
- password reset/confirmation
- email verification
- protected `/dashboard` and `/profile` routes

### Functional Requirements
- `laravel/breeze` is installed (use `^2.0` on Laravel 12).
- Auth routes exist (`routes/auth.php`) and are required from `routes/web.php`.
- Breeze auth controllers and request classes are present and used.
- Post-login redirect points to `/dashboard` (`RouteServiceProvider::HOME`).
- User profile management is protected by `auth` middleware (`/profile` edit/update/delete).
- Breeze frontend support is enabled:
  - `alpinejs` in `resources/js/app.js`
  - `@tailwindcss/forms` in `tailwind.config.js`
  - updated Vite/Tailwind pipeline.

### Setup Steps
For a clean environment:
- `composer require laravel/breeze --dev`
- `php artisan breeze:install blade`
- `npm install`
- `npm run build` (or `npm run dev` locally)
- `php artisan migrate`

Use Laravel 12 compatible versions:
- PHP `^8.3`
- Laravel `^12.x`
- Breeze `^2.0`

### Definition of Done (DoD)
- Pages and flows are available: register, login, forgot/reset password, verify email.
- Login/logout works and session regeneration is correct.
- `/dashboard` is protected by `auth + verified`.
- `/profile` is accessible only to authenticated users.
- Auth/profile feature tests pass (at minimum: `tests/Feature/Auth/*`, `tests/Feature/ProfileTest.php`).
- README and internal docs reflect Breeze usage on Laravel 12.

### Technical Notes
- Avoid manual changes to Breeze internals unless explicitly required by business logic.
- Keep login throttling through built-in `RateLimiter` in `LoginRequest`.
- Ensure UI navigation points to `/dashboard` instead of legacy `/home`.
