# Fix 010: Upgrade Laravel 10 → 13 and all dependencies

- **Report IDs:** D12. Also S9 (2FA bypass), found during the upgrade.
- **Severity:** High
- **Date:** 2026-10-20

## Problem
- **Vulnerable packages.** `composer audit` reported **48 known vulnerabilities in 14 PHP
  packages**.
- **Unsupported framework.** Laravel 10 stopped receiving security fixes in February 2025,
  so the framework advisories couldn't be fixed without a major upgrade.
- **Old scaffolding.** Jetstream 3 and Livewire 2 were two major versions behind, and the
  front-end build used Vite 4.

## Versions
| Package | Before | After |
|---|---|---|
| PHP (runtime) | 8.1+ (host's) | 8.4 (container) |
| `laravel/framework` | v10.48.29 | v13.38.0 |
| `laravel/jetstream` | v3.3.3 | v5.5.3 |
| `laravel/fortify` | v1.25.4 | v1.41.0 |
| `laravel/sanctum` | v3.3.3 | v4.3.3 |
| `livewire/livewire` | v2.12.8 | v3.8.10 |
| `laravel/tinker` | v2.10.1 | v3.0.2 |
| `guzzlehttp/guzzle` | 7.9.3 | 8.3.0 |
| `phpunit/phpunit` | 10.5.46 | 12.5.40 |
| `nunomaduro/collision` | v7.12.0 | v8.9.5 |
| `spatie/laravel-ignition` | 2.9.1 | 2.12.1 |
| `vite` / `laravel-vite-plugin` | 4.3 / 0.7 | 8.3 / 3.2 |
| `tailwindcss` | 3.3 | 3.4 (kept on 3; see below) |
| Alpine.js | separate package | removed (bundled with Livewire 3) |

**Result:** `composer audit` now reports **0 advisories**.

## Change
1. **`composer.json`.** New constraints (`php ^8.3`, `laravel/framework ^13.0`, Jetstream 5.5,
   Livewire 3.8, Sanctum 4.3, Tinker 3, PHPUnit 12, Collision 8), then `composer update -W`.
   `jenssegers/agent` dropped out, because Jetstream 5 no longer needs it.
2. **App structure kept.** The Laravel 10 layout (`app/Http/Kernel.php`, middleware
   classes, `RouteServiceProvider`) is still supported in Laravel 13 and was left as it is.
3. **Jetstream/Fortify scaffolding refreshed from the Jetstream 5 stubs.** Only files that
   already existed were replaced; the Teams feature wasn't added. 67 files:
   - `app/Actions/Fortify/PasswordValidationRules.php`
   - `app/Actions/Fortify/ResetUserPassword.php`
   - `app/Actions/Fortify/UpdateUserPassword.php`
   - `app/Actions/Fortify/UpdateUserProfileInformation.php`
   - `app/Models/User.php`
   - `app/Providers/JetstreamServiceProvider.php`
   - `config/sanctum.php`
   - `postcss.config.js`
   - `resources/views/api/api-token-manager.blade.php`
   - `resources/views/api/index.blade.php`
   - `resources/views/auth/confirm-password.blade.php`
   - `resources/views/auth/forgot-password.blade.php`
   - `resources/views/auth/two-factor-challenge.blade.php`
   - `resources/views/auth/verify-email.blade.php`
   - `resources/views/components/action-message.blade.php`
   - `resources/views/components/action-section.blade.php`
   - `resources/views/components/application-logo.blade.php`
   - `resources/views/components/authentication-card-logo.blade.php`
   - `resources/views/components/banner.blade.php`
   - `resources/views/components/button.blade.php`
   - `resources/views/components/checkbox.blade.php`
   - `resources/views/components/confirmation-modal.blade.php`
   - `resources/views/components/confirms-password.blade.php`
   - `resources/views/components/danger-button.blade.php`
   - `resources/views/components/dialog-modal.blade.php`
   - `resources/views/components/dropdown.blade.php`
   - `resources/views/components/dropdown-link.blade.php`
   - `resources/views/components/form-section.blade.php`
   - `resources/views/components/input.blade.php`
   - `resources/views/components/input-error.blade.php`
   - `resources/views/components/label.blade.php`
   - `resources/views/components/modal.blade.php`
   - `resources/views/components/nav-link.blade.php`
   - `resources/views/components/responsive-nav-link.blade.php`
   - `resources/views/components/secondary-button.blade.php`
   - `resources/views/components/section-border.blade.php`
   - `resources/views/components/section-title.blade.php`
   - `resources/views/components/switchable-team.blade.php`
   - `resources/views/components/validation-errors.blade.php`
   - `resources/views/components/welcome.blade.php`
   - `resources/views/layouts/app.blade.php`
   - `resources/views/layouts/guest.blade.php`
   - `resources/views/navigation-menu.blade.php`
   - `resources/views/policy.blade.php`
   - `resources/views/profile/delete-user-form.blade.php`
   - `resources/views/profile/logout-other-browser-sessions-form.blade.php`
   - `resources/views/profile/show.blade.php`
   - `resources/views/profile/two-factor-authentication-form.blade.php`
   - `resources/views/profile/update-password-form.blade.php`
   - `resources/views/profile/update-profile-information-form.blade.php`
   - `resources/views/terms.blade.php`
   - `tailwind.config.js`
   - `tests/Feature/ApiTokenPermissionsTest.php`
   - `tests/Feature/AuthenticationTest.php`
   - `tests/Feature/BrowserSessionsTest.php`
   - `tests/Feature/CreateApiTokenTest.php`
   - `tests/Feature/DeleteAccountTest.php`
   - `tests/Feature/DeleteApiTokenTest.php`
   - `tests/Feature/EmailVerificationTest.php`
   - `tests/Feature/PasswordConfirmationTest.php`
   - `tests/Feature/PasswordResetTest.php`
   - `tests/Feature/ProfileInformationTest.php`
   - `tests/Feature/RegistrationTest.php`
   - `tests/Feature/TwoFactorAuthenticationSettingsTest.php`
   - `tests/Feature/UpdatePasswordTest.php`
   - `vite.config.js`
   - `database/factories/UserFactory.php`
4. **Customisations kept or re-applied:**
   - `auth/login.blade.php` and `components/authentication-card.blade.php` were kept as
     they were.
   - The layouts take the page title from `APP_NAME` (was hard-coded "Mak-IoT").
   - The layouts no longer load Tailwind 2.1 from unpkg on top of the Tailwind 3 built by
     Vite. The two conflicted.
   - The dark-mode classes are back, but `darkMode: 'class'` keeps the pages light, as the
     earlier customisation intended.
   - `RegistrationTest` still checks that guests are sent to the login page.
5. **`User` model:** passwords now use the `'hashed'` cast. The old `UserFactory` had a
   hard-coded hash that the cast's safety check rejects, so it was replaced with the current
   one.
6. **Config:**
   - `config/sanctum.php` replaced with Sanctum 4's (new middleware class names).
   - `config/fortify.php` gets `lowercase_usernames => true`.
   - `FortifyServiceProvider` replaced with the current stub: a better login throttle key
     and the 2FA redirect.
7. **Front end.** `package.json` gets `"type": "module"`, Vite 8 and `laravel-vite-plugin` 3.
   The Tailwind/PostCSS/Vite configs are the Jetstream 5 versions. `resources/js/app.js` no
   longer starts a second copy of Alpine.

## S9: two-factor authentication bypass (found during the upgrade)
After the upgrade, two routes were named `login`: Fortify's `/login` and the lab's
`/oldlogin`. Tracing that showed the lab's own `POST /mylogin` logged admins in with
`Auth::attempt()` and **never asked for the 2FA code**. An admin who turned on 2FA on their
profile page wasn't protected by it.

Fix:
- **One login: Fortify's `POST /login`.** Fortify enforces 2FA, its 5-per-minute email+IP
  limit and lowercase emails.
- **The lab's branded page stays.** `Fortify::loginView()` renders `oldlogin.blade.php`,
  which now shows validation errors and posts to `/login`. "Remember me" is now wired up.
- **Old addresses.** `/oldlogin` redirects to `/login`. `POST /mylogin` and the controller
  methods behind it are removed. The navbar and the empty-site redirect point to `/login`.
- **Dashboard greeting.** The dashboard greets the logged-in admin by name instead of using
  a flash message that only the old login set.
- **Test.** `AuthenticationFixesTest::test_login_requires_the_two_factor_code_when_2fa_is_enabled`
  checks that a 2FA user is sent to `/two-factor-challenge` and isn't logged in.

## Known and accepted
- **npm (8 advisories, 5 high):** `braces` and `postcss-selector-parser`, brought in by
  Tailwind 3's build tools. They only run while the CSS is compiled, in a Docker build
  stage that's thrown away, so they aren't in the production image or sent to browsers. The
  fix is Tailwind 4, planned with the public-site redesign.
- **Public-page CDN libraries** (Bootstrap 5.0/5.3, Font Awesome 5.10, Flowbite 1.4/2.1,
  DataTables 1.10/1.13, the jQuery plugins in `public/lib`) are unchanged. The redesign
  replaces them.
- **Passkeys:** Fortify 1.41 supports them, but they're left off. That's a new feature with
  its own migration and UI.

## Verification
- `php artisan --version` reports Laravel 13.38.0, and all 94 routes load.
- `composer audit` finds no advisories.
- The test suite in the container passes: **53 passed, 8 skipped** (API tokens, email
  verification and public registration are switched off).
- The production image builds, every Blade template compiles, and the smoke test passes
  (`scripts/check.sh`).
