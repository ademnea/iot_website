# Fix 002: Authentication

- **Report IDs:** C1, C2, S2, S3, S4 (SRS FR-AUTH-1/2, NFR-SEC-1/2/3)
- **Severity:** Critical
- **Date:** 2026-10-20

## Problem
1. `/register`, `/registeradminform` and `POST /registeradmin` were public, so anyone could
   create an admin account. They were moved outside the `auth` group in commit `a9e5bae`
   (2025-05-27). Fortify's own `POST /register` was also enabled.
2. `insert_admin` stored the password as typed, and `iot_admin_login@login` compared it with
   `==`.
3. `GET /logmein?email=…&password=…` logged people in, so passwords ended up in server logs
   and browser history.
4. The custom login skipped Fortify's throttle, so brute-forcing passwords was unlimited.
5. Registration errors were flashed as `message`, but the register page only displays
   `$status`. Every failure therefore looked like a silent reload.

## Change
| File | Change |
|---|---|
| `routes/web.php` | Removed `GET /logmein`. `POST /mylogin` is rate-limited to 5 attempts per minute (named limiter `admin-login` since [fix 008](008-auth-pages-and-rate-limits.md); the first version used `throttle:5,1`, which shared its budget with the contact form). The three registration routes moved into the `auth` group. |
| `app/Http/Controllers/iot_admin_login.php` | `login` validates its input, uses `Auth::attempt()` (checks the bcrypt hash) and regenerates the session ID. It no longer stores `Session('id')`. `registerme` passes the flashed `message` to the view as `$status`. |
| `app/Http/Controllers/insert_data.php` (`insert_admin`) | Validates name, email (unique) and password (min 8, confirmed). Stores `Hash::make()`. Sends the first error back to the form. |
| `config/fortify.php` | `Features::registration()` commented out, which removes Fortify's public `POST /register`. |
| `routes/console.php` | New `php artisan admin:create {email} --name=` (prompts for the password and hashes it) and `php artisan admin:hash-passwords` (hashes any stored password that isn't already a hash, so it is safe to run more than once). |

## Deployment impact
- **Existing admins can't log in until `php artisan admin:hash-passwords` runs.** Run it as
  part of the deploy.
- Treat every existing password as compromised: it sat in plain text in the database, and
  possibly in logs through `/logmein`. Ask admins to change theirs after the deploy.
- The first admin on a fresh install is created with `php artisan admin:create`. Before
  this change it was a manual SQL insert.

## Verification
- `php artisan route:list`: the registration routes carry `Authenticate`, `mylogin` carries
  `ThrottleRequests:5,1`, and `/logmein` is gone.
- Regression tests in `tests/Feature/Fixes/AuthenticationFixesTest.php`:
  - guests can't reach registration
  - Fortify registration is disabled
  - new admins get hashed passwords
  - registration errors are shown on the form
  - login checks the hash
  - `/logmein` returns 404
  - the 6th login attempt in a minute gets HTTP 429
  - `admin:hash-passwords` converts legacy plain-text passwords
