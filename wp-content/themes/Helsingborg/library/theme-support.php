<?php
function Helsingborg_theme_support() {
    // Add language support
    load_theme_textdomain('helsingborg', get_template_directory() . '/languages');

    // Add menu support
    add_theme_support('menus');

    // Add post thumbnail support: http://codex.wordpress.org/Post_Thumbnails
    add_theme_support('post-thumbnails');
    // set_post_thumbnail_size(150, 150, false);

    // rss thingy
    add_theme_support('automatic-feed-links');

    // Add post formarts support: http://codex.wordpress.org/Post_Formats
    add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));
}

add_action('after_setup_theme', 'Helsingborg_theme_support');

/**
 * Remove medium image size from media uploader
 */
function hbg_remove_image_size($sizes) {
    unset($sizes['medium']);
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'hbg_remove_image_size');

function admin_script_enqueue( $hook ) {
    wp_enqueue_script('admin-js', get_template_directory_uri() . '/js/admin.js' );
}
add_action('admin_enqueue_scripts', 'admin_script_enqueue');
