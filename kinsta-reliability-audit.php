<?php
/**
 * Plugin Name: Kinsta Reliability Audit
 * Description: Capture reliability checklist status and export a JSON runbook report.
 * Version: 0.1.0
 * Author: VictorStack AI
 * License: GPL-2.0-or-later
 * Text Domain: kinsta-reliability-audit
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

define('KINSTA_RELIABILITY_AUDIT_VERSION', '0.1.0');

$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

add_action('init', static function (): void {
    load_plugin_textdomain('kinsta-reliability-audit', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

if (class_exists('KinstaReliabilityAudit\\AdminPage')) {
    add_action('plugins_loaded', static function (): void {
        $admin = new KinstaReliabilityAudit\AdminPage();
        $admin->register();
    });
}
