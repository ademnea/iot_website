#!/usr/bin/env bash
# Maintainer checks for the IoT Lab website: tests plus DevSecOps scans. Everything runs in
# Docker, so no local PHP, Node or scanner installs are needed.
#
#   scripts/check.sh              everything: guard, build, then all checks in parallel
#   scripts/check.sh security     only the security scanners, in parallel
#   scripts/check.sh <job>...     specific jobs, in parallel:
#                                 guard tests audit sast secrets image config lint compile smoke
#
# Logs, the SBOM and scanner reports go to reports/<timestamp>/. Exit code is non-zero if any
# job fails. Jobs that only report (e.g. npm build tools, unpatched OS packages) show "warn".
set -uo pipefail
cd "$(dirname "$0")/.."

# Scanner versions are pinned so results don't change between runs without a reviewed bump.
GITLEAKS_IMAGE=zricethezav/gitleaks:v8.30.1
SEMGREP_IMAGE=semgrep/semgrep:1.181.0
TRIVY_IMAGE=aquasec/trivy:0.76.0
HADOLINT_IMAGE=hadolint/hadolint:v2.15.1
NODE_IMAGE=node:22-alpine
APP_IMAGE=iot-website:latest
SCANNER_CACHE=iot-scanner-cache   # Docker volume holding the Trivy vulnerability database

# Archives/executables that are known and legitimate. Anything else committed fails the guard.
ALLOWED_BINARIES=()

RUN_ID=$(date +%Y%m%d-%H%M%S)
REPORTS="$PWD/reports/$RUN_ID"
mkdir -p "$REPORTS"

BOLD=$'\033[1m'; RED=$'\033[31m'; GREEN=$'\033[32m'; YELLOW=$'\033[33m'; RESET=$'\033[0m'
pass() { printf '✓ %s\n' "$*"; }
warn() { printf '! %s\n' "$*"; touch "$REPORTS/${JOB:-main}.warn"; }
fail() { printf '✗ %s\n' "$*" >&2; exit 1; }

# Tracked files plus new files not yet committed (anything not git-ignored).
repo_files() { git ls-files --cached --others --exclude-standard -- "$@"; }

app_port() { local p; p=$(grep -E '^APP_PORT=' .env 2>/dev/null | cut -d= -f2); echo "${p:-8080}"; }

# ---------------------------------------------------------------------------------------
# Jobs. Each one prints to its own log and exits non-zero on failure.
# ---------------------------------------------------------------------------------------

job_guard() {
    local bad hits allowed
    bad=$(repo_files | grep -iE '\.(zip|rar|7z|tar|gz|exe|dll|so|cmd|bat|scr|msi|jar|ps1|vbs|apk)$' || true)
    for allowed in "${ALLOWED_BINARIES[@]+"${ALLOWED_BINARIES[@]}"}"; do
        bad=$(printf '%s\n' "$bad" | grep -vxF "$allowed" || true)
    done
    if [ -n "$bad" ]; then
        printf '%s\n' "$bad"
        fail "archives or executables are committed (see docs/incident-report-2025-12-malware.md). Remove them, or add a reviewed path to ALLOWED_BINARIES."
    fi
    pass "no archives or executables committed"

    hits=$(repo_files '*.php' '*.js' '*.htaccess' \
        | grep -vE '\.min\.js$|^(public|resources)/lib/' \
        | xargs -r grep -nE 'eval\(|base64_decode\(|gzinflate\(|str_rot13\(|shell_exec\(|passthru\(|proc_open\(|assert\(\$_' || true)
    [ -z "$hits" ] || { printf '%s\n' "$hits"; fail "code patterns typical of webshells or obfuscated malware were found."; }
    pass "no webshell or obfuscation patterns in app code"

    if repo_files | grep -E '(^|/)\.env(\.|$)' | grep -vE '\.env\.example$' | grep -q .; then
        fail ".env files with secrets are committed."
    fi
    pass "no .env files committed"
}

job_tests() {
    docker compose --profile test run --rm test php artisan test || fail "tests failed"
    pass "tests passed"
}

job_audit() {
    docker compose --profile test run --rm --no-deps test composer audit --locked \
        || fail "PHP packages have known vulnerabilities: run composer update <package> and re-check."
    pass "no known vulnerabilities in PHP packages"

    # npm packages are build tools only (a throwaway Docker build stage); nothing from
    # node_modules is in the production image or sent to browsers. Reported, not failing.
    if docker run --rm -v "$PWD":/app:ro -w /app "$NODE_IMAGE" npm audit --audit-level=high; then
        pass "no high-severity npm advisories"
    else
        warn "npm build tools have advisories (not shipped; see docs/fixes/010-dependency-upgrade.md)"
    fi
}

