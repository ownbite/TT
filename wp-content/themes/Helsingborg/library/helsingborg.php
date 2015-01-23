<?php

/* Fix to rename Default Template text to 'Artikel', since this page is default */
function change_default_template_to_artikel( $translation, $text, $domain ) {
  if ( $text == 'Default Template' ) {
    return _('Artikel');
  }
  return $translation;
}
add_filter( 'gettext', 'change_default_template_to_artikel', 10, 3 );

/* Add ID field for each user, which is used for listing events */
add_action( 'show_user_profile', 'helsingborg_happy_user_id_field' );
add_action( 'edit_user_profile', 'helsingborg_happy_user_id_field' );
function helsingborg_happy_user_id_field( $user ) { ?>
  <h3><?php _e("Evenemangshantering", "blank"); ?></h3>
  <table class="form-table">
    <tr>
      <th><label for="happy_user_id"><?php _e("Användarens ID"); ?></label></th>
      <td>
        <input type="text" name="happy_user_id" id="happy_user_id" class="regular-text"
            value="<?php echo esc_attr( get_the_author_meta( 'happy_user_id', $user->ID ) ); ?>" /><br />
        <span class="description"><?php _e("Skriv in id som används för autentiering för evenemangshantering."); ?></span>
    </td>
    </tr>
  </table>
<?php
}

/* When the users ID is saved */
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

/* Adds the event to the database, then deletes the entry */
add_action('gform_after_submission', 'event_add_entry_to_db', 10, 2);
function event_add_entry_to_db($entry, $form) {

  // Make sure to only hijack the event form -> set in settings
  if (strcmp($entry['form_id'], get_option('helsingborg_event_form_id')) === 0) {
    // Event
    $name         = $entry[1];
    $description  = $entry[15];
    $approved     = 0;
    $organizer    = $entry[11];
    $location     = $entry[7];
    $external_id  = null;

    // Event time
    $type_of_date  = $entry[4];
    $single_date   = $entry[5];
    $time          = $entry[6];
    $start_date    = $entry[8];
    $end_date      = $entry[9];

    // Selected days
    for($e=1;$e<=7;$e++) {
      if (strlen($entry["10.$e"])>0) {
        $days_array[] = $e;
      }
    }

    // Create event
    $event        = 	array ( 'Name'            => $name,
                              'Description'     => $description,
                              'Approved'        => $approved,
                              'OrganizerID'     => $organizer,
                              'Location'        => $location,
                              'ExternalEventID' => $external_id );

    // Administration units
    $administrations = explode(',', $entry[3]);
    foreach($administrations as $unit) {
      $administration_units[] = HelsingborgEventModel::get_administration_id_from_name($unit)->AdministrationUnitID;
    }

    // Event types
    $event_types  = explode(',', $entry[2]);

    // Create time/times
    $event_times = array();
    if ($single_date) { // Single occurence
      $event_time = array('Date'  => $single_date,
                          'Time'  => $time,
                          'Price' => 0);
      array_push($event_times, $event_time);
    } else { // Must be start and end then
      $dates_array = create_date_range_array($start_date, $end_date);
      $filtered_days = filter_date_array_by_days($dates_array, $days_array);

      foreach($filtered_days as $date) {
        $event_time = array('Date'  => $date,
                            'Time'  => $time,
                            'Price' => 0);
        array_push($event_times, $event_time);
      }
    }

    // Image
    if ($entry[16])
      $image = handle_gf_image($entry[16], $entry[13]);

    // Now create the Event in DB
    HelsingborgEventModel::create_event($event, $event_types, $administration_units, $image, $event_times);

    // Now remove the entry from GF !
    delete_form_entry($entry);
  }
}

/* Copies the image to the new location. Returns an array with the new image path and author */
function handle_gf_image($path, $author) {
  $image_path   = $path;
  $file_name    = basename($image_path);
  $uploads      = wp_upload_dir();
  $new_path     = $uploads['basedir'].'/eventimages/'.$file_name;
  $save_path     = $uploads['baseurl'].'/eventimages/'.$file_name;

  if (!file_exists($uploads['basedir'].'/eventimages/')) {
    mkdir($uploads['basedir'].'/eventimages/', 0777, true);
  }

  if (!copy($image_path, $new_path))
    return false;

  return array( 'ImagePath' => $save_path, 'Author' => $auther);
}

/* Completely removes all trace of an entry */
function delete_form_entry( $entry ) {
  $delete = GFAPI::delete_entry( $entry['id'] );
  $result = ( $delete ) ? "entry {$entry['id']} successfully deleted." : $delete;
  GFCommon::log_debug( "GFAPI::delete_entry() - form #{$form['id']}: " . print_r( $result, true ) );
}

/* Filter the days so only those in $days_array is returned. */
function filter_date_array_by_days($dates_array, $days_array) {
  $return_array=array();
  foreach($dates_array as $date_string) {
    if (in_array(date('N', strtotime($date_string)), $days_array)) {
      array_push($return_array, $date_string);
    }
  }
  return $return_array;
}

