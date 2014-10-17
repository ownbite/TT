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

add_action( 'show_user_profile', 'helsingborg_happy_user_id_field' );
add_action( 'edit_user_profile', 'helsingborg_happy_user_id_field' );
function helsingborg_happy_user_id_field( $user ) {
?>
  <h3><?php _e("Evenemangshantering", "blank"); ?></h3>
  <table class="form-table">
    <tr>
      <th><label for="happy_user_id"><?php _e("Evenemangs ID"); ?></label></th>
      <td>
        <input type="text" name="happy_user_id" id="happy_user_id" class="regular-text"
            value="<?php echo esc_attr( get_the_author_meta( 'happy_user_id', $user->ID ) ); ?>" /><br />
        <span class="description"><?php _e("Skriv in id som används för autentiering för evenemangshantering."); ?></span>
    </td>
    </tr>
  </table>
<?php
}

add_action( 'personal_options_update', 'helsingborg_save_happy_user_id_field' );
add_action( 'edit_user_profile_update', 'helsingborg_save_happy_user_id_field' );
function helsingborg_save_happy_user_id_field( $user_id ) {
  $saved = false;
  if ( current_user_can( 'edit_user', $user_id ) ) {
    update_user_meta( $user_id, 'happy_user_id', $_POST['happy_user_id'] );
    $saved = true;
  }
  return true;
}

add_filter('gform_pre_render', 'populate_gf_from_sql');
function populate_gf_from_sql($form){

    foreach($form['fields'] as &$field){

        if ($field['type'] == 'multiselect' && $field['cssClass'] == 'event_units') {
          $event_units = HelsingborgEventModel::load_administration_units();

          $choices = array(array('text' => 'Välj enhet', 'value' => ' '));
          foreach($event_units as $unit){
              $choices[] = array('text' => $unit->Name, 'value' => $unit->AdministrationUnitID);
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

add_action('gform_after_submission', 'event_add_entry_to_db', 10, 2);
function event_add_entry_to_db($entry, $form) {

	  // uncomment to see the entry object
	  // echo '<pre>';
	  // var_dump($entry);
	  // echo '</pre>';

    // EVENT
	  $name         = 	$entry[1];
	  $description  = 	$entry[4];
	  $approved     = 	false; // Not approved as default
    $organizer_id = 	$entry[12];
    $location     = 	$entry[8];
    $external_id  = 	NULL;
    $event        = 	array ($name, $description, $approved, $organizer_id, $location, $external_id);

    // TYPE GROUP
    $event_types_name = 	$entry[99];
    //$event_id -> get
    $group            = 	array ($event_types_name);

    // ADMINISTRATION UNIT
    $administration_unit_id  = 	$entry[2];
    //$event_id -> get
    $administration          = 	array ($administration_unit_id);

    // IMAGE
    $image_id   = 	$entry[3];
    // $event_id -> get
    $image_path = 	$entry[3];
    $autor      = 	$entry[3];
    $image      = 	$entry[3];

    // $wpdb->query( $wpdb->prepare(
    //   'INSERT INTO ' . self::get_happy_event_table() . ' (Name, Description, Approved, OrganizerID, Location, ExternalEventID)
    //   VALUES (%s, %s, %d, %d, %s, NULL),' . $event
    // ));
    //
    // $wpdb->query( $wpdb->prepare(
    //   'INSERT INTO ' . self::get_happy_event_types_group_table() . ' (EventTypesName, EventID)
    //   VALUES (%s, %d),' . $group
    // ));
    //
    // $wpdb->query( $wpdb->prepare(
    //   'INSERT INTO ' . self::get_happy_event_administration_unit_table() . ' (AdministrationUnitID, EventID)
    //   VALUES (%s, %d),' . $administration
    // ));
    //
    // $wpdb->query( $wpdb->prepare(
    //   'INSERT INTO ' . self::get_happy_event_images_table() . ' (ImageID, EventID, ImagePath, Author)
    //   VALUES (%s, %d, %s, %s),' . $image
    // ));

    // [1]=&gt;
    // string(6) "TESTAR"
    // [2]=&gt;
    // string(20) "Allerums fÃ¶rskola"
    // [3]=&gt;
    // string(1) "1"
    // [4]=&gt;
    // string(11) "BESKRIVNING"
    // [5]=&gt;
    // string(17) "Enstaka händelse"
    // [6]=&gt;
    // string(10) "2014-10-08"
    // [7]=&gt;
    // string(5) "00:00"
    // [8]=&gt;
    // string(11) "Helsingborg"
    // [12]=&gt;
    // string(8) "Wallmans"
    // [9]=&gt;
    // string(0) ""
    // [10]=&gt;
    // string(0) ""
    // [11]=&gt;
    // string(0) ""
    // [13]=&gt;
    // string(0) ""
    // [14]=&gt;
    // string(0) ""
    // [15]=&gt;
    // string(0) ""
    // [16]=&gt;
    // string(0) ""
    // [17]=&gt;
    // string(0) ""
    // ["18.1"]=&gt;
    // string(0) ""
}

/**
 * trims text to a space then adds ellipses if desired
 * @param string $input text to trim
 * @param int $length in characters to trim to
 * @param bool $ellipses if ellipses (...) are to be added
 * @param bool $strip_html if html tags are to be stripped
 * @param bool $strip_style if css style are to be stripped
 * @return string
 */
function trim_text($input, $length, $ellipses = true, $strip_tag = true,$strip_style = true) {
    //strip tags, if desired
    if ($strip_tag) {
        $input = strip_tags($input);
    }

    //strip tags, if desired
    if ($strip_style) {
        $input = preg_replace('/(<[^>]+) style=".*?"/i', '$1',$input);
    }

    if($length=='full')
    {

        $trimmed_text=$input;

    }
    else
    {
        //no need to trim, already shorter than trim length
        if (strlen($input) <= $length) {
        return $input;
        }

        //find last space within length
        $last_space = strrpos(substr($input, 0, $length), ' ');
        $trimmed_text = substr($input, 0, $last_space);

        //add ellipses (...)
        if ($ellipses) {
        $trimmed_text .= '...';
        }       
    }

    return $trimmed_text;
}

?>
