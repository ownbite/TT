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
define('HELSINGBORG_ALARM_BASE', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ) );
define('ALARM_MARKERS_BASE_URL', 'http://alarmservice.helsingborg.se/AlarmServices.svc/GetAlarmMarkers/');
define('ALARM_FOR_CITIES_URL'  , 'http://alarmservice.helsingborg.se/AlarmServices.svc/GetAlarmsForCities/');

function load_scripts() {
  wp_deregister_script( 'jquery' );
  wp_deregister_script( 'jquery-ui' );
  wp_register_script( 'jquery', get_template_directory_uri() . '/js/jquery/dist/jquery.min.js', array(), '1.0.0', false );
  wp_register_script( 'jquery-ui', get_template_directory_uri() . '/js/jquery/dist/jquery-ui.min.js', array(), '1.0.0', false );
  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui');
}
add_action('wp_enqueue_scripts', 'load_scripts');

// Include the neccessary classes
include_once('classes/alarm-widget.php');
include_once('classes/alarm-shortcode.php');
$AlarmList = new AlarmList();

/**
 * AJAX function for retrieving markers from AlarmService
 **/
add_action( 'wp_ajax_get_markers', 'get_markers_callback' );
add_action( 'wp_ajax_nopriv_get_markers', 'get_markers_callback' );
function get_markers_callback() {
  $options = $_GET['options'];
  $url = ALARM_MARKERS_BASE_URL . urlencode($options);
  $result = file_get_contents($url);
  die($result);
}

/**
* AJAX function for retrieving full alarms from AlarmService
**/
add_action( 'wp_ajax_get_alarm_for_cities', 'get_alarm_for_cities_callback' );
add_action( 'wp_ajax_nopriv_get_alarm_for_cities', 'get_alarm_for_cities_callback' );
function get_alarm_for_cities_callback() {
  $options = $_GET['options'];
  $url = ALARM_FOR_CITIES_URL . urlencode($options);
  $result = file_get_contents($url);
  die($result);
}

// Simple function for encoding the values only, not the delimiters
function encode_values($options, $delimiter_in, $delimiter_out) {
  $values = explode($delimiter_in, $options);
  $encoded_values = array();
  foreach($values as $value) {
    array_push($encoded_values, urlencode($value));
  }
  return implode($delimiter_out, $values);
}
