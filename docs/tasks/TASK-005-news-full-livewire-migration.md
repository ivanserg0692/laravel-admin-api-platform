# TASK-005: News Full Livewire Migration

## RU

### Цель
Выполнить полный переезд CRUD/action-flow страницы `news.index` на Livewire и окончательно отказаться от legacy JS режима для этого сценария.

### Постановка задачи
Текущий поток `news.index` должен работать только через Livewire-методы и Livewire-состояние, без промежуточных `*-init` API-инициализаций и TS action runner для модалок.

Необходимо:
- зафиксировать Livewire как единственный источник поведения модалок в News index;
- удалить/не использовать legacy JS-совместимость в этом потоке;
- сохранить UX (анимации модалок, лоадеры, валидация, сообщения об ошибках).

### Функциональные требования
- CRUD-сценарии News index работают через Livewire:
  - `openCreateModal` + `saveCreate`;
  - `openUpdateModal` + `saveUpdate`;
  - `openPreviewModal`;
  - `openDeleteModal` + `deleteSelectedNews`.
- Кнопки и row actions вызывают только `wire:click`/`wire:submit`.
- Модалки открываются/закрываются через browser events:
  - `open-modal`
  - `close-modal`
- Preview/Delete получают данные через payload events:
  - `news-preview-loaded`
  - `news-delete-values-loaded`

### Технические требования
- Для modal instance имен использовать только `\Illuminate\Support\Str::uuid()->toString()`.
- Не использовать `uniqid()` для instanceId/modalName.
- HTML5 валидацию отключить (`novalidate`), оставить серверную Livewire-валидацию.
- Ошибки валидации выводить под полями и локализовывать через `lang/*/validation.php`.
- Подсветка поля ошибкой должна работать вместе с Livewire validation state.

### Архитектурные инварианты
- В потоке News index не использовать legacy TS init-паттерн (`news-*-init`, action configs, runtime JS bootstrap).
- Состояние форм хранится в Livewire properties (`newsCreateValues`, `newsUpdateValues`).
- Правила и human-readable attributes централизованы в Livewire-компоненте.
- Для loading UX использовать `wire:loading` + `wire:target` на кнопках и блокировках таблицы.

### Критерии готовности (DoD)
- В News index не осталось зависимости от старого JS-режима модалок.
- Все CRUD modal сценарии работают end-to-end через Livewire.
- Ошибки валидации отображаются под конкретными полями.
- При загрузке действий видны лоадеры, кнопки/области корректно блокируются.
- Документация `docs/news-actions.md` соответствует новой Livewire-архитектуре.

### Ограничения
- Не добавлять backward compatibility слой для старого JS режима в News index.
- Не менять визуальный стиль вне действующих стандартов проекта.
- Не вводить абстрактные базовые классы без реальной поведенческой необходимости.

### Артефакты
Ожидаемые области изменений:
- Livewire:
  - `app/Livewire/News/Index.php`
  - `resources/views/livewire/news/index.blade.php`
- Blade CRUD/UI:
  - `resources/views/components/crud/*`
  - `resources/views/components/news/rows/actions.blade.php`
  - `resources/views/components/forms/factory.blade.php`
  - `resources/views/components/ui/spinner.blade.php`
- Localization:
  - `resources/lang/en/validation.php`
  - `resources/lang/ru/validation.php`
- Documentation:
  - `docs/news-actions.md`
  - `docs/tasks/TASK-005-news-full-livewire-migration.md`

## EN

### Goal
Complete the full migration of `news.index` CRUD/action flow to Livewire and fully drop legacy JS mode for this scenario.

### Task Description
`news.index` must operate through Livewire methods/state only, without `*-init` API preload endpoints and without TS modal action runners.

Required:
- establish Livewire as the single source of truth for News index modal behavior;
- remove/avoid legacy JS compatibility in this flow;
- keep UX quality (modal animations, loading states, validation, and error rendering).

### Functional Requirements
- News index CRUD modal flows run via Livewire:
  - `openCreateModal` + `saveCreate`;
  - `openUpdateModal` + `saveUpdate`;
  - `openPreviewModal`;
  - `openDeleteModal` + `deleteSelectedNews`.
- Buttons and row actions use `wire:click` / `wire:submit` only.
- Modals open/close through browser events:
  - `open-modal`
  - `close-modal`
- Preview/Delete payloads are delivered via:
  - `news-preview-loaded`
  - `news-delete-values-loaded`

### Technical Requirements
- Modal instance names must use `\Illuminate\Support\Str::uuid()->toString()` only.
- Do not use `uniqid()` for instanceId/modal names.
- Disable HTML5 validation (`novalidate`); rely on Livewire server-side validation.
- Render field errors under inputs and localize via `lang/*/validation.php`.
- Error field highlighting must be integrated with Livewire validation state.

### Architectural Invariants
- No legacy TS init pattern in News index flow (`news-*-init`, action configs, runtime JS bootstrap).
- Form state is stored in Livewire properties (`newsCreateValues`, `newsUpdateValues`).
- Validation rules and attribute labels are centralized in Livewire component.
- Loading UX uses `wire:loading` + `wire:target` for buttons and table blocking.

### Definition of Done (DoD)
- News index has no dependency on old JS modal mode.
- All CRUD modal flows work end-to-end through Livewire.
- Validation errors are rendered under concrete fields.
- Loading indicators and temporary blocking work correctly during async actions.
- `docs/news-actions.md` is aligned with the new Livewire architecture.

### Constraints
- Do not add a backward compatibility layer for old JS mode in News index.
- Keep current project visual standards.
- Do not introduce abstract/base classes unless behavior truly diverges.

### Deliverables
Expected change areas:
- Livewire:
  - `app/Livewire/News/Index.php`
  - `resources/views/livewire/news/index.blade.php`
- Blade CRUD/UI:
  - `resources/views/components/crud/*`
  - `resources/views/components/news/rows/actions.blade.php`
  - `resources/views/components/forms/factory.blade.php`
  - `resources/views/components/ui/spinner.blade.php`
- Localization:
  - `resources/lang/en/validation.php`
  - `resources/lang/ru/validation.php`
- Documentation:
  - `docs/news-actions.md`
  - `docs/tasks/TASK-005-news-full-livewire-migration.md`
