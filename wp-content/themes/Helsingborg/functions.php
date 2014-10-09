<?php

// Various clean up functions
require_once('library/cleanup.php');

// Required for Foundation to work properly
require_once('library/foundation.php');

// Register all navigation menus
require_once('library/navigation.php');

// Add menu walker
require_once('library/menu-walker.php');

// Add menu walker
require_once('library/sidebar-menu-walker.php');

// Create widget areas in sidebar and footer
require_once('library/widget-areas.php');

// Return entry meta information for posts
require_once('library/entry-meta.php');

// Enqueue scripts
require_once('library/enqueue-scripts.php');

// Add theme support
require_once('library/theme-support.php');

require_once('meta_boxes/meta-functions.php');

add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);

function my_css_attributes_filter($var) {
  return is_array($var) ? array_intersect($var, array('current-menu-item')) : '';
}

add_filter('gform_pre_render', 'populate_gf_from_sql');
function populate_gf_from_sql($form){

    foreach($form['fields'] as &$field){

        if ($field['type'] == 'multiselect' && $field['cssClass'] == 'event_units') {
          $event_units = HelsingborgEventModel::load_administration_units();

          $choices = array(array('text' => 'Välj enhet', 'value' => ' '));
          foreach($event_units as $unit){
              $choices[] = array('text' => $unit->Name, 'value' => $unit->Name);
          }

          $field['choices'] = $choices;
        }

        else if ($field['type'] == 'multiselect' && $field['cssClass'] == 'event_types') {
          $event_types = HelsingborgEventModel::get_happy_event_types_table();

          $choices = array(array('text' => 'Välj typ av evenemang', 'value' => count($event_types)));
          foreach($event_types as $type){
              $choices[] = array('text' => $type->Name, 'value' => $type->Name);
          }

          $field['choices'] = $choices;
        }
    }

    return $form;
}
?>
