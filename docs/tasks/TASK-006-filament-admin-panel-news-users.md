# TASK-006: Filament Admin Panel for News and Users

## RU

### Цель
Добавить отдельную Filament-админку для управления новостями и пользователями, включая контроль доступа через блокировку аккаунта.

### Что сделано в feature/admin
- Установлен и подключен Filament.
- Добавлен AdminPanelProvider c панелью admin на пути /admin.
- Созданы ресурсы NewsResource и UserResource со страницами list/create/edit.
- Настроены схемы форм и таблиц для News/Users.
- Добавлены фильтры в таблице News: soft-deletes, статус, диапазон дат публикации, публикации за сегодня.
- Добавлены inline-действия редактирования полей News в таблице.
- Добавлена миграция users.is_blocked и переключатель блокировки в Users table.
- Ограничен доступ заблокированного пользователя: вход и доступ к Filament панели.
- Добавлены локализации Filament (EN/RU).

### Ключевые технические решения
- Конфигурация панели: app/Providers/Filament/AdminPanelProvider.php.
- Ресурсы News: app/Filament/Resources/News/*.
- Ресурсы Users: app/Filament/Resources/Users/*.
- Фабрика inline-экшенов: app/Filament/Tables/Actions/InlineFieldEditActionFactory.php.
- Проверка доступа в панель: App\Models\User::canAccessPanel().
- Блокировка на логине: App\Http\Requests\Auth\LoginRequest::authenticate() с is_blocked = false.
- Миграция: database/migrations/2026_03_02_121500_add_is_blocked_to_users_table.php.

### Критерии готовности (DoD)
- /admin доступен авторизованным и незаблокированным пользователям.
- CRUD в админке для News и Users работает end-to-end.
- Фильтры и табличные действия News работают корректно.
- Флаг is_blocked фактически ограничивает доступ к системе.

## EN

### Goal
Introduce a dedicated Filament admin panel for managing News and Users, including account blocking as an access-control mechanism.

### Delivered in feature/admin
- Filament installed and integrated.
- AdminPanelProvider added with admin panel at /admin.
- NewsResource and UserResource added with list/create/edit pages.
- News/Users forms and tables configured.
- News table filters added: soft-deletes, status, publication date range, published today.
- Inline News table field edit actions added.
- users.is_blocked migration and toggle column added.
- Blocked users restricted from login and Filament panel access.
- Filament EN/RU translations added.

### Key Technical Notes
- Panel config: app/Providers/Filament/AdminPanelProvider.php.
- News resources: app/Filament/Resources/News/*.
- Users resources: app/Filament/Resources/Users/*.
- Inline action factory: app/Filament/Tables/Actions/InlineFieldEditActionFactory.php.
- Panel access gate: App\Models\User::canAccessPanel().
- Login guard: App\Http\Requests\Auth\LoginRequest::authenticate() with is_blocked = false.
- Migration: database/migrations/2026_03_02_121500_add_is_blocked_to_users_table.php.

### Definition of Done (DoD)
- /admin is available for authenticated non-blocked users.
- Admin CRUD for News and Users works end-to-end.
- News filters and table actions behave correctly.
- is_blocked effectively restricts access.