job_sast() {
    # Static analysis of the app code with Semgrep's PHP and secrets rules. ERROR findings
    # fail; WARNING/INFO are listed for review.
    docker run --rm -v "$PWD":/src:ro -v "$REPORTS":/out -w /src "$SEMGREP_IMAGE" sh -c '
        semgrep scan --config p/php --config p/secrets --metrics=off --quiet \
            --exclude vendor --exclude node_modules --exclude public/lib --exclude resources/lib \
            --exclude public/build --exclude data --exclude reports \
            --json --output /out/semgrep.json || exit 2
        python3 scripts/lib/semgrep_summary.py /out/semgrep.json'
    case $? in
        0) pass "no static-analysis findings" ;;
        3) warn "static analysis found warnings to review (reports/$RUN_ID/semgrep.json)" ;;
        1) fail "static analysis found ERROR-level issues (reports/$RUN_ID/semgrep.json)" ;;
        *) fail "semgrep did not run (network needed to fetch rules?)" ;;
    esac
}

job_secrets() {
    # Committed history first (anything ever pushed), then the current tracked tree.
    docker run --rm -v "$PWD":/repo:ro -w /repo "$GITLEAKS_IMAGE" git --no-banner --redact --config .gitleaks.toml . \
        || fail "secrets found in git history: rotate them, then remove them from history."
    pass "no secrets in git history"
    docker run --rm -v "$PWD":/repo:ro -w /repo "$GITLEAKS_IMAGE" dir --no-banner --redact --config .gitleaks.toml . \
        || fail "secrets found in the working tree (not yet committed): remove them before committing."
    pass "no secrets in the working tree"
}

job_image() {
    local trivy=(docker run --rm -v /var/run/docker.sock:/var/run/docker.sock -v "$SCANNER_CACHE":/root/.cache -v "$REPORTS":/out "$TRIVY_IMAGE")
    # Fail: any HIGH/CRITICAL vulnerability that already has a fix (rebuild to pick it up), or
    # any secret baked into the image.
    "${trivy[@]}" image --quiet --scanners vuln,secret --ignore-unfixed --severity HIGH,CRITICAL --exit-code 1 "$APP_IMAGE" \
        || fail "the image has fixable HIGH/CRITICAL vulnerabilities or a secret: docker compose build --pull app"
    pass "no fixable HIGH/CRITICAL vulnerabilities and no secrets in the image"

    # Report: vulnerabilities Debian has not patched yet.
    local total
    total=$("${trivy[@]}" image --quiet --scanners vuln --severity HIGH,CRITICAL --format table "$APP_IMAGE" | grep -m1 '^Total:' || true)
    [ -z "$total" ] || warn "unpatched upstream (no fix available yet): $total"

    # Software bill of materials for the record (reports/<run>/sbom.cdx.json).
    "${trivy[@]}" image --quiet --format cyclonedx --output /out/sbom.cdx.json "$APP_IMAGE" >/dev/null \
        && pass "SBOM written to reports/$RUN_ID/sbom.cdx.json"
}

job_config() {
    docker run --rm -v "$PWD":/src:ro -w /src -v "$SCANNER_CACHE":/root/.cache "$TRIVY_IMAGE" \
        config --quiet --skip-dirs vendor,node_modules --ignorefile .trivyignore --severity HIGH,CRITICAL --exit-code 1 . \
        || fail "Dockerfile/compose misconfiguration (HIGH/CRITICAL). Fix it or record a reasoned entry in .trivyignore."
    pass "no HIGH/CRITICAL misconfigurations in Docker files"
}

job_lint() {
    docker run --rm -i -v "$PWD/.hadolint.yaml":/.config/hadolint.yaml:ro "$HADOLINT_IMAGE" hadolint - < Dockerfile \
        || fail "Dockerfile lint errors"
    pass "Dockerfile lint clean"
}

job_compile() {
    docker compose run --rm --no-deps app sh -c 'php artisan view:cache && php artisan route:list >/dev/null && php artisan config:cache' \
        || fail "a Blade template, route or config file is broken."
    pass "views, routes and config load"
}

job_smoke() {
    local base="http://127.0.0.1:$(app_port)" got probe
    expect() {
        got=$(curl -s -o /dev/null -w '%{http_code}' "$base$1")
        [ "$got" = "$2" ] || fail "$1 returned $got, expected $2"
        pass "$1 → $2"
    }
    expect /login 200
    expect /forgot-password 200
    expect /about 200
    expect /oldlogin 302
    expect /dashboard 302
    expect /register 302
    expect /logmein 404

    curl -sI "$base/login" | grep -qi '^x-content-type-options: nosniff' || fail "security headers are missing"
    if curl -sI "$base/login" | grep -qi '^x-powered-by'; then fail "X-Powered-By header leaks the PHP version"; fi
    pass "security headers set, PHP version hidden"

    docker compose exec -T app sh -c 'printf "<?php echo \"EXECUTED\";" > public/images/.check-probe.php' || fail "could not write probe file"
    probe=$(curl -s -w ' %{http_code}' "$base/images/.check-probe.php")
    docker compose exec -T app rm -f public/images/.check-probe.php
    case "$probe" in *EXECUTED*) fail "a PHP file in public/images was executed";; esac
    pass "scripts in the upload folder are refused (${probe##* })"
}

