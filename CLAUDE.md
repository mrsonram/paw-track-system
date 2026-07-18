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
- **Controllers:** `app/Http/Controllers/`. Only implemented, routed actions exist —
  unused resource stubs have been removed.
- **Models:** `app/Models/`. `PetController`, `AdminController`, and
  `GoogleMapController` all share a single `Animal` model (`app/Models/Animal.php`)
  mapped to the `animals` table — there used to be three separate model classes
  (`Pet`/`Admin`/`GoogleMap`) with identical `$table`/`$primaryKey`, consolidated into
  one. `$timestamps = false` on most models. `$fillable` is used for mass assignment.
- **Views:** `resources/views/`, organized by area (`pet/`, `admin/`, `google/`,
  `pages/`, `auth/`, `layouts/`). Layouts: `layouts/app.blade.php`,
  `layouts/main.blade.php`.
- **Database:** migrations in `database/migrations/`. Core table is `animals`.

## Data model

- `animals`: `name, species, marking, gender, collar, age, status, vet, owner,
  image, location, lat, lng`. Only `name`/`lat`/`lng` are `NOT NULL` — the rest are
  nullable because the table serves two flows that don't share all columns: full
  dog profiles (`AdminController`, which validates all fields at the app level) and
  lightweight map pins (`GoogleMapController@add/@store`, which only collects
  `name`/`lat`/`lng`).
- `news`: `title, subtitle, detail`
- `contacts`: `name, email, title, message`
- `users`: Laravel default auth table

## Working notes / gotchas

- Google Maps needs an API key embedded in the relevant Blade views to render; it's
  currently hardcoded rather than read from config/env, and has no HTTP referrer
  restriction on Google Cloud Console — still open.
- See [`REVIEW.md`](REVIEW.md) for the full list of known issues and
  [`docs/plans/todo.md`](docs/plans/todo.md) for current status.

## Conventions for changes

- Match the existing Blade/Bootstrap style already in the views.
- Keep new routes in `routes/web.php` consistent with the surrounding style.
- When touching the `animals` table, update the migration **and** check
  `app/Models/Animal.php`'s `$fillable` and every controller that uses it
  (`PetController`, `AdminController`, `GoogleMapController`).
- Do not commit `.env`, `/vendor`, or `/node_modules` (already in `.gitignore`).
- Add validation to any controller action that writes user input to the database.
