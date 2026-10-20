# IoT-ra Lab Website — Documentation

This folder documents the **as-built** system in this repository as of 2026-10-20
(commit `af3c109`). It is the baseline to plan further work against: these
documents describe what the code *does today*, and they call out where that
differs from what it *should* do.

| Document | Purpose |
|---|---|
| [workflows.md](workflows.md) | How admins and visitors use the site, step by step, verified in Chrome on 2026-10-20 |
| [SRS.md](SRS.md) | Software Requirements Specification: scope, actors, functional and non-functional requirements |
| [architecture.md](architecture.md) | Architecture overview: context, containers, components, deployment, cross-cutting concerns |
| [system-design.md](system-design.md) | System design: data model, request flows, file storage, auth, routes |
| [SDD.md](SDD.md) | Software Design Description: module-by-module design, interfaces, known defects |
| [adr/](adr/README.md) | Architecture Decision Records: as-built decisions and proposed replacements |
| [incident-report-2025-12-malware.md](incident-report-2025-12-malware.md) | Incident report: the SoccerDevC malware commits, impact, how they were removed, open actions |
| [fixes/](fixes/README.md) | Fix log: one record per fix with problem, change, verification and deploy steps |
| [defects-and-malware-report.md](defects-and-malware-report.md) | Defects, malware and vulnerabilities that could crash, take down or compromise the site, with triage order |

## ⚠️ Read first: repository compromise

Commit `4e4dcbe` (2025-12-25, author `SoccerDevC`, message "Update README.md")
added `public/lib/owlcarousel/assets/iot_website-v1.5.zip`. It contains
`Launcher.cmd`, `luajit.exe`, `lua51.dll` and an obfuscated `clib.txt`, which is
the signature of a known Windows malware-loader campaign that spreads through
GitHub repositories. Commit `af3c109` then rewrote every link in the root
`README.md` to point at that zip.

**Do not download, extract or run that archive.** Remove it and restore the
README before doing anything else (see [ADR-0008](adr/0008-repository-hygiene-and-supply-chain.md)).
Anyone who downloaded the zip from GitHub on a Windows machine should treat that
machine as compromised.

## Conventions

- File references use `path:line` against commit `af3c109`.
- "Admin" means any row in the `users` table. The system has no roles, so every
  authenticated user has full control.
- "Must / should / may" in the SRS follow RFC 2119.
