# ADR-0002: Query builder with verb-grouped controllers

- **Status:** Accepted (legacy, retroactive). Part B of ADR-0005 proposes the replacement.
- **Date:** 2023-04 (recorded 2026-10-20)

## Context
Early development optimised for speed. Each form needed a "save" endpoint quickly.

## Decision
Group controllers by operation instead of by entity: `show_data` (public reads),
`retrieve_data` (admin reads), `insert_data`, `edit_data`, `delete_stuff`,
`restore_stuff`. Persist with `DB::table()` and hand-written joins. Models only bind
table names.

## Consequences
- ✅ Fast to add a form when there's one developer.
- ❌ Adding an entity touches 4–6 files. There are no relationships, `$fillable`,
  FormRequests or policies.
- ❌ No validation layer, which is the root cause of most crashes in
  [defects report §4](../defects-and-malware-report.md).
- ❌ Non-PSR class names (`show_data`) and string route handlers make IDE navigation and
  route caching harder.
