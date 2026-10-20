# Fix 011: Maintainer check suite with parallel DevSecOps scans

- **Report IDs:** T3 (no tests or CI for the lab's code), NFR-MAINT-2. Also covers the
  ADR-0008 supply-chain guard, and hardens the image added in fix 009.
- **Severity:** Medium
- **Date:** 2026-10-20

## Problem
- **No shared check.** Nothing ran the tests or scanned for problems, so maintainers had no
  common check to run before a merge or deploy. That gap is how the May 2025 "login fix"
  went out with public admin registration, and how the December 2025 malware zip sat
  unnoticed for ten months.
- **The fix 009 image itself.** Its first scan found **2 critical and 181 high**
  vulnerabilities, 56 of them already fixable. Causes: an out-of-date Debian base, plus the
  C compiler toolchain and `-dev` packages left over from building the PHP extensions.

## Change

### `scripts/check.sh`: one command, everything in Docker
```bash
scripts/check.sh              # guard → build → all jobs in parallel (≈30 s after the first build)
scripts/check.sh security     # only the security scanners
scripts/check.sh tests smoke  # any jobs by name
```

**Order of a run:**
1. The **guard** runs first and alone. It takes seconds and stops before anything is built
   from a tree that contains planted files.
2. The app and test **images build** together, and the stack starts. The run waits until
   the app passes its health check.
3. **Every remaining job runs in parallel**, so the run takes about as long as its slowest
   job (the test suite).
4. A **summary table** shows pass, warn or fail for each job with its time. The last lines
   of any failing or warning job are printed underneath.

| Job | Tool (pinned) | Fails when | Only reports |
|---|---|---|---|
| `guard` | git + grep | Committed archives/executables (`.zip .exe .dll .cmd .ps1 …`), webshell or obfuscation patterns in app code, a committed `.env` | — |
| `tests` | PHPUnit 12 in the `test` image | Any test fails | — |
| `audit` | `composer audit`, `npm audit` | A PHP package has a known advisory | npm build-tool advisories (not shipped) |
| `sast` | Semgrep 1.181.0, `p/php` + `p/secrets` | Any ERROR-severity finding | WARNING/INFO findings |
| `secrets` | Gitleaks v8.30.1 | A secret anywhere in git history, or in the current tree (tracked and new files) | — |
| `image` | Trivy 0.76.0 | A HIGH/CRITICAL vulnerability **with a fix available**, or a secret baked into the image | Unpatched upstream vulnerabilities; writes a CycloneDX **SBOM** |
| `config` | Trivy 0.76.0 (`trivy config`) | A HIGH/CRITICAL misconfiguration in the Dockerfile or compose file, unless accepted in `.trivyignore` | — |
| `lint` | Hadolint v2.15.1 | Error-level Dockerfile issues | — |
| `compile` | `artisan view:cache`, `route:list`, `config:cache` | A broken Blade template, route or config file | — |
| `smoke` | curl against the running site | Wrong status on key pages, missing security headers, `X-Powered-By` present, or a PHP file in `public/images` executes | — |

**Reports:** each run writes `reports/<timestamp>/`, which holds the job logs, `semgrep.json`
and `sbom.cdx.json`. The folder is git-ignored and excluded from the Docker build context.

**Scanner configuration:**
- `.gitleaks.toml`: the default rules, plus an allowlist for git-ignored local paths
  (`.env`, `vendor/`, `node_modules/`, `data/`, …).
- `.trivyignore`: accepted findings, each with its reason. Only one so far: `DS-0002`, the
  Apache parent process runs as root; the workers run as `www-data`.
- `.hadolint.yaml`: only errors fail. `DL3008` (pin apt versions) is ignored because the
  image uses `apt-get upgrade` instead.
- `scripts/lib/semgrep_summary.py`: turns the Semgrep JSON report into the pass/warn/fail
  result.

**When a check fails:**

| Failing job | What to do |
|---|---|
| `guard` | Remove the file. If it's a reviewed, legitimate binary, add its path to `ALLOWED_BINARIES` in the script. |
| `audit` | `composer update <package>`, then re-run. |
| `image` | `docker compose build --pull app` to pick up base-image fixes. |
| `secrets` | Rotate the secret first, then remove it. If it's in history, rewrite history (runbook step G4 in the incident report). |

### Image hardening (`Dockerfile`)
- `apt-get upgrade` during the build, so Debian security fixes are always included.
- **No compilers or `-dev` packages in the runtime image.** After building the extensions,
  everything is marked auto-installed. Only the shared libraries the `.so` files link
  against, plus the base image's runtime packages, are kept, and the rest is purged:
  `$PHPIZE_DEPS`, `libc6-dev` and its `linux-libc-dev` headers.
- A build-time assertion that `pdo_mysql`, `zip`, `gd`, `intl`, OPcache and `curl` are still
  present.
- `SHELL ["/bin/bash", "-o", "pipefail", "-c"]`, so a failure anywhere in a pipe fails the
  build.
- `HEALTHCHECK`: `curl -fsS http://127.0.0.1/login`. The container only counts as healthy
  when Apache, PHP and the database all answer. `docker compose up --wait` and the check
  suite rely on this.

| Image scan (HIGH + CRITICAL) | Before | After |
|---|---|---|
| Total | 183 (181 HIGH, 2 CRITICAL) | 59 (58 HIGH, 1 CRITICAL) |
| Fixable now | 56 | **0** |
| `-dev` / compiler packages | many, including gcc, g++ and kernel headers | 0 |
| Size | 612 MB | 604 MB |

The remaining critical is `CVE-2026-6653` in `libxml2`, which PHP's XML support needs. It's
a denial-of-service issue reported against the MinGW (Windows) build, Debian status
"affected" with no fix released. It's reported by the `image` job and will clear on a
rebuild once Debian ships a fix.

## Verification (2026-10-20)
- Full run: **all checks passed**. Steady-state duration is **32 s**; the parallel stage is
  bounded by the 27 s test suite, while the scanners take 5–20 s each in parallel. The
  first run, which compiles the PHP extensions, takes about 7 minutes.
- **Negative tests:**
  - An untracked `public/lib/owlcarousel/assets/planted-v9.zip` (the same pattern as the
    real attack) failed the `guard`.
  - A fake AWS key pair in an untracked `config/planted_secret.php` failed `secrets`.
  - Both files were then deleted.
- Current results: 0 Semgrep findings, 0 secrets in 55 commits of history, 0 HIGH/CRITICAL
  Docker misconfigurations, Hadolint clean.

## Next steps
- Run the same script in GitHub Actions on every pull request (ADR-0008). It already works
  without a TTY, and the exit code reflects the result.
- When the Tailwind 4 move clears the npm advisories, make `audit` fail on npm findings too.
