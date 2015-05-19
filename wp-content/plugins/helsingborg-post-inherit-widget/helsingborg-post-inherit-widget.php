<?php

/*
 * Plugin Name: Helsingborg Arvsinnehåll
 * Plugin URI: -
 * Description: Skapa innehåll till en widget som kan visas på flera sidor
 * Version: 1.0
 * Author: Kristoffer Svanmark
 * Author URI: -
 *
 * Copyright (C) 2015 Helsingborg stad
 */

define('HELSINGBORG_POST_INHERIT_WIDGET_BASE', plugin_dir_path(__FILE__));
define('HELSINGBORG_POST_INHERIT_WIDGET_URL', plugin_dir_url(__FILE__));

/**
 * Import required plugin files
 */
require_once(HELSINGBORG_POST_INHERIT_WIDGET_BASE . 'classes/helsingborg-post-inherit-widget-widget.php');
require_once(HELSINGBORG_POST_INHERIT_WIDGET_BASE . 'classes/helsingborg-post-inherit-widget-custom-post-type.php');

/**
 * Initialize
 */
add_action('widgets_init', 'hbgPostInheritWidgetRegister');
function hbgPostInheritWidgetRegister() {
    register_widget('HelsingborgPostInheritWidget');
}

$hbgPostInheritCustomPostType = new HelsingborgGPostInheritCustomPostType();