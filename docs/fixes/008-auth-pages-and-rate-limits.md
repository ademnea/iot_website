# Fix 008: Password-reset pages, the forgot-password link, and separate rate limits

- **Report IDs:** F9, F10. Also corrects a flaw in the first version of fixes 002 and 005.
- **Severity:** High
- **Date:** 2026-10-20

## Problem
1. **F9.** Fortify's pages render the customised `auth/login` view and the
   `authentication-card` component without the `$contents` (lab logo) and `$status`
   variables those views use. These pages returned HTTP 500, so admins couldn't reset a
   forgotten password:
   - `/login`
   - `/forgot-password`
   - `/reset-password/{token}`
   - `/user/confirm-password`
2. **F10.** The "Forgot password" link on `/oldlogin` and `/register` pointed to
   `/forgot_password`, which returns 404.
3. **Found while testing this batch.** Fixes 002 and 005 first used `throttle:5,1` on both
   `POST /mylogin` and `POST /insert_message`. Laravel keys that kind of limit by
   domain and IP only, so both forms shared **one** budget of 5 requests per minute per
   IP. On a campus network many people share one public IP, so five failed admin logins
   would have blocked the contact form for everyone, and the reverse. The regression tests
   caught this in the container before the change was ever deployed.

## Change
| File | Change |
|---|---|
| `app/Providers/AppServiceProvider.php` | A view composer for `auth.*` and `components.authentication-card` that supplies `$contents` and `$status` when the controller didn't. It only queries when one of those views renders, so it's safe before migrations. It replaces the commented-out `View::share` attempt. |
| `resources/views/oldlogin.blade.php`, `register.blade.php` | Link changed to `/forgot-password`. |
| `app/Providers/RouteServiceProvider.php` | Two named limiters: `admin-login`, 5 per minute per **email + IP** (the same rule Fortify uses), and `contact`, 5 per minute per IP. |
| `routes/web.php` | `throttle:admin-login` on `POST /mylogin`, `throttle:contact` on `POST /insert_message`. |

## Verification
- Jetstream's own tests now pass, including the login screen, the reset-link screen, the
  reset-password screen and the confirm-password screen.
- `AuthenticationFixesTest::test_failed_logins_do_not_use_up_the_contact_form_limit`:
  after 6 failed logins from one IP, the contact form still accepts a message.
- `AuthenticationFixesTest::test_login_is_rate_limited` still gets HTTP 429 on the 6th
  failed login.
