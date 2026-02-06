<?php

declare(strict_types=1);

namespace KinstaReliabilityAudit;

use DateTimeImmutable;

final class AdminPage
{
    private const OPTION_KEY = 'kinsta_reliability_audit_statuses';

    public function register(): void
    {
        add_action('admin_menu', [$this, 'registerMenu']);
        add_action('admin_post_kinsta_reliability_audit_save', [$this, 'handleSave']);
    }

    public function registerMenu(): void
    {
        add_submenu_page(
            'tools.php',
            'Reliability Audit',
            'Reliability Audit',
            'manage_options',
            'kinsta-reliability-audit',
            [$this, 'render']
        );
    }

    public function render(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $items = Checklist::items();
        $statuses = get_option(self::OPTION_KEY, []);
        if (!is_array($statuses)) {
            $statuses = [];
        }

        $report = (new ReportBuilder())->build(
            $this->siteInfo(),
            $this->sanitizeStatuses($statuses),
            new DateTimeImmutable('now')
        );
        $reportJson = wp_json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        echo '<div class="wrap">';
        echo '<h1>Reliability Audit</h1>';
        echo '<p>Track readiness signals for mission-critical WordPress delivery and export a JSON report for runbooks.'
            . '</p>';

        if (!empty($_GET['updated'])) {
            echo '<div class="notice notice-success is-dismissible"><p>Checklist updated.</p></div>';
        }

        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        wp_nonce_field('kinsta_reliability_audit_save');
        echo '<input type="hidden" name="action" value="kinsta_reliability_audit_save" />';
        echo '<table class="widefat striped">';
        echo '<thead><tr><th>Checklist Item</th><th>Status</th><th>Evidence Hint</th></tr></thead>';
        echo '<tbody>';

        foreach ($items as $item) {
            $current = $statuses[$item['id']] ?? 'unknown';
            echo '<tr>';
            echo '<td><strong>' . esc_html($item['title']) . '</strong><br /><span style="color:#646970;">'
                . esc_html($item['description'])
                . '</span></td>';
            echo '<td>' . $this->statusSelect($item['id'], $current) . '</td>';
            echo '<td>' . esc_html($item['evidence_hint']) . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '<p><button class="button button-primary" type="submit">Save Checklist</button></p>';
        echo '</form>';

        echo '<h2>JSON Report</h2>';
        echo '<textarea readonly rows="16" style="width:100%; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, '
            . 'Consolas, \"Liberation Mono\", \"Courier New\", monospace;">'
            . esc_textarea($reportJson ?: '')
            . '</textarea>';
        echo '</div>';
    }

    public function handleSave(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized', '', ['response' => 403]);
        }

        check_admin_referer('kinsta_reliability_audit_save');

        $raw = $_POST['status'] ?? [];
        if (!is_array($raw)) {
            $raw = [];
        }

        $sanitized = $this->sanitizeStatuses($raw);
        update_option(self::OPTION_KEY, $sanitized, false);

        wp_safe_redirect(add_query_arg('updated', '1', menu_page_url('kinsta-reliability-audit', false)));
        exit;
    }

    /**
     * @param array<string, mixed> $statuses
     * @return array<string, string>
     */
    private function sanitizeStatuses(array $statuses): array
    {
        $sanitized = [];
        $allowed = ['pass', 'fail', 'unknown'];

        foreach (Checklist::items() as $item) {
            $value = strtolower((string) ($statuses[$item['id']] ?? 'unknown'));
            $sanitized[$item['id']] = in_array($value, $allowed, true) ? $value : 'unknown';
        }

        return $sanitized;
    }

    /**
     * @return array<string, mixed>
     */
    private function siteInfo(): array
    {
        return [
            'name' => get_bloginfo('name'),
            'url' => home_url(),
            'wp_version' => get_bloginfo('version'),
            'php_version' => PHP_VERSION,
        ];
    }

    private function statusSelect(string $id, string $current): string
    {
        $options = [
            'unknown' => 'Unknown',
            'pass' => 'Pass',
            'fail' => 'Needs Attention',
        ];

        $html = '<select name="status[' . esc_attr($id) . ']">';
        foreach ($options as $value => $label) {
            $selected = selected($current, $value, false);
            $html .= '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
        }
        $html .= '</select>';

        return $html;
    }
}
