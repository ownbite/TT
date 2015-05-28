<?php

/*
 * Plugin Name: Helsingborg Widgets
 * Plugin URI: -
 * Description: Skapar en samling med Widgets anpassade för Helsingborg stad
 * Version: 1.0
 * Author: Henric Lind
 * Author URI: -
 *
 * Copyright (C) 2014 Helsingborg stad
 */


/**
 * Include settings page
 */
include_once('helsingborg-settings.php');

/**
 * Include all plugin widgets and stuff
 */
function includeHbgWidgets() {
    $basedir = plugin_dir_path(__FILE__);

    $exclude = array(
        'assets',
        'js',
        'slider-widget',
        'post_author',
        'helsingborg-settings',
        'helsingborg-post-thumbnail',
    );

    $plugins = glob($basedir . '*', GLOB_ONLYDIR);

    foreach ($plugins as $plugin) {
        $plugin = basename($plugin);
        //var_dump(in_array($plugin, $exclude), $plugin);
        if (!in_array($plugin, $exclude)) {
            include_once($basedir . '' . $plugin . '/' . $plugin . '.php');
        }
    }
}

includeHbgWidgets();


/**
 * Add resources used by link-list-widget
 */
add_action('admin_enqueue_scripts', 'hbgWidgetAdminEnqueue');
function hbgWidgetAdminEnqueue () {
    wp_enqueue_style( 'helsingborg-widgets-css', plugin_dir_url(__FILE__) .'assets/css/helsingborg-widgets.css');
    wp_enqueue_script( 'jquery', get_template_directory_uri() . 'js/jquery/dist/jquery.min.js');
    wp_enqueue_script( 'helsingborg-list-sort-js', plugin_dir_url(__FILE__) .'assets/js/helsingborg-list-sort.js');
    wp_enqueue_script( 'helsingborg-media-selector-original-js', plugin_dir_url(__FILE__) .'assets/js/helsingborg-media-selector-original.js');
    wp_enqueue_script( 'steps-js', plugin_dir_url(__FILE__) . 'assets/js/steps.js');
}

/**
 * Function to purge the cache of a specific page_id/post_id when page widgets is updated
 */
if (!function_exists("hbg_purge_page")) {
    function hbg_purge_page($args) {
        $post_id = $args['post_id'];
        if (function_exists('w3tc_pgcache_flush_post')){
            w3tc_pgcache_flush_post($post_id);
            //print '<!-- Post with id ' . $post_id . ' purged -->';
        }
    }
    add_filter('hbg_page_widget_save', 'hbg_purge_page');
}


