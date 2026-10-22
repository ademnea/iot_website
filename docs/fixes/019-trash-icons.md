# Fix 019: The admin trash icons did nothing

- **Found by:** the phone and tablet check (018)
- **Severity:** Medium: admins could not remove a project, news item, publication or
  partner, or clear a value on the Home page
- **Date:** 2026-10-22

## Problem
There were 14 red trash icons on the admin pages. All of them were bare icons, with no
link, no form and no route behind them, so clicking one did nothing:
- **Projects:** one on each card (ongoing and past).
- **News:** one on each card (ongoing and past).
- **Products:** one on each publication row.
- **Home content:** one beside each of these:
  - logo, banner, lab name
  - vision, motto, mission, objectives
  - the team leader's message
  - each partner

The trash icons on Current Team already worked (they move a member to Past Team) and are
unchanged.

## Change
Each icon is now a delete button inside a form, and the browser asks for confirmation
first. The confirmation handler lives in `partials/admin-theme`, which every admin page
includes.

| Icon | Route | What it does |
|---|---|---|
| Project card | `POST /deleteproject` | Deletes the project with its photo rows. **Its prototypes are deleted with it** (`prototypes.project_id` cascades), and the confirmation names them, for example "Delete the project “Hive” and its 1 prototype?". |
| News card | `POST /deletenews` | Deletes the event and its photo rows. |
| Publication row | `POST /deletepublication` | Deletes the publication and its links to team members. |
| Partner | `POST /deletepartner` | Deletes the partner. |
| Logo, banner, lab name, vision, motto, mission, objectives, leader's message | `POST /clearhomefield` | Empties that one value. The public site then falls back: the default IoT-ra logo, a plain navy banner, the default name (`APP_NAME`), or the section is hidden. |

- All routes are admin only (the authenticated group), and ids are validated against
  their tables.
- `/clearhomefield` accepts only the eight fields above. Contacts and social links are not
  on the list; their "Show on the website" switches cover that.
- Uploaded files (photos, logos, documents) stay on disk, as they already do when one is
  replaced.

## Verification
`DeleteContentTest`:
- Each delete removes its row (and its photo rows) and leaves other rows untouched.
- Each Home value is cleared on its own, without changing the others.
- Fields outside the list and unknown ids are rejected.
- Visitors are sent to the login page and nothing is changed.
- The pages render the forms with their confirmation text.
