# Kinsta Reliability Audit

Capture a reliability checklist inside WordPress and export a JSON runbook report. The checklist aligns with agency-grade hosting practices (SLOs, runbooks, backups, caching, monitoring) so teams can track readiness for mission-critical launches.

## Features
- Admin checklist under Tools → Reliability Audit.
- JSON report snapshot for runbooks or client handoffs.
- Deterministic scoring so teams can track progress over time.

## Development

```bash
composer install
composer lint
composer test
```

## Notes
This plugin does not require a specific host, but it was inspired by reliability guidance for managed WordPress platforms.
