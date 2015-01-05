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

// Include the widget files
include_once('classes/link-list-widget.php');
include_once('classes/news-list-widget.php');
include_once('classes/index-widget.php');
include_once('classes/index-large-widget.php');
include_once('classes/image-list-widget.php');
include_once('classes/helsingborg-event-list-widget.php');
include_once('helsingborg-settings.php');

// Setup event handling
require_once('models/event_model.php');
// require_once ('classes/helsingborg-post-thumbnail.php');

if (!class_exists( 'post_author' ) ) {
    include_once ('classes/post_author.php');
    $post_author_filter = new post_author_filter();
}

// Initiate widgets
$SimpleLinkList     = new SimpleLinkList();
$News_List_Widget   = new News_List_Widget();
$Index_Widget       = new Index_Widget();
$Index_Large_Widget = new Index_Large_Widget();
$Image_List         = new Image_List();
$EventList          = new EventList();

// Add resources used by link-list-widget
wp_enqueue_style( 'helsingborg-widgets-css', plugin_dir_url(__FILE__) .'css/helsingborg-widgets.css');
wp_enqueue_script( 'helsingborg-list-sort-js', plugin_dir_url(__FILE__) .'js/helsingborg-list-sort.js');
wp_enqueue_script( 'helsingborg-media-selector-original-js', plugin_dir_url(__FILE__) .'js/helsingborg-media-selector-original.js');
