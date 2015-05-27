<?php
/*
 * Plugin Name: Helsingborg Extra Mer
 * Plugin URI: -
 * Description: Innehåller bland annat mediagalleri och sociala widgets
 * Version: 1.0
 * Author: Kristoffer Svanmark
 * Author URI: -
 *
 * Copyright (C) 2014 Helsingborg stad
 */

function includeExtraMer() {
    $exclude = array();
    $basedir = plugin_dir_path(__FILE__);
    $plugins = glob($basedir . '*', GLOB_ONLYDIR);

    foreach ($plugins as $plugin) {
        $plugin = basename($plugin);
        include_once($basedir . '/' . $plugin . '/' . $plugin . '.php');
    }
}

includeExtraMer();
