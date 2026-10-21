# Fix 017: Admin News list crashed on past events and edited the wrong event

- **Found by:** the page-data tests added for the refactor (`tests/Feature/Refactor/PageDataTest.php`)
- **Severity:** High: admins could not open News once any past event existed
- **Date:** 2026-10-21

## Problem
`/newscontent` (sidebar → **News**) had three defects:
1. **The page crashed.** The past-events loop was written as `$pastvent`, but the Edit link
   used `$pastevent`. As soon as any event was marked Past, the page returned a server error.
2. **Edit opened the wrong event.** The query selected `events.*, event_photos.*`. Both
   tables have `id` and `status` columns, so each row carried the *photo's* id. The Edit
   link then opened whichever event had that number, or none at all.
3. **Events were listed more than once.** An event with three photos appeared as three
   cards.

## Change
- `resources/views/newscontent.blade.php`: the loop variable is spelled consistently.
- `retrieve_data@fetch_events`:
  - Takes only the `photo` column from `event_photos`, so each row keeps the event's own
    `id` and `status`.
  - Lists each event once, with its first photo.
- Events without any photo are still left out (D9, awaiting a decision).

## Verification
`PageDataTest::test_admin_news_list_shows_each_event_once_with_a_working_edit_link`:
- **Setup:** one ongoing event, and one past event with two photos.
- **Expected:** the page opens, the past event is listed once, and its Edit link uses the
  event's own id.
- **Result:** the test failed with the server error before the fix and passes after it.
