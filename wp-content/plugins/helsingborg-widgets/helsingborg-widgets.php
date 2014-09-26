<?php
/*
 * Plugin Name: Helsingborg Widgets
 * Plugin URI: -
 * Description: Skapar en samling med Widgets anpassade för Helsingborg stad
 * Version: 1.0
 * Author: Henric Lind
 * Author URI: -
 * License: GPLv3
 *
 * Copyright (C) 2014 Helsingborg stad
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

// Include the widget files
include_once('classes/link-list-widget.php');
include_once('classes/news-list-widget.php');
//include_once('classes/slider-widget.php');
include_once('classes/index-widget.php');
include_once('classes/index-large-widget.php');
include_once('classes/image-list-widget.php');

// Initiate widgets
$SimpleLinkList = new SimpleLinkList();
$News_List_Widget = new News_List_Widget();
//$Slider_Widget = new Slider_Widget();
$Index_Widget = new Index_Widget();
$Index_Large_Widget = new Index_Large_Widget();
$Image_List = new Image_List();

// Add resources used by link-list-widget
wp_enqueue_style( 'sllw-css', plugin_dir_url(__FILE__) .'css/sllw.css');
wp_enqueue_script( 'sllw-sort-js', plugin_dir_url(__FILE__) .'js/sllw-sort.js');
wp_enqueue_script( 'image-widget-js', plugin_dir_url(__FILE__) .'js/image-widget.js');
