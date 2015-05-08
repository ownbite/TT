<?php
/*
 * Plugin Name: Helsingborg Kultur
 * Plugin URI: -
 * Description: Innehåller funktionallitet för Helsingborg Stads kultursidor
 * Version: 1.0
 * Author: Helsingborg Stad
 * Author URI: -
 *
 * Copyright (C) 2014 Helsingborg stad
 */

define('HELSINGBORG_KULTUR_WIDGET_BASE', plugin_dir_path(__FILE__));
define('HELSINGBORG_KULTUR_WIDGET_URL', plugin_dir_url(__FILE__));

include_once('classes/helsingborg-booking-widget.php');
include_once('classes/helsingborg-timeline-widget.php');

$HbgBookingWidget  = new HbgBookingWidget();
$HbgTimelineWidget = new HbgTimelineWidget();
