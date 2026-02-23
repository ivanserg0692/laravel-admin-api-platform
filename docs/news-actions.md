# News Actions: Edit/Preview Modal Flow

## Overview
This document describes how `News` row actions initialize and populate modals for:
- `Edit`
- `Preview`

The flow is event-driven:
1. User clicks action link in row dropdown.
2. Frontend TS action sends `POST` request to `*-init` endpoint.
3. TS dispatches a window `CustomEvent` with payload.
4. Target modal (Alpine) listens for the event and applies payload.
5. Modal is opened by name via `open-modal`.

## Key Files
- [`resources/views/components/crud/index.blade.php`](../resources/views/components/crud/index.blade.php)
- [`resources/views/components/lists/table.blade.php`](../resources/views/components/lists/table.blade.php)
- [`resources/views/components/news/rows/actions.blade.php`](../resources/views/components/news/rows/actions.blade.php)
- [`resources/views/components/crud/modals/update-product.blade.php`](../resources/views/components/crud/modals/update-product.blade.php)
- [`resources/views/components/crud/modals/read-product.blade.php`](../resources/views/components/crud/modals/read-product.blade.php)
- [`resources/js/news-edit-action.ts`](../resources/js/UI/Modal/news-edit-action.ts)
- [`resources/js/app.ts`](../resources/js/app.ts)
- [`routes/web.php`](../routes/web.php)
- [`app/Http/Controllers/NewsController.php`](../app/Http/Controllers/NewsController.php)

## Frontend Architecture
Frontend now uses a single reusable action runner:

- [`resources/js/news-edit-action.ts`](../resources/js/UI/Modal/news-edit-action.ts) exports `NewsModalAction`
- [`resources/js/app.ts`](../resources/js/app.ts) defines two configs:
  - `newsEditActionConfig`
  - `newsPreviewActionConfig`

`NewsModalAction` handles common behavior:
- read `data-*` request url and modal name from link
- loading guard (`dataset.loading`)
- lock/unlock (`pointerEvents`)
- `POST` request with CSRF token
- dispatch custom event
- open modal (based on config mode)

Differences between Edit and Preview are expressed by config only:
- data keys (`editInitUrl/editModal` vs `previewInitUrl/previewModal`)
- event name
- payload mapping (`buildDetail`)
- modal open mode (`always` vs `when_detail`)

## Modal Names And Uniqueness
Unique modal names are generated per CRUD component render in:
- [`resources/views/components/crud/index.blade.php`](../resources/views/components/crud/index.blade.php)

Current naming:
- Edit modal: `update-product-{uniqid}`
- Preview modal: `read-product-{uniqid}`

This prevents collisions when multiple CRUD blocks are present on one page.

## ModalId Propagation (Step-by-step)
The same generated modal name is passed through all layers.

1. Generate unique names in [`resources/views/components/crud/index.blade.php`](../resources/views/components/crud/index.blade.php):
   - `$updateModalName = 'update-product-' . $crudInstanceId`
   - `$previewModalName = 'read-product-' . $crudInstanceId`

2. Pass names to table props in [`resources/views/components/crud/index.blade.php`](../resources/views/components/crud/index.blade.php):
   - `:edit-modal="$updateModalName"`
   - `:preview-modal="$previewModalName"`

3. Forward names to row actions in [`resources/views/components/lists/table.blade.php`](../resources/views/components/lists/table.blade.php):
   - `:edit-modal="$editModal"`
   - `:preview-modal="$previewModal"`

4. Write names into action link attributes in [`resources/views/components/news/rows/actions.blade.php`](../resources/views/components/news/rows/actions.blade.php):
   - `data-edit-modal="{{ $editModal }}"`
   - `data-preview-modal="{{ $previewModal }}"`

5. Read names in TS and include them in event payload/open call in [
   `resources/js/news-edit-action.ts`](../resources/js/UI/Modal/news-edit-action.ts):
   - `link.dataset.editModal` / `link.dataset.previewModal`
   - `detail.modal = ...`
   - `window.dispatchEvent(new CustomEvent('open-modal', { detail: modalName }))`

6. Bind generated names to modal components in [`resources/views/components/crud/index.blade.php`](../resources/views/components/crud/index.blade.php):
   - `@include('components.crud.modals.update-product', ['name' => $updateModalName])`
   - `@include('components.crud.modals.read-product', ['name' => $previewModalName])`

