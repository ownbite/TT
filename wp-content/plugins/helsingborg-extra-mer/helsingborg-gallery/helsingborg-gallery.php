<?php

define('HELSINGBORG_GALLERY_BASE', plugin_dir_path(__FILE__));
define('HELSINGBORG_GALLERY_URL', plugin_dir_url(__FILE__));

/**
 * Import required plugin files
 */
require_once(HELSINGBORG_GALLERY_BASE . 'classes/helsingborg-gallery-youtube-wrapper.php');
require_once(HELSINGBORG_GALLERY_BASE . 'classes/helsingborg-gallery-custom-post.php');

/**
 * Initialize
 */

$hbgVideoGallery = new HelsingborgGalleryCustomPost();

/**
 * Ajax actions
 */
 add_action('wp_ajax_hbg_gallery_get_video_info', array('HelsingborgGalleryYoutubeWrapper', 'getVideos'));