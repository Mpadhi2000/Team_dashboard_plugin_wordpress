<?php

add_action('rest_api_init', function () {

    // TEAMS ROUTES

    register_rest_route('teamtask/v1', '/teams', [
        'methods' => 'GET',
        'callback' => 'get_teams',
    ]);

    register_rest_route('teamtask/v1', '/teams', [
        'methods' => 'POST',
        'callback' => 'create_team',
        'permission_callback' => function() { return current_user_can('manage_options'); }
    ]);

    register_rest_route('teamtask/v1', '/teams/(?P<id>\d+)', [
        'methods' => 'PUT',
        'callback' => 'update_team',
        'permission_callback' => function() { return current_user_can('manage_options'); }
    ]);

    register_rest_route('teamtask/v1', '/teams/(?P<id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'delete_team',
        'permission_callback' => function() { return current_user_can('manage_options'); }
    ]);

    // TASKS ROUTES

    register_rest_route('teamtask/v1', '/tasks', [
        'methods' => 'GET',
        'callback' => 'get_tasks',
    ]);

    register_rest_route('teamtask/v1', '/tasks', [
        'methods' => 'POST',
        'callback' => 'create_task',
        'permission_callback' => function() { return current_user_can('manage_options'); }
    ]);

    register_rest_route('teamtask/v1', '/tasks/(?P<id>\d+)', [
        'methods' => 'PUT',
        'callback' => 'update_task',
        'permission_callback' => function() { return current_user_can('manage_options'); }
    ]);

    register_rest_route('teamtask/v1', '/tasks/(?P<id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'delete_task',
        'permission_callback' => function() { return current_user_can('manage_options'); }
    ]);

});

// ---- TEAMS CALLBACKS ----

function get_teams() {
    global $wpdb;
    $table = $wpdb->prefix . 'teams';
    $results = $wpdb->get_results("SELECT * FROM $table");
    return rest_ensure_response($results);
}

function create_team($request) {
    global $wpdb;
    $table = $wpdb->prefix . 'teams';

    $name = sanitize_text_field($request->get_param('name'));
    $desc = sanitize_textarea_field($request->get_param('description'));

    $wpdb->insert($table, [
        'name' => $name,
        'description' => $desc
    ]);

    return rest_ensure_response(['success' => true, 'id' => $wpdb->insert_id]);
}

function update_team($request) {
    global $wpdb;
    $table = $wpdb->prefix . 'teams';
    $id = (int) $request['id'];

    $name = sanitize_text_field($request->get_param('name'));
    $desc = sanitize_textarea_field($request->get_param('description'));

    $wpdb->update($table, [
        'name' => $name,
        'description' => $desc
    ], ['id' => $id]);

    return rest_ensure_response(['success' => true, 'id' => $id]);
}

function delete_team($request) {
    global $wpdb;
    $table = $wpdb->prefix . 'teams';
    $id = (int) $request['id'];

    $wpdb->delete($table, ['id' => $id]);

    return rest_ensure_response(['success' => true, 'id' => $id]);
}

// ---- TASKS CALLBACKS ----

function get_tasks() {
    global $wpdb;
    $table = $wpdb->prefix . 'tasks';
    $results = $wpdb->get_results("SELECT * FROM $table");
    return rest_ensure_response($results);
}

function create_task($request) {
    global $wpdb;
    $table = $wpdb->prefix . 'tasks';

    $title = sanitize_text_field($request->get_param('title'));
    $desc = sanitize_textarea_field($request->get_param('description'));
    $team_id = intval($request->get_param('team_id'));
    $priority = sanitize_text_field($request->get_param('priority'));
    $due_date = sanitize_text_field($request->get_param('due_date'));
    $status = sanitize_text_field($request->get_param('status'));

    $wpdb->insert($table, [
        'title' => $title,
        'description' => $desc,
        'team_id' => $team_id,
        'priority' => $priority,
        'due_date' => $due_date,
        'status' => $status ? $status : 'Pending'
    ]);

    return rest_ensure_response(['success' => true, 'id' => $wpdb->insert_id]);
}

function update_task($request) {
    global $wpdb;
    $table = $wpdb->prefix . 'tasks';
    $id = (int) $request['id'];

    $title = sanitize_text_field($request->get_param('title'));
    $desc = sanitize_textarea_field($request->get_param('description'));
    $team_id = intval($request->get_param('team_id'));
    $priority = sanitize_text_field($request->get_param('priority'));
    $due_date = sanitize_text_field($request->get_param('due_date'));
    $status = sanitize_text_field($request->get_param('status'));

    $wpdb->update($table, [
        'title' => $title,
        'description' => $desc,
        'team_id' => $team_id,
        'priority' => $priority,
        'due_date' => $due_date,
        'status' => $status
    ], ['id' => $id]);

    return rest_ensure_response(['success' => true, 'id' => $id]);
}

function delete_task($request) {
    global $wpdb;
    $table = $wpdb->prefix . 'tasks';
    $id = (int) $request['id'];

    $wpdb->delete($table, ['id' => $id]);

    return rest_ensure_response(['success' => true, 'id' => $id]);
}
