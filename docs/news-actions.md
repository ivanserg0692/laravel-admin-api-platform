# News Actions: Livewire Modal Flow

## Overview
`News` actions on index page are now implemented in Livewire only.
No JS `*-init` routes or TS action runners are used.

Supported actions:
- `create`
- `update`
- `preview`
- `delete`

High-level flow:
1. User clicks action button/link with `wire:click`.
2. Livewire method loads/prepares data on server.
3. Livewire dispatches browser `CustomEvent` (`open-modal`, `close-modal`, payload events).
4. Modal component (Alpine-based) receives event and opens/closes.
5. Submit buttons call Livewire save/delete methods.

## Key Files
- [`app/Livewire/News/Index.php`](../app/Livewire/News/Index.php)
- [`resources/views/livewire/news/index.blade.php`](../resources/views/livewire/news/index.blade.php)
- [`resources/views/components/crud/index.blade.php`](../resources/views/components/crud/index.blade.php)
- [`resources/views/components/news/rows/actions.blade.php`](../resources/views/components/news/rows/actions.blade.php)
- [`resources/views/components/crud/modals/create-product.blade.php`](../resources/views/components/crud/modals/create-product.blade.php)
- [`resources/views/components/crud/modals/update-product.blade.php`](../resources/views/components/crud/modals/update-product.blade.php)
- [`resources/views/components/crud/modals/read-product.blade.php`](../resources/views/components/crud/modals/read-product.blade.php)
- [`resources/views/components/crud/modals/confirm-delete.blade.php`](../resources/views/components/crud/modals/confirm-delete.blade.php)
- [`resources/views/components/forms/factory.blade.php`](../resources/views/components/forms/factory.blade.php)

## Livewire Action Methods
Implemented in `App\Livewire\News\Index`:

- `openCreateModal()`
  - resets create values (`createInitialValues()`)
  - clears validation state
  - dispatches `open-modal` with create modal name

- `saveCreate()`
  - enables live validation mode (`createValidationActive = true`)
  - validates with `newsRules('newsCreateValues')`
  - saves `News`
  - resets form/validation
  - dispatches `close-modal`

- `openUpdateModal(int $newsId)`
  - loads selected `News`
  - fills `newsUpdateValues`
  - stores `editingNewsId`
  - clears validation state
  - dispatches `open-modal`

- `saveUpdate()`
  - requires `editingNewsId`
  - enables live validation mode (`updateValidationActive = true`)
  - validates with `newsRules('newsUpdateValues', $news->id)`
  - updates `News`
  - resets validation state
  - dispatches `close-modal`

- `openPreviewModal(int $newsId)`
  - loads `News`
  - dispatches `news-preview-loaded` payload for preview modal
  - dispatches `open-modal`

- `openDeleteModal(int $newsId)`
  - loads `News`
  - stores `deletingNewsId` and `deletingNewsTitle`
  - dispatches `news-delete-values-loaded`
  - dispatches `open-modal`

- `deleteSelectedNews()`
  - requires `deletingNewsId`
  - deletes `News`
  - closes delete modal
  - if same record was open in update modal, closes update modal too

## Modal Names and Uniqueness
Modal names are generated once per Livewire component mount using UUID:
- `create-product-{uuid}`
- `update-product-{uuid}`
- `read-product-{uuid}`
- `delete-product-{uuid}`

Generation source:
- [`app/Livewire/News/Index.php`](../app/Livewire/News/Index.php)

UUID policy in project: `Str::uuid()->toString()`.

## Browser Events Used
Events dispatched from Livewire (`$this->js(...)` wrapper):

- `open-modal`
  - `detail`: modal name string

- `close-modal`
  - `detail`: modal name string

- `news-preview-loaded`
  - `detail`: `{ modal, id, preview }`

- `news-delete-values-loaded`
  - `detail`: `{ modal, id, title }`

These events preserve existing Alpine modal animation behavior, because modal open/close still goes through the same browser event contract.

## Validation Model
Validation is server-side (Livewire), not HTML5 browser validation.

- Forms use `novalidate`.
- Field rules are centralized in `newsRules(...)`.
- Attribute labels are centralized in `newsValidationAttributes(...)`.
- Per-field live validation starts only after first submit attempt:
  - create: `createValidationActive`
  - update: `updateValidationActive`
- `updated($property)` performs:
  - `resetValidation($property)`
  - `validateOnly($property, ...)` when corresponding `*ValidationActive` is enabled

## Error Rendering
Error rendering is done in shared form factory:
- [`resources/views/components/forms/factory.blade.php`](../resources/views/components/forms/factory.blade.php)

Behavior:
- field gets error style class when validation fails
- error text is rendered under field
- messages use Laravel validation translations (`lang/*/validation.php`) + `newsValidationAttributes(...)`

## Loading/Blocking UX
In CRUD index and modal actions:
- buttons use `wire:loading` + `wire:target` for spinner/disable states
- table area overlay is shown for modal open actions
- table content gets temporary `pointer-events-none` while target action is loading

Default table blocking targets in `crud/index`:
- `openCreateModal,openUpdateModal,openPreviewModal,openDeleteModal`

Can be overridden by `tableLoadingTargets` prop.

## Row Action Wiring
Row dropdown actions are direct Livewire calls:
- `wire:click.prevent="openUpdateModal(id)"`
- `wire:click.prevent="openPreviewModal(id)"`
- `wire:click.prevent="openDeleteModal(id)"`

File:
- [`resources/views/components/news/rows/actions.blade.php`](../resources/views/components/news/rows/actions.blade.php)

## Routes Status
Legacy `*-init` routes for modal preload are no longer required for News index action flow.
News index modal CRUD behavior is now independent of those routes.

## Implementation Notes
- No compatibility layer with old JS mode is required for News index.
- If extending with new News modal action, prefer adding method in Livewire `Index` and reusing same `open-modal`/`close-modal` event contract.
