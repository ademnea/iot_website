# Fix 012: Make the Profile & security page reachable

- **Report IDs:** W18 (found in the 2026-10-20 browser walkthrough)
- **Severity:** Medium (blocked the 2FA rollout)
- **Date:** 2026-10-20

## Problem
The Jetstream profile page (`/user/profile`) is where an admin changes their password,
turns on two-factor authentication and logs out other sessions. The sidebar link to it was
wrapped in a Blade comment (`{{-- … --}}`), so no admin page linked there. Admins could only
reach it by typing the URL. The organisation plans to make 2FA mandatory, so every admin
needs to find this page.

## Change
| File | Change |
|---|---|
| `resources/views/sidebar.blade.php` | Link restored at the top of the sidebar and labelled **"Profile & security"** (was "Profile", commented out). |

## Verification
- In Chrome, as the local test admin, the sidebar shows "Profile & security" and opens
  `/user/profile`. That page shows profile details, password change, **Two Factor
  Authentication → Enable**, browser sessions and account deletion.
- Regression test `StabilityFixesTest::test_admin_sidebar_links_to_profile_and_security`.
  The suite now has 54 passing tests.
