# Документация Blade-компонентов

Папка: `resources/views/components`

## Общие правила
- Использование: `<x-имя-компонента />`.
- Для вложенных папок: `<x-folder.component />` (пример: `<x-buttons.primary />`).
- Крупные составные блоки можно подключать через `@include(...)`, но базовые UI-части лучше держать в компонентах.

## Текущая структура
```text
resources/views/components/
  application-logo.blade.php
  auth-session-status.blade.php
  cloudflare-captcha.blade.php
  theme-toggle.blade.php
  buttons/
    danger.blade.php
    icon-close.blade.php
    primary.blade.php
    secondary.blade.php
  crud/
    create.blade.php
    forms/
      create.blade.php
      update.blade.php
    index.blade.php
    show.blade.php
    update.blade.php
    modals/
      create-product.blade.php
      delete-product.blade.php
      read-product.blade.php
      update-product.blade.php
    toolbar/
      actions-dropdown.blade.php
      filter-dropdown.blade.php
  dropdown/
    link.blade.php
    menu.blade.php
    row-actions.blade.php
    toggle-panel.blade.php
  forms/
    factory.blade.php
    input-error.blade.php
    input-label.blade.php
    search.blade.php
    select.blade.php
    textarea.blade.php
    text-input.blade.php
  lists/
    table.blade.php
  modals/
    modal.blade.php
    panel.blade.php
  navigation/
    nav-link.blade.php
    responsive-nav-link.blade.php
```

## Структурные правила
- `buttons/`: только кнопки и их визуальные варианты (`primary`, `secondary`, `danger`). Не хранить здесь dropdown/menu-компоненты.
- `dropdown/`: каркасы и элементы выпадающих меню (trigger/content wrappers, links, row-actions).
- `forms/`: базовые поля формы и вспомогательные части (`input-label`, `input-error`, `text-input`, `textarea`, `select`, `search`).
- `navigation/`: компоненты навигации шапки/меню (`nav-link`, `responsive-nav-link`).
- `lists/`: табличные и списочные представления данных.
- `modals/`: общие модальные обертки (`modal`, `panel`) для переиспользования между доменами.
- `crud/`: доменные CRUD-компоненты (index, модалки, тулбар-части), привязанные к CRUD-сценарию.
- `crud/forms/`: общие формы CRUD-операций (create/update), переиспользуемые страницами и модалками.
- Корень `components/`: только truly shared компоненты, которые не относятся к конкретной папке-домену (`theme-toggle`, `application-logo`, `auth-session-status`, `cloudflare-captcha`).
- Новый компонент класть в максимально узкую папку по назначению; если он становится cross-domain, переносить в общий слой.

