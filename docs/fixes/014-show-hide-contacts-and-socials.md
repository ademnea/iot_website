# Fix 014: Show/hide switches, icons, tab icon, password help, blue admin

- **Date:** 2026-10-21
- **Asked for by:** the lab, after the redesign walkthrough

## 1. "Show on the website" switches for social links and contacts
Admins can now hide a social link or contact detail without deleting it, and bring it back
later with one tick.

A detail appears on the public site only when it **has a value and its switch is on**. That
rule lives in `App\Support\SiteContent`, and the header strip, the footer and the Team &
contact page all use it.

| Where (admin) | Items with a switch |
|---|---|
| Home → Topbar information → pencil (`/edittopbar`) | X (Twitter), Facebook, Instagram, YouTube, LinkedIn |
| Home → Lab contacts → pencil (`/editcontacts`) | Address, email, phone |

- **Admin Home** marks switched-off items with a grey **"Hidden on the website"** badge.
- **Migration** `2026_10_21_000000_add_visibility_toggles_to_website_content` adds eight
  `*_visible` columns, **defaulting to on**, so nothing that was visible before disappears.
- **Labels:** the edit forms' labels are now linked to their inputs (part of W5).

## 2. Social icons
- **Icons instead of text names:** social links show as round icons, with each network's
  name as the accessible label.
- **Source:** X, Facebook, Instagram and YouTube come from Simple Icons 16.34.0 (CC0). The
  LinkedIn "in" mark is drawn for this site, because Simple Icons removed LinkedIn. See
  `docs/design/licenses/simple-icons.md`.

## 3. Browser tab icon
The site never had one:
- **`public/favicon.ico`** was Laravel's empty placeholder, and it was deleted in `7ba828c`
  (May 2023), so every page's icon request returned 404.
- **`img/favicon.ico`**, which the old pages linked to, never existed.

New icons, made from the IoT-ra logo's tower mark:
- `public/favicon.ico` (16/32/48 px), used by every page including the admin
- `public/img/favicon.ico`, which the legacy pages ask for
- `public/img/icon-192.png`
- `public/img/apple-touch-icon.png`

## 4. Password help
- **Change your own password (logged in):** Profile & security → Update Password. This
  already worked and is covered by `UpdatePasswordTest`.
- **Forgot password (logged out):** "Forgot password?" emails a reset link, which **needs
  working mail settings** (`MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`,
  `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS` in `.env`). The defaults point to `mailpit`, which
  doesn't exist on the server, and the request used to crash with an error page. Now the
  page says: *"The reset email couldn't be sent because email isn't set up on this server
  yet. Ask the site administrator to reset your password."* (`app/Exceptions/Handler.php`).
- **New server commands** for when email isn't available:
  - `php artisan admin:password you@example.org`: set a new password for an admin.
  - `php artisan admin:reset-2fa you@example.org`: turn off two-factor login for an admin who
    lost their authenticator app and recovery codes. They set it up again afterwards.

## 5. Blue admin pages
- **Before:** the admin area still used the old template's green, about 200 Tailwind
  `*-green-*` classes across 23 views.
- **One shared palette:** `resources/views/partials/admin-theme.blade.php` loads Tailwind's
  runtime CDN (now pinned to 3.4.17, previously unpinned) together with a `brand` palette
  built from the logo navy, where `brand-700` is `#034486`. The sidebar and the
  login/register/edit pages include it, and `@once` loads it only once per page.
- **The classes:** every `*-green-N` class became `*-brand-N`.
- **Still to do:** the full admin cleanup (bundling with Vite and removing the CDN
  libraries).

## Content note
The local test database had a fictional team leader, with an invented bio and an invented
"Word from the team leader". The leader is now **Dr Julianne Susanne Sansa Otim** (role:
Team Leader). The invented bio and message were **removed** rather than attributed to her,
so admins see a hint to add her real words. Her contact email in the local copy is a
placeholder; public pages don't show it.

## Verification
- New tests:
  - `tests/Feature/Fixes/VisibilityTogglesTest.php` (7 tests: switches, kept links,
    empty-value rule, contacts on every page, admin round trip, form state, tab icon)
  - In `AuthenticationFixesTest`: reset email when mail isn't set up, `admin:password`,
    `admin:reset-2fa`
- Suite: **71 passed**, 8 skipped. `scripts/check.sh` passes.
- Checked in Chrome:
  - With YouTube switched off it disappears from the public strip and shows "Hidden on the
    website" in the admin; switching it back on restores it.
  - The admin pages render in the brand blue.
  - The real logo, uploaded through Home → Logo, shows in the header.

## 6. The lab's name everywhere: IoT-ra (Internet of Things - Research & Applications) Lab
- **`APP_NAME`** (in `.env` and `.env.example`) is now the full name. Laravel uses it on the
  login, profile and password-reset pages and in password-reset emails.
- **Every admin page title** now reads "*Page* · *lab name*", for example "Edit social
  links · IoT-ra (…) Lab". Before, there were 26 inconsistent hard-coded titles such as
  "IoT - Home", "Admin - IoT" and "add-team". The sidebar's empty `<title>` was removed.
- **Public pages** use the lab name entered under Home → lab name (`Labname`). Until it's
  entered they use `APP_NAME`, in the tab title, the logo's alt text and the footer.
- **Docs, README, `composer.json` and the mockup** use the new name.
- **Deploying:** Laravel names its session cookie after `APP_NAME`, so **every admin has to
  log in again once** after this change goes live.
- **Test:** `PublicRedesignTest::test_the_lab_name_is_used_in_titles_and_footer_everywhere`.
  The suite now has 72 passing tests.
