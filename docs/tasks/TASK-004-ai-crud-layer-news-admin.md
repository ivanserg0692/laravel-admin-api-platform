# TASK-004: AI CRUD Layer and News Admin Panel

## RU

### Цель
Реализовать единый AI-ориентированный CRUD layer и завершить админку новостей на его основе, чтобы все сценарии `create/read/update/delete` работали консистентно по архитектуре, UI и событиям.

### Постановка задачи
В проекте уже существует базовый набор CRUD-компонентов, modal-flow и TS action-конфигов для News, но часть решений была внедрена итеративно и требует формализации в единый слой.

Необходимо:
- зафиксировать и довести до production-ready единый подход для CRUD-операций в админке News;
- устранить разрозненные паттерны (особенно в delete-flow);
- обеспечить расширяемость для других сущностей без дублирования бизнес-логики.

### Функциональные требования
- Админка News поддерживает полный CRUD:
  - создание новости;
  - просмотр/чтение;
  - редактирование/обновление;
  - удаление с подтверждением.
- Все modal-сценарии работают через единый event-driven flow.
- Для list-режима используется single-include modal pattern (модалки рендерятся один раз на CRUD-инстанс, а не в каждой строке).
- Delete поддерживает:
  - `delete-init` (получение контекста: `id`, `title`, `delete_url`);
  - confirm modal с доменным текстом;
  - корректный `DELETE` submit.
- На странице `show` delete-confirm использует тот же modal-компонент и корректный доменный контент.

### Технические требования
- UUID/instanceId генерируются только через `\Illuminate\Support\Str::uuid()->toString()`.
- UI-события для multi-instance сценариев должны учитывать `instanceId` (или эквивалентный идентификатор инстанса) и фильтроваться слушателем.
- Доменные заголовки и сообщения модалок прокидываются из доменного слоя (`news/*`) в generic CRUD layer.
- Generic CRUD-компоненты не содержат захардкоженной доменной лексики News.
- Все пользовательские строки локализованы (`resources/lang/en/*`, `resources/lang/ru/*`).
- Не добавлять proxy/wrapper Blade-компоненты без реальной ценности.

### Архитектурные инварианты
- `crud/index` генерирует modal names один раз на инстанс и прокидывает вниз через props/data attrs.
- `lists/table` передает modal names в row-action компонент.
- Row actions только инициируют событие/запрос, но не рендерят модалки внутри dropdown.
- `confirm-delete` работает на контракте `deleteUrl` (без внешнего `formId`) и содержит внутреннюю форму.
- TS action configs являются source of truth для mapping `init-response -> event detail`.

### Критерии готовности (DoD)
- Все CRUD-сценарии News работают end-to-end в UI.
- Delete из:
  - row actions;
  - update modal;
  - show page
  открывает корректный confirm modal и удаляет нужную запись.
- Модалки не обрезаются контейнерами dropdown/таблиц.
- Нет `uniqid()` в слое `resources/views` для instance-id сценариев.
- Нет хардкода доменных title/message в generic CRUD модалках.
- Сборка frontend успешна: `npm run build`.

### Ограничения
- Не менять визуальную палитру вне текущих стандартов проекта.
- Не переносить доменную логику в абстрактные/базовые классы без необходимости.
- Не использовать runtime `env()` вне config.

### Артефакты
Ожидаемые области изменений:
- Blade:
  - `resources/views/components/crud/*`
  - `resources/views/components/lists/table.blade.php`
  - `resources/views/components/news/rows/actions.blade.php`
  - `resources/views/news/*.blade.php`
- Frontend TS:
  - `resources/js/UI/Modal/news-action-configs.ts`
  - `resources/js/UI/Modal/news-edit-action.ts`
  - `resources/js/app.ts`
- Backend:
  - `routes/web.php`
  - `app/Http/Controllers/NewsController.php`
- Localization:
  - `resources/lang/en/*.php`
  - `resources/lang/ru/*.php`
- Документация:
  - `docs/news-actions.md`
  - `AGENTS.md` (правила UUID/архитектурные ограничения)

## EN

### Goal
Implement a unified AI-oriented CRUD layer and finalize the News admin panel on top of it, so `create/read/update/delete` flows are consistent in architecture, UI, and event routing.

### Task Description
The project already has base CRUD components, modal flow, and TS action configs for News. However, parts were introduced iteratively and must be consolidated into a single production-ready layer.

Required:
- finalize a single consistent approach for News admin CRUD;
- eliminate fragmented patterns (especially in delete flow);
- keep the solution extensible for future entities with minimal duplication.

### Functional Requirements
- News admin supports full CRUD:
  - create;
  - read/preview;
  - edit/update;
  - delete with confirmation.
- All modal flows follow one event-driven pattern.
- List mode uses single-include modal pattern (modals rendered once per CRUD instance, not per row).
- Delete supports:
  - `delete-init` payload (`id`, `title`, `delete_url`);
  - domain-aware confirmation text;
  - correct `DELETE` submit.
- Show page uses the same confirm-delete modal component with proper domain content.

### Technical Requirements
- UUID/instanceId generation must use `\Illuminate\Support\Str::uuid()->toString()` only.
- Multi-instance UI events must include and validate instance identity (`instanceId` or equivalent).
- Domain modal titles/messages must be passed from domain layer (`news/*`) into generic CRUD layer.
- Generic CRUD components must not hardcode News-specific wording.
- All user-facing strings must be localized (`resources/lang/en/*`, `resources/lang/ru/*`).
- No proxy Blade wrappers without real added value.

### Architectural Invariants
- `crud/index` generates modal names once per instance and propagates them via props/data attributes.
- `lists/table` forwards modal names into row-action component.
- Row actions trigger requests/events only; they do not render modal markup inside dropdowns.
- `confirm-delete` works with `deleteUrl` contract (no external `formId`) and contains its own form.
- TS action configs are the source of truth for `init-response -> event detail` mapping.

### Definition of Done (DoD)
- All News CRUD flows work end-to-end.
- Delete launched from:
  - row actions;
  - update modal;
  - show page
  opens the correct confirmation modal and deletes the intended record.
- Modals are not clipped by dropdown/table containers.
- No `uniqid()` remains in `resources/views` for instance-id scenarios.
- No domain title/message hardcoding remains in generic CRUD modals.
- Frontend build passes: `npm run build`.

### Constraints
- Keep current project color/style standards.
- Do not introduce abstract/base inheritance unless behavior truly diverges.
- Do not use runtime `env()` outside config files.

### Deliverables
Expected change areas:
- Blade:
  - `resources/views/components/crud/*`
  - `resources/views/components/lists/table.blade.php`
  - `resources/views/components/news/rows/actions.blade.php`
  - `resources/views/news/*.blade.php`
- Frontend TS:
  - `resources/js/UI/Modal/news-action-configs.ts`
  - `resources/js/UI/Modal/news-edit-action.ts`
  - `resources/js/app.ts`
- Backend:
  - `routes/web.php`
  - `app/Http/Controllers/NewsController.php`
- Localization:
  - `resources/lang/en/*.php`
  - `resources/lang/ru/*.php`
- Documentation:
  - `docs/news-actions.md`
  - `AGENTS.md` (UUID and architecture rules)
