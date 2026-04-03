# TASK-009: API Auth and News API with Sanctum, Cloudflare Protection, and OpenAPI Docs

## RU

### Цель
Добавить production-ready API-аутентификацию на Laravel Sanctum и API новостей с защитой Cloudflare Turnstile для JSON-запросов там, где это необходимо, и актуальной OpenAPI/Swagger-документацией.

### Постановка задачи
Необходимо формализовать API-слой для внешних клиентов поверх уже установленного Sanctum, существующей модели `User` и доменной модели новостей.

Решение должно покрыть:
- регистрацию пользователя через API;
- логин пользователя с выдачей Sanctum token;
- logout по текущему токену;
- получение текущего аутентифицированного пользователя;
- получение списка новостей через API;
- получение детальной информации по отдельной новости;
- при необходимости дальнейшее расширение до защищенных news mutation-ручек;
- совместимую с API обработку Cloudflare Turnstile;
- генерацию и поддержку Swagger/OpenAPI документации для auth- и news-ручек.

### Функциональные требования
- Должен существовать отдельный API-контроллер `App\Http\Controllers\Api\AuthController`.
- Должны быть доступны маршруты:
  - `POST /api/register`;
  - `POST /api/login`;
  - `POST /api/logout`;
  - `GET /api/me`.
- Должны быть доступны news API маршруты как минимум:
  - `GET /api/news`;
  - `GET /api/news/{news}`.
- `register` должен создавать нового пользователя и возвращать токен доступа.
- `login` должен аутентифицировать пользователя и возвращать новый токен доступа.
- `logout` должен удалять текущий access token.
- `me` должен возвращать текущего пользователя.
- `GET /api/news` должен возвращать список новостей в согласованном JSON-формате.
- `GET /api/news/{news}` должен возвращать одну новость в согласованном JSON-формате.
- В публичный News API должны попадать только допустимые к публикации записи.
- API login не должен допускать вход заблокированных пользователей (`is_blocked = true`).
- API auth-ручки, защищенные Cloudflare, должны корректно работать в JSON-сценарии без web-redirect.

### Технические требования
- Для API-регистрации и логина должны использоваться отдельные `FormRequest` классы.
- Регистрация и логин должны использовать Sanctum personal access tokens через `createToken(...)->plainTextToken`.
- Для login rate limiting должна использоваться логика с `RateLimiter`.
- При превышении лимита попыток должно генерироваться стандартное Laravel-событие `Lockout`.
- News API должен быть оформлен через отдельный API-контроллер или контроллеры, а не смешиваться с web CRUD.
- Для ответов News API должен использоваться стабильный serialization layer: API Resource, DTO-like transformer или другой единый формат представления.
- Middleware `VerifyCloudflare` должен поддерживать два режима ответа:
  - web form: `back()->withErrors()->withInput()`;
  - JSON/API: `422` JSON с ошибкой в поле `cf-turnstile-response`.
- В проект должна быть добавлена Swagger/OpenAPI интеграция на базе `darkaonline/l5-swagger`.
- OpenAPI спецификация должна собираться из PHP attributes и reusable schema/components классов.
- Swagger/OpenAPI документация должна генерироваться через `l5-swagger`.
- Повторяющиеся OpenAPI-схемы auth и news должны быть вынесены в reusable components, а не дублироваться inline в каждом endpoint.

### UI и DX требования
- Swagger UI должен отображать request/response схемы для всех auth- и news-ручек.
- Swagger UI должен использоваться как стандартная точка просмотра и ручного тестирования API-контрактов проекта.
- Для `register` и `login` в документации должно быть описано поле `cf-turnstile-response`.
- В README должна быть добавлена инструкция по генерации API-документации.
- Swagger/OpenAPI metadata должны быть вынесены в отдельный глобальный spec-класс.

### Архитектурные инварианты
- Не смешивать web session auth-flow Breeze и API token auth-flow Sanctum в одном контроллере.
- Не смешивать web News CRUD и публичный/внешний News API в одном контроллере без явной причины.
- Не возвращать web redirect-ответы для JSON API-запросов.
- Не дублировать OpenAPI-схемы ответа пользователя, auth token response и news payload в нескольких методах контроллера.
- Не добавлять runtime-логику приложения в OpenAPI spec-классы; они должны служить только для генерации документации.

### Критерии готовности (DoD)
- API auth-маршруты зарегистрированы и отображаются в `php artisan route:list --path=api`.
- News API маршруты зарегистрированы и отображаются в `php artisan route:list --path=api`.
- `register` и `login` возвращают валидный Sanctum token.
- `logout` удаляет текущий токен.
- `me` возвращает текущего пользователя.
- `GET /api/news` возвращает корректный список новостей.
- `GET /api/news/{news}` возвращает корректную новость или ожидаемый not found response.
- Заблокированный пользователь не может войти через API.
- `VerifyCloudflare` возвращает JSON `422` для API-запросов и redirect для web-форм.
- `php artisan l5-swagger:generate` успешно генерирует документацию.
- README и task docs отражают новый API auth + news + docs flow.

### Ограничения
- Не переходить на session/cookie auth вместо Sanctum token flow.
- Не дублировать web-контроллеры Breeze для API-сценария.
- Не дублировать web-контроллеры новостей для API-сценария без выделенного API-контракта.
- Не оставлять API middleware с redirect-only поведением.
- Не держать длинные дублирующиеся OpenAPI схемы прямо в методах контроллера.

### Артефакты
Ожидаемые области изменений/верификации:
- API auth:
  - `app/Http/Controllers/Api/AuthController.php`
  - `app/Http/Requests/Api/Auth/LoginRequest.php`
  - `app/Http/Requests/Api/Auth/RegisterRequest.php`
  - `routes/api.php`
