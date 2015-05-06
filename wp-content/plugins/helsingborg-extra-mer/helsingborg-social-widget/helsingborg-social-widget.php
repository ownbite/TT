<?php
define('HELSINGBORG_SOCIAL_WIDGET_BASE', plugin_dir_path(__FILE__));
define('HELSINGBORG_SOCIAL_WIDGET_URL', plugin_dir_url(__FILE__));

/**
 * Import required plugin files
 */
require_once(HELSINGBORG_SOCIAL_WIDGET_BASE . 'classes/helsingborg-curl.php');
require_once(HELSINGBORG_SOCIAL_WIDGET_BASE . 'classes/helsingborg-social-widget.php');

/**
 * Initialize
 */

add_action('widgets_init', 'hbgSocialWidgetRegister');
function hbgSocialWidgetRegister() {
    register_widget('HelsingborgSocialWidget');
}