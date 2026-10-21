# ADR-0010: Run the site in Docker behind the server's Apache

- **Status:** Accepted, deployed 21 Oct 2026
- **Date:** 2026-10-21
- **Deciders:** Lab maintainers

## Context
The site ran directly on the shared server (defect D13):
- **Shared runtime.** It used the server's Apache, PHP-FPM 8.2 and MySQL, together with
  `smarthive.iot-ra.net`, `tasks.iot-ra.net` and other sites. Its PHP version, extensions
  and upload limits were whatever the server had, and a change for one site could break
  another.
- **Web-served code folder.** The code lived under `/var/www/iot-ra.net/public_html`, a
  folder left over from the cPanel host.
- **Content loss.** When the server was moved from cPanel, the database was recreated
  empty, and no copy of the content survived.

Fix 009 had already containerised the app for development and testing. The server has
Docker (Engine 29, Compose v5). Its Apache owns ports 80 and 443 for every site, with
Let's Encrypt certificates.

## Decision
- **Docker Compose** runs the site from `/srv/iot-website`:
  - an app container with Apache, PHP 8.4 and Laravel;
  - its own MariaDB container;
  - uploads in `./data`.
- **Not publicly reachable on its own.** The app is published only on `127.0.0.1:8095`;
  8080 is used by the host's Apache.
- **The host's Apache stays the front door.** It handles HTTPS for iot-ra.net and proxies
  to the container (`ProxyPass`, `X-Forwarded-Proto: https`). The other sites' configs are
  untouched.
- **Production settings** live in `.env` on the server:
  - `APP_ENV=production`, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`;
  - random database passwords.
- **Nginx isn't used.** It's installed on the server but serves nothing.

## Consequences
- **Same environment everywhere.** The site runs the same PHP, extensions and limits as
  development and the check suite.
- **Safe starts.** Migrations and password hashing run on every start, so they can't be
  forgotten.
- **Isolated.** Changes to the server's PHP or MySQL can't break the site, and the site
  can't break the other sites.
- **Simple rollback.** Restoring one Apache config file brings back the old site, as long
  as it still exists.
- **Separate backups.** The database lives in a Docker volume, so it needs its own
  backup, as in [deployment.md §5.5](../deployment.md#55-backups).
- **Container skills needed.** Maintainers need basic Docker Compose commands; the
  deployment guide lists them.

## Alternatives considered
- **Keep running on the host's PHP-FPM.** Rejected: it doesn't fix D13, and the
  production runtime would keep differing from the tested one.
- **Replace the host's Apache with Nginx.** Rejected for now. It would mean migrating
  every site's config and certificate on the server, with brief downtime for all of them,
  for no benefit to this site. Swapping Apache for Nginx *inside* this site's container is
  possible later without affecting anyone else.
- **Publish the container directly on 80/443.** Impossible, because Apache already owns
  those ports for the other sites.
