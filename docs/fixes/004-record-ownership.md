# Fix 004: Record ownership from the session

- **Report IDs:** S7, part of F2 (NFR-SEC-6)
- **Severity:** Medium
- **Date:** 2026-10-20

## Problem
The create forms sent `<input type="hidden" name="admin" value="{{ Session('id') }}">`, and
the controllers saved that value as `admin_id`:
- Anyone could edit the field and attribute records to another admin.
- `Session('id')` was only set by the custom login. After a session expiry, or a login
  through Fortify, it was empty. `events.admin_id` and `publications.admin_id` are
  `NOT NULL`, so saving returned HTTP 500.
- `insert_team` stored a **user** id in `members.registered_by`, but that column is a
  foreign key to **members**. This either failed or pointed at the wrong member.

## Change
| File | Change |
|---|---|
| `insert_data.php` | `admin_id` comes from `auth()->id()` in `insert_partner`, `insert_news`, `insert_project` and `insert_publication`. `insert_team` stores `registered_by = null`, with a comment explaining the foreign-key mismatch. |
| `register_home`, `register_news`, `register_projects`, `register_publications`, `register_team` views | Hidden `admin` input removed. |
| `iot_admin_login.php` | No longer writes `Session('id')`. Nothing reads it any more. |

## Verification
`UploadFixesTest::test_owner_is_the_logged_in_admin_not_a_form_field`: posting
`admin=<another user's id>` still records the logged-in admin.

## Follow-up
`members.registered_by` should either reference `users.id` (needs a migration) or be
dropped. That decision is left to the team.
