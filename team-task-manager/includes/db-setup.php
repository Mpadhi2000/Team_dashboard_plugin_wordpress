<?php

function team_task_manager_install() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $table_teams = $wpdb->prefix . 'teams';
    $table_tasks = $wpdb->prefix . 'tasks';

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $sql1 = "CREATE TABLE $table_teams (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(100) NOT NULL,
        description text,
        PRIMARY KEY (id)
    ) $charset_collate;";

    $sql2 = "CREATE TABLE $table_tasks (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(200) NOT NULL,
        description text,
        team_id mediumint(9) NOT NULL,
        priority varchar(50),
        due_date date,
        status varchar(50) DEFAULT 'Pending',
        PRIMARY KEY (id),
        FOREIGN KEY (team_id) REFERENCES $table_teams(id) ON DELETE CASCADE
    ) $charset_collate;";

    dbDelta($sql1);
    dbDelta($sql2);

    // Debug log
    error_log('✅ team_task_manager_install() ran — tables created!');
}

// Deactivation — truncate (empty) tables
function team_task_manager_deactivate() {
    global $wpdb;

    $table_teams = $wpdb->prefix . 'teams';
    $table_tasks = $wpdb->prefix . 'tasks';

    $wpdb->query("TRUNCATE TABLE $table_teams");
    $wpdb->query("TRUNCATE TABLE $table_tasks");

    error_log('✅ team_task_manager_deactivate() ran — tables truncated!');
}