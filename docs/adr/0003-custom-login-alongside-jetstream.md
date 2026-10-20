# ADR-0003: Custom plaintext login alongside Jetstream/Fortify

- **Status:** Deprecated. To be superseded by ADR-0006.
- **Date:** 2023-05, modified 2025-05-26/27 (recorded 2026-10-20)

## Context
The default Jetstream login page didn't match the site's look, and the team wanted a
login page that shows the lab branding from `website_content`.

## Decision (as built)
Add `iot_admin_login` with routes `/oldlogin` (named `login`), `POST /mylogin` and
`GET /logmein`. It compares the submitted password to `users.password` as plain text,
then calls `Auth::login()` and stores `Session::put('id')`. Admins are created through
`insert_data@insert_admin`, which stores the raw password. Commit `a9e5bae` (2025-05-27)
moved the registration routes outside the `auth` middleware.

## Consequences
- ❌ Passwords are stored in plaintext (critical).
- ❌ Anyone can register as an admin (critical).
- ❌ Login over GET puts credentials in logs and history. There is no throttling.
- ❌ This login can't work with Fortify's hashed password reset, profile password change
  or 2FA. A password set through Fortify can't be used at `/mylogin`, and the reverse is
  also true.
- ❌ Forms depend on `Session('id')`, which only this login sets.