# ---------------------------------------------------------------------------------------
# Runner
# ---------------------------------------------------------------------------------------

prepare() {
    printf '%sBuilding images (app + test) and starting the stack…%s\n' "$BOLD" "$RESET"
    docker volume create "$SCANNER_CACHE" >/dev/null
    docker compose --profile test build app test >"$REPORTS/build.log" 2>&1 \
        || { tail -20 "$REPORTS/build.log"; fail "image build failed (reports/$RUN_ID/build.log)"; }
    docker compose up -d --wait >>"$REPORTS/build.log" 2>&1 \
        || { docker compose logs --tail 30 app; fail "the stack did not become healthy (reports/$RUN_ID/build.log)"; }
}

run_parallel() {
    local names=("$@") pids=() name i rc failed=0
    printf '%sRunning in parallel:%s %s\n' "$BOLD" "$RESET" "${names[*]}"
    for name in "${names[@]}"; do
        (
            JOB=$name; start=$SECONDS
            # fail() exits the subshell directly, so record the duration on any exit.
            trap 'echo $((SECONDS - start)) > "$REPORTS/$JOB.time"' EXIT
            "job_$name"
        ) >"$REPORTS/$name.log" 2>&1 &
        pids+=($!)
    done

    printf '\n%-9s %-6s %6s  %s\n' JOB RESULT TIME LOG
    for i in "${!names[@]}"; do
        name=${names[$i]}
        wait "${pids[$i]}"; rc=$?
        local t; t=$(cat "$REPORTS/$name.time" 2>/dev/null || echo "?")
        if [ $rc -ne 0 ]; then
            printf '%-9s %s%-6s%s %5ss  reports/%s/%s.log\n' "$name" "$RED" fail "$RESET" "$t" "$RUN_ID" "$name"; failed=1
        elif [ -f "$REPORTS/$name.warn" ]; then
            printf '%-9s %s%-6s%s %5ss  reports/%s/%s.log\n' "$name" "$YELLOW" warn "$RESET" "$t" "$RUN_ID" "$name"
        else
            printf '%-9s %s%-6s%s %5ss\n' "$name" "$GREEN" pass "$RESET" "$t"
        fi
    done

    for name in "${names[@]}"; do
        if grep -q '^✗' "$REPORTS/$name.log" 2>/dev/null; then
            printf '\n%s── %s ──%s\n' "$RED" "$name" "$RESET"; tail -25 "$REPORTS/$name.log"
        elif [ -f "$REPORTS/$name.warn" ]; then
            printf '\n%s── %s ──%s\n' "$YELLOW" "$name" "$RESET"; grep '^!' "$REPORTS/$name.log"
        fi
    done
    return $failed
}

SECURITY_JOBS=(audit sast secrets image config lint)
ALL_JOBS=(tests "${SECURITY_JOBS[@]}" compile smoke)
NEEDS_STACK=" tests audit image compile smoke "

case "${1:-all}" in
    all)      jobs=(guard "${ALL_JOBS[@]}") ;;
    security) jobs=("${SECURITY_JOBS[@]}") ;;
    -h|--help|help) sed -n '2,11p' "$0"; exit 0 ;;
    *)        jobs=("$@") ;;
esac
for j in "${jobs[@]}"; do declare -F "job_$j" >/dev/null || { echo "unknown job: $j (try --help)" >&2; exit 2; }; done

started=$SECONDS
# The guard is quick and catches planted files before anything is built from the tree.
if [[ " ${jobs[*]} " == *" guard "* ]]; then
    printf '%sRepository guard%s\n' "$BOLD" "$RESET"
    ( JOB=guard; job_guard ) || exit 1
    jobs=("${jobs[@]/guard}"); read -ra jobs <<<"${jobs[*]}"
fi
[ ${#jobs[@]} -eq 0 ] && exit 0
for j in "${jobs[@]}"; do [[ "$NEEDS_STACK" == *" $j "* ]] && { prepare; break; }; done

if run_parallel "${jobs[@]}"; then
    printf '\n%sAll checks passed%s in %ss. Reports: reports/%s/\n' "$GREEN$BOLD" "$RESET" $((SECONDS - started)) "$RUN_ID"
else
    printf '\n%sSome checks failed%s (%ss). Reports: reports/%s/\n' "$RED$BOLD" "$RESET" $((SECONDS - started)) "$RUN_ID"
    exit 1
fi
