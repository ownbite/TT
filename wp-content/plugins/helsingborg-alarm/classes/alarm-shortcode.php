<?php

add_action( 'wp_enqueue_scripts', 'hbgAlarmShortcodeScripts');

function hbgAlarmShortcodeScripts() {
    wp_enqueue_script('alarm-widget-js', HELSINGBORG_ALARM_BASE . '/js/alarm-widget.js', array('jquery'), '1.0.0', false);
    wp_localize_script('alarm-widget-js', 'ajaxalarm', array('url' => admin_url('admin-ajax.php')));
}

add_shortcode('alarm-map', function() {
    wp_enqueue_style('alarm-map-css', HELSINGBORG_ALARM_BASE . '/css/alarm-map.css');
    wp_enqueue_script('gmap-api-js', '//maps.googleapis.com/maps/api/js?key=AIzaSyDUKb32DWSPtPx5RN7BBd-gIuHPn8HpOBo', array(), '1.0.0', false);

    return '<div id="map-canvas"></div>';
});

?>
