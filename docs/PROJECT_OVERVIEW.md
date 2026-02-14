# Project Overview

## Goal

Training project for Laravel 12 with built-in auth flow from Breeze and a standard Blade frontend pipeline.

## Current Scope

- Web auth flow (register/login/password reset/email verification)
- Authenticated dashboard
- User profile management (`/profile`)
- Minimal API endpoint (`/api/user`) via Sanctum

## Main Folders

- `app/Http/Controllers` - web/auth controllers
- `routes` - route declarations (`web.php`, `auth.php`, `api.php`)
- `resources/views` - Blade templates
- `resources/js`, `resources/css` - frontend assets for Vite
- `tests/Feature` - feature tests for auth/profile flow

## Infrastructure

- Sail containers: app, mysql, redis, mailpit
- Frontend dev server: Vite (`npm run dev`)
