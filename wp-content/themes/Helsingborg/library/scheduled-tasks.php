<?php

/**
 * Cron interval used by our schedule events
 * @param  [type] $schedules [description]
 * @return [type]            [description]
 */
function cron_add_3min( $schedules ) {
    $schedules['3min'] = array(
        'interval' => 3*60,
        'display'  => __('Once every three minutes')
    );

    return $schedules;
}
add_filter('cron_schedules', 'cron_add_3min');

/**
 * Add scheduled work for alarm
 * @return void
 */
require_once('scheduled-tasks/scheduled_alarms.php');
function setup_scheduled_alarms() {
    if ( ! wp_next_scheduled( 'scheduled_alarms' ) ) {
        // Set scheduled task to occur each 3rd minute
        wp_schedule_event(time(), '3min', 'scheduled_alarms');
    }
}
add_action('wp', 'setup_scheduled_alarms');

 /**
  * Add scheduled work for CBIS events
  * @return void
  */
require_once('scheduled-tasks/scheduled_cbis.php');
function setup_scheduled_cbis() {
  if ( ! wp_next_scheduled( 'scheduled_cbis' ) ) {
    // Set scheduled task to occur at 22.30 each day
    wp_schedule_event( strtotime(date("Y-m-d", time()) . '22:30'), 'daily', 'scheduled_cbis');
  }
}
add_action('wp', 'setup_scheduled_cbis');

/**
 * Add scheduled work for XCap events
 * @return void
 */
require_once('scheduled-tasks/scheduled_xcap.php');
function setup_scheduled_xcap() {
  if ( ! wp_next_scheduled( 'scheduled_xcap' ) ) {
    // Set scheduled task to occur at 22.30 each day
    wp_schedule_event( strtotime(date("Y-m-d", time()) . '22:30'), 'daily', 'scheduled_xcap');
  }
}
add_action('wp', 'setup_scheduled_xcap');