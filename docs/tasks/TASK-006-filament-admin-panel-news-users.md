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
- Добавлены таблицы user_tags и user_user_tag для many-to-many связи пользователей и тегов.
- Добавлено управление тегами пользователя в форме и таблице UsersResource.
- Добавлен сидер: пользователю с id=1 автоматически назначается тег admin.
- Ограничен доступ заблокированного пользователя: вход и доступ к Filament панели.
- Добавлена policy-авторизация для News:
  - `admin` и `news_admin` управляют всеми новостями;
  - `author` может изменять только свои новости (`author_id = user_id`);
  - blocked user не имеет доступа к операциям.
- Проверки прав внедрены в mutation/service слой (server-side authorize) и в UI (видимость create/edit/delete кнопок по policy).
- Добавлены локализации Filament (EN/RU).

### Ключевые технические решения
- Конфигурация панели: app/Providers/Filament/AdminPanelProvider.php.
- Ресурсы News: app/Filament/Resources/News/*.
- Ресурсы Users: app/Filament/Resources/Users/*.
- Фабрика inline-экшенов: app/Filament/Tables/Actions/InlineFieldEditActionFactory.php.
- Проверка доступа в панель: App\Models\User::canAccessPanel().
- Блокировка на логине: App\Http\Requests\Auth\LoginRequest::authenticate() с is_blocked = false.
- Миграция: database/migrations/2026_03_02_121500_add_is_blocked_to_users_table.php.
- Миграция: database/migrations/2026_03_03_000001_create_user_tags_and_user_user_tag_tables.php.
- Сидер: database/seeders/UserTagSeeder.php (tag admin -> user id=1).
- Policy: app/Policies/NewsPolicy.php.
- Регистрация policy: app/Providers/AuthServiceProvider.php.
- Server-side authorize:
  - app/Support/News/NewsCrudMutationService.php
  - app/Http/Controllers/NewsController.php
- Policy-aware UI:
  - resources/views/components/news/rows/actions.blade.php
  - resources/views/components/crud/index.blade.php
  - resources/views/components/crud/update.blade.php
  - resources/views/components/crud/show.blade.php

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
- user_tags and user_user_tag tables added for users-to-tags many-to-many mapping.
- user tag management added to UsersResource form/table.
- seeding added: user with id=1 gets admin tag automatically.
- Blocked users restricted from login and Filament panel access.
- News policy authorization added:
  - `admin` and `news_admin` can manage all news;
  - `author` can update only own news;
  - blocked users are denied.
- Permission checks applied both server-side (authorize in mutation/controller) and in UI (policy-aware action visibility).
- Filament EN/RU translations added.

### Key Technical Notes
- Panel config: app/Providers/Filament/AdminPanelProvider.php.
- News resources: app/Filament/Resources/News/*.
- Users resources: app/Filament/Resources/Users/*.
- Inline action factory: app/Filament/Tables/Actions/InlineFieldEditActionFactory.php.
- Panel access gate: App\Models\User::canAccessPanel().
- Login guard: App\Http\Requests\Auth\LoginRequest::authenticate() with is_blocked = false.
- Migration: database/migrations/2026_03_02_121500_add_is_blocked_to_users_table.php.
- Migration: database/migrations/2026_03_03_000001_create_user_tags_and_user_user_tag_tables.php.
- Seeder: database/seeders/UserTagSeeder.php (admin tag assigned to user id=1).
- Policy: app/Policies/NewsPolicy.php.
- Policy registration: app/Providers/AuthServiceProvider.php.
- Server-side authorization:
  - app/Support/News/NewsCrudMutationService.php
  - app/Http/Controllers/NewsController.php
- Policy-aware UI:
  - resources/views/components/news/rows/actions.blade.php
  - resources/views/components/crud/index.blade.php
  - resources/views/components/crud/update.blade.php
  - resources/views/components/crud/show.blade.php

### Definition of Done (DoD)
- /admin is available for authenticated non-blocked users.
- Admin CRUD for News and Users works end-to-end.
- News filters and table actions behave correctly.
- is_blocked effectively restricts access.
