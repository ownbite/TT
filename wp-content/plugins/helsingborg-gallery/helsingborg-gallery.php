<?php
/*
 * Plugin Name: Galleri
 * Plugin URI: -
 * Description: Skapar en widget för att visa alarm samt möjlighet att lägga till karta.
 * Version: 1.0
 * Author: Kristoffer Svanmark
 * Author URI: -
 *
 * Copyright (C) 2014 Helsingborg stad
 */

define('HELSINGBORG_GALLERY_BASE', plugin_dir_path(__FILE__));
define('HELSINGBORG_GALLERY_URL', plugin_dir_url(__FILE__));

/**
 * Import required plugin files
 */
require_once(HELSINGBORG_GALLERY_BASE . 'classes/helsingborg-gallery-custom-post.php');

/**
 * Initialize
 */

$hbgVideoGallery = new HelsingborgGalleryCustomPost();