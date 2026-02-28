<?php

declare(strict_types=1);

namespace KinstaReliabilityAudit;

if (!defined('ABSPATH')) {
    exit;
}

use DateTimeImmutable;

final class ReportBuilder
{
    /**
     * @param array<string, mixed> $siteInfo
     * @param array<string, string> $statusById
     * @return array<string, mixed>
     */
    public function build(array $siteInfo, array $statusById, ?DateTimeImmutable $generatedAt = null): array
    {
        $generatedAt = $generatedAt ?? new DateTimeImmutable('now');
        $items = [];
        $passed = 0;
        $failed = 0;
        $unknown = 0;

        foreach (Checklist::items() as $item) {
            $status = strtolower((string) ($statusById[$item['id']] ?? 'unknown'));
            if (!in_array($status, ['pass', 'fail', 'unknown'], true)) {
                $status = 'unknown';
            }

            if ($status === 'pass') {
                $passed++;
            } elseif ($status === 'fail') {
                $failed++;
            } else {
                $unknown++;
            }

            $items[] = [
                'id' => $item['id'],
                'title' => $item['title'],
                'category' => $item['category'],
                'description' => $item['description'],
                'evidence_hint' => $item['evidence_hint'],
                'status' => $status,
            ];
        }

        $total = count($items);
        $score = $total > 0 ? (int) round(($passed / $total) * 100) : 0;

        return [
            'generated_at' => $generatedAt->format(DateTimeImmutable::ATOM),
            'site' => $siteInfo,
            'summary' => [
                'total' => $total,
                'passed' => $passed,
                'failed' => $failed,
                'unknown' => $unknown,
                'score' => $score,
            ],
            'items' => $items,
        ];
    }
}
