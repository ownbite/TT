<?php
/*
Plugin Name: Daskal
Plugin URI: http://namaste-lms.org/daskal.php
Description: Create Tutorials inside your blog or turn it into tutorials site. Go to <a href="options-general.php?page=daskal_options">Daskal Options</a> to manage general settings or straight to <a href="post-new.php?post_type=daskal_tutorial">Creating a Tutorial</a>.
Author: Kiboko Labs
Version: 0.6.5
Author URI: http://calendarscripts.info/
License: GPLv2 or later
*/

define( 'DASKAL_PATH', dirname( __FILE__ ) );
define( 'DASKAL_RELATIVE_PATH', dirname( plugin_basename( __FILE__ )));
define( 'DASKAL_URL', plugin_dir_url( __FILE__ ));

// require controllers and models
require(DASKAL_PATH."/helpers/htmlhelper.php");
require(DASKAL_PATH."/models/daskal.php");
require(DASKAL_PATH."/models/template.php");
require(DASKAL_PATH."/models/rating.php");
require(DASKAL_PATH."/models/tutorial.php");
require(DASKAL_PATH."/models/widget.php");
require(DASKAL_PATH."/controllers/templates.php");
require(DASKAL_PATH."/controllers/ajax.php");

add_action('init', array("DaskalTutorial", "register_tutorial_type"));
add_action('init', array("Daskal", "init"));

register_activation_hook(__FILE__, array("Daskal", "install"));
add_action('admin_menu', array("Daskal", "menu"));
add_action('admin_enqueue_scripts', array("Daskal", "scripts"));

// show the things on the front-end
add_action( 'wp_enqueue_scripts', array("Daskal", "scripts"));

// other actions
add_action('wp_ajax_daskal_ajax', 'daskal_ajax');
add_action('wp_ajax_nopriv_daskal_ajax', 'daskal_ajax');
add_action('save_post', array('DaskalTutorial', 'save_meta'));
add_action( 'pre_get_posts', array("DaskalTutorial", "pre_get_posts") );
add_filter('posts_orderby', array("DaskalTutorial", "orderby") );
add_filter( 'posts_results', array("DaskalTutorial", "post_results") );

// widget
add_action( 'widgets_init', array("Daskal", "register_widgets") );