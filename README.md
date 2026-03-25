# ReelRoute

ReelRoute is a simple Laravel movie website built to meet the coursework rubric with a clear MVC structure, database-backed content, AJAX interactions, responsive design, version control history, and two live integrations for cinema planning.

## Features

- Laravel MVC structure with routes, controllers, Eloquent models, Blade views, migrations, and seeders.
- Database-backed movie catalogue with showtimes and user reviews.
- AJAX movie search and filtering without full page reloads.
- AJAX review submission on movie detail pages.
- Browser geolocation to find nearby cinemas on an interactive map.
- Weather API integration to support cinema trip planning.
- Responsive layout for desktop and mobile.
- Local storage shortlist so users can save films while browsing.

## Marking Criteria Coverage

- Architectural pattern: Laravel MVC is used throughout, with movies, showtimes, and reviews stored in the database.
- Third-party API: live weather data is fetched programmatically with Open-Meteo.
- Mobile and hardware APIs: browser geolocation is used to find cinemas near the user and display them on a map.
- RIA / AJAX: live catalogue search and review submission are both handled asynchronously.
- Version control: the repository is intended to be developed and tracked in staged commits with issues, labels, and milestones.

## APIs Used

- Open-Meteo for weather forecasts.
- Overpass / OpenStreetMap data for nearby cinema discovery.
- Browser Geolocation API for user location on mobile and desktop devices.

## Local Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm install
npm run build
php artisan serve
```

Open the local Laravel address shown by `php artisan serve`.

## Development Commands

```bash
php artisan test
php artisan migrate:fresh --seed
npm run dev
```

## Deployment Notes

- The current local setup uses SQLite for convenience.
- The migrations are MySQL-friendly, so the project can be moved to a standard PHP/MySQL student server by changing the database values in `.env`.
- Build the frontend with `npm run build` before deployment.

## Assets

- No copyrighted movie posters are bundled.
- The poster areas are generated through CSS gradients and typography so the site stays visually complete without requiring licensed artwork.
