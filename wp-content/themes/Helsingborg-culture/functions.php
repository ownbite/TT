<?php
add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_styles_and_scripts' );
function child_theme_enqueue_styles_and_scripts() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
    wp_enqueue_style('child-style-app', get_stylesheet_directory_uri() . '/css/app-culture.css', array('child-style'));
}

// Add functions for samling_bokning_timeline pages, widgets
require_once('meta_boxes/metabox-samling.php');
require_once('library/samling_bokning_timeline.php');
?>