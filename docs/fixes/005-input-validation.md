# Fix 005: Input validation and visible errors

- **Report IDs:** F2, F3, D2, D6, S8 (NFR-REL-2, NFR-SEC-8)
- **Severity:** High (most of the admin-side HTTP 500 errors)
- **Date:** 2026-10-20

## Problem
1. Controllers validated files only. A blank required field (for example `designation`,
   `description` or `title`) or a duplicate member email or phone reached MySQL and failed
   with a `QueryException`, which is an HTTP 500.
2. `insert_prototype` used `$picname` even when no image was sent, so it failed with
   "Undefined variable".
3. `insert_news` and `insert_prototype` found the new row with `latest()->first()`. Two
   admins saving at the same moment could attach photos to the wrong record.
4. `edit_event` *required* a new image on every edit. When one was given, it overwrote
   **every** photo of the event with that single file.
5. In the `edit_*` methods, text was saved first and the image validated afterwards. A
   rejected image therefore still left a half-updated record.
6. No view displayed validation errors at all, so even the existing file checks failed
   silently.
7. The public contact form accepted anything at any rate.

## Change
| File | Change |
|---|---|
| `insert_data.php` | Every create method validates all its fields up front. Required fields match the `NOT NULL` columns. `members.email` and `members.phone` are unique. Dates and `end_date ≥ start_date` are checked, and `project_id` must exist. `insertGetId` replaces `latest()`. Prototype image is optional (`$picname = null`). |
| `edit_data.php` | `edit_partner`, `edit_project`, `edit_event`, `edit_publication`, `edit_team` and `edit_leader` validate everything (id exists, required fields, optional image or file) **before** any update. `edit_team` allows the member's own email (unique, ignoring own id). `edit_event` image is optional, and a new image is **added** to the event's photos. |
| `resources/views/sidebar.blade.php` | Shared red error banner. It appears on every admin page because they all include the sidebar. |
| `resources/views/about_us.blade.php` | Error list under the contact form. |
| `routes/web.php` | `POST /insert_message` is rate-limited to 5 per minute per IP (named limiter `contact`, see [fix 008](008-auth-pages-and-rate-limits.md)). |
| `insert_data.php` (`insert_message`) | Name, valid email and message required, matching the column length. |

Link fields (`website`, `link`, `project_link`) are validated as text up to 255 characters,
not as strict URLs. Admins often type `www.example.com` without a scheme, and rejecting
that would be a new usability problem.

## Behaviour changes admins will notice
- Forms now show a red message instead of crashing or silently reloading.
- Editing an event no longer needs a new photo. A photo that is uploaded is added to the
  gallery and doesn't replace the others. There is still no way to *remove* an event
  photo; that is a separate feature.

## Verification
`tests/Feature/Fixes/StabilityFixesTest.php`:
- empty forms return validation errors (not 500) for team, partner, publication and project
- a duplicate member email is a validation error
- the error banner renders
- a prototype can be added without an image
- an event can be edited without an image and keeps both of its photos
- the contact form rejects bad input
