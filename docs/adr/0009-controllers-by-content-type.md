# ADR-0009: Controllers grouped by content type

- **Status:** Accepted. Supersedes the controller part of ADR-0002 and carries out part B of
  ADR-0005.
- **Date:** 2026-10-21
- **Deciders:** Lab maintainers

## Context
The controllers were grouped by operation (ADR-0002): `insert_data`, `edit_data`,
`edit_forms`, `retrieve_data` and so on. That spread one content type across four or five
files. A project's list, add form, edit page and save lived in four controllers. The same
code was also repeated many times over:

| Repeated code | Copies |
|---|---|
| Upload handling (generate a name, move the file) | 15 |
| Image validation rule | 15 |
| Site-content query, made for every page | 19 |
| Six random member photos, loaded although only the gallery shows them | 11 |

Each change had to be made in several places, and one copy was missed: the admin News list
crashed once a past event existed (fix 017).

## Decision
- **One controller per content type.** In `app/Http/Controllers/`:
  - `PublicPageController` (the public pages)
  - `HomeContentController`, `PartnerController`, `ProjectController`, `EventController`,
    `PublicationController`, `PrototypeController`, `MemberController`
  - `AdminAccountController`, `MessageController`
- **Each controller owns its content type's list, add, edit page and save.** Method names
  follow Laravel's resource conventions (`index`, `store`, `edit`, `update`). The home
  content has one `edit…`/`update…` pair per form.
- **URLs don't change.** Admins' bookmarks and every form keep working. `routes/web.php`
  groups the routes by controller.
- **Shared code lives in one place:**
  - `App\Support\Upload`: the upload rules (`IMAGE`, `DOCUMENT`) and `store()`.
  - Base `Controller::findForEdit()` and `dateInput()`, used by every edit page.
  - A view composer in `AppServiceProvider` that gives every view needing it the site
    content (`$contents`).
- **Persistence:** queries still use `DB::table()`. Moving to Eloquent relationships is a
  separate step.
- **The old controllers are kept** in `legacy/controllers/` for reference. Nothing loads
  them.

## Consequences
- **Easier:**
  - Everything about one kind of content is in one file.
  - A new content type is added by copying one controller.
  - Changing the upload rules or the stored-name scheme is a one-line change.
- **Same behaviour:**
  - `tests/Feature/Refactor/AdminWritesTest.php` pins down every form: redirect, message,
    saved values and stored file. It passed before and after the move.
  - 38 pages rendered byte-for-byte the same before and after.
- **Harder:** anyone used to the old file names needs the mapping table in
  `legacy/controllers/README.md`.

## Alternatives considered
- **Laravel resource routes (`Route::resource`).** Rejected for now, because it would
  change every URL. Old bookmarks and the forms' `action` attributes would break.
- **FormRequest classes for validation.** Deferred. Each rule set is used by a single
  action, so separate classes would add files without removing duplication.
