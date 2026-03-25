# ReelRoute

ReelRoute is a simple Laravel movie website built to meet the coursework rubric with a clear MVC structure, database-backed content, AJAX interactions, responsive design, version control history, and two live integrations for cinema planning.

## Features

- Laravel MVC structure with routes, controllers, Eloquent models, Blade views, migrations, and seeders.
- Database-backed movie catalogue with showtimes and user reviews.
- IMDb-linked movie importing through the OMDb API.
- AJAX movie search and filtering without full page reloads.
- AJAX review submission on movie detail pages.
- Browser geolocation to find nearby cinemas on an interactive map.
- Weather API integration to support cinema trip planning.
- Responsive layout for desktop and mobile.
- Local storage shortlist so users can save films while browsing.

## Marking Criteria Coverage

- Architectural pattern: Laravel MVC is used throughout, with movies, showtimes, and reviews stored in the database.
- Third-party API: live weather data is fetched programmatically with Open-Meteo.
- Third-party API: IMDb-linked movie data can be fetched from the OMDb REST API.
- Mobile and hardware APIs: browser geolocation is used to find cinemas near the user and display them on a map.
- RIA / AJAX: live catalogue search and review submission are both handled asynchronously.
- Version control: the repository is intended to be developed and tracked in staged commits with issues, labels, and milestones.

## APIs Used

- Open-Meteo for weather forecasts.
- Overpass / OpenStreetMap data for nearby cinema discovery.
- OMDb for IMDb-linked movie search and movie details.
- Browser Geolocation API for user location on mobile and desktop devices.

## Local Setup

```bash
composer install
cp .env.example .env
mysql -u root -p -e "CREATE DATABASE reelroute CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan key:generate
php artisan migrate:fresh --seed
npm install
npm run build
php artisan serve
```

Before running migrations, edit `.env` if your MySQL host, username, password, or database name differs from the defaults in `.env.example`.

To enable automatic IMDb-linked importing, add your OMDb API key to `.env`:

```bash
OMDB_API_KEY=your_omdb_key
```

When the key is present, featured movies are refreshed automatically and search requests can import matching OMDb results into the local MySQL catalogue.

For a university MySQL server, replace the local values with your own student database credentials.

## Development Commands

```bash
php artisan test
php artisan migrate:fresh --seed
php artisan movies:sync-imdb
npm run dev
```

## Deployment Notes

- The application is configured for MySQL by default.
- Create the target MySQL database first, then set the production `.env` values and run the migrations.
- The migrations are compatible with a standard PHP/MySQL student server.
- Build the frontend with `npm run build` before deployment.

## Assets

- No copyrighted movie posters are bundled.
- The poster areas are generated through CSS gradients and typography so the site stays visually complete without requiring licensed artwork.
