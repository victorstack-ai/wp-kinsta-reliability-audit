<?php

declare(strict_types=1);

namespace KinstaReliabilityAudit\Tests;

use DateTimeImmutable;
use KinstaReliabilityAudit\ReportBuilder;
use PHPUnit\Framework\TestCase;

final class ReportBuilderTest extends TestCase
{
    public function testBuildReportSummarizesStatuses(): void
    {
        $builder = new ReportBuilder();
        $siteInfo = [
            'name' => 'Example',
            'url' => 'https://example.com',
            'wp_version' => '6.6',
            'php_version' => '8.2',
        ];
        $statuses = [
            'slo' => 'pass',
            'incident_runbook' => 'fail',
        ];
        $date = new DateTimeImmutable('2026-02-06T12:00:00+00:00');

        $report = $builder->build($siteInfo, $statuses, $date);

        $this->assertSame('2026-02-06T12:00:00+00:00', $report['generated_at']);
        $this->assertSame($siteInfo, $report['site']);
        $this->assertSame(13, $report['summary']['total']);
        $this->assertSame(1, $report['summary']['passed']);
        $this->assertSame(1, $report['summary']['failed']);
        $this->assertSame(11, $report['summary']['unknown']);
        $this->assertSame(8, $report['summary']['score']);
    }

    public function testBuildNormalizesInvalidStatus(): void
    {
        $builder = new ReportBuilder();
        $report = $builder->build(
            ['name' => 'X'],
            ['slo' => 'maybe'],
            new DateTimeImmutable('2026-02-06T00:00:00+00:00')
        );

        $statuses = array_column($report['items'], 'status', 'id');
        $this->assertSame('unknown', $statuses['slo']);
    }
}
