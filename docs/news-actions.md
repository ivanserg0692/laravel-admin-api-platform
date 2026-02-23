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

## Prompt Templates (Create/Edit/Update/Delete)

Below are ready-to-use prompts for implementing CRUD actions in this project style.

### Prompt: Create
```text
Task:
Implement/create the News "Create" modal flow in the existing CRUD style.

Context:
- Use existing architecture: modal name generated in crud/index and propagated through table -> row action/data attrs.
- Keep event-driven pattern with CustomEvent and Alpine listeners.
- Keep compatibility with existing NewsModalAction flow.

Requirements:
1) Add/create init endpoint only if needed by UX.
2) Ensure modal name uniqueness is per CRUD instance (not per row).
3) Wire action entrypoint under window.App.UI.NewsActions.
4) Use loading guard and avoid duplicate requests.
5) Do not create wrapper/proxy Blade components.
6) Do not introduce new color styles.

Files allowed to change:
- resources/views/components/crud/index.blade.php
- resources/views/components/lists/table.blade.php
- resources/views/components/news/rows/actions.blade.php
- resources/views/components/crud/modals/create-product.blade.php
- resources/js/UI/Modal/news-action-configs.ts
- resources/js/UI/Modal/news-edit-action.ts
- resources/js/app.ts
- routes/web.php
- app/Http/Controllers/NewsController.php

Done criteria:
- Create modal opens from UI action.
- Data mapping matches response contract.
- Event listener filters by modal name.
- npm run build passes.
```

### Prompt: Edit
```text
Task:
Implement/fix the News "Edit" init + modal prefill flow in the current project pattern.

Context:
- Edit should use POST init endpoint and dispatch event with values payload.
- Modal is opened by "open-modal" event with generated modal name.

Requirements:
1) Route: POST /api/session/news/{news}/edit-init (news.edit-init).
2) Controller returns:
   {
     ok: true,
     data: { id, values: {...} }
   }
3) Row action carries:
   - data-edit-init-url
   - data-edit-modal
4) TS config maps response into detail:
   { modal, id, values }
5) Update modal listens to "news-edit-values-loaded" and applies values only when modal matches.
6) Keep one modal include per CRUD instance; no per-row modal rendering.

Files allowed to change:
- routes/web.php
- app/Http/Controllers/NewsController.php
- resources/views/components/news/rows/actions.blade.php
- resources/views/components/crud/modals/update-product.blade.php
- resources/js/UI/Modal/news-action-configs.ts
- resources/js/UI/Modal/news-edit-action.ts
- resources/js/app.ts

Done criteria:
- Edit click loads values and opens correct modal.
- No collisions with other CRUD instances.
- npm run build passes.
```

### Prompt: Update
```text
Task:
Implement/fix News "Update" submit flow from update modal/form with existing component conventions.

Context:
- Update is form submit (PUT/PATCH), not init-only action.
- Keep form rendering and naming conventions already used by x-crud.forms.update.

Requirements:
1) Ensure update form action/method are correct for the selected news item.
2) Keep validation errors displayed through existing error handling.
3) Do not break Edit init prefill flow.
4) Preserve back button and delete button behavior in update UI.
5) Keep existing Tailwind visual style and button components.

Files allowed to change:
- resources/views/components/crud/update.blade.php
- resources/views/components/crud/modals/update-product.blade.php
- resources/views/components/crud/forms/update.blade.php
- app/Http/Controllers/NewsController.php
- app/Http/Requests/UpdateNewsRequest.php (if needed)

Done criteria:
- Update submits to correct endpoint.
- Validation + success path work.
- Existing modal fill behavior still works.
```

### Prompt: Delete
```text
Task:
Implement/fix News "Delete" confirm modal flow using a single modal include per CRUD instance.

Context:
- Delete button is in row action.
- Confirm modal is included once in crud/index with unique modal name per CRUD instance.
- Delete details (id/title/deleteUrl) are loaded via delete-init event.

Requirements:
1) Route: POST /api/session/news/{news}/delete-init (news.delete-init).
2) Controller returns:
   {
     ok: true,
     data: {
       id,
       title,
       delete_url
     }
   }
3) Row action carries:
   - data-delete-init-url
   - data-delete-modal (from crud/index modal name)
4) TS delete config dispatches detail:
   { modal, id, title, deleteUrl }
5) confirm-delete modal:
   - receives only modal name from include
   - listens to news-delete-values-loaded
   - updates message template with {id}/{title}
   - submits DELETE via internal hidden form with dynamic action
6) Do not render per-row delete modals in dropdown.
7) Keep include style aligned with create/update/read (single include in crud/index).

Files allowed to change:
- resources/views/components/crud/index.blade.php
- resources/views/components/lists/table.blade.php
- resources/views/components/news/rows/actions.blade.php
- resources/views/components/crud/modals/confirm-delete.blade.php
- resources/js/UI/Modal/news-action-configs.ts
- resources/js/UI/Modal/news-edit-action.ts
- resources/js/app.ts
- routes/web.php
- app/Http/Controllers/NewsController.php
- resources/lang/en/crud.php
- resources/lang/ru/crud.php

Done criteria:
- Delete opens full modal (not clipped in dropdown).
- Message shows id/title placeholders resolved.
- Confirm submits DELETE to correct row URL.
- npm run build passes.
```

## CRUD Action Do/Don't Checklist
- Do: generate modal names in `crud/index` once per CRUD instance.
- Do: pass modal names down via props/data attributes.
- Do: filter all modal listeners by `detail.modal`.
- Do: keep one modal include per CRUD instance for create/read/update/delete.
- Don't: render confirmation modals inside dropdown row markup.
- Don't: introduce proxy Blade wrappers without added value.
- Don't: mix per-row modal rendering with single-modal include pattern.
