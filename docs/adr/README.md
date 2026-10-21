# Architecture Decision Records

Format: [Michael Nygard's ADR template](https://cognitect.com/blog/2011/11/15/documenting-architecture-decisions).
ADRs 0001–0005 record decisions **already embodied in the code** (written retroactively,
2026-10-20). ADRs 0006–0008 are **proposals** that need team sign-off before work starts.

| # | Title | Status |
|---|---|---|
| [0001](0001-laravel-monolith-server-rendered.md) | Laravel 10 monolith with server-rendered Blade | Accepted |
| [0002](0002-query-builder-and-verb-grouped-controllers.md) | Query builder + verb-grouped controllers | Controllers superseded by 0009; query builder still in use |
| [0003](0003-custom-login-alongside-jetstream.md) | Custom plaintext login alongside Jetstream/Fortify | Deprecated, to be superseded by 0006 |
| [0004](0004-local-public-directory-uploads.md) | Uploads written directly to `public/` with client filenames | Deprecated, to be superseded by 0007 |
| [0005](0005-soft-delete-members-via-status.md) | Soft-delete members via `status` enum; resource controllers going forward | Accepted / Proposed (part B) |
| [0006](0006-single-auth-stack-fortify.md) | Single auth stack: Fortify, hashed passwords, invite-only admins | Proposed |
| [0007](0007-storage-disk-generated-filenames.md) | Uploads via `Storage` disk with generated filenames | Proposed |
| [0008](0008-repository-hygiene-and-supply-chain.md) | Repository hygiene and supply-chain protection | Proposed (urgent) |
| [0009](0009-controllers-by-content-type.md) | Controllers grouped by content type; shared upload and page-data code | Accepted (2026-10-21) |

New ADRs: copy [template.md](template.md), take the next number, open a PR.
