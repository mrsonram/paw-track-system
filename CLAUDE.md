# CLAUDE.md

Guidance for Claude Code (and developers) working in this repository.

## Project overview

**PawTrack (VRU VET Project 2021).** A Laravel 8 web application for
managing and displaying information about stray / community dogs. It has a public
site (dog listings, news, map, contact, about) and an admin panel for CRUD on dogs,
news, and viewing contact messages. Auth is provided by `laravel/ui`.

This is a **university project**; code favors simplicity over production hardening.
Comments in models are in Thai.

## Tech stack

- PHP 8.1 (composer requires `^7.3|^8.0`), Laravel 8.40
- Blade + Bootstrap 4.6 + jQuery + Vue 2.6, bundled with Laravel Mix (Webpack)
- MySQL
- Google Maps JavaScript API
- Docker (PHP-FPM 8.1) via `Dockerfile` / `docker-compose.yml`

## Common commands

```bash
composer install                 # PHP deps
npm install                      # JS deps
cp .env.example .env             # first-time setup
php artisan key:generate         # generate APP_KEY
php artisan migrate              # create tables
php artisan serve                # run dev server (http://localhost:8000)
npm run dev                      # build assets
npm run watch                    # rebuild on change
php artisan test                 # run tests (PHPUnit)
./vendor/bin/phpunit             # run tests directly
docker compose up -d --build     # run via Docker
```

## Architecture & conventions

- **Routing:** all routes are in `routes/web.php`. Mix of `resource` routes and
  explicit `GET`/`POST` definitions. Controllers are referenced both by string
  (`'PetController@index'`) and by class (`[PetController::class, 'index']`).
- **Controllers:** `app/Http/Controllers/`. Many resource methods are stubbed
  (`//`) — only the used actions are implemented.
- **Models:** `app/Models/`. `Pet`, `Admin`, and `GoogleMap` all map to the same
  `animals` table. `$timestamps = false` on most models. `$fillable` is used for
  mass assignment.
- **Views:** `resources/views/`, organized by area (`pet/`, `admin/`, `google/`,
  `pages/`, `auth/`, `layouts/`). Layouts: `layouts/app.blade.php`,
  `layouts/main.blade.php`.
- **Database:** migrations in `database/migrations/`. Core table is `animals`.

## Data model

- `animals`: `name, species, marking, gender, collar, age, status, vet, owner,
  image, location, lat, lng`
- `news`: `title, subtitle, detail`
- `contacts`: `name, email, title, message`
- `users`: Laravel default auth table

## Working notes / gotchas

- `Pet`, `Admin`, and `GoogleMap` models **share** the `animals` table. Be careful
  when changing one — check the others.
- `GoogleMap` model's `$fillable` includes `city`, and `GoogleMapController@index`
  reads `$value->title` / `$value->description`, but the `animals` table has no such
  columns. Verify columns before relying on these.
- `Contact` model has a typo: `$fillbale` instead of `$fillable` — mass assignment
  guard does not work as intended there.
- User input is passed straight to `Model::create($request->all())` without
  validation in several controllers. Add `$request->validate([...])` before writing.
- Google Maps needs an API key embedded in the relevant Blade views to render.
- See [`REVIEW.md`](REVIEW.md) for the full list of known issues.

## Conventions for changes

- Match the existing Blade/Bootstrap style already in the views.
- Keep new routes in `routes/web.php` consistent with the surrounding style.
- When touching the `animals` table, update the migration **and** check every model
  that maps to it (`Pet`, `Admin`, `GoogleMap`).
- Do not commit `.env`, `/vendor`, or `/node_modules` (already in `.gitignore`).
- Add validation to any controller action that writes user input to the database.