- News API:
  - `app/Http/Controllers/Api/NewsController.php`
  - `app/Http/Resources/*` или другой serialization layer
  - `routes/api.php`
- Cloudflare middleware:
  - `app/Http/Middleware/VerifyCloudflare.php`
- OpenAPI / Swagger:
  - `app/OpenApi/OpenApiSpec.php`
  - `app/OpenApi/AuthSchemas.php`
  - дополнительные schema/components для News API
  - `README.md`

## EN

### Goal
Introduce a production-ready Laravel Sanctum API authentication flow and a News API, with Cloudflare Turnstile protection for JSON requests where needed and up-to-date OpenAPI/Swagger documentation.

### Task Description
The goal is to formalize an API layer for external clients on top of the existing Sanctum setup, the current `User` model, and the news domain model.

The solution must cover:
- user registration via API;
- user login with Sanctum token issuance;
- logout by current token;
- fetching the current authenticated user;
- fetching a news list via API;
- fetching detailed data for a single news item;
- keeping room for future protected news mutation endpoints if needed;
- API-compatible Cloudflare Turnstile handling;
- generation and maintenance of Swagger/OpenAPI docs for auth and news endpoints.

### Functional Requirements
- A dedicated API controller `App\Http\Controllers\Api\AuthController` must exist.
- The following routes must be available:
  - `POST /api/register`;
  - `POST /api/login`;
  - `POST /api/logout`;
  - `GET /api/me`.
- News API routes must exist at minimum:
  - `GET /api/news`;
  - `GET /api/news/{news}`.
- `register` must create a new user and return an access token.
- `login` must authenticate the user and return a new access token.
- `logout` must delete the current access token.
- `me` must return the current authenticated user.
- `GET /api/news` must return a news collection in a consistent JSON format.
- `GET /api/news/{news}` must return a single news item in a consistent JSON format.
- Only records valid for public exposure must appear in the public News API.
- API login must reject blocked users (`is_blocked = true`).
- API auth endpoints protected by Cloudflare must work correctly in JSON mode without web redirects.

### Technical Requirements
- API registration and login must use dedicated `FormRequest` classes.
- Registration and login must use Sanctum personal access tokens via `createToken(...)->plainTextToken`.
- Login rate limiting must use `RateLimiter`.
- On throttle lockout, the standard Laravel `Lockout` event must be emitted.
- News API must use dedicated API controller(s), not be merged into the web CRUD layer.
- News API responses must use a stable serialization layer: API Resource, DTO-like transformer, or another single representation format.
- `VerifyCloudflare` middleware must support two response modes:
  - web form: `back()->withErrors()->withInput()`;
  - JSON/API: `422` JSON with error in `cf-turnstile-response`.
- The project must include Swagger/OpenAPI integration based on `darkaonline/l5-swagger`.
- OpenAPI specification must be built from PHP attributes and reusable schema/component classes.
- Swagger/OpenAPI docs must be generated via `l5-swagger`.
- Repeated auth and news OpenAPI schemas must be extracted into reusable components instead of being duplicated inline per endpoint.

### UI and DX Requirements
- Swagger UI must display request/response schemas for all auth and news endpoints.
- Swagger UI must serve as the standard project entry point for viewing and manually testing API contracts.
- `register` and `login` documentation must include the `cf-turnstile-response` field.
- README must include instructions for generating API docs.
- Swagger/OpenAPI metadata must be stored in a dedicated global spec class.

### Architectural Invariants
- Do not mix Breeze web session auth flow and Sanctum token API flow in the same controller.
- Do not mix the web News CRUD layer and the public/external News API in the same controller without explicit reason.
- Do not return web redirect responses for JSON API requests.
- Do not duplicate user, auth token, or news payload schemas across multiple controller methods.
- Do not put application runtime logic inside OpenAPI spec classes; they must remain documentation-only.

### Definition of Done (DoD)
- API auth routes are registered and visible in `php artisan route:list --path=api`.
- News API routes are registered and visible in `php artisan route:list --path=api`.
- `register` and `login` return valid Sanctum tokens.
- `logout` deletes the current token.
- `me` returns the current authenticated user.
- `GET /api/news` returns a valid news collection.
- `GET /api/news/{news}` returns a valid news item or the expected not found response.
- A blocked user cannot log in through API.
- `VerifyCloudflare` returns JSON `422` for API requests and redirect responses for web forms.
- `php artisan l5-swagger:generate` successfully generates documentation.
- README and task docs reflect the new API auth + news + docs flow.

### Constraints
- Do not switch from Sanctum token auth to session/cookie auth.
- Do not duplicate Breeze web controllers for the API scenario.
- Do not duplicate web news controllers for the API scenario without an explicit API contract.
- Do not keep API middleware with redirect-only behavior.
- Do not leave long duplicated OpenAPI schemas inline inside controller methods.

### Deliverables
Expected change/verification areas:
- API auth:
  - `app/Http/Controllers/Api/AuthController.php`
  - `app/Http/Requests/Api/Auth/LoginRequest.php`
  - `app/Http/Requests/Api/Auth/RegisterRequest.php`
  - `routes/api.php`
- News API:
  - `app/Http/Controllers/Api/NewsController.php`
  - `app/Http/Resources/*` or another serialization layer
  - `routes/api.php`
- Cloudflare middleware:
  - `app/Http/Middleware/VerifyCloudflare.php`
- OpenAPI / Swagger:
  - `app/OpenApi/OpenApiSpec.php`
  - `app/OpenApi/AuthSchemas.php`
  - additional schema/components for News API
  - `README.md`
