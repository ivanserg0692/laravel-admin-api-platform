# Документация Blade-компонентов

Папка: `resources/views/components`

## Общие правила
- Использование: `<x-имя-компонента />`.
- Для вложенных папок: `<x-folder.component />` (пример: `<x-forms.fields.text />`).
- `@include(...)` используется для крупных partial-блоков (пример: таблица/модалки в демо CRUD).

## Текущая структура
```text
resources/views/components/
  lists/
    toolbar.blade.php
    table.blade.php
    row-actions.blade.php
    pagination.blade.php
  forms/
    fields/
      text.blade.php
      textarea.blade.php
      select.blade.php
    errors.blade.php
  overlays/
    modal.blade.php
    confirm-dialog.blade.php
  navigation/
    dropdown.blade.php
    dropdown-link.blade.php
  feedback/
    badge.blade.php
    alert.blade.php
```

## Карта компонентов

### Base
- `application-logo.blade.php`
- `auth-session-status.blade.php`
- `cloudflare-captcha.blade.php`
- `danger-button.blade.php`
- `dropdown-link.blade.php`
- `dropdown.blade.php`
- `input-error.blade.php`
- `input-label.blade.php`
- `modal.blade.php`
- `nav-link.blade.php`
- `primary-button.blade.php`
- `responsive-nav-link.blade.php`
- `secondary-button.blade.php`
- `text-input.blade.php`
- `theme-toggle.blade.php`

### Lists
- `lists/toolbar.blade.php`
- `lists/table.blade.php`
- `lists/row-actions.blade.php`
- `lists/pagination.blade.php`

### Forms
- `forms/errors.blade.php`
- `forms/fields/text.blade.php`
- `forms/fields/textarea.blade.php`
- `forms/fields/select.blade.php`

### Overlays
- `overlays/modal.blade.php`
- `overlays/confirm-dialog.blade.php`

### Navigation
- `navigation/dropdown.blade.php`
- `navigation/dropdown-link.blade.php`

### Feedback
- `feedback/badge.blade.php`
- `feedback/alert.blade.php`

### CRUD (demo composition)
- `crud/index.blade.php`
- `crud/modals/create-product.blade.php`
- `crud/modals/update-product.blade.php`
- `crud/modals/read-product.blade.php`
- `crud/modals/delete-product.blade.php`