## Карта компонентов и использование
- `application-logo.blade.php` (`x-application-logo`): `resources/views/layouts/guest.blade.php`
- `auth-session-status.blade.php` (`x-auth-session-status`): `resources/views/auth/forgot-password.blade.php`, `resources/views/auth/login.blade.php`
- `buttons/danger.blade.php` (`x-buttons.danger`): `resources/views/components/crud/modals/delete-product.blade.php`, `resources/views/components/crud/modals/update-product.blade.php`, `resources/views/profile/partials/delete-user-form.blade.php`
- `buttons/icon-close.blade.php` (`x-buttons.icon-close`): `resources/views/components/modals/panel.blade.php`
- `buttons/primary.blade.php` (`x-buttons.primary`): `resources/views/auth/confirm-password.blade.php`, `resources/views/auth/forgot-password.blade.php`, `resources/views/auth/login.blade.php`, `resources/views/auth/register.blade.php`, `resources/views/auth/reset-password.blade.php`, `resources/views/auth/verify-email.blade.php`, `resources/views/components/crud/index.blade.php`, `resources/views/components/crud/modals/create-product.blade.php`, `resources/views/components/crud/modals/update-product.blade.php`, `resources/views/profile/partials/update-password-form.blade.php`, `resources/views/profile/partials/update-profile-information-form.blade.php`
- `buttons/secondary.blade.php` (`x-buttons.secondary`): `resources/views/auth/verify-email.blade.php`, `resources/views/components/crud/modals/delete-product.blade.php`, `resources/views/components/dropdown/toggle-panel.blade.php`, `resources/views/profile/partials/delete-user-form.blade.php`
- `cloudflare-captcha.blade.php` (`x-cloudflare-captcha`): `resources/views/auth/register.blade.php`
- `crud/index.blade.php` (`x-crud.index`): `resources/views/news/index.blade.php`
- `crud/create.blade.php` (`x-crud.create`): `resources/views/news/create.blade.php`
- `crud/forms/create.blade.php` (`x-crud.forms.create`): `resources/views/components/crud/create.blade.php`, `resources/views/components/crud/modals/create-product.blade.php`
- `crud/forms/update.blade.php` (`x-crud.forms.update`): `resources/views/components/crud/update.blade.php`, `resources/views/components/crud/modals/update-product.blade.php`
- `crud/show.blade.php` (`x-crud.show`): `resources/views/news/show.blade.php`
- `crud/update.blade.php` (`x-crud.update`): `resources/views/news/update.blade.php`
- `crud/modals/create-product.blade.php` (`x-crud.modals.create-product`): `resources/views/components/crud/index.blade.php`
- `crud/modals/delete-product.blade.php` (`x-crud.modals.delete-product`): `resources/views/components/crud/index.blade.php`
- `crud/modals/read-product.blade.php` (`x-crud.modals.read-product`): `resources/views/components/crud/index.blade.php`
- `crud/modals/update-product.blade.php` (`x-crud.modals.update-product`): `resources/views/components/crud/index.blade.php`
- `crud/toolbar/actions-dropdown.blade.php` (`x-crud.toolbar.actions-dropdown`): `resources/views/components/crud/index.blade.php`
- `crud/toolbar/filter-dropdown.blade.php` (`x-crud.toolbar.filter-dropdown`): `resources/views/components/crud/index.blade.php`
- `dropdown/link.blade.php` (`x-dropdown.link`): `resources/views/layouts/navigation.blade.php`
- `dropdown/menu.blade.php` (`x-dropdown.menu`): `resources/views/layouts/navigation.blade.php`
- `dropdown/row-actions.blade.php` (`x-dropdown.row-actions`): `resources/views/components/lists/table.blade.php`
- `dropdown/toggle-panel.blade.php` (`x-dropdown.toggle-panel`): `resources/views/components/crud/toolbar/actions-dropdown.blade.php`, `resources/views/components/crud/toolbar/filter-dropdown.blade.php`
- `forms/factory.blade.php` (`x-forms.factory`): `resources/views/components/crud/forms/create.blade.php`, `resources/views/components/crud/forms/update.blade.php`
- `forms/input-error.blade.php` (`x-forms.input-error`): `resources/views/auth/confirm-password.blade.php`, `resources/views/auth/forgot-password.blade.php`, `resources/views/auth/login.blade.php`, `resources/views/auth/register.blade.php`, `resources/views/auth/reset-password.blade.php`, `resources/views/components/cloudflare-captcha.blade.php`, `resources/views/components/forms/factory.blade.php`, `resources/views/profile/partials/delete-user-form.blade.php`, `resources/views/profile/partials/update-password-form.blade.php`, `resources/views/profile/partials/update-profile-information-form.blade.php`
- `forms/input-label.blade.php` (`x-forms.input-label`): `resources/views/auth/confirm-password.blade.php`, `resources/views/auth/forgot-password.blade.php`, `resources/views/auth/login.blade.php`, `resources/views/auth/register.blade.php`, `resources/views/auth/reset-password.blade.php`, `resources/views/components/forms/factory.blade.php`, `resources/views/profile/partials/delete-user-form.blade.php`, `resources/views/profile/partials/update-password-form.blade.php`, `resources/views/profile/partials/update-profile-information-form.blade.php`
- `forms/search.blade.php` (`x-forms.search`): `resources/views/components/crud/index.blade.php`
- `forms/select.blade.php` (`x-forms.select`): `resources/views/components/forms/factory.blade.php`
- `forms/textarea.blade.php` (`x-forms.textarea`): `resources/views/components/forms/factory.blade.php`
- `forms/text-input.blade.php` (`x-forms.text-input`): `resources/views/auth/confirm-password.blade.php`, `resources/views/auth/forgot-password.blade.php`, `resources/views/auth/login.blade.php`, `resources/views/auth/register.blade.php`, `resources/views/auth/reset-password.blade.php`, `resources/views/components/forms/factory.blade.php`, `resources/views/profile/partials/delete-user-form.blade.php`, `resources/views/profile/partials/update-password-form.blade.php`, `resources/views/profile/partials/update-profile-information-form.blade.php`
- `lists/table.blade.php` (`x-lists.table`): `resources/views/components/crud/index.blade.php`
- `modals/modal.blade.php` (`x-modals.modal`): `resources/views/components/modals/panel.blade.php`, `resources/views/profile/partials/delete-user-form.blade.php`
- `modals/panel.blade.php` (`x-modals.panel`): `resources/views/components/crud/modals/create-product.blade.php`, `resources/views/components/crud/modals/update-product.blade.php`, `resources/views/components/crud/modals/read-product.blade.php`, `resources/views/components/crud/modals/delete-product.blade.php`
- `navigation/nav-link.blade.php` (`x-navigation.nav-link`): `resources/views/layouts/navigation.blade.php`
- `navigation/responsive-nav-link.blade.php` (`x-navigation.responsive-nav-link`): `resources/views/layouts/navigation.blade.php`
- `theme-toggle.blade.php` (`x-theme-toggle`): `resources/views/layouts/navigation.blade.php`, `resources/views/welcome.blade.php`
