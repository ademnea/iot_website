# IoT-ra Lab Website

The public website and content-management console for the IoT-ra (Internet of Things - Research & Applications) Lab
at Makerere University (CoCIS, Block B). Visitors browse the lab's team, projects, prototypes, publications, news
and partners. Admins log in to edit all of it without touching code.

**Stack:** Laravel 13 · PHP 8.4 · Blade · Jetstream 5 / Fortify (login, 2FA, password reset)
· MariaDB 11.4 · Apache. It runs in Docker, isolated from anything else on the server.

## Quick start (Docker)

You need Docker with the Compose plugin. PHP, Node and MySQL don't need to be installed.

```bash
git clone git@github.com:ademnea/iot_website.git
cd iot_website
cp .env.example .env
```

In `.env`, set `DB_PASSWORD` and `DB_ROOT_PASSWORD` (any strong values), then:

```bash
docker compose run --rm --no-deps app php artisan key:generate --show   # put the output in .env as APP_KEY=
docker compose up -d --build                                            # site on http://127.0.0.1:8080
docker compose exec app php artisan admin:create you@example.org --name="Your Name"
```

Log in at **`/login`** and add the site content first: lab name, mission, logo and banner.
Until that content exists, the public home page sends visitors to the login page.

| Task | Command |
|---|---|
| Stop / start | `docker compose stop` / `docker compose start` |
| Logs | `docker compose logs -f app` |
| Artisan | `docker compose exec app php artisan <command>` |
| Database shell | `docker compose exec db sh -c 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"'` |
| Rebuild after pulling | `docker compose up -d --build` |
| Reset an admin's password | `docker compose exec app php artisan admin:password you@example.org` |
| Turn off an admin's 2FA (lost phone) | `docker compose exec app php artisan admin:reset-2fa you@example.org` |

On every start the container runs the migrations, hashes any admin passwords still stored as
plain text (`admin:hash-passwords`), and caches the config and views.

## Checks to run before every merge or deploy

```bash
scripts/check.sh
```

One command runs everything in Docker: the repository guard, the test suite and the
DevSecOps scans. After the guard and the image build, the jobs run **in parallel**, so the
security scans add almost no time. A typical run takes about 30 seconds; the first run takes
longer because it builds the images.

| Job | What it checks |
|---|---|
| `guard` | No archives/executables committed (the [Dec 2025 malware](docs/incident-report-2025-12-malware.md) pattern), no webshell code, no `.env` files |
| `tests` | The PHPUnit suite against a throwaway database (`iot_website_test`) |
| `audit` | Known vulnerabilities in PHP packages (fails) and npm build tools (reported) |
| `sast` | Semgrep static analysis of the code |
| `secrets` | Gitleaks: secrets in git history and the working tree |
| `image` | Trivy: fixable vulnerabilities or secrets in the Docker image; writes an SBOM |
| `config` | Trivy: Dockerfile/compose misconfigurations |
| `lint` | Hadolint: Dockerfile issues |
| `compile` | Every Blade template, route and config file loads |
| `smoke` | The running site answers correctly, sends security headers, and refuses to run scripts from uploads |

To run specific jobs, name them (`scripts/check.sh tests smoke`), or use
`scripts/check.sh security` for the scanners only. Logs, the Semgrep report and the SBOM
are saved in `reports/<timestamp>/`. The meaning of each job, and what to do when one
fails, is in [docs/fixes/011](docs/fixes/011-maintainer-checks.md).

## Deploying on the server

1. Clone the repo on the server and create `.env` as in the quick start, with
   `APP_ENV=production`, `APP_DEBUG=false` and `APP_URL=https://<your domain>`.
   Fill in the `MAIL_*` settings with the lab's email account, so "Forgot password?" can send
   reset emails.
2. Copy existing uploads into `./data/images` and `./data/documents`. To bring the database
   over, load a `mysqldump` with `docker compose exec -T db mariadb …` (see
   [fix 009](docs/fixes/009-containerisation.md)).
3. Run `docker compose up -d --build`. The site listens on `127.0.0.1:8080` only.
4. Have the host's web server proxy the domain to it (Apache example in
   [fix 009](docs/fixes/009-containerisation.md)). `TRUSTED_PROXIES=*` in `.env` is safe
   while the port is bound to localhost.
5. Run `scripts/check.sh smoke` against the live stack.

**Back up** `./data/` and the database:
`docker compose exec db sh -c 'mariadb-dump -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' > backup.sql`.

## Project layout

| Path | Contents |
|---|---|
| `app/Http/Controllers/` | `show_data` (public pages), `retrieve_data` (admin lists), `insert_data` / `edit_data` (create/update), `edit_forms` (edit pages) |
| `resources/views/` | Public pages (Bootstrap theme) and admin pages (Tailwind) |
| `routes/web.php` | All routes; admin pages are behind `auth` |
| `routes/console.php` | `admin:create`, `admin:hash-passwords` |
| `docker/`, `Dockerfile`, `docker-compose.yml` | The container setup |
| `scripts/check.sh` | The maintainer check suite |
| `tests/Feature/Fixes/` | Regression tests for every fixed defect |
| `docs/` | Documentation (below) |

## Documentation

- [Workflows](docs/workflows.md): how to do everything in the admin area, step by step
- [docs/](docs/README.md): SRS, architecture, system design, SDD and ADRs
- [Defects and malware report](docs/defects-and-malware-report.md) and the
  [fix log](docs/fixes/README.md)
- [Incident report: Dec 2025 malware](docs/incident-report-2025-12-malware.md), including a
  runbook of the clean-up commands

## Security

Report vulnerabilities privately to the lab maintainers, not in a public issue.

- Never commit archives or executables (`.zip`, `.exe`, `.dll`, `.cmd`). `scripts/check.sh`
  fails if you do.
- Admin accounts are created only by another admin or with `php artisan admin:create`.
- Two-factor authentication is available on each admin's profile page and is enforced at
  login.
