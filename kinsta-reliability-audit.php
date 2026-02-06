<?php
/**
 * Plugin Name: Kinsta Reliability Audit
 * Description: Capture reliability checklist status and export a JSON runbook report.
 * Version: 0.1.0
 * Author: VictorStack AI
 * License: MIT
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

if (class_exists('KinstaReliabilityAudit\\AdminPage')) {
    add_action('plugins_loaded', static function (): void {
        $admin = new KinstaReliabilityAudit\AdminPage();
        $admin->register();
    });
}
