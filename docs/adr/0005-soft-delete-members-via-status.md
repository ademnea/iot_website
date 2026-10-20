# ADR-0005: Soft-delete members via status; resource controllers going forward

- **Status:** Part A accepted (retroactive, 2023-11). Part B proposed.
- **Date:** 2026-10-20

## Context
When lab members leave, their records should disappear from the site but be restorable
and keep their history (publications, projects).

## Decision
**A (as built):** `members.status` enum `Active|Inactive`. "Delete" sets `Inactive` and
"restore" sets `Active`. Public queries filter on `Active`. `/oldteam` lists inactive
members.

**B (proposed):** Extend soft deletion to every content entity through Laravel's
`SoftDeletes` (`deleted_at`). Restructure into resource controllers
(`MemberController`, `ProjectController`, `EventController`, `PublicationController`,
`PartnerController`, `PrototypeController`, `SiteContentController`) with FormRequests
and Eloquent relationships. Migrate one entity at a time behind the existing URLs.

## Consequences
- ✅ (A) Simple and reversible.
- ❌ (A) It's a custom pattern. Every query has to remember the status filter, and the
  leader query already forgets it (`show_data.php:26-29`).
- ✅ (B) Global scopes apply the filter automatically, deletion works the same for every
  entity, and the structure is testable.
- ❌ (B) Refactoring effort. Needs feature tests first to avoid regressions.
