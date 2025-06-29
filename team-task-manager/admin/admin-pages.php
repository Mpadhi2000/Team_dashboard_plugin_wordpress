<?php

add_action('admin_menu', 'team_task_manager_admin_menu');

function team_task_manager_admin_menu() {
    add_menu_page(
        'Team Task Manager',
        'Team Task Manager',
        'manage_options',
        'team-task-manager',
        'render_team_page',
        'dashicons-groups',
        6
    );

    add_submenu_page(
        'team-task-manager',
        'Manage Teams',
        'Teams',
        'manage_options',
        'team-task-manager',
        'render_team_page'
    );

    add_submenu_page(
        'team-task-manager',
        'Manage Tasks',
        'Tasks',
        'manage_options',
        'team-task-manager-tasks',
        'render_task_page'
    );
}

function render_team_page() {
    global $wpdb;
    $table = $wpdb->prefix . 'teams';
    $results = $wpdb->get_results("SELECT * FROM $table");

    echo '<h2>Manage Teams</h2>';

    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>ID</th><th>Name</th><th>Description</th></tr></thead>';
    echo '<tbody>';

    foreach ($results as $row) {
        echo '<tr>';
        echo '<td>' . esc_html($row->id) . '</td>';
        echo '<td>' . esc_html($row->name) . '</td>';
        echo '<td>' . esc_html($row->description) . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
}

function render_task_page() {
    global $wpdb;
    $table = $wpdb->prefix . 'tasks';
    $results = $wpdb->get_results("SELECT * FROM $table");

    echo '<h2>Manage Tasks</h2>';

    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr>
        <th>ID</th><th>Title</th><th>Description</th><th>Team ID</th>
        <th>Priority</th><th>Due Date</th><th>Status</th>
    </tr></thead>';
    echo '<tbody>';

    foreach ($results as $row) {
        echo '<tr>';
        echo '<td>' . esc_html($row->id) . '</td>';
        echo '<td>' . esc_html($row->title) . '</td>';
        echo '<td>' . esc_html($row->description) . '</td>';
        echo '<td>' . esc_html($row->team_id) . '</td>';
        echo '<td>' . esc_html($row->priority) . '</td>';
        echo '<td>' . esc_html($row->due_date) . '</td>';
        echo '<td>' . esc_html($row->status) . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
}
