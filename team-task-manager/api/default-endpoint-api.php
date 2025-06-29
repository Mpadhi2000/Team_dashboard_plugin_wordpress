<?php

add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/pages/(?P<slug>[a-zA-Z0-9-_]+)', [
        'methods'  => 'GET',
        'callback' => 'get_page_by_slug',
        'permission_callback' => '__return_true',
    ]);
});

function get_page_by_slug($data) {
    $slug = $data['slug'];
    $page = get_page_by_path($slug);

    if (!$page) {
        return new WP_Error('not_found', 'Page not found', ['status' => 404]);
    }

    // ✅ Check if ACF is active
    $acf_fields = function_exists('get_fields') ? get_fields($page->ID) : null;

    // ✅ Optional: Get Yoast title (if available)
    $yoast_title = get_post_meta($page->ID, '_yoast_wpseo_title', true);

    return [
        'id'    => $page->ID,
        'slug'  => $slug,
        'title' => get_the_title($page),
        'acf'   => $acf_fields,
        'yoast_title' => $yoast_title,
    ];
}
