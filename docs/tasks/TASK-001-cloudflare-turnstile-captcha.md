# TASK-001: Cloudflare Turnstile CAPTCHA for Registration

## RU

### Цель
Добавить защиту формы регистрации через Cloudflare Turnstile с клиентской и серверной проверкой токена.

### Постановка задачи
Необходимо реализовать CAPTCHA на странице регистрации (`/register`) для снижения количества автоматических регистраций.

В текущем коде уже используется точка интеграции:
- компонент в форме регистрации: `resources/views/auth/register.blade.php`
- blade-компонент капчи: `resources/views/components/cloudflare-captcha.blade.php`
- конфигурация Cloudflare: `config/services.php`

Задача считается постановкой на полную рабочую интеграцию, включая backend-валидацию.

### Функциональные требования
- В форме регистрации должен отображаться виджет Cloudflare Turnstile.
- При отправке формы должен передаваться токен капчи.
- На сервере должна выполняться проверка токена через Cloudflare Siteverify API.
- При невалидной/отсутствующей капче регистрация должна отклоняться.
- Пользователь должен видеть понятное сообщение об ошибке.

### Конфигурация
Использовать переменные окружения:
- `CLOUDFLARE_SITE_KEY`
- `CLOUDFLARE_SECRET_KEY`

Чтение значений должно происходить через `config/services.php`.

### Критерии готовности (DoD)
- Регистрация без валидной капчи не проходит.
- Регистрация с валидной капчей проходит успешно.
- Ошибки капчи корректно отображаются в UI.
- Логика проверки покрыта тестами (минимум: success/fail сценарии).
- Решение работает в локальном окружении Sail.

### Технические заметки
- Не использовать `env()` напрямую в runtime-коде (кроме `config/*.php`).
- Учесть сценарий отсутствия ключей в окружении (dev/prod).

## EN

### Goal
Add registration form protection with Cloudflare Turnstile, including client-side widget and server-side token validation.

### Task Description
Implement CAPTCHA on the registration page (`/register`) to reduce automated signups.

The current codebase already has integration points:
- component usage in registration form: `resources/views/auth/register.blade.php`
- captcha Blade component: `resources/views/components/cloudflare-captcha.blade.php`
- Cloudflare configuration: `config/services.php`

This task defines the full production-ready integration, including backend validation.

### Functional Requirements
- Cloudflare Turnstile widget must be shown on the registration form.
- Captcha token must be submitted with the form.
- Token must be verified on the server via Cloudflare Siteverify API.
- Registration must be rejected when captcha is missing/invalid.
- User must receive a clear validation error message.

### Configuration
Use environment variables:
- `CLOUDFLARE_SITE_KEY`
- `CLOUDFLARE_SECRET_KEY`

Values must be read via `config/services.php`.

### Definition of Done (DoD)
- Registration fails without a valid captcha.
- Registration succeeds with a valid captcha.
- Captcha errors are correctly displayed in UI.
- Validation logic is covered by tests (at least success/failure flows).
- Works in local Sail environment.

### Technical Notes
- Do not use `env()` directly in runtime code (except inside `config/*.php`).
- Handle missing keys in environment (dev/prod).
