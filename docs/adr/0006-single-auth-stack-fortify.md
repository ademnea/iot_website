# ADR-0006: Single authentication stack: Fortify with hashed passwords and invite-only admins

- **Status:** Proposed
- **Date:** 2026-10-20

## Context
See ADR-0003 and defects C1/C2. Two parallel auth paths, plaintext passwords and
public admin registration are the most serious problems in the codebase after the
malware.

## Decision
1. Remove `/logmein`, `/mylogin`, `/registeradminform`, `/registeradmin` and the custom
   `/register`. Use Fortify's `POST /login`, and keep the branded login page by
   registering it with `Fortify::loginView(...)`.
2. Disable `Features::registration()`. Admins are created by an existing admin from a
   protected screen, or with `php artisan make:admin` (new command), using
   `Hash::make` / `CreateNewUser`.
3. Migration step: a one-off command rehashes existing plaintext passwords
   (`Hash::make($user->password)` where `Hash::info($user->password)['algo'] === null`),
   then admins are told to change their passwords.
4. Replace hidden `admin` fields with `auth()->id()` in controllers.
5. (Optional) Add `is_admin` or a role column plus a Gate for future role separation.

## Consequences
- ✅ Hashed passwords, throttling, reset, 2FA and session-logout all work.
- ✅ No more anonymous admin creation.
- ❌ Every existing admin has to re-verify or reset after the rehash.
- ❌ The Jetstream feature tests must be updated to match the disabled registration.

## Alternatives considered
Keep the custom login and add `Hash::check` (rejected: still duplicates Fortify and
misses throttling and 2FA).
