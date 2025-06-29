<?php
/**
 * Plugin Name: Team Task Manager
 * Description: A simple team task management system.
 * Version: 1.0
 * Author: Mayank
 */

if (!defined('ABSPATH')) exit; // 🚨 Security check to prevent direct access

// 🔧 Include core features
require_once plugin_dir_path(__FILE__) . 'includes/db-setup.php';
require_once plugin_dir_path(__FILE__) . 'includes/roles-setup.php'; // 👈 (if you split role setup separately)
require_once plugin_dir_path(__FILE__) . 'api/rest-routes.php';
require_once plugin_dir_path(__FILE__) . 'api/auth-routes.php';
require_once plugin_dir_path(__FILE__) . 'admin/admin-pages.php';
require_once plugin_dir_path(__FILE__) . 'api/default-endpoint-api.php';

// Allow cross-origin requests (CORS) for REST API + cookies
add_action('init', function () {
    if (strpos($_SERVER['REQUEST_URI'], '/wp-json/') === 0) {
        header("Access-Control-Allow-Origin: http://localhost:3000"); // ✅ React app origin
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    }
});

// Register activation hook
register_activation_hook(__FILE__, function () {
    team_task_manager_install();           // create tables
    team_task_manager_setup_roles();       // add roles
});

// Register deactivation hook
register_deactivation_hook(__FILE__, 'team_task_manager_deactivate');
