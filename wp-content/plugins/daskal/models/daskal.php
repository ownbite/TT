<?php
// main model containing general config and UI functions
class Daskal {
   static function install() {
   	global $wpdb;	
   	$wpdb -> show_errors();   	
   	self::init();
   	
		// set initial difficulty levels
		$levels = get_option('daskal_levels');
		if(empty($levels)) update_option('daskal_levels', array(__('Beginner', 'daskal'), __('Intermediate', 'daskal'), __('Advanced', 'daskal')));   
		
		// create DB tables
		if($wpdb->get_var("SHOW TABLES LIKE '".DASKAL_TEMPLATES."'") != DASKAL_TEMPLATES) {        
			$sql = "CREATE TABLE `" . DASKAL_TEMPLATES . "` (
				  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `name` VARCHAR(255) NOT NULL DEFAULT '',
				  `content` TEXT
				) DEFAULT CHARSET=utf8;";
			
			$wpdb->query($sql);
	   }
	   
	   if($wpdb->get_var("SHOW TABLES LIKE '".DASKAL_RATINGS."'") != DASKAL_RATINGS) {        
			$sql = "CREATE TABLE `" . DASKAL_RATINGS . "` (
				  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `tutorial_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `user_id` INT UNSIGNED NOT NULL DEFAULT 0,
				  `ip` VARCHAR(25) NOT NULL DEFAULT '',
				  `rating` TINYINT UNSIGNED NOT NULL DEFAULT 1
				) DEFAULT CHARSET=utf8;";
			
			$wpdb->query($sql);
	   }
   	
	   DaskalTutorial::register_tutorial_type();      
      flush_rewrite_rules();
   }
   
   // main menu
   static function menu() {
   	add_options_page(__('Daskal Options', 'daskal'), __('Daskal Options', 'daskal'), "manage_options", "daskal_options", 
   		array(__CLASS__, "options"));	
   	add_theme_page( __('Daskal Templates', 'daskal'), __('Daskal Templates', 'daskal'), 'manage_options', 'daskal_templates', array('DaskalTemplates', 'manage') );		
	}
	
	// CSS and JS
	static function scripts() {
		wp_enqueue_script('jquery');	   
		
	  wp_enqueue_style(
		'daskal-style',
		plugins_url().'/daskal/css/main.css',
		array(),
		'0.3.5');
		
		wp_enqueue_script('daskal-js',
			plugins_url('/daskal/js/common.js'),
			array(),
			'0.4');
		
		$ratings = get_option('daskal_ratings');   
		if($ratings == '5stars') {
			wp_enqueue_script('star-rating',
			plugins_url('/daskal/js/star-rating.js'),
			array(),
			'0.4');
		}
	}
	
	// initialization
	static function init() {
		global $wpdb;
		load_plugin_textdomain( 'daskal', false, DASKAL_RELATIVE_PATH."/languages/" );	
		
		define('DASKAL_TEMPLATES', $wpdb->prefix.'daskal_templates');	
		define('DASKAL_RATINGS', $wpdb->prefix.'daskal_ratings');
		add_action( 'template_redirect', array('DaskalTutorial', 'template_redirect') );
	}
	
		
	// manage general options
	static function options() {
		if(!empty($_POST['ok'])) {
			// update levels - never allow empty levels
			if(!empty($_POST['daskal_levels'])) {
				$levels = explode(PHP_EOL, $_POST['daskal_levels']);
				update_option('daskal_levels', $levels);
			}
			
			// no ratings, 5 stars, hands up / hands down
			update_option('daskal_ratings', $_POST['daskal_ratings']);
			update_option('daskal_rating_login', @$_POST['daskal_rating_login']);
		}		
		
		$ratings = get_option('daskal_ratings');   	
		$rating_login = get_option('daskal_rating_login');
		require(DASKAL_PATH."/views/options.html.php");
	}	
	
	static function help() {
		require(DASKAL_PATH."/views/help.php");
	}	
	
	static function register_widgets() {
		register_widget('DaskalWidget');
	}
}