/* Creates an Array with strings with all dates between the from and to dates inserted. */
function create_date_range_array($strDateFrom,$strDateTo)
{
    $aryRange=array();
    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),substr($strDateTo,8,2),substr($strDateTo,0,4));
    if ($iDateTo>=$iDateFrom)
    {
        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange,date('Y-m-d',$iDateFrom));
        }
    }
    return $aryRange;
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
    if ($strip_tag) { $input = strip_tags($input); }

    //strip tags, if desired
    if ($strip_style) { $input = preg_replace('/(<[^>]+) style=".*?"/i', '$1',$input); }

    if($length=='full') { $trimmed_text=$input; }
    else
    {
        //no need to trim, already shorter than trim length
        if (strlen($input) <= $length) { return $input; }

        //find last space within length
        $last_space = strrpos(substr($input, 0, $length), ' ');
        $trimmed_text = substr($input, 0, $last_space);

        //add ellipses (...)
        if ($ellipses) { $trimmed_text .= '...'; }
    }
    return $trimmed_text;
}

/* Prints the breadcrumb */
function the_breadcrumb() {
    global $post;
    $title = get_the_title();
    $output = '';
    echo '<ul class="breadcrumbs">';
    if (!is_front_page()) {
        if (is_category() || is_single()) {
            echo '<li>';
            the_category(' <li> ');
            if (is_single()) {
                echo '<li>';
                the_title();
                echo '</li>';
            }
        } elseif (is_page()) {
            if($post->post_parent){
                $anc = get_post_ancestors( $post->ID );
                $title = get_the_title();
                foreach ( $anc as $ancestor ) {
                  if (get_post_status($ancestor) != 'private') {
                    $output = '<li><a href="'.get_permalink($ancestor).'" title="'.get_the_title($ancestor).'">'.get_the_title($ancestor).'</a></li></li>' . $output;
                  }
                }
                echo $output;
                echo '<strong title="'.$title.'"> '.$title.'</strong>';
            } else {
                echo '<li><strong> '.get_the_title().'</strong></li>';
            }
        }
    }
    echo '</ul>';
}

/* AJAX FUNCTIONS */
add_action( 'wp_ajax_load_pages', 'load_pages_callback');
function load_pages_callback() {
  global $wpdb;
  $title     = $_POST['title'];
  $id        = $_POST['id'];
  $name      = $_POST['name'];

  $pages = $wpdb->get_results(
   "SELECT ID, post_title
    FROM $wpdb->posts
    WHERE post_type = 'page'
    AND post_title LIKE '%" . $title . "%'"
  );

  $list = '<select id="' . $id . '" name="' . $name . '">';
  foreach ($pages as $page) {
    $list .= '<option value="' . $page->ID . '">';
    $list .= $page->post_title . ' (' . $page->ID . ')';
    $list .= '</option>';
  }
  $list .= '</select>';

  echo $list;
  die();
}

add_action( 'wp_ajax_nopriv_load_event_organizers', 'load_event_organizers_callback' );
add_action( 'wp_ajax_load_event_organizers', 'load_event_organizers_callback' );
function load_event_organizers_callback() {
  $id     = $_POST['id'];
  $result = HelsingborgEventModel::get_organizers_with_event_id($id);
  echo json_encode($result);
  die();
}

add_action( 'wp_ajax_nopriv_load_event_dates', 'load_event_dates_callback' );
add_action( 'wp_ajax_load_event_dates', 'load_event_dates_callback' );
function load_event_dates_callback() {
  $id     = $_POST['id'];
  $result = HelsingborgEventModel::load_event_times_with_event_id($id);
  echo json_encode($result);
  die();
}

add_action( 'wp_ajax_nopriv_load_event_types', 'load_event_types_callback' );
add_action( 'wp_ajax_load_event_types', 'load_event_types_callback' );
function load_event_types_callback() {
  $result = HelsingborgEventModel::load_event_types();
  echo json_encode($result);
  die();
}

/* Load events */
add_action( 'wp_ajax_nopriv_load_events', 'load_events_callback' );
add_action( 'wp_ajax_load_events', 'load_events_callback' );
function load_events_callback() {
  $ids     = $_POST['ids'];
  $result = HelsingborgEventModel::load_events($ids);
  echo json_encode($result);
  die();
}

/* Add AJAX functions for admin. So Event may be changed by users
 Note: wp_ajax_nopriv_X is not used, since events cannot be changed by other than logged in users */
add_action( 'wp_ajax_approve_event', 'approve_event_callback' );
add_action( 'wp_ajax_deny_event',    'deny_event_callback' );
add_action( 'wp_ajax_save_event',    'save_event_callback' );

/* Function for approving events, returns true if success. */
function approve_event_callback() {
  global $wpdb;
  $id     = $_POST['id'];
  $result = HelsingborgEventModel::approve_event($id);
  die();
}

