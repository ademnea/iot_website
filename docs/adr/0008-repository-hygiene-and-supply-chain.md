# ADR-0008: Repository hygiene and supply-chain protection

- **Status:** Proposed (urgent)
- **Date:** 2026-10-20

## Context
Commit `4e4dcbe` (2025-12-25) added a Windows malware loader
(`public/lib/owlcarousel/assets/iot_website-v1.5.zip`), and commit `af3c109` pointed
every README link at it. Both were pushed directly to `main` by `SoccerDevC`. Nothing
stopped or flagged this. Details are in the
[defects report §2](../defects-and-malware-report.md#2-malware).

## Decision
1. **Immediate:** delete the zip, restore the README, and remove the zip from the
   deployed server. Rotate collaborator tokens and audit collaborators and deploy keys.
2. **Branch protection on `main`:** PRs required, at least 1 review, no force-push,
   signed commits encouraged.
3. **CI (GitHub Actions):** `composer install`, `php artisan test`, and a check that
   fails on binary or archive files under `public/` (`*.zip, *.exe, *.dll, *.cmd, *.bat,
   *.scr, *.msi`) or anything git-ignored that is still tracked.
4. **Dependencies:** commit `composer.lock` / `package-lock.json` deliberately (remove
   them from `.gitignore`), run `composer audit` and `npm audit` in CI, and enable
   Dependabot.
5. **Front-end vendor code:** keep one copy only (`public/lib` or Vite-bundled, not both
   `public/lib` and `resources/lib`). Pin CDN assets with SRI hashes or bundle them.
6. **README:** replace the Laravel boilerplate with a real project README (setup,
   deploy, links to `docs/`).

## Consequences
- ✅ Malicious or accidental binaries can't land on `main` unnoticed. Dependency
  vulnerabilities get surfaced.
- ❌ Slightly slower merges because of review requirements.
