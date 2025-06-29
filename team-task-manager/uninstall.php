<?php

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;

$table_teams = $wpdb->prefix . 'teams';
$table_tasks = $wpdb->prefix . 'tasks';

// Drop tables
$wpdb->query("DROP TABLE IF EXISTS $table_teams");
$wpdb->query("DROP TABLE IF EXISTS $table_tasks");

// Optionally remove role (if needed)
remove_role('team_member');

// Log
error_log('✅ uninstall.php ran — tables dropped, role removed!');
