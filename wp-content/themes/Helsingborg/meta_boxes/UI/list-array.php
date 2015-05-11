<?php

// Get list categories from db table
global $wpdb;
$listResult = $wpdb->get_results("SELECT title FROM list_categories ORDER BY id ASC", OBJECT);

// Setup list categories as an array formatted array(0 => 'TITLE');
$list = array();
foreach ($listResult as $item) {
    $list[] = $item->title;
}
