# Fix log

Fixes for the defects in [defects-and-malware-report.md](../defects-and-malware-report.md),
made on branch `fix/security-and-stability` (started 2026-10-20). Each fix has its own record:
the problem, what changed, how it was verified, and what is left.

| # | Fix | Report IDs | Severity | Status |
|---|---|---|---|---|
| [001](001-malware-removal.md) | Remove the malware zip, replace the README | M1 | Critical | Done in repo; server and other repos still pending |
| [002](002-authentication.md) | Hashed passwords, login-only admin registration, no GET login, rate limits | C1, C2, S2–S4 | Critical | Done, tests pass |
| [003](003-uploads.md) | Validate every upload, generated filenames, no script execution in upload folders | C1, S0, S5, D5 | Critical | Done, tests pass |
| [004](004-record-ownership.md) | Record owner taken from the session, not a hidden form field | S7, F2 | Medium | Done, tests pass |
| [005](005-input-validation.md) | Validate all form input before saving; show errors on the page | F2, F3, D2, D6, S8 | High | Done, tests pass |
| [006](006-edit-pages.md) | Edit pages load records by id instead of reading them from the URL | F1, F7 | High | Done, tests pass |
| [007](007-display-fixes.md) | Escape rich text (XSS), inactive leader hidden | S6, D1 | Medium | Done, tests pass |
| [008](008-auth-pages-and-rate-limits.md) | Password-reset pages work, forgot-password link fixed, separate login/contact rate limits | F9, F10 | High | Done, tests pass |
| [009](009-containerisation.md) | Run in its own containers (Apache + PHP 8.4 + MariaDB), upload limits, upload guard at Apache level | D13, F4 | Medium | Done, verified locally |
| [010](010-dependency-upgrade.md) | Upgrade Laravel 10 → 13 and all dependencies; one login through Fortify (fixes 2FA bypass) | D12, S9 | High | Done, tests pass |
| [011](011-maintainer-checks.md) | `scripts/check.sh`: tests + parallel DevSecOps scans (Semgrep, Gitleaks, Trivy, Hadolint, SBOM); hardened image | T3 | Medium | Done, all checks pass |
| [012](012-profile-link.md) | "Profile & security" sidebar link restored, so admins can reach 2FA | W18 | Medium | Done, tests pass |
| [013](013-public-redesign.md) | Public site redesign in the IoT-ra brand: shared layout, no CDN libraries, admin-only placeholders | W10–W12, W14, W17 | Medium | Done, tests pass |
| [014](014-show-hide-contacts-and-socials.md) | Show/hide switches for socials and contacts, social icons, tab icon, password help (graceful reset, `admin:password`, `admin:reset-2fa`), blue admin | — | Medium | Done, tests pass |
| [015](015-dashboard-shortcuts.md) | Dashboard "+" boxes are working shortcuts; no green or indigo left anywhere | W20 | Low | Done, tests pass |
| [016](016-home-page-first.md) | The home page always shows first, even before any content is added | F5 | Medium | Done, tests pass |

## Deploying these fixes

1. `composer install --no-dev --optimize-autoloader`
2. `php artisan migrate` (no new migrations in this batch, safe to run)
3. **`php artisan admin:hash-passwords`.** Run this immediately after deploying. Until it
   runs, existing admins can't log in, because their passwords are still stored as plain text.
4. Ask every admin to change their password. The old plain-text values must be treated as
   leaked.
5. Delete `public/lib/owlcarousel/assets/iot_website-v1.5.zip` from the server if it is there.
6. Check that `public/images/.htaccess` and `public/documents/.htaccess` were deployed.
   On Nginx, add the equivalent `location` rule (see [003](003-uploads.md)).
7. Set `APP_DEBUG=false` in the production `.env`.

## Not fixed in this batch

| Report ID | Defect | Why not yet |
|---|---|---|
| F8 | `APP_DEBUG=true` in `.env.example` | Correct for local development. Production must override it (deploy step 7). |
| D9 | Projects and events without a photo hidden on public pages | Changes what visitors see. Confirm with stakeholders first. |
| T1, T2, T4–T6 | Structural debt (resource controllers, Storage disk, duplicate libs) | See ADR-0005, 0007. Larger refactors, planned separately. T3 (tests/checks) is done in 011; CI still to wire up. |
| — | Prototype editing | Never implemented. The dead link was removed (006). |
| — | Admin roles, delete for projects/events/publications/partners, viewing messages | New features, not defects. |
