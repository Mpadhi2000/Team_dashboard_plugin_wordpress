<?php

add_filter('rest_prepare_user', function ($response, $user, $request) {
    if (get_current_user_id() === $user->ID) {
        $response->data['roles'] = $user->roles;
    }
    return $response;
}, 10, 3);

add_action('rest_api_init', function () {

    // Login
    register_rest_route('teamtask/v1', '/login', [
        'methods' => 'POST',
        'callback' => function ($request) {
            $creds = [
                'user_login'    => sanitize_text_field($request['username']),
                'user_password' => $request['password'],
                'remember'      => true,
            ];

            $user = wp_signon($creds, false);

            if (is_wp_error($user)) {
                return new WP_Error('invalid_login', 'Invalid credentials', ['status' => 403]);
            }

            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID, true);

            return [
                'success'   => true,
                'user_id'   => $user->ID,
                'roles'     => $user->roles,
                'user_name' => $user->display_name
            ];
        },
        'permission_callback' => '__return_true'
    ]);

    // Get current user
    register_rest_route('teamtask/v1', '/me', [
        'methods' => 'GET',
        'callback' => function () {
            $user = wp_get_current_user();
            return [
                'user_id'   => $user->ID,
                'roles'     => $user->roles,
                'user_name' => $user->display_name
            ];
        },
        'permission_callback' => function () {
            return is_user_logged_in(); 
        }
    ]);

    // Logout
register_rest_route('teamtask/v1', '/logout', [
    'methods' => 'POST',
    'callback' => function () {
        wp_logout();
        return ['success' => true];
    },
    'permission_callback' => '__return_true' // ✅ Always allow logout
]);
});
