---
name: livewire-thin-component-refactor
description: Рефакторинг Livewire CRUD в стиле thin component / fat mutator с единоразовым bind компонента
---

# Livewire Thin Component Refactor

## Purpose
Сделать Livewire-компоненты максимально тонкими:
- public action-методы в компонентах: 1-2 строки;
- вся логика сценариев в одном mutator service.

## Use When
- Есть дублирование логики между `Index` и `Update` Livewire.
- В компонентах много валидации, guard-ов, dispatch-событий.
- Нужно централизовать CRUD-flow без передачи `$this` в каждый вызов.

## Rules
- Bind компонента один раз в `boot`:
  - `$this->crudMutationService = $crudMutationService->bind($this);`
- Не использовать суффиксные имена методов:
  - запрещено: `saveUpdateFromIndex`, `handleUpdatePageUpdatedProperty`
- Использовать короткие общие имена:
  - `updated`
  - `openCreateModal`
  - `saveCreate`
  - `openUpdateModal`
  - `saveUpdate`
  - `openPreviewModal`
  - `openDeleteModal`
  - `deleteSelectedNews`
- Не прокидывать `NewsCrudState` туда-сюда между компонентом и сервисом.
- Не вызывать `app()`/фасады для DI-сервисов.
- Сохранить текущий UI-поток: модалки, события, редиректы, ошибки валидации.

## Component Target Shape
Каждый action-метод компонента должен выглядеть так:

```php
public function saveUpdate(): void
{
    $this->crudMutationService->saveUpdate();
}
```

## Mutator Responsibilities
Mutator должен:
- читать/менять state bound-компонента;
- выполнять validate/validateOnly;
- делать guard-условия;
- делать dispatch modal/window events;
- делать resetValidation;
- вызывать persistence-методы (`createNewsFromValues`, `updateNewsFromValues`, `deleteNewsById`).

## Refactor Steps
1. Найти action-методы в `Index` и `Update`.
2. Перенести их сценарную логику в mutator (с короткими именами).
3. Оставить в компонентах только прокси-вызовы.
4. Удалить неиспользуемые зависимости/DTO-state-обвязку.
5. Проверить синтаксис:
   - `php -l` для измененных файлов.

## Acceptance Criteria
- Public action-методы в компонентах = 1-2 строки.
- В компонентах нет сценарной бизнес-логики.
- В mutator нет длинных суффиксных имен.
- Поведение UI не изменилось.
- `php -l` проходит без ошибок.