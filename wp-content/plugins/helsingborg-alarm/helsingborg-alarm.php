<?php
/*
 * Plugin Name: Alameringswidget
 * Plugin URI: -
 * Description: Skapar en widget för att visa alarm samt möjlighet att lägga till karta.
 * Version: 1.0
 * Author: Henric Lind
 * Author URI: -
 *
 * Copyright (C) 2014 Helsingborg stad
 */

$HELSINGBORG_ALARM_BASE = plugin_dir_url(__FILE__);

include_once('classes/alarm-widget.php');
include_once('classes/alarm-shortcode.php');
$AlarmList = new AlarmList();
