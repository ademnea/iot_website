# ADR-0004: Uploads written directly to `public/` using client filenames

- **Status:** Deprecated. To be superseded by ADR-0007.
- **Date:** 2023-04 (recorded 2026-10-20)

## Context
Images and documents had to be viewable straight away with no extra configuration on
basic hosting.

## Decision (as built)
`$file->move(public_path('images/<area>'), $file->getClientOriginalName())`. Only the
bare filename is stored in the DB, and views prepend the directory path. `public/images`
and `public/documents` are git-ignored.

## Consequences
- ✅ Zero configuration. The web server serves the files directly.
- ❌ Same-name uploads overwrite each other across records.
- ❌ An attacker-controlled filename lands in an executable web root. One endpoint
  (`insert_news`) skips type validation entirely.
- ❌ Orphaned files are never removed. Moving to S3 or a CDN means touching every
  controller and view.
- ❌ Media isn't in git or the DB, so it needs its own backup plan.
