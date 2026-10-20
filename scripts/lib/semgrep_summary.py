"""Summarise a Semgrep JSON report for scripts/check.sh.

Exit codes: 0 no findings, 1 at least one ERROR finding, 3 only WARNING/INFO findings.
"""
import collections
import json
import sys

results = json.load(open(sys.argv[1]))["results"]
by_severity = collections.Counter(r["extra"]["severity"] for r in results)

for r in results:
    print(f'{r["extra"]["severity"]:8} {r["path"]}:{r["start"]["line"]} {r["check_id"]}')
print(f"semgrep: {len(results)} findings {dict(by_severity)}")

if by_severity.get("ERROR"):
    sys.exit(1)
sys.exit(3 if results else 0)
