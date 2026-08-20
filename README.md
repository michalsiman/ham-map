# ham-map.com

An interactive map for outdoor amateur radio activities — [WWFF](https://wwff.co/) (Flora & Fauna), [POTA](https://pota.app/) (Parks on the Air), [SOTA](https://www.sota.org.uk/) (Summits on the Air), [GMA](https://www.cqgma.org/) (Mountains Award), and [WWBOTA](https://wwbota.org/) (Bunkers on the Air) reference points shown together on one [Leaflet](https://leafletjs.com/)-based map, built by OK1SIM.

Live site: https://ham-map.com

## How it works

- `index.php` renders the map (PHP + Leaflet), querying activation points from a MySQL/MariaDB database.
- `cron/synchro*.php` are standalone scripts that download the public CSV/data feeds published by WWFF, POTA, SOTA, GMA and WWBOTA and load them into the database. They're meant to run daily via cron (see `docker/crontab`).
- `cron/synchroc.php` / `cron/synchrocenter.php` seed the `country` reference table (country codes and map-centering coordinates) — this rarely changes, so it's a one-off/occasional script rather than part of the daily schedule.
- `aprs.php` shows live temperature readings from a handful of APRS weather stations via [aprs.fi](https://aprs.fi/).

## Running with Docker

Requirements: Docker and Docker Compose.

1. Copy the environment template and fill in your own values:

   ```
   cp .env.example .env
   ```

   You'll need two free API keys:
   - **Mapy.cz** tiles: https://developer.mapy.cz/
   - **aprs.fi**: https://aprs.fi/page/api (only needed for `aprs.php`)

2. Start everything:

   ```
   docker compose up -d
   ```

   This starts three containers:
   - `web` — the PHP/Apache app, on http://localhost:8080
   - `db` — MariaDB, auto-provisioned from `sql/schema.sql` on first boot
   - `cron` — runs the daily data-sync jobs (`docker/crontab`)

3. The database starts empty. Either wait for the next scheduled cron run, or populate it immediately:

   ```
   docker compose exec cron php cron/synchro.php
   docker compose exec cron php cron/synchro2.php
   docker compose exec cron php cron/synchro3.php
   docker compose exec cron php cron/synchro4.php
   docker compose exec cron php cron/synchro5.php
   docker compose exec cron php cron/synchroc.php
   docker compose exec cron php cron/synchrocenter.php
   ```

## Running without Docker

Requirements: PHP 8.2+ with the `mysqli` extension, and a MySQL/MariaDB server.

1. Create a database and load `sql/schema.sql` into it.
2. Set the environment variables listed in `.env.example` (`DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`, `MAPY_CZ_API_KEY`, ...) in your web server / PHP-FPM environment.
3. Point your web server's document root at the repository root.
4. Schedule `cron/synchro*.php` to run daily (see `docker/crontab` for the recommended schedule).

## Contributing

Issues and pull requests are welcome. Please don't commit real API keys or database credentials — everything sensitive is read from environment variables (`settings/db_credentials.php`, `.env.example`).

## License

MIT — see [LICENSE](LICENSE).
