# Resume Project Context

## Project Summary

Training and portfolio Laravel project that goes beyond the default framework skeleton and covers a complete web + API application flow. The system combines classic server-rendered pages, Livewire-driven interactive CRUD, token-based API authentication, an admin panel, background export processing, and external service integrations.

## Product Scope

- User authentication flow with registration, login, password reset, email verification, and profile management
- News management module with web UI, API access, and admin panel support
- Role/tag-based access control for admin, news admin, and author scenarios
- API authentication with Laravel Sanctum
- OpenAPI/Swagger documentation for auth and news endpoints
- Cloudflare Turnstile validation for public auth endpoints
- Background CSV export pipeline for news data with batch progress tracking
- Notifications and event-driven processing around news lifecycle

## Main Stack

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
- AWS S3 compatible storage

## Architecture And Technical Highlights

### Backend

- Laravel application with separated web and API routes
- Eloquent models for users, news, exports, and user tags
- Policies for access control in the news module
- Form requests and resources for request validation and API responses
- Event/listener pipeline for notification-related workflows
- Queue jobs for chunked export generation and final file assembly

### Frontend

- Blade-based UI for standard pages
- Livewire-based modal CRUD flow for the news index
- Alpine-compatible browser event contract for modal opening, closing, and payload delivery
- Vite-based asset pipeline with Tailwind and Alpine.js

### Admin And Internal Tools

- Filament admin panel resources for:
  - users
  - user tags
  - news
  - news export monitoring

### API

- Auth endpoints: register, login, logout, current user
- Protected news endpoints: paginated list and single item
- Sanctum token issuing and token invalidation
- OpenAPI schema annotations directly in controllers and schema classes

## Domain Model

### News

The `news` entity includes title, slug, preview, content, publication state, publish timestamp, author relation, cover image, SEO fields, sorting, views counter, and soft deletes.

### Users And Roles

Users can be blocked and are assigned tags through a many-to-many relation. Access to Filament and news management is controlled through tag slugs such as `admin`, `news_admin`, and `author`.

### Exports

News exports are tracked as separate records connected to Laravel job batches. Progress is derived from batch state, and export files are version-aware on S3-compatible storage.

## Implemented Functional Areas

### Authentication

- Breeze-based auth flow extended with API auth
- Sanctum-based personal access tokens
- Email verification and profile management
- Cloudflare Turnstile verification on register/login API endpoints

### News Module

- Web CRUD screens
- Livewire modal actions for create, update, preview, and delete
- Sorting, pagination, and list query logic
- Authorization rules depending on user role and ownership
- API read access for authenticated users

### Export Pipeline

- News is exported in chunks into CSV files
- Chunks are stored on S3-compatible storage
- Finalization job merges chunks into a single export file
- Export progress and completion are tracked through job batches
- Object version listing is supported for previously generated export files

### Documentation And Testing

- Swagger/OpenAPI docs for API contracts
- Feature tests for auth/profile/news behavior
- Unit tests for news sorting/query logic
- Internal task and overview docs that describe implementation stages

## Resume-Relevant Experience Signals

This project can be used to demonstrate experience in:

- building full-stack Laravel applications
- designing authenticated REST APIs
- implementing role-based authorization
- integrating CAPTCHA/anti-bot protection
- building admin panels with Filament
- creating reactive CRUD interfaces with Livewire
- documenting APIs with OpenAPI/Swagger
- implementing queue-based background processing
- exporting data to CSV in scalable batches
- integrating S3-compatible object storage
- writing feature and unit tests for business behavior

## Possible Resume Formulations

### Short Version

Developed a Laravel 12 application with Blade, Livewire, Filament, and Sanctum, including authenticated web/API flows, role-based access control, news CRUD, admin tools, OpenAPI documentation, and background CSV export to S3-compatible storage.

### Stronger Backend Version

Built a full-stack Laravel application with a token-protected API, role-aware content management, queue-based export processing, Cloudflare Turnstile validation, OpenAPI documentation, and Filament-based administration for users, tags, news, and export monitoring.

### Full Version

Implemented a multi-layer Laravel 12 project combining server-rendered pages, Livewire interactive CRUD, Sanctum API authentication, Filament admin resources, event/notification flows, Cloudflare Turnstile validation, and batch-based CSV exports to S3-compatible storage, with supporting feature and unit tests.

## Notes For Future Resume Use

- If you want, this file can be expanded into:
  - `resume-bullets.md` with 5-10 ready-made bullet points
  - `project-description-ru.md` for Russian resume platforms
  - `interview-talking-points.md` with concise architecture explanations
- Before using the text publicly, it is worth separating:
  - what is already implemented and verified in code
  - what exists only in docs/tasks as planned work