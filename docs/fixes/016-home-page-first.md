# Fix 016: The home page always shows first

- **Report IDs:** F5 (incident report item 21)
- **Severity:** Medium
- **Date:** 2026-10-21

## Problem
`show_data@fetch_home` redirected `/` to the login page whenever the `website_content`
table was empty. This was changed in `d83bb56` (2025-05-26), when the redirect pointed to
`/oldlogin`. On a fresh install, or after a lost database, visitors to the lab's address
saw an admin login form instead of a website.

## Change
- **`app/Http/Controllers/show_data.php`:** the redirect is removed. `/` always renders
  the home page.
- **`resources/views/index.blade.php`:** with no content yet, the heading falls back to the
  lab's name from `APP_NAME`. Sections with nothing to show stay hidden or show the neutral
  empty state, as on every other public page.
- **What admins see:** signed-in admins see the usual "hasn't been added yet" hints. Before
  any home content exists, those hints link to **Add New Home Content**
  (`/register_home`) instead of an edit page for a record that doesn't exist yet.

Admins reach the login page as before: the **Admin login** button in the header, `/login`,
or an old `/oldlogin` bookmark.

## Verification (2026-10-21)
- **Tests (`tests/Feature/ExampleTest.php`):**
  - A fresh install returns the home page (200) under the lab's name, with no admin
    hints.
  - A signed-in admin is pointed to `/register_home`.
- **Old-system admin (`AuthenticationFixesTest`):** a plain-text password as left by the
  old system is hashed by `admin:hash-passwords`, and then:
  - the admin logs in at `/login` with the same password;
  - the dashboard and every admin content page open.
- **Chrome:** `/` opens the home page for a visitor. `/oldlogin` lands on the same Admin
  Login card as before, and admin pages send signed-out visitors to it.
- **Totals:** 77 tests pass, 8 skipped.
