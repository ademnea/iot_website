# Architecture Decision Records

Format: [Michael Nygard's ADR template](https://cognitect.com/blog/2011/11/15/documenting-architecture-decisions).
ADRs 0001–0005 record decisions **already embodied in the code** (written retroactively,
2026-10-20). ADRs 0006–0008 started as proposals; their status below shows how far each
has been carried out. ADRs 0009–0010 record decisions taken and deployed on 2026-10-21.

| # | Title | Status |
|---|---|---|
| [0001](0001-laravel-monolith-server-rendered.md) | Laravel 10 monolith with server-rendered Blade | Accepted (now Laravel 13, fix 010) |
| [0002](0002-query-builder-and-verb-grouped-controllers.md) | Query builder + verb-grouped controllers | Controllers superseded by 0009; query builder still in use |
| [0003](0003-custom-login-alongside-jetstream.md) | Custom plaintext login alongside Jetstream/Fortify | Superseded by 0006 (fixes 002, 010) |
| [0004](0004-local-public-directory-uploads.md) | Uploads written directly to `public/` with client filenames | Generated filenames done (fix 003, `Support\Upload`); still written to `public/` |
| [0005](0005-soft-delete-members-via-status.md) | Soft-delete members via `status` enum; resource controllers going forward | Accepted; part B carried out by 0009 |
| [0006](0006-single-auth-stack-fortify.md) | Single auth stack: Fortify, hashed passwords, invite-only admins | Implemented (fixes 002, 010) |
| [0007](0007-storage-disk-generated-filenames.md) | Uploads via `Storage` disk with generated filenames | Partly: generated names done; `Storage` disk not yet |
| [0008](0008-repository-hygiene-and-supply-chain.md) | Repository hygiene and supply-chain protection | Partly: malware removed, local guard in `scripts/check.sh`; branch protection and CI pending |
| [0009](0009-controllers-by-content-type.md) | Controllers grouped by content type; shared upload and page-data code | Accepted (2026-10-21) |
| [0010](0010-docker-behind-host-apache.md) | Run in Docker behind the server's Apache | Accepted, deployed 2026-10-21 |

New ADRs: copy [template.md](template.md), take the next number, open a PR.
