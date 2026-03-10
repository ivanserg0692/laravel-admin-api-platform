# TASK-008: News Export Pipeline with Filament, Queue Batches, and MinIO Versioning

## RU

### Цель
Добавить production-ready поток экспорта новостей в CSV из Filament-админки с асинхронной batch-обработкой, отслеживанием прогресса и хранением версий файлов в MinIO/S3.

### Постановка задачи
Необходимо формализовать отдельный export-flow для News, который запускается из Filament, не блокирует UI и позволяет получать итоговый CSV из объектного хранилища.

Задача должна покрыть полный жизненный цикл экспорта:
- запуск экспорта из админки;
- разбиение данных на чанки и обработку через queue batch;
- сборку итогового CSV-файла;
- отображение прогресса и статуса в панели;
- скачивание актуальной или предыдущей версии экспортного файла из MinIO/S3.

### Функциональные требования
- В Filament должна быть доступна отдельная сущность `NewsExportResource`.
- Пользователь админки может запустить экспорт новостей через UI-действие.
- Экспорт формируется в CSV-формате.
- Данные News выгружаются как минимум с колонками:
  - `id`;
  - `title`;
  - `slug`;
  - `status`;
  - `published_at`;
  - `created_at`.
- Каждый запуск экспорта сохраняется в таблице `news_exports`.
- Для экспорта отображается прогресс выполнения в процентах и человекочитаемый статус.
- После завершения экспорт доступен для скачивания из Filament.
- Для завершенного экспорта доступен выбор версии файла при скачивании.

### Технические требования
- Экспорт должен быть реализован через Laravel Queue Batches.
- Генерация должна быть разделена минимум на два этапа:
  - chunk generation (`GenerateNewsExportFileJob`);
  - finalize/merge (`FinalizeNewsExportFileJob`).
- Промежуточные chunk-файлы сохраняются во временную S3/MinIO директорию.
- Финальный CSV сохраняется в `exports/news/*`.
- Метаданные batch-хранилища должны сохраняться в `job_batches`.
- Привязка между export-записью и batch должна храниться в `news_exports.job_batch_id`.
- Источником прогресса является состояние batch (`total_jobs`, `pending_jobs`, `failed_jobs`, `finished_at`, `cancelled_at`).
- Для работы с версиями объекта должен использоваться S3 API `listObjectVersions` / `getObject` с `VersionId`.

### Инфраструктура и эксплуатация
- `QUEUE_CONNECTION` должен оставаться совместимым с асинхронным запуском export jobs.
- Хранилище экспорта должно быть настроено через S3-compatible disk и работать с MinIO.
- В окружении должны быть заданы:
  - `AWS_ACCESS_KEY_ID`;
  - `AWS_SECRET_ACCESS_KEY`;
  - `AWS_DEFAULT_REGION`;
  - `AWS_BUCKET`;
  - `AWS_ENDPOINT`;
  - `AWS_USE_PATH_STYLE_ENDPOINT`.
- Для локальной разработки должен быть доступен MinIO контейнер.
- Для ручной работы с бакетом должен быть доступен `mc` runner через `docker compose run --rm mc`.

### UI и UX требования
- Экспорт должен запускаться из Filament без перехода в кастомный внешний интерфейс.
- Таблица экспортов должна показывать:
  - путь/имя файла;
  - прогресс;
  - batch name;
  - `created_at`.
- Прогресс должен отображаться как числовой процент и визуальный progress bar.
- Статусы должны быть локализованы через `resources/lang/{locale}/filament.php`.
- Кнопка скачивания должна быть доступна только для завершенного и реально существующего экспортного файла.

### Архитектурные инварианты
- UI не должен самостоятельно вычислять прогресс по эвристикам вне batch state.
- Сборка итогового файла не должна выполняться в синхронном HTTP request lifecycle.
- Версионирование экспортов должно опираться на возможности object storage, а не на локальное ручное копирование файлов.
- Export-flow должен быть изолирован от основного CRUD News и не перегружать `NewsResource` неэкспортной логикой.

### Критерии готовности (DoD)
- В админке доступен отдельный экран экспортов новостей.
- Запуск экспорта создает запись в `news_exports` и queue batch в `job_batches`.
- Chunk jobs обрабатывают все новости и формируют итоговый CSV.
- После завершения batch итоговый файл доступен в S3/MinIO.
- В таблице экспортов корректно отображаются прогресс и статус.
- Завершенный экспорт скачивается из админки.
- При наличии object versions пользователь может выбрать нужную версию файла для скачивания.
- README и task docs отражают новый export-flow.

### Ограничения
- Не переносить export orchestration в frontend JS.
- Не делать синхронную генерацию полного CSV в HTTP-запросе.
- Не завязывать решение только на локальную файловую систему без S3-compatible сценария.
- Не ломать существующую структуру Filament navigation group для News.

### Артефакты
Ожидаемые области изменений/верификации:
- Filament resources/pages/tables:
  - `app/Filament/Resources/NewsExports/NewsExportResource.php`
  - `app/Filament/Resources/NewsExports/Pages/ListNewsExports.php`
  - `app/Filament/Resources/NewsExports/Pages/ViewNewsExport.php`
  - `app/Filament/Resources/NewsExports/Tables/NewsExportsTable.php`
  - `app/Filament/Resources/NewsExports/Schemas/NewsExportInfolist.php`
