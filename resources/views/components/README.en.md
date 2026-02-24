# Blade Components Documentation

Folder: `resources/views/components`

## General Rules
- Usage: `<x-component-name />`.
- For nested folders: `<x-folder.component />` (example: `<x-buttons.primary />`).
- Large composed blocks can use `@include(...)`, but base UI pieces should remain components.

## Current Structure
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
      confirm-delete.blade.php
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
    table-sort-link.blade.php
  modals/
    modal.blade.php
    panel.blade.php
  navigation/
    nav-link.blade.php
    responsive-nav-link.blade.php
```

## Structure Rules
- `buttons/`: only buttons and their visual variants (`primary`, `secondary`, `danger`). Do not place dropdown/menu components here.
- `dropdown/`: dropdown building blocks and menu elements (trigger/content wrappers, links, row-actions).
- `forms/`: base form controls and helper parts (`input-label`, `input-error`, `text-input`, `textarea`, `select`, `search`).
- `navigation/`: header/menu navigation components (`nav-link`, `responsive-nav-link`).
- `lists/`: table/list data presentation components.
- `modals/`: shared modal wrappers (`modal`, `panel`) reused across domains.
- `crud/`: domain CRUD components (index, modals, toolbar parts) tied to CRUD scenarios.
- `crud/forms/`: shared CRUD operation forms (create/update) reused by pages and modals.
- Root `components/`: only truly shared components that do not belong to a domain folder (`theme-toggle`, `application-logo`, `auth-session-status`, `cloudflare-captcha`).
- Place a new component into the narrowest folder by responsibility; if it becomes cross-domain, move it to a shared layer.

## Component Map and Usage
- `application-logo.blade.php` (`x-application-logo`): `resources/views/layouts/guest.blade.php`
- `auth-session-status.blade.php` (`x-auth-session-status`): `resources/views/auth/forgot-password.blade.php`, `resources/views/auth/login.blade.php`
- `buttons/danger.blade.php` (`x-buttons.danger`): `resources/views/components/crud/modals/confirm-delete.blade.php`, `resources/views/components/crud/modals/update-product.blade.php`, `resources/views/profile/partials/delete-user-form.blade.php`
- `buttons/icon-close.blade.php` (`x-buttons.icon-close`): `resources/views/components/modals/panel.blade.php`
- `buttons/primary.blade.php` (`x-buttons.primary`): `resources/views/auth/confirm-password.blade.php`, `resources/views/auth/forgot-password.blade.php`, `resources/views/auth/login.blade.php`, `resources/views/auth/register.blade.php`, `resources/views/auth/reset-password.blade.php`, `resources/views/auth/verify-email.blade.php`, `resources/views/components/crud/index.blade.php`, `resources/views/components/crud/modals/create-product.blade.php`, `resources/views/components/crud/modals/update-product.blade.php`, `resources/views/profile/partials/update-password-form.blade.php`, `resources/views/profile/partials/update-profile-information-form.blade.php`
- `buttons/secondary.blade.php` (`x-buttons.secondary`): `resources/views/auth/verify-email.blade.php`, `resources/views/components/crud/modals/confirm-delete.blade.php`, `resources/views/components/dropdown/toggle-panel.blade.php`, `resources/views/profile/partials/delete-user-form.blade.php`
- `cloudflare-captcha.blade.php` (`x-cloudflare-captcha`): `resources/views/auth/register.blade.php`
- `crud/index.blade.php` (`x-crud.index`): `resources/views/livewire/news/index.blade.php`
- `crud/create.blade.php` (`x-crud.create`): `resources/views/news/create.blade.php`
- `crud/forms/create.blade.php` (`x-crud.forms.create`): `resources/views/components/crud/create.blade.php`, `resources/views/components/crud/modals/create-product.blade.php`
- `crud/forms/update.blade.php` (`x-crud.forms.update`): `resources/views/components/crud/update.blade.php`, `resources/views/components/crud/modals/update-product.blade.php`
- `crud/show.blade.php` (`x-crud.show`): `resources/views/news/show.blade.php`
- `crud/update.blade.php` (`x-crud.update`): `resources/views/news/update.blade.php`
- `crud/modals/create-product.blade.php` (`x-crud.modals.create-product`): `resources/views/components/crud/index.blade.php`
- `crud/modals/confirm-delete.blade.php` (`x-crud.modals.confirm-delete`): `resources/views/components/crud/index.blade.php`
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
- `lists/table-sort-link.blade.php` (`x-lists.table-sort-link`): `resources/views/components/lists/table.blade.php`
- `modals/modal.blade.php` (`x-modals.modal`): `resources/views/components/modals/panel.blade.php`, `resources/views/profile/partials/delete-user-form.blade.php`
- `modals/panel.blade.php` (`x-modals.panel`): `resources/views/components/crud/modals/create-product.blade.php`, `resources/views/components/crud/modals/update-product.blade.php`, `resources/views/components/crud/modals/read-product.blade.php`, `resources/views/components/crud/modals/confirm-delete.blade.php`
- `navigation/nav-link.blade.php` (`x-navigation.nav-link`): `resources/views/layouts/navigation.blade.php`
- `navigation/responsive-nav-link.blade.php` (`x-navigation.responsive-nav-link`): `resources/views/layouts/navigation.blade.php`
- `theme-toggle.blade.php` (`x-theme-toggle`): `resources/views/layouts/navigation.blade.php`, `resources/views/welcome.blade.php`

## Form Factory Conventions
- `x-forms.factory` is an optional component.
- It is currently used by CRUD flows, but it is not CRUD-limited and can be used for any other form scenarios.
- Using `x-forms.factory` is not mandatory when building forms.
- Benefits:
  - unified schema-driven field contract (`FormFieldDto`);
  - unified rendering for label/input/select/textarea/error;
  - shared handling of `old()` / `errorBag` / `nameMode`;
  - less duplicated markup and classes across forms.
- Current usage examples:
  - [`resources/views/components/crud/forms/create.blade.php`](resources/views/components/crud/forms/create.blade.php)
  - [`resources/views/components/crud/forms/update.blade.php`](resources/views/components/crud/forms/update.blade.php)
  - [`resources/views/news/create.blade.php`](resources/views/news/create.blade.php)
  - [`resources/views/news/update.blade.php`](resources/views/news/update.blade.php)
- `x-forms.factory` supports `errorBag` to isolate validation messages between forms.
- If `errorBag` is not provided:
  - bag is derived from `idPrefix` (camelCase), fallback is `default`.
- `nameMode` controls the HTML field name format:
  - `plain` (default): `field`
  - `dot`: `namespace.field`
  - `bracket`: `namespace[field]`
- `inputNamespace` can be passed explicitly; if omitted and `dot/bracket` is used, namespace is derived from `errorBag` or `idPrefix`.
- `old()` and validation keys are aligned as:
  - `plain`: `field`
  - `dot`: `namespace.field`
  - `bracket`: `namespace.field`
- Backend access example: `$request->input('createNews.title')`.

## Search Note
- In the current implementation, `x-forms.search` is Livewire-oriented and uses `wire:model.live.debounce.300ms`.
- For correct behavior, pass `livewireModel` (example: `search`).
