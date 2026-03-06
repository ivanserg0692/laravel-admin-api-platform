# TASK-007: News Events and Notifications Pipeline

## RU

### Цель
Зафиксировать и довести до production-ready основной backend-поток уведомлений о новостях: событие -> listener -> queued notification -> доставка в database/mail.

### Постановка задачи
Ключевая суть задачи — не UI, а надежная событийная модель уведомлений для News.  
UI-часть уведомлений рассматривается как вторичный слой, который только потребляет результат backend pipeline.

Необходимо:
- формализовать жизненный цикл `NewsCreatedEvent`;
- зафиксировать контракт listener-а и notification payload;
- обеспечить асинхронную доставку через очередь и предсказуемое поведение при ошибках.

### Функциональные требования (Primary Scope)
- При создании новости корректно диспатчится `NewsCreatedEvent`.
- Listener `SendNewsCreatedNotification` получает событие и инициирует отправку уведомления.
- Notification `App\Notifications\NewsCreated` работает по каналам:
  - `database`;
  - `mail`.
- Отправка уведомления выполняется асинхронно через очередь (`ShouldQueue`).
- В `database_notifications` сохраняется валидный payload:
  - `news_id`,
  - `message`,
  - `url` (для перехода к новости).

### Технические требования
- Pipeline должен быть построен на Laravel Events/Listeners/Notifications.
- Notification обязана оставаться queue-aware (`ShouldQueue` + `Queueable`).
- Payload должен быть стабильным и обратно совместимым для UI-слоя.
- Формирование `url` должно вести на `route('news.show', $news)`.
- Не переносить orchestration событий в UI/JS слой.

### Надежность и эксплуатация
- Поток должен быть совместим с `php artisan queue:work`.
- После деплоя/изменений в notification-flow воркер перезапускается через `php artisan queue:restart`.
- Ошибки очереди диагностируются через `queue:failed`.
- Наличие неработающего воркера не должно ломать создание новости (асинхронная доставка).

### Secondary Scope (UI)
- UI уведомлений (desktop/mobile) остаётся потребителем `database_notifications`.
- UI не должен определять бизнес-правила доставки.
- Отображение/клик/read/delete допускаются как второстепенные задачи.

### Архитектурные инварианты
- Source of truth для генерации уведомления — backend событие `NewsCreatedEvent`.
- Listener отвечает за маршрутизацию к notification, а не Blade/JS.
- Контракт payload (`news_id`, `message`, `url`) централизован в `NewsCreated`.

### Критерии готовности (DoD)
- `NewsCreatedEvent` стабильно диспатчится на create-flow новости.
- Listener отправляет `NewsCreated` без синхронной блокировки UI-потока.
- Очередь обрабатывает notification job end-to-end.
- В таблице `database_notifications` появляется корректная запись с ожидаемым payload.
- Переход по `url` из consumer-слоя (UI) открывает страницу новости.

### Ограничения
- Не смещать основную цель задачи в верстку/стили.
- Не дублировать backend логику доставки в frontend.
- Не ломать существующие каналы уведомлений (`mail`, `database`) без явного решения.

### Артефакты
Ожидаемые области изменений/верификации:
- Events/Listeners:
  - `app/Events/NewsCreatedEvent.php`
  - `app/Listeners/SendNewsCreatedNotification.php`
- Notifications:
  - `app/Notifications/NewsCreated.php`
- Create flow integration:
  - `app/Support/News/NewsCrudMutationService.php` (или актуальная точка dispatch)
- Queue runtime / ops:
  - `.env` (`QUEUE_CONNECTION`)
  - `php artisan queue:work`, `queue:restart`, `queue:failed`
- Secondary UI consumers:
  - `app/Livewire/Notifications/Dropdown.php`
  - `resources/views/livewire/notifications/*`

## EN

### Goal
Establish a production-ready backend notification pipeline for news creation: event -> listener -> queued notification -> database/mail delivery.

### Task Description
The core objective is backend event-driven notifications, not UI.  
UI is treated as a secondary consumer layer of the backend pipeline output.

Required:
- formalize the `NewsCreatedEvent` lifecycle;
- lock listener and notification payload contracts;
- ensure async queue delivery with predictable failure handling.

### Functional Requirements (Primary Scope)
- `NewsCreatedEvent` is dispatched reliably when a news item is created.
- `SendNewsCreatedNotification` listens to the event and triggers notification dispatch.
- `App\Notifications\NewsCreated` delivers through:
  - `database`;
  - `mail`.
- Notification delivery is asynchronous (`ShouldQueue`).
- `database_notifications` stores a valid payload:
  - `news_id`,
  - `message`,
  - `url` (for opening the news page).

### Technical Requirements
- Pipeline must use Laravel Events/Listeners/Notifications.
- Notification must remain queue-aware (`ShouldQueue` + `Queueable`).
- Payload contract must stay stable and UI-consumer friendly.
- `url` must resolve to `route('news.show', $news)`.
- Event orchestration must not be shifted into UI/JS.

### Reliability and Operations
- Flow must be compatible with `php artisan queue:work`.
- After notification-flow changes, workers are restarted via `php artisan queue:restart`.
- Queue failures are diagnosable via `queue:failed`.
- News creation must not fail if queue workers are temporarily down (async delivery model).

### Secondary Scope (UI)
- Desktop/mobile notification UI consumes `database_notifications`.
- UI must not define delivery business rules.
- Rendering/click/read/delete is allowed as a secondary concern.

### Architectural Invariants
- Backend event `NewsCreatedEvent` is the source of truth for notification generation.
- Listener handles routing to notification; Blade/JS does not.
- Payload contract (`news_id`, `message`, `url`) is centralized in `NewsCreated`.

### Definition of Done (DoD)
- `NewsCreatedEvent` is dispatched consistently in news create flow.
- Listener dispatches `NewsCreated` without synchronous UI blocking.
- Queue workers process notification jobs end-to-end.
- `database_notifications` contains expected payload entries.
- Consumer-layer click on `url` opens the news page.

### Constraints
- Do not shift task focus to layout/styling work.
- Do not duplicate backend delivery logic in frontend.
- Do not break existing notification channels (`mail`, `database`) without explicit decision.

### Deliverables
Expected change/verification areas:
- Events/Listeners:
  - `app/Events/NewsCreatedEvent.php`
  - `app/Listeners/SendNewsCreatedNotification.php`
- Notifications:
  - `app/Notifications/NewsCreated.php`
- Create flow integration:
  - `app/Support/News/NewsCrudMutationService.php` (or current dispatch point)
- Queue runtime / ops:
  - `.env` (`QUEUE_CONNECTION`)
  - `php artisan queue:work`, `queue:restart`, `queue:failed`
- Secondary UI consumers:
  - `app/Livewire/Notifications/Dropdown.php`
  - `resources/views/livewire/notifications/*`
