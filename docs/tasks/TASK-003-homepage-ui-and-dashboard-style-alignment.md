# TASK-003: Homepage UI and Dashboard Style Alignment

## RU

### Цель
Сформировать единый визуальный стиль интерфейса проекта: завершить UI главной страницы и привести `/dashboard` к той же дизайн-системе.

### Постановка задачи
На текущем этапе основная работа идет над UI. Главная страница уже реализована в формате pre-launch (с CTA на регистрацию и вход), но требуется выровнять визуальную часть личного кабинета (`/dashboard`) под те же принципы:
- единая цветовая палитра
- согласованные типографика и отступы
- одинаковые состояния кнопок, карточек, ссылок и форм
- корректная работа `light/dark` темы в одном стиле

Задача направлена на устранение визуального разрыва между публичной частью (`/`) и авторизованной частью (`/dashboard`).

### Функциональные требования
- Dashboard использует те же базовые UI-паттерны, что и главная страница.
- Цвета (`primary`, нейтральные, акцентные) применяются консистентно.
- Компоненты действий (кнопки/ссылки) имеют единое поведение hover/focus/active.
- Типографика и вертикальные ритмы соответствуют главной.
- Темная тема поддерживается на обоих экранах без контрастных конфликтов.

### Критерии готовности (DoD)
- Визуальный стиль `/dashboard` и `/` выглядит как единый продукт.
- Нет резких несогласованных элементов между страницами (цвета, размеры, отступы, бордеры, тени).
- Навигация и CTA читаемы в light и dark режимах.
- Все изменения проходят ручную проверку на desktop и mobile.

### Технические заметки
- Не дублировать стили точечно в Blade-шаблонах, если можно переиспользовать существующие utility-классы и паттерны.
- Сохранять текущую структуру Breeze, изменяя только визуальный слой.
- Для новых UI-решений использовать существующую локализацию (`resources/lang/*`) и не хардкодить пользовательские строки.

### Ссылки
- MR: https://github.com/ivanserg0692/laravel-admin-api-platform/pull/2

## EN

### Goal
Establish a consistent UI language across the project by finalizing the homepage design and aligning `/dashboard` to the same design system.

### Task Description
Current work is focused on UI. The homepage is already implemented as a pre-launch screen with auth CTAs, but the dashboard (`/dashboard`) must be visually aligned with the same principles:
- shared color palette
- consistent typography and spacing
- uniform button/card/link/form states
- consistent `light/dark` theme behavior

This task removes the visual gap between the public page (`/`) and the authenticated area (`/dashboard`).

### Functional Requirements
- Dashboard follows the same base UI patterns as the homepage.
- Color usage (`primary`, neutrals, accents) is consistent.
- Action components (buttons/links) share hover/focus/active behavior.
- Typography and vertical rhythm match the homepage.
- Dark theme is supported on both pages without contrast conflicts.

### Definition of Done (DoD)
- `/dashboard` and `/` look like one product.
- No visually inconsistent elements between pages (colors, sizing, spacing, borders, shadows).
- Navigation and CTAs remain readable in both light and dark modes.
- Changes are manually verified on desktop and mobile layouts.

### Technical Notes
- Avoid one-off inline style duplication in Blade templates; reuse utility patterns where possible.
- Keep Breeze structure intact and change only presentation layer where needed.
- Reuse localization from `resources/lang/*` and avoid hardcoded user-facing strings.

### Links
- MR: https://github.com/ivanserg0692/laravel-admin-api-platform/pull/2
