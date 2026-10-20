# Fix 007: Escaped rich text and the inactive-leader query

- **Report IDs:** S6, D1 (NFR-SEC-5, FR-TEAM-1)
- **Severity:** Medium
- **Date:** 2026-10-20

## Problem
1. **Stored XSS.** Bios, descriptions, objectives and the leader's message were printed
   with `{!! preg_replace('/\r\n|\r|\n/', '<br>', $text) !!}`, which outputs the raw HTML
   an admin typed. A `<script>` in a bio would run for every visitor.
2. **Inactive leader shown.** `show_data@fetch_team` built
   `role='researcher' AND status='Active' OR role='leader'`. SQL evaluates `AND` before
   `OR`, so a leader marked Inactive still appeared on the About page.

## Change
| File | Change |
|---|---|
| 15 outputs in `about_us`, `index`, `homecontent`, `news`, `projects`, `events_gallery`, `project_gallery`, `prototypes_gallery`, `team_gallery` | `{!! nl2br(e($text)) !!}`. The text is HTML-escaped first, then line breaks become `<br />`. Same look, no executable HTML. |
| `show_data.php` (`fetch_team`) | `where('status','Active')->where(fn ($q) => $q->where('role','researcher')->orWhere('role','leader'))`. |

`policy.blade.php` and `terms.blade.php` still use `{!! !!}`. They render Markdown files
from the repository (`resources/markdown/`), not user input.

## Verification
`StabilityFixesTest::test_about_page_hides_inactive_leader_and_escapes_bios`: an inactive
leader isn't shown, a `<script>` bio is printed as escaped text, and line breaks still become
`<br />`.
