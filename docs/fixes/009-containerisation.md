# Fix 009: Run the site in its own containers

- **Report IDs:** D13, F4 (also F8 in practice)
- **Severity:** Medium
- **Date:** 2026-10-20

## Problem
The site had never been containerised. It ran on the shared server's own PHP and Apache:
- Its PHP version, extensions and `php.ini` limits were whatever that server had. The
  2 MB upload limit (F4) came from there.
- A change made for any other site on the host could break this one, and the reverse.
- Installing it meant following `installation.txt` by hand.

## Change
| File | Purpose |
|---|---|
| `Dockerfile` | Multi-stage build: Composer dependencies without dev packages, Vite assets built with Node 22, then a runtime on `php:8.4-apache` with `pdo_mysql`, `gd`, `zip`, `intl` and `opcache`. A `test` stage adds the dev dependencies and runs the test suite. |
| `docker-compose.yml` | `app` (the site), `db` (MariaDB 11.4 LTS, its own volume, not published to the host), and `test` (only with `--profile test`). The site is published on `127.0.0.1:8080` by default, for the host's web server to proxy. |
| `docker/php/php.ini` | `upload_max_filesize=12M`, `post_max_size=64M` (fixes F4), `display_errors=Off`, `expose_php=Off`, OPcache. |
| `docker/apache/000-default.conf` | Document root `public/`. An **Apache-level** rule denies `.php`/`.phtml`/`.phar`/scripts and turns off PHP in `public/images` and `public/documents`, so the upload guard from fix 003 holds even if a volume has no `.htaccess`. |
| `docker/apache/security.conf` | `ServerTokens Prod`, no signature, no TRACE, `nosniff`, `SAMEORIGIN`, a referrer policy. |
| `docker/entrypoint.sh` | Refuses to start the web server without `APP_KEY` (one-off commands like `key:generate` still run), prepares the storage and upload folders, waits for the database, runs `migrate --force`, **runs `admin:hash-passwords` on every start** (the deploy step from fix 002 can't be forgotten), and caches the config and views. |
| `docker/mariadb/init/01-test-database.sh` | Creates `iot_website_test` for the test suite when the database volume is first created. |
| `.dockerignore` | Keeps `.env`, `.git`, `vendor`, `node_modules`, uploads, logs and `docs/` out of the image. |
| `.env.example` | New settings: `DB_ROOT_PASSWORD`, `APP_BIND`, `APP_PORT`, `DATA_DIR`, `TRUSTED_PROXIES`. The default DB user is now `iot`, because the MariaDB image refuses `root`. |
| `config/app.php`, `app/Http/Middleware/TrustProxies.php` | `TRUSTED_PROXIES` setting, so client IPs (used for rate limits) and HTTPS links are correct behind the host's web server. |
| `phpunit.xml` | `<server … force="true">` entries. Docker environment variables otherwise **override** PHPUnit's `<env>` values. That made the first container test runs use the file cache and database sessions, and it could have pointed `RefreshDatabase` at the real database. |

Data that survives a rebuild:
- `./data/images` and `./data/documents` on the host (uploads; easy to back up as plain
  folders)
- the `storage` volume (sessions, cache, compiled views)
- the `db` volume (the database)

## Using it
```bash
cp .env.example .env            # set DB_PASSWORD, DB_ROOT_PASSWORD, APP_URL, APP_ENV=production, APP_DEBUG=false
docker compose run --rm app php artisan key:generate --show    # copy the value into .env as APP_KEY
docker compose up -d --build
docker compose exec app php artisan admin:create admin@example.org --name="Lab Admin"
docker compose --profile test run --rm test                    # run the test suite
```

On the server, the host's Apache proxies the domain to the container:
```apache
<VirtualHost *:443>
    ServerName iot.example.org
    ProxyPreserveHost On
    ProxyPass        / http://127.0.0.1:8080/
    ProxyPassReverse / http://127.0.0.1:8080/
    RequestHeader set X-Forwarded-Proto "https"
    # ... existing SSL certificate lines ...
</VirtualHost>
```
**Production differs from this example:** the live server's Apache already uses 8080, so
production sets `APP_PORT=8095` and proxies to `127.0.0.1:8095`. The exact config is in
[deployment.md §3.6](../deployment.md#36-switch-the-domain-to-the-new-site).

Moving existing uploads in: copy the server's current `public/images/*` and
`public/documents/*` into `./data/images` and `./data/documents` before the first
`docker compose up`. To bring over the existing database, `mysqldump` it and load it with
`docker compose exec -T db sh -c 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' < dump.sql`.

Backups: copy `./data/`, plus
`docker compose exec db sh -c 'mariadb-dump -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' > backup.sql`.

## Verification (2026-10-20, local Docker 29.9)
- Both images build. The site answers on `http://127.0.0.1:8080`: `/oldlogin` returns 200,
  and `/` redirects to `/oldlogin` on an empty database, as designed. The migrations and
  `admin:hash-passwords` ran on start.
- The responses carry `X-Content-Type-Options: nosniff` and `X-Frame-Options: SAMEORIGIN`,
  with no `X-Powered-By` header.
- A `.php` or `.phtml` file placed in `public/images` gets HTTP 403 and never runs; a
  `.txt` file is served normally.
- The test suite in the container: **51 passed, 8 skipped** (Jetstream features this site
  switched off: API tokens, email verification, registration).

## Notes
- `APP_DEBUG` still comes from `.env`. Set `APP_DEBUG=false` there in production (F8).
- On the host, the upload folders end up owned by `www-data` (uid 33). That's intended,
  so Apache in the container can write uploads.