/* Function for denying events, returns true if success. */
function deny_event_callback() {
  global $wpdb;
  $id     = $_POST['id'];
  $result = HelsingborgEventModel::deny_event($id);

  die();
}

/* Function for saving events, returns true if success. */
function save_event_callback() {
	global $wpdb;

  $id          = $_POST['id'];
  $type        = $_POST['type'];
  $name        = $_POST['name'];
  $description = $_POST['description'];
  $days        = $_POST['days'];
  $start_date  = $_POST['startDate'];
  $end_date    = $_POST['endDate'];
  $time        = $_POST['time'];
  $units       = $_POST['units'];
  $types       = $_POST['types'];
  $organizer   = $_POST['organizer'];
  $location    = $_POST['location'];
  $imageUrl    = $_POST['imageUrl'];
  $author      = $_POST['author'];

  // Create event
  $event        = 	array ( 'EventID'         => $id,
                            'Name'            => $name,
                            'Description'     => $description,
                            'Approved'        => $approved,
                            'OrganizerID'     => $organizer,
                            'Location'        => $location,
                            'ExternalEventID' => $external_id );

  // Event types
  $event_types_x  = explode(',', $types);
  $event_types = array();
  foreach ($event_types_x as $type) {
    $new_type = array('Name' => $type);
    array_push($event_types, $new_type);
  }

  // Administration units
  if ($units && !empty($units)){
    $administrations = explode(',', $units);
    foreach($administrations as $unit) {
      $administration_units[] = HelsingborgEventModel::get_administration_id_from_name($unit)->AdministrationUnitID;
    }
  }

  // Image
  if ($imageUrl)
    $image = array( 'ImagePath' => $imageUrl, 'Author' => $author);

  // Create time/times
  $event_times = array();
  if (!$end_date) { // Single occurence
    $event_time = array('Date'  => $single_date,
                        'Time'  => $time,
                        'Price' => 0);
    array_push($event_times, $event_time);
  } else { // Must be start and end then
    $dates_array = create_date_range_array($start_date, $end_date);
    $filtered_days = filter_date_array_by_days($dates_array, $days_array);

    foreach($filtered_days as $date) {
      $event_time = array('Date'  => $date,
                          'Time'  => $time,
                          'Price' => 0);
      array_push($event_times, $event_time);
    }
  }

  HelsingborgEventModel::update_event($event, $event_types, $administration_units, $image, $times);

	die();
}

// Allow script & iframe tag within posts
function allow_post_tags( $allowedposttags ){
  $allowedposttags['script'] = array(
    'type' => true,
    'src' => true,
    'height' => true,
    'width' => true,
  );
  $allowedposttags['iframe'] = array(
    'src' => true,
    'width' => true,
    'height' => true,
    'class' => true,
    'frameborder' => true,
    'webkitAllowFullScreen' => true,
    'mozallowfullscreen' => true,
    'allowFullScreen' => true
  );
  return $allowedposttags;
}
add_filter('wp_kses_allowed_html','allow_post_tags', 1);


/**
 * Override of Page Attribute meta box
 * This is because the load time of wp_dropdown_pages(), which is removed in our version.
 * (original: page_attributes_meta_box() in wp-admin/includes/meta-boxes.php)
 */

// Remove the oroginal meta box
add_action( 'admin_menu', 'helsingborg_remove_meta_box');
function helsingborg_remove_meta_box(){
  remove_meta_box('pageparentdiv', 'page', 'side');
}

// Add our own meta box instead
add_action( 'add_meta_boxes', 'helsingborg_add_meta_box');
function helsingborg_add_meta_box() {
  add_meta_box('pageparentdiv', __('Page Attributes') , 'helsingborg_page_attributes_meta_box', 'page', 'side');
}

// Use custom page attributes meta box, no need to load dropdown with pages!
function helsingborg_page_attributes_meta_box($post) {
  if ( 'page' == $post->post_type && 0 != count( get_page_templates( $post ) ) ) {
    $template = !empty($post->page_template) ? $post->page_template : false;
    ?>
    <p><strong><?php _e('Template') ?></strong></p>
    <label class="screen-reader-text" for="page_template"><?php _e('Page Template') ?></label><select name="page_template" id="page_template">
      <option value='default'><?php _e('Default Template'); ?></option>
      <?php page_template_dropdown($template); ?>
    </select>
    <?php
  } ?>
  <p><strong><?php _e('Order') ?></strong></p>
  <p><label class="screen-reader-text" for="menu_order"><?php _e('Order') ?></label><input name="menu_order" type="text" size="4" id="menu_order" value="<?php echo esc_attr($post->menu_order) ?>" /></p>
  <p><?php if ( 'page' == $post->post_type ) _e( 'Need help? Use the Help tab in the upper right of your screen.' ); ?></p>
  <?php
}
?>
