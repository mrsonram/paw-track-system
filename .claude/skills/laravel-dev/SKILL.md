---
name: laravel-dev
description: Development workflow for PawTrack (Laravel 8 dog management system, VRU VET Project). Use when running the app, adding routes/controllers/models/views, running migrations or tests, or building front-end assets in this repo.
---

# Laravel Dev — PawTrack

Project-specific workflow for this Laravel 8 dog management app. Follow this instead
of generic Laravel guesses.

## Environment setup (first time)

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
# edit .env → DB_DATABASE / DB_USERNAME / DB_PASSWORD
php artisan migrate
```

## Run the app

```bash
php artisan serve      # http://localhost:8000
npm run watch          # rebuild assets on change (run in a second terminal)
```

Docker alternative: `docker compose up -d --build` (needs an external MySQL — set `DB_HOST`).

## Run tests

```bash
php artisan test          # preferred
./vendor/bin/phpunit      # direct
```

## Adding a feature — checklist

1. **Route** → add to `routes/web.php`. Match the surrounding style; prefer explicit
   `Route::get/post` over `Route::resource` unless all actions are implemented.
2. **Controller** → `app/Http/Controllers/`. Implement only the actions you route to.
3. **Validate input** → always `$request->validate([...])` before `Model::create()`.
   Do NOT pass raw `$request->all()` to `create()`.
4. **Model** → `app/Models/`. Set `$table`, `$fillable`, `$primaryKey`,
   `$timestamps = false` (matching existing models).
5. **Migration** → `php artisan make:migration ...`, then `php artisan migrate`.
6. **View** → `resources/views/<area>/`. Extend `layouts/app.blade.php` or
   `layouts/main.blade.php`. Use Bootstrap 4 markup like the existing views.

## Data model facts

- Core table is **`animals`** — used by `Pet`, `Admin`, AND `GoogleMap` models.
  Changing its columns affects all three; update every model.
- Columns: `name, species, marking, gender, collar, age, status, vet, owner, image,
  location, lat, lng`.
- `news`: `title, subtitle, detail` · `contacts`: `name, email, title, message`.

## Known traps (see REVIEW.md)

- `Contact` model has `$fillbale` (typo) — fix to `$fillable` before touching contacts.
- `GoogleMap` references columns (`city`, `title`, `description`) that don't exist in
  `animals`. Verify columns before relying on map data.
- `GoogleMapController@store` redirects to non-existent `/maps` route.

## Conventions

- Never commit `.env`, `/vendor`, `/node_modules`.
- Google Maps views need an API key to render.
- Keep controllers thin; keep queries with Eloquent (`Model::get()`), matching current code.