7. Filter events by modal name in Alpine:
   - [`resources/views/components/crud/modals/update-product.blade.php`](../resources/views/components/crud/modals/update-product.blade.php)
   - [`resources/views/components/crud/modals/read-product.blade.php`](../resources/views/components/crud/modals/read-product.blade.php)
   - Guard: `if ($event.detail?.modal !== modalName) return;`

## Row Action Data Attributes
Defined in [`resources/views/components/news/rows/actions.blade.php`](../resources/views/components/news/rows/actions.blade.php):

Edit action:
- `data-edit-init-url`: endpoint `news.edit-init`
- `data-edit-modal`: unique edit modal name

Preview action:
- `data-preview-init-url`: endpoint `news.preview-init`
- `data-preview-modal`: unique preview modal name

## Frontend Action Entrypoints
Registered in [`resources/js/app.ts`](../resources/js/app.ts):
- `window.App.UI.NewsActions.editInit(event, link)`
- `window.App.UI.NewsActions.previewInit(event, link)`

Both entrypoints construct and run the same class:
- `new NewsModalAction(event, link, newsEditActionConfig).run()`
- `new NewsModalAction(event, link, newsPreviewActionConfig).run()`

Compatibility alias:
- `window.newsEditInit` -> `editInit`

## Backend Init Endpoints
Routes in [`routes/web.php`](../routes/web.php):
- `POST /api/session/news/{news}/edit-init` (`news.edit-init`)
- `POST /api/session/news/{news}/preview-init` (`news.preview-init`)

Controller methods:
- `NewsController::editInit(News $news)`
- `NewsController::previewInit(News $news)`

## JSON Contracts
### Edit init response
```json
{
  "ok": true,
  "data": {
    "id": 123,
    "values": {
      "title": "...",
      "slug": "...",
      "status": "...",
      "published_at": "...",
      "preview": "...",
      "content": "...",
      "cover_image": "...",
      "meta_title": "...",
      "meta_description": "...",
      "sort_order": 0
    }
  }
}
```

### Preview init response
```json
{
  "ok": true,
  "data": {
    "id": 123,
    "preview": {
      "title": "...",
      "status": "...",
      "published_at": "...",
      "preview": "...",
      "content": "...",
      "cover_image": "..."
    }
  }
}
```

## Custom Events

Dispatched from [`resources/js/news-edit-action.ts`](../resources/js/UI/Modal/news-edit-action.ts):

Edit:
- Event: `news-edit-values-loaded`
- Detail:
```json
{
  "modal": "update-product-...",
  "id": 123,
  "values": { "...": "..." }
}
```

Preview:
- Event: `news-preview-loaded`
- Detail:
```json
{
  "modal": "read-product-...",
  "id": 123,
  "preview": { "...": "..." }
}
```

Modal opening event:
- Event: `open-modal`
- Detail: modal name string (same as `detail.modal`)

## Alpine Modal Binding
### Update modal
[`resources/views/components/crud/modals/update-product.blade.php`](../resources/views/components/crud/modals/update-product.blade.php):
- Holds `modalName` and `form` in `x-data`
- Listens to `news-edit-values-loaded`
- Filters by modal name match
- Applies payload to `form`

### Preview modal
[`resources/views/components/crud/modals/read-product.blade.php`](../resources/views/components/crud/modals/read-product.blade.php):
- Holds `modalName` and `preview` in `x-data`
- Listens to `news-preview-loaded`
- Filters by modal name match
- Applies payload to `preview`

## Loading Guard
Both actions use `link.dataset.loading` as an in-flight guard:
- prevents duplicate requests
- temporarily disables pointer events
- restores state in `finally`

## Extending With New Actions
To add a new row action (example: `Duplicate`):
1. Add unique modal name generation in `crud.index` (if modal is needed).
2. Pass modal name through `lists.table` -> row actions.
3. Add action data attributes (`data-*-init-url`, `data-*-modal`).
4. Add backend `*-init` route + controller response contract.
5. Add a new `NewsModalActionConfig` in [`resources/js/app.ts`](../resources/js/app.ts).
6. Add modal Alpine listener with strict modal name filter.
