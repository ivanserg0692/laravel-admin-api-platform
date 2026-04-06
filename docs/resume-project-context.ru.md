# Контекст проекта для резюме

## Краткое описание проекта

Учебно-портфельный проект на Laravel, который вышел за рамки стандартного framework skeleton и включает полноценный web + API сценарий. В системе сочетаются классический server-rendered интерфейс, интерактивный CRUD на Livewire, токен-авторизация для API, административная панель, фоновые экспорты и внешние интеграции.

## Функциональный охват

- Аутентификация пользователей: регистрация, вход, сброс пароля, подтверждение email, управление профилем
- Модуль новостей с web-интерфейсом, API-доступом и админской частью
- Role/tag-based доступ для сценариев admin, news admin и author
- API-аутентификация через Laravel Sanctum
- OpenAPI/Swagger документация для auth и news endpoint-ов
- Защита публичных auth endpoint-ов через Cloudflare Turnstile
- Фоновый CSV export pipeline для данных новостей с отслеживанием прогресса
- Уведомления и event-driven обработка вокруг жизненного цикла новостей

## Основной стек

- PHP 8.3
- Laravel 12
- Blade
- Livewire 4
- Filament 5
- Laravel Sanctum
- L5 Swagger / OpenAPI attributes
- Vite
- Tailwind CSS 4
- Alpine.js
- Flowbite
- AWS S3-совместимое хранилище

## Архитектура и технические особенности

### Backend

- Laravel-приложение с разделением web и API маршрутов
- Eloquent-модели для пользователей, новостей, экспортов и пользовательских тегов
- Policies для контроля доступа в news-модуле
- Form Request и API Resource слои для валидации и выдачи данных
- Event/listener pipeline для уведомлений и связанных процессов
- Очереди и jobs для chunked export generation и финальной сборки файла

### Frontend

- Blade-интерфейс для обычных страниц
- Livewire modal CRUD flow для списка новостей
- Совместимый с Alpine browser event contract для открытия, закрытия и передачи payload в модалки
- Vite pipeline для frontend assets с Tailwind и Alpine.js

### Админка и внутренние инструменты

- Filament admin panel resources для:
  - users
  - user tags
  - news
  - мониторинга news exports

### API

- Auth endpoint-ы: register, login, logout, current user
- Защищенные news endpoint-ы: пагинированный список и отдельная запись
- Выдача и отзыв Sanctum-токенов
- OpenAPI schema annotations прямо в контроллерах и schema-классах

## Доменная модель

### Новости

Сущность `news` включает title, slug, preview, content, статус публикации, дату публикации, связь с автором, cover image, SEO-поля, сортировку, счетчик просмотров и soft deletes.

### Пользователи и роли

Пользователи могут быть заблокированы и получают теги через many-to-many связь. Доступ в Filament и к управлению новостями ограничивается по slug-ролям вроде `admin`, `news_admin` и `author`.

### Экспорты

Экспорты новостей хранятся как отдельные записи, связанные с Laravel job batches. Прогресс считается по состоянию batch-задач, а export files поддерживают version-aware работу в S3-совместимом хранилище.

## Реализованные функциональные зоны

### Аутентификация

- Auth flow на базе Breeze, расширенный API-аутентификацией
- Personal access tokens через Sanctum
- Подтверждение email и управление профилем
- Проверка Cloudflare Turnstile на register/login API endpoint-ах

### Модуль новостей

- Web CRUD экраны
- Livewire modal actions для create, update, preview и delete
- Сортировка, пагинация и query logic для списка
- Правила авторизации в зависимости от роли и владения записью
- API read access для аутентифицированных пользователей

### Экспортный pipeline

- Экспорт новостей в CSV чанками
- Сохранение chunk-файлов в S3-совместимое хранилище
- Финализирующая job, которая объединяет чанки в один итоговый файл
- Отслеживание прогресса и завершения через job batches
- Поддержка просмотра версий ранее созданных export-файлов

### Документация и тестирование

- Swagger/OpenAPI документация для API-контрактов
- Feature tests для auth/profile/news поведения
- Unit tests для news sorting/query logic
- Внутренние task и overview документы, отражающие этапы реализации

## Что можно использовать в резюме как опыт

Этот проект можно использовать как подтверждение опыта в:

- разработке full-stack приложений на Laravel
- проектировании authenticated REST API
- реализации role-based / tag-based авторизации
- интеграции CAPTCHA / anti-bot защиты
- разработке админок на Filament
- создании реактивных CRUD-интерфейсов на Livewire
- документировании API через OpenAPI/Swagger
- реализации фоновой обработки через очереди и jobs
- экспорте данных в CSV с масштабированием через batches
- интеграции S3-совместимого object storage
- написании feature и unit тестов под бизнес-логику

## Готовые формулировки для резюме

### Короткая версия

Разработал Laravel 12 приложение с использованием Blade, Livewire, Filament и Sanctum: реализовал аутентифицированные web/API сценарии, role-based доступ, CRUD для новостей, административные инструменты, OpenAPI-документацию и фоновый экспорт CSV в S3-совместимое хранилище.

### Усиленная backend-версия

Разработал full-stack приложение на Laravel с token-protected API, role-aware управлением контентом, queue-based экспортом данных, Cloudflare Turnstile валидацией, OpenAPI-документацией и Filament-админкой для пользователей, тегов, новостей и мониторинга экспортов.

### Полная версия

Реализовал многоуровневый Laravel 12 проект, объединяющий server-rendered страницы, интерактивный CRUD на Livewire, API-аутентификацию через Sanctum, Filament admin resources, event/notification pipeline, защиту через Cloudflare Turnstile и batch-based экспорт CSV в S3-совместимое хранилище, с покрытием ключевого поведения feature и unit тестами.

## Заметки для дальнейшего использования

- При необходимости из этого файла можно сделать:
  - `resume-bullets.ru.md` с готовыми bullet points
  - `hh-project-description.md` под HH.ru
  - `interview-talking-points.ru.md` с краткими архитектурными тезисами
- Перед публикацией стоит отдельно разделить:
  - то, что уже реализовано и подтверждается кодом
  - то, что пока отражено только в docs/tasks как план