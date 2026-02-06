<?php

declare(strict_types=1);

namespace KinstaReliabilityAudit;

final class Checklist
{
    /**
     * @return array<int, array<string, string>>
     */
    public static function items(): array
    {
        return [
            [
                'id' => 'slo',
                'title' => 'Service level objective defined',
                'category' => 'Operations',
                'description' => 'Define uptime/latency targets and error budgets for the site.',
                'evidence_hint' => 'Link to SLO doc or contract.',
            ],
            [
                'id' => 'incident_runbook',
                'title' => 'Incident runbook maintained',
                'category' => 'Operations',
                'description' => 'Document escalation paths, rollback steps, and comms templates.',
                'evidence_hint' => 'Runbook location and last review date.',
            ],
            [
                'id' => 'backups',
                'title' => 'Automated backups with retention',
                'category' => 'Resilience',
                'description' => 'Backups run on schedule and are periodically restored for verification.',
                'evidence_hint' => 'Backup schedule + restore drill notes.',
            ],
            [
                'id' => 'staging',
                'title' => 'Staging environment exists',
                'category' => 'Resilience',
                'description' => 'Changes are validated in a staging environment before production.',
                'evidence_hint' => 'Staging URL or environment ID.',
            ],
            [
                'id' => 'monitoring',
                'title' => 'Uptime and performance monitoring',
                'category' => 'Observability',
                'description' => 'Synthetic checks and real-user metrics are monitored.',
                'evidence_hint' => 'Monitoring provider or dashboard.',
            ],
            [
                'id' => 'security_patching',
                'title' => 'Security patching process',
                'category' => 'Security',
                'description' => 'Core, plugin, and theme updates are reviewed on a schedule.',
                'evidence_hint' => 'Patch cadence and ownership.',
            ],
            [
                'id' => 'waf',
                'title' => 'Web application firewall or edge protection',
                'category' => 'Security',
                'description' => 'Edge protection mitigates common attacks and abusive traffic.',
                'evidence_hint' => 'Provider and enabled rules.',
            ],
            [
                'id' => 'edge_cache',
                'title' => 'Edge or full-page caching',
                'category' => 'Performance',
                'description' => 'Cache strategy reduces origin load and improves stability.',
                'evidence_hint' => 'Cache layer, TTL, and purge workflow.',
            ],
            [
                'id' => 'object_cache',
                'title' => 'Object cache enabled',
                'category' => 'Performance',
                'description' => 'Persistent object cache reduces database contention.',
                'evidence_hint' => 'Redis/Memcached details.',
            ],
            [
                'id' => 'database_hygiene',
                'title' => 'Database hygiene checks',
                'category' => 'Performance',
                'description' => 'Slow queries are tracked and cleanup is scheduled.',
                'evidence_hint' => 'Query log or optimization notes.',
            ],
        ];
    }
}
