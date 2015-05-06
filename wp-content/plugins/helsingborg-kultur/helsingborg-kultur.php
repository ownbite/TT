<?php
/*
 * Plugin Name: Helsingborg Kultur
 * Plugin URI: -
 * Description: Innehåller funktionallitet för Helsingborg Stads kultursidor
 * Version: 1.0
 * Author: Håkan Folkesson
 * Author URI: -
 *
 * Copyright (C) 2014 Helsingborg stad
 */

include_once('classes/helsingborg-booking-widget.php');
include_once('classes/helsingborg-timeline-widget.php');

$HbgBookingWidget  = new HbgBookingWidget();
$HbgTimelineWidget = new HbgTimelineWidget();
