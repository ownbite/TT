<?php
/*
 * Plugin Name: Helsingborg Text Widget
 * Plugin URI: -
 * Description: Text widget med WYSIWYG-widget
 * Version: 1.0
 * Author: Kristoffer Svanmark
 * Author URI: -
 *
 * Copyright (C) 2014 Helsingborg stad
 */

// Include the widget files
include_once('classes/helsingborg-text-widget.php');

// Initiate widgets
$HbgTextWidget      = new HbgTextWidget();

// Add resources used by link-list-widget
wp_enqueue_style( 'helsingborg-widgets-css', plugin_dir_url(__FILE__) .'css/helsingborg-widgets.css');
wp_enqueue_script( 'jquery', get_template_directory_uri() . '/js/jquery/dist/jquery.min.js');
wp_enqueue_script( 'helsingborg-list-sort-js', plugin_dir_url(__FILE__) .'js/helsingborg-list-sort.js');
wp_enqueue_script( 'helsingborg-media-selector-original-js', plugin_dir_url(__FILE__) .'js/helsingborg-media-selector-original.js');
wp_enqueue_script( 'steps-js', plugin_dir_url(__FILE__) . 'js/steps.js');

// Function to purge the cache of a specific page_id/post_id when page widgets is updated
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