# ADR-0007: Uploads via `Storage` disk with generated filenames

- **Status:** Proposed
- **Date:** 2026-10-20

## Context
See ADR-0004 and defects C1/F4. Uploads collide, trust client names, and one path has
no validation.

## Decision
- Validate every upload with a shared rule set (`image|mimes:jpg,jpeg,png,webp|max:5120`;
  documents `mimes:pdf,doc,docx,xls,xlsx,csv`), including `images.*` arrays.
- Store with `$file->store('<area>', 'public')`, which generates a hashed name. Keep the
  original name in a separate column only if the UI needs it.
- Serve through `php artisan storage:link` (`/storage/...`). Views use
  `Storage::url($path)`.
- Delete the old file when a record's media is replaced or the record is force-deleted.
- Add a one-off migration command that moves existing `public/images/**` files into
  `storage/app/public/**` and rewrites the DB paths.
- Align PHP `upload_max_filesize` / `post_max_size` with the rules.

## Consequences
- ✅ No collisions. Nothing user-controlled ends up in an executable directory. Moving to
  S3 becomes a config change.
- ❌ One-time data migration. Views must change from string concatenation to
  `Storage::url`.