- Jobs / domain model:
  - `app/Jobs/GenerateNewsExportFileJob.php`
  - `app/Jobs/FinalizeNewsExportFileJob.php`
  - `app/Models/NewsExport.php`
  - `app/Models/JobBatch.php`
  - `app/Support/News/NewsExportProgressStatus.php`
- Database:
  - `database/migrations/2026_03_06_134525_create_job_batches_table.php`
  - `database/migrations/2026_03_06_134526_create_news_exports_table.php`
- Infra/config/docs:
  - `.env.example`
  - `docker-compose.yml`
  - `resources/lang/en/filament.php`
  - `resources/lang/ru/filament.php`
  - `README.md`

## EN

### Goal
Introduce a production-ready CSV export flow for news in the Filament admin panel with async batch processing, progress tracking, and file version storage in MinIO/S3.

### Task Description
The task is to formalize a dedicated News export flow launched from Filament, without blocking the UI, and to make the final CSV downloadable from object storage.

The flow must cover the full export lifecycle:
- start export from admin UI;
- split processing into queue batch chunks;
- merge chunk outputs into a final CSV file;
- expose progress and status in the panel;
- download the latest or previous file version from MinIO/S3.

### Functional Requirements
- A dedicated `NewsExportResource` must exist in Filament.
- Admin users can start a news export from the UI.
- Export output is generated as CSV.
- News rows must include at least:
  - `id`;
  - `title`;
  - `slug`;
  - `status`;
  - `published_at`;
  - `created_at`.
- Each export run is stored in the `news_exports` table.
- Every export shows progress percent and a human-readable status.
- Completed exports are downloadable from Filament.
- Completed exports allow choosing a file version on download.

### Technical Requirements
- Export processing must use Laravel Queue Batches.
- Generation must be split into at least two stages:
  - chunk generation (`GenerateNewsExportFileJob`);
  - finalize/merge (`FinalizeNewsExportFileJob`).
- Temporary chunk files are stored in an S3/MinIO temporary directory.
- Final CSV is stored under `exports/news/*`.
- Batch metadata is stored in `job_batches`.
- Export-to-batch linkage is stored in `news_exports.job_batch_id`.
- Progress source of truth is batch state (`total_jobs`, `pending_jobs`, `failed_jobs`, `finished_at`, `cancelled_at`).
- File version access must use S3 APIs `listObjectVersions` / `getObject` with `VersionId`.

### Infrastructure and Operations
- `QUEUE_CONNECTION` must remain compatible with async export job processing.
- Export storage must use an S3-compatible disk backed by MinIO.
- Environment configuration must include:
  - `AWS_ACCESS_KEY_ID`;
  - `AWS_SECRET_ACCESS_KEY`;
  - `AWS_DEFAULT_REGION`;
  - `AWS_BUCKET`;
  - `AWS_ENDPOINT`;
  - `AWS_USE_PATH_STYLE_ENDPOINT`.
- A MinIO container must be available for local development.
- An `mc` runner must be available for bucket inspection via `docker compose run --rm mc`.

### UI and UX Requirements
- Export is triggered from Filament, without a custom external interface.
- Exports table must show:
  - file path/name;
  - progress;
  - batch name;
  - `created_at`.
- Progress must be rendered as both numeric percentage and visual progress bar.
- Status labels must be localized via `resources/lang/{locale}/filament.php`.
- Download action must be available only for completed exports with an existing file in storage.

### Architectural Invariants
- UI must not compute progress through heuristics outside batch state.
- Final file assembly must not run in the synchronous HTTP request lifecycle.
- Export versioning must rely on object storage capabilities, not on manual local file duplication.
- Export flow must remain isolated from core News CRUD behavior and avoid overloading `NewsResource` with unrelated export orchestration.

### Definition of Done (DoD)
- A dedicated news exports screen exists in admin panel.
- Starting an export creates a `news_exports` row and a queue batch in `job_batches`.
- Chunk jobs process all news items and produce a final CSV.
- After batch completion, the final file is available in S3/MinIO.
- Exports table shows correct progress and status.
- Completed export can be downloaded from admin UI.
- When object versions exist, user can choose which file version to download.
- README and task docs reflect the new export flow.

### Constraints
- Do not move export orchestration into frontend JS.
- Do not generate the full CSV synchronously inside HTTP request handling.
- Do not couple the solution to local filesystem only; keep S3-compatible storage as a core scenario.
- Do not break existing News navigation grouping in Filament.

### Deliverables
Expected change/verification areas:
- Filament resources/pages/tables:
  - `app/Filament/Resources/NewsExports/NewsExportResource.php`
  - `app/Filament/Resources/NewsExports/Pages/ListNewsExports.php`
  - `app/Filament/Resources/NewsExports/Pages/ViewNewsExport.php`
  - `app/Filament/Resources/NewsExports/Tables/NewsExportsTable.php`
  - `app/Filament/Resources/NewsExports/Schemas/NewsExportInfolist.php`
- Jobs / domain model:
  - `app/Jobs/GenerateNewsExportFileJob.php`
  - `app/Jobs/FinalizeNewsExportFileJob.php`
  - `app/Models/NewsExport.php`
  - `app/Models/JobBatch.php`
  - `app/Support/News/NewsExportProgressStatus.php`
- Database:
  - `database/migrations/2026_03_06_134525_create_job_batches_table.php`
  - `database/migrations/2026_03_06_134526_create_news_exports_table.php`
- Infra/config/docs:
  - `.env.example`
  - `docker-compose.yml`
  - `resources/lang/en/filament.php`
  - `resources/lang/ru/filament.php`
  - `README.md`
