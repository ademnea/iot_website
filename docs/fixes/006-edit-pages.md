# Fix 006: Edit pages load records by id

- **Report IDs:** F1, F7, D8
- **Severity:** High
- **Date:** 2026-10-20

## Problem
List pages built links that carried every field in the URL, for example
`/editproject? id=5&name=…&description=…&participants=…`. The edit views then read them
with `$_GET['…']`. This crashed with "Undefined array key" (HTTP 500) when:
- **Past projects (always):** the link left out `link`.
- **Past events (always):** the link left out `link` and `enddate`, and sent `startdate`
  twice.
- **Any record whose text contained `&` or `#`:** values weren't URL-encoded, so `&` split
  them and `#` cut off the rest of the URL. Ordinary text like "R&D" or "C#" was enough.
- **Long descriptions:** these could exceed URL length limits (HTTP 414).

Date inputs also received `2024-01-02 09:00:00`, which a `type="date"` field can't display.
The prototype "Edit" link pointed at `/editprototype`, which doesn't exist, so it returned
404. The `Route::view('/editX')` registrations served the views without any controller.

## Change
| File | Change |
|---|---|
| `app/Http/Controllers/edit_forms.php` (new) | One method per edit page. Each validates `id`, loads the record (`abort 404` if missing) and passes `$form` with the same keys the old links used. Dates are cut to `Y-m-d`. `editleader` reads the leader's photo from `members`. |
| `routes/web.php` | The 11 `Route::view('/editX','editX')` lines now point to `edit_forms@editX`. |
| `edit*.blade.php` (11 views) | `$_GET['x']` → `$form['x']`. Values are still escaped with `{{ }}`. |
| `homecontent`, `aboutuscontent`, `oldteam`, `projectscontent`, `newscontent`, `publicationscontent` | 18 edit links reduced to `/editX?id={{ $record->id }}` (the 19th, `/editprototype`, was removed; see next row). |
| `publicationscontent.blade.php` | Dead `/editprototype` link removed, with a Blade comment left in its place. |

## Verification
- `grep -rn '\$_GET' resources/views` returns nothing.
- `php artisan view:cache` compiles every template.
- `StabilityFixesTest`:
  - a project whose description contains `R&D … #2` opens and shows it escaped
  - past project and past event pages open, and the event date shows as `2024-01-02`
  - list links contain only the id
  - a missing id returns 404
  - all 11 edit pages render